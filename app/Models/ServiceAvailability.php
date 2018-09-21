<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AvailableAdhocs;
use App\Models\AvailableDays;
use App\Models\AvailableHours;
use DB;

class ServiceAvailability extends Model
{
    protected $service;
    protected $service_id;
    protected $service_adhocs;
    protected $service_days;
    protected $service_hours;
    protected $resources;

    /**
     * Create a new service availability instance.
     *
     * @return void
     */
	public function __construct($service)
	{
        $this->service_id = $service->id;
        $this->service = $service;
        $available_adhocs = new AvailableAdhocs();
        $available_days = new AvailableDays();
        $available_hours = new AvailableHours();

        $this->service_adhocs = $available_adhocs->getAvailableAdhocHoursByServiceId($this->service_id);
        $this->service_days = $available_days->getDaysByServiceId($this->service_id);
        $this->service_hours = $available_hours->getHoursByServiceId($this->service_id);
        $this->resources = $service->resources;
    }

    /**
     * Execute Store procedure to get availability of a service by day
     *
     * @return void
     */
    public function getServiceAvailability()
    {
        $sp = DB::select('exec be_service_availability_sp :service_id', [':service_id' => $this->service_id]);
        //return self::categorizeIsInterpreterAvailability($sp);
        return self::categorizeAvailability($sp);
    }

    /**
     * Check if the type is adhoc or regular
     *
     * @param array $availability
     * @return array
     */
    public function categorizeAvailability($availability)
    {
        $data = [];
        foreach ($availability as $info) {
            $data[$info->type][] = $info;
        }
        return $data;
    }

    public function categorizeIsInterpreterAvailability($availability)
    {
        $data = [];
        foreach ($availability as $info) {
            if($info->is_interpreter){
                $data['interpreter'][] = $info;
            } else {
                $data['regular'][] = $info;
            }
        }
        return $data;
    }

    public function getResourcesAvailability()
    {
        $resource_availability = [];
        $resources = $this->resources;
        foreach ($resources as $resource) {
            $resource_availability[$resource->id] = DB::select('exec be_resource_unavailability_sp :resource_id', [':resource_id' => $resource->id]);
        }
        return $resource_availability;
    }

    public function compare()
    {
        $service_days = self::getServiceAvailability();
        $regular_days = self::selectDays($service_days['regular']);
        $adhoc_days = self::selectDays($service_days['adhoc']);

        $regular_merged = [];
        $interpreter_merged = [];

        $regular_merged = self::mergeDays($regular_days['regular'], $adhoc_days['regular']);
        $interpreter_merged = self::mergeDays($regular_days['interpreter'], $adhoc_days['interpreter']);

        //Compare against Resources
        //dd();
        dd($service_days,$regular_days, $adhoc_days, $regular_merged, $interpreter_merged, $this->service );
    }

    public function initServiceData($service)
    {
        if(!isset($service['regular'])){
            $service['regular'] = [];
        }
        if(!isset($service['interpreter'])){
            $service['interpreter'] = [];
        }
        return $service;
    }

    public function selectDays($available_days)
    {
        $output = [];
        foreach($available_days as $slot){
            $slot->week_day = date('D', strtotime($slot->date));
            if($slot->type == 'regular'){
                $times = $this->service->AvailableHours->where('day_week', $slot->week_day)->where('is_interpreter', $slot->is_interpreter);
                $subset = $times->map(function($times) use ($slot) {
                    $times->duration = ( ($slot->is_interpreter) ? $this->service->interpreter_duration : $this->service->duration);
                    return $times->only(['start_time', 'time_length', 'duration']);
                });
                $slot->times = $subset->toArray();
            } else { // Adhoc
                $times = $this->service->AvailableAdhocs->where('date', $slot->date);
                $subset = $times->map(function($times) {
                    return $times->only(['start_time','time_length','duration']);
                });
                $slot->times = $subset->toArray();
            }
            if($slot->is_interpreter) {
                if(!isset($output['interpreter'][$slot->date])) {
                    $output['interpreter'][$slot->date] = $slot;
                }
            } else {
                if(!isset($output['regular'][$slot->date])) {
                    $output['regular'][$slot->date] = $slot;
                }
            }
        }
        return self::initServiceData($output);
    }

    public function mergeDays($adhoc_days, $regular_days)
    {
        $diff_dates = array_diff_key($regular_days, $adhoc_days);
        return $adhoc_days + $diff_dates;
    }
}