<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Booking  ;

class Availability extends Model
{
    protected $resources;
    protected $service_availability;
    protected $service_bookings;
    protected $start;
    protected $end;
    /**
     * Create a new service availability instance.
     *
     * @return void
     */
	public function __construct($resources, $service_availability, $service_bookings, $start, $end)
	{
        $this->resources = $resources;
        $this->service_availability = $service_availability;
        $this->service_bookings = $service_bookings;
        $this->start = $start;
        $this->end = $end;
    }

    //compare them using the algorithm
    public function get()
    {
        $service_availability = $this->compareServiceAndResources();
        $booking_obj = new Booking();
        $bookings = $booking_obj->parseBookingsDates($this->service_bookings);
        return $this->compareServiceAvailabilityAndBookings($service_availability, $bookings);
    }

    /**
     * Check if an appt date is in the range of dates
     *
     * @param String $date in format YYYY-MM-DD
     * @return boolean
     */
    public function is_date_in_date_range($date)
    {
        return (($date >= $this->start) && ($date <= $this->end));
    }

    /**
     * Compare a service availability with all resources available for that service
     *
     * @return array
     */
    public function compareServiceAndResources()
    {
        $availability = [];
        $service_availability = $this->service_availability;
        $resources = $this->resources;

        foreach($resources as $resource_id => $resource_info){
            $final_dates = array_diff_key($service_availability, $resource_info); //Dates that are availables in the resource
            $check_dates = array_intersect_key($resource_info, $service_availability); //Dates that the resouce and the service have in common
            foreach($final_dates as $date => $final_date){
                if(self::is_date_in_date_range($date)) {
                    foreach($final_date->times as $time){
                        $time['text']=valueToHour($time['start_time'], $time['duration']);
                        $time['resource_id'] = $resource_id;
                        $availability[$date][$time['start_time']][] = $time;
                    }
                }
            }
            foreach($check_dates as $resource_date => $resouce_date_info){ //Loop all the dates that the resouce and the service have in common

                foreach($service_availability as $service_date => $service_info){ //Loop the serice avdates

                    foreach($service_info->times as $service_time){ //Loop the times in the service
                        $service_start_time = $service_time['start_time'];
                        $service_duration = $service_time['duration'];
                        $service_time['text'] = valueToHour($service_start_time, $service_duration);
                        if($resource_date === $service_date && self::is_date_in_date_range($resource_date)) { //Check only the dates that match the service and the resource against each other
                            $is_available = true;
                            foreach($resouce_date_info->times as $resource_time){//Loop the times in the service
                                $resource_start_time = $resource_time['start_time'];
                                $resource_length = $resource_time['length'];

                                $args = [
                                            'sv_start_time' => $service_start_time,
                                            'sv_final_time' => $service_start_time + $service_duration,
                                            'rs_start_time' => $resource_start_time,
                                            'rs_final_time' => $resource_start_time + $resource_length,
                                ];

                                if(!self::compareRanges($args)){ //If resource is not available stop looping its times
                                    $is_available = false;
                                    break;
                                }
                            }
                            if($is_available){
                                $service_time['resource_id'] = $resource_id;
                                $availability[$service_date][$service_time['start_time']][] = $service_time;
                            }
                        }
                    }

                }
            }
        }
        return $availability;
    }

    /**
     * Compare a Service availability with existing bookings
     *
     * @param array $service_availability
     * @param array $bookings
     * @return void
     */
    public function compareServiceAvailabilityAndBookings($service_availability, $bookings)
    {
        foreach($bookings as $date => $booking_time){ //Loop bookings
            if(isset($service_availability[$date])) { // Check if there are taken bookings in the service available dates

                foreach ($service_availability[$date] as $service_start_time => $sv_resources) { // Loop times in service by date

                    foreach ($sv_resources as $sv_resources_key => $sv_resource_availability) { // Loop available resources in service dates

                        foreach($booking_time as $bo_time => $booking){ //Loop times in bookings

                            foreach ($booking['times'] as $bo_resource_id => $bo_re_info) { //Loop not available resources in times

                                if($bo_resource_id == $sv_resource_availability['resource_id']){//if resources not availables are in service times
                                    $args = [
                                                'sv_start_time' => $service_start_time,
                                                'sv_final_time' => $service_start_time + $sv_resource_availability['duration'],
                                                'rs_start_time' => $bo_time,
                                                'rs_final_time' => $bo_time + $bo_re_info['duration'],
                                    ];
                                    if(!self::compareRanges($args)){ //Check if the time falls in the range of bookings and services
                                        unset($service_availability[$date][$service_start_time][$sv_resources_key]); //Remove available time
                                        if ( empty($service_availability[$date][$service_start_time]) ) {
                                            unset($service_availability[$date][$service_start_time]); //If there are no more times available remove the time
                                        }
                                        if (empty($service_availability[$date])) {
                                            unset($service_availability[$date]); //If there are no more times available remove the date
                                        }
                                    }
                                }
                            }

                        }
                    }
                }
            }
        }
        return $service_availability;
    }
    /**
     * Algorithm to check if the range of times fits or not in order to say if an appt is available or not
     *
     * @param array $args
     * @return boolean
     */
    public function compareRanges($args)
    {
        $is_available = true;
        if($args['sv_start_time'] >= $args['rs_start_time'] && $args['sv_start_time'] <= ($args['rs_final_time'] - 1)){
            $is_available = false;
        }
        if( ($args['sv_final_time'] - 1) >= $args['rs_start_time'] && ($args['sv_final_time'] - 1) <= ($args['rs_final_time'] - 1)){
            $is_available = false;
        }
        if($args['sv_start_time'] <= $args['rs_start_time'] && ($args['sv_final_time'] - 1) >= ($args['rs_final_time'] - 1)){
            $is_available = false;
        }
        return $is_available;
    }

}