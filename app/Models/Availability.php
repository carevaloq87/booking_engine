<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    protected $resources;
    protected $service_availability;
    protected $service_bookings;
    /**
     * Create a new service availability instance.
     *
     * @return void
     */
	public function __construct($resources, $service_availability, $service_bookings)
	{
        $this->resources = $resources;
        $this->service_availability = $service_availability;
        $this->service_bookings = $service_bookings;
    }

    //compare them using the algorithm
    public function get()
    {
        $service_availability = $this->compareServiceAndResources();
        $bookings = $this->parseBookingsDates();
        return $this->compareServiceAvailabilityAndBookings($service_availability, $bookings);
    }

    public function compareServiceAndResources()
    {
        $availability = [];
        $service_availability = $this->service_availability;
        $resources = $this->resources;

        foreach($resources as $resource_id => $resource_info){
            $final_dates = array_diff_key($service_availability, $resource_info); //Dates that are availables in the resource
            $check_dates = array_intersect_key($resource_info, $service_availability); //Dates that the resouce and the service have in common
            foreach($final_dates as $date => $final_date){
                foreach($final_date->times as $time){
                    $time['text']=self::valueToHour($time['start_time'], $time['duration']);
                    $time['resource_id'] = $resource_id;
                    $availability[$date][$time['start_time']] = $time;
                }
            }
            foreach($check_dates as $resource_date => $resouce_date_info){ //Loop all the dates that the resouce and the service have in common
                foreach($service_availability as $service_date => $service_info){ //Loop the serice avdates
                    foreach($service_info->times as $service_time){ //Loop the times in the service
                        $service_start_time = $service_time['start_time'];
                        $service_duration = $service_time['duration'];
                        $service_time['text'] = self::valueToHour($service_start_time, $service_duration);
                        if($resource_date === $service_date) { //Check only the dates that match the service and the resource against each other
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
                                $availability[$service_date][$service_time['start_time']] = $service_time;
                            }
                        }
                    }
                }
            }
        }
        return $availability;
    }

    public function compareServiceAvailabilityAndBookings($service_availability, $bookings)
    {
        foreach($bookings as $date => $booking_time){
            if(isset($service_availability[$date])) {
                foreach($booking_time as $time => $booking){
                    if(isset($service_availability[$date][$time])) {
                        unset($service_availability[$date][$time]);
                    }
                }
            }
        }
        return $service_availability;
    }

    public function parseBookingsDates()
    {
        $bookings = $this->service_bookings;
        $booked_dates = [];
        foreach($bookings as $booking){
            $start_time = $booking['start_hour'];
            $duration = $booking['time_length'];
            $date = date('Y-m-d', strtotime($booking['date']));
            $booked_dates[$date][$start_time]['date'] =  $date;
            $booked_dates[$date][$start_time]['week_day'] =  $booking['day'];
            $booked_dates[$date][$start_time]['times'][] =  [
                'start_time' => $start_time,
                'duration' => $duration,
            ];
        }
        return $booked_dates;
    }

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

    /**
     * Transform time in minutes to Hours and minutes on plain text
     *
     * @param int $value Minutes
     * @param int $duration Duraton of the appt
     * @return string
     */
    public function valueToHour($value, $duration)
    {
        $valute_in_hours = $value/60;
        $hour = sprintf("%02d", floor($valute_in_hours) );
        $minute = sprintf("%02d", round(fmod($valute_in_hours, 1) * 60));

        $finish_time = ($value + $duration) / 60;
        $finish_hour = sprintf("%02d",  floor($finish_time) );
        $finish_minute = sprintf("%02d", round(fmod($finish_time, 1) * 60));

        return "$hour:$minute - $finish_hour:$finish_minute";
    }
}