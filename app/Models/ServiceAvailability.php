<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AvailableAdhocs;
use App\Models\AvailableDays;
use App\Models\AvailableHours;
use App\Models\Booking  ;
use DB;

class ServiceAvailability extends Model
{
    protected $service;
    protected $service_id;
    protected $service_adhocs;
    protected $service_bookings;
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
        $bookings_obj = new Booking();

        $this->service_adhocs = $available_adhocs->getAvailableAdhocHoursByServiceId($this->service_id);
        $this->service_days = $available_days->getDaysByServiceId($this->service_id);
        $this->service_hours = $available_hours->getHoursByServiceId($this->service_id);
        $this->resources = $service->resources;
        $this->bookings = $bookings_obj->getFutureBookingsByService($service->id);
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
     * Split array between adhoc and regular
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

    /**
     * Split array between interpreter or regular
     *
     * @param array $availability
     * @return array
     */
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

    /**
     * Get availability of the current service resources
     *
     * @return array
     */
    public function getResourceUnavailability()
    {
        $resource_availability = [];
        $resources = $this->resources;
        foreach ($resources as $resource) {
            $resource_unvailability_obj = new \App\Models\ResourceUnvailability($resource);
            $resource_availability[$resource->id] = $resource_unvailability_obj->getResourceUnavailability();
        }
        return $resource_availability;
    }

    /**
     * returns the availability of a service
     *
     * @return array
     */
    public function get()
    {
        $service_days = self::getServiceAvailability();

        $regular_days = self::selectDays( ( isset($service_days['regular']) ? $service_days['regular'] : [] ) );
        $adhoc_days = self::selectDays( ( isset($service_days['adhoc']) ? $service_days['adhoc'] : [] ) );

        $regular_merged = self::mergeDays($regular_days['regular'], $adhoc_days['regular']);
        $interpreter_merged = self::mergeDays($regular_days['interpreter'], $adhoc_days['interpreter']);

        //Compare against Bookings
        $service_bookings = self::categorizeIsInterpreterAvailability($this->bookings);

        $service_bookings_regular = (isset($service_bookings['regular']) ? $service_bookings['regular'] : []);
        $service_bookings_interpreter = (isset($service_bookings['interpreter']) ? $service_bookings['interpreter'] : []);
        //dd($regular_merged, $service_bookings_regular,$service_bookings_interpreter);
        //Compare against Resources
        $resources = self::getResourceUnavailability();
        $availability_regular = new \App\Models\Availability($resources, $regular_merged, $service_bookings_regular);
        $availability_interpreter = new \App\Models\Availability($resources, $interpreter_merged, $service_bookings_interpreter);

        return ['regular'=> $availability_regular->get(), 'interpreter' => $availability_interpreter->get()];
    }

    /**
     * Normalize array with interpreter and regular
     *
     * @param array $service
     * @return array
     */
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

    /**
     * Add hours to available days
     *
     * @param array $available_days
     * @return array
     */
    public function selectDays($available_days)
    {
        $output = [];
        foreach($available_days as $slot){
            $slot->week_day = date('D', strtotime($slot->date));
            $end_time = PHP_INT_MIN;
            if($slot->type == 'regular'){
                $subset = [];
                $times = $this->service->AvailableHours->where('day_week', $slot->week_day)->where('is_interpreter', $slot->is_interpreter);
                $duration = ( ($slot->is_interpreter) ? $this->service->interpreter_duration : $this->service->duration );
                $slot->times = self::getHourSlots($times, $duration);
            } else { // Adhoc
                $subset = [];
                $times = $this->service->AvailableAdhocs->where('date', $slot->date);
                $slot->times = self::getHourSlots($times);
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

    /**
     * organize slots of hours by duration
     *
     * @param array $times
     * @param integer $duration
     * @return array
     */
    public function getHourSlots($times, $duration = 0)
    {
        $slots = [];
        $time_length = 0 ;
        $start_time = PHP_INT_MIN;
        foreach($times as $time){
            $duration = (isset($time['duration']) ? $time['duration'] : $duration);
            if($time['start_time'] >= $start_time ){
                $start_time = $time['start_time'];
                //Keep looping if the duration fits more than one time in the time length
                while ($start_time < ($time['start_time'] + $time['time_length'])) {
                    $slots[] = [
                                    'start_time' => $start_time,
                                    'time_length' => $time['time_length'],
                                    'duration' => $duration
                                ];
                    $start_time += $duration;
                }
            }
        }
        return $slots;
    }

    /**
     * Remove adhoc days from regular available days and replace them with the adhoc ones
     *
     * @param array $adhoc_days
     * @param array $regular_days
     * @return array
     */
    public function mergeDays($adhoc_days, $regular_days)
    {
        $diff_dates = array_diff_key($regular_days, $adhoc_days);
        return $adhoc_days + $diff_dates;
    }
}