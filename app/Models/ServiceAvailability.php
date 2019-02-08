<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AvailableAdhocs;
use App\Models\AvailableDays;
use App\Models\AvailableHours;
use App\Models\Booking ;
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
    protected $start;
    protected $end;

    /**
     * Create a new service availability instance.
     *
     * @return void
     */
	public function __construct($service, $start, $end)
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

        $this->start = explode('T', $start)[0];// The format that the Fullcalendar
        $this->end = explode('T', $end)[0];// The format that the Fullcalendar
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

        //Compare against Resources
        $resources = self::getResourceUnavailability();
        $availability_regular = new \App\Models\Availability($resources, $regular_merged, $this->bookings, $this->start, $this->end);
        $availability_interpreter = new \App\Models\Availability($resources, $interpreter_merged, $this->bookings, $this->start, $this->end);

        $bookings_obj = new Booking();
        $unavailable_dates =  $bookings_obj->getFutureBookingsByServiceAndDate($this->service_id,  $this->start, $this->end);

        return ['regular'=> $availability_regular->get(), 'interpreter' => $availability_interpreter->get(), 'unavailable' => $unavailable_dates];
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
        $current_date = date('d-m-Y', time());
        foreach($available_days as $slot){
            $slot->week_day = date('D', strtotime($slot->date));
            $slot_date = date('d-m-Y', strtotime($slot->date));
            $current_hour = date('H', time()) * 60 + date('i', time());
            $end_time = PHP_INT_MIN;
            if($slot->type == 'regular'){
                $subset = [];
                $current_date == $slot_date ?
                    $times = $this
                            ->service
                            ->AvailableHours
                            ->where('day_week', $slot->week_day)
                            ->where('start_time', '>=', $current_hour)
                            ->where('is_interpreter', $slot->is_interpreter) :
                    $times = $this
                            ->service
                            ->AvailableHours
                            ->where('day_week', $slot->week_day)
                            ->where('is_interpreter', $slot->is_interpreter);
                $duration = ( ($slot->is_interpreter) ? $this->service->interpreter_duration : $this->service->duration );
                $slot->times = self::getHourSlots($times, $duration);
            } else { // Adhoc
                $subset = [];
                $current_date == $slot_date ?
                    $times = $this
                            ->service
                            ->AvailableAdhocs
                            ->where('date', $slot->date)
                            ->where('start_time', '>=', $current_hour)
                            ->where('is_interpreter', $slot->is_interpreter) :
                    $times = $this
                            ->service
                            ->AvailableAdhocs
                            ->where('date', $slot->date)
                            ->where('is_interpreter', $slot->is_interpreter);
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
     * Get the start time, time lengh and duration split
     *
     * @param array $times array of hours
     * @param integer $duration
     * @return void
     */
    public function getHourSlots($times, $duration = 0)
    {
        $slots = [];
        $in_a_row = 1;
        $start_times = $times->pluck('start_time')->toArray();
        $listed_duration = min($this->service->duration, $this->service->interpreter_duration);
        foreach($times as $time){
            $duration = (isset($time['duration']) ? $time['duration'] : $duration);
            $next = $time['start_time'] + $time['time_length'];
            if(in_array($next, $start_times)) {
                $in_a_row++; //Count the number of blocks that are next to each other
            } else {
                $initial_time = ($time['start_time'] + $time['time_length']) - ($in_a_row * $time['time_length']);
                $resultant = ($time['time_length'] * $in_a_row) / $listed_duration;
                if($resultant >= 1) {
                    for ($rep=0; $rep < floor($resultant); $rep++) {
                        if($rep == floor($resultant)-1) {
                            if( $listed_duration >= $duration) {
                                $slots[] = [
                                    'start_time' => $initial_time + ($listed_duration * $rep),
                                    'time_length' => $time['time_length'],
                                    'duration' => $duration
                                ];
                            }
                        } else {
                            $slots[] = [
                                'start_time' => $initial_time + ($listed_duration * $rep),
                                'time_length' => $time['time_length'],
                                'duration' => $duration
                            ];
                        }
                    }
                }
                $in_a_row = 1;
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