<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\UnavailableHours;
use DB;

class ResourceUnvailability extends Model
{
    protected $resource;
    protected $resource_id;
    protected $resource_adhocs;
    protected $resource_days;
    protected $resource_hours;
    protected $services;

    /**
     * Create a new service availability instance.
     *
     * @return void
     */
	public function __construct($resource)
	{
        $this->resource_id = $resource->id;
        $this->resource = $resource;
        /*
        $available_adhocs = new AvailableAdhocs();
        $available_days = new AvailableDays();
        $available_hours = new AvailableHours();

        $this->service_adhocs = $available_adhocs->getAvailableAdhocHoursByServiceId($this->service_id);
        $this->service_days = $available_days->getDaysByServiceId($this->service_id);
        $this->service_hours = $available_hours->getHoursByServiceId($this->service_id);
        $this->resources = $service->resources;
        */
    }

    /**
     * Check if the type is adhoc or regular
     *
     * @param array $availability
     * @return array
     */
    public function categorizeUnavailability($availability)
    {
        $data = [];
        foreach ($availability as $info) {
            $data[$info->type][] = $info;
        }
        return $data;
    }

    /**
     * Call the stored procedure to retrieve the future unavailable days for
     * specific resource
     *
     * @return void
     */
    public function getResourceDays()
    {
        $sp = DB::select('exec be_resource_unavailability_sp :resource_id', [':resource_id' => $this->resource_id]);
        return self::categorizeUnavailability($sp);
    }

    /**
     * Get the resource unavailability per day
     *
     * @return void
     */
    public function getResourceUnavailability()
    {
        $regular_merged = [];
        $regular_days = [];
        $adhoc_days = [];
        $resource_days = self::getResourceDays();
        if (isset($resource_days['regular'])) {
            $regular_days = self::selectDays($resource_days['regular']);
        }
        if (isset($resource_days['adhoc'])) {
            $adhoc_days = self::selectDays($resource_days['adhoc']);
        }
        if (isset($resource_days['booking'])) {
            $booking_days = self::selectDays($resource_days['booking']);
        }
        $regular_merged =   $regular_days + $adhoc_days + $booking_days;//  self::mergeDays($regular_days['regular'], $adhoc_days['regular']);
        return $regular_merged;
    }

    /**
     * Get the hour for each resource
     */
    public function selectDays($available_days)
    {
        $output = [];
        foreach($available_days as $slot){
            $slot->week_day = date('D', strtotime($slot->date));
            if ($slot->type == 'regular'){
                $times = $this->resource->unavailableHours->where('day_week', $slot->week_day);
                $subset = $times->map(function($times) use ($slot) {
                    return $times->only(['start_time', 'length']);
                });
            }
            if ($slot->type == 'adhoc') {
                $times = $this->resource->unavailableAdhocs->where('date', $slot->date);
                $subset = $times->map(function($times) {
                    return $times->only(['start_time','length']);
                });
            }
            if ($slot->type == 'booking') {
                $times = $this->resource->bookings()->where('date', $slot->date)->get();
                $subset = $times->map(function($times) {
                    return $times->only(['start_hour','time_length']);
                });
            }
            $slot->times = $subset->toArray();
            if(!isset($output[$slot->date])) {
                $output[$slot->date] = $slot;
            }

        }
        return $output ;
    }

}