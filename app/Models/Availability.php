<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    protected $resources;
    protected $service_availability;
    /**
     * Create a new service availability instance.
     *
     * @return void
     */
	public function __construct($resources, $service_availability)
	{
        $this->resources = $resources;
        $this->service_availability = $service_availability;
    }

    //compare them using the algorithm
    public function compare()
    {
        $availability = [];
        $service_availability = $this->service_availability;
        $resources = $this->resources;

        foreach($resources as $resource_id => $resource_info){
            $final_dates = array_diff_key($service_availability, $resource_info);
            $check_dates = array_intersect_key($resource_info, $service_availability);
            foreach($final_dates as $date => $final_date){
                $availability[$date] = $final_date->times;
            }
            foreach($check_dates as $resource_date => $resouce_date_info){
                foreach($service_availability as $service_date => $service_info){
                    foreach($service_info->times as $service_time){
                        $service_start_time = $service_time['start_time'];
                        $service_duration = $service_time['duration'];
                        if($resource_date === $service_date) {
                            $is_available = true;
                            foreach($resouce_date_info->times as $resource_time){
                                $resource_start_time = $resource_time['start_time'];
                                $resource_length = $resource_time['length'];

                                $args = [
                                            'sv_start_time' => $service_start_time,
                                            'sv_final_time' => $service_start_time + $service_duration,
                                            'rs_start_time' => $resource_start_time,
                                            'rs_final_time' => $resource_start_time + $resource_length,
                                ];

                                if(!self::compareRanges($args)){
                                    $is_available = false;
                                    break;
                                }
                            }
                            if($is_available){
                                $availability[$service_date][] = $service_time;
                            }
                        }
                    }
                }
            }
        }
dd($availability);
/******************************************************* Ignore me 
        foreach($service_availability as $service_date => $service_info){
            foreach($service_info->times as $service_time){

                $service_start_time = $service_time['start_time'];
                $service_duration = $service_time['duration'];

                foreach($resources as $resource_id => $resource_info){
                    foreach($resource_info as $resource_date => $resouce_date_info){

                        if($resource_date === $service_date) {
                            $is_available = true;
                            foreach($resouce_date_info->times as $resource_time){
                                $resource_start_time = $resource_time['start_time'];
                                $resource_length = $resource_time['length'];

                                $args = [
                                            'sv_start_time' => $service_start_time,
                                            'sv_final_time' => $service_start_time + $service_duration,
                                            'rs_start_time' => $resource_start_time,
                                            'rs_final_time' => $resource_start_time + $resource_length,
                                ];

                                if(!self::compareRanges($args)){
                                    $is_available = false;
                                    break;
                                }
                            }
                            if($is_available){
                                $availability[$service_date][] = $service_time;
                            }
                        }
                    }
                }
            }
        }*/
        return $availability;
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
}