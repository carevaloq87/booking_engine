<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvailableAdhocs extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'available_adhocs';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'date',
                  'time_length',
                  'start_time',
                  'duration',
                  'is_interpreter',
                  'service_id'
              ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Get the service for this model.
     */
    public function service()
    {
        return $this->belongsTo('App\Models\Service','service_id');
    }

    /**
     * Get available Adhoc Hours by Service
     *
     * @param int $service_id
     * @return array Array of regular and interpreter hours and options
     */
    public function getAvailableAdhocHoursByServiceId($service_id)
    {
        $adhoc_regular = self::where('service_id','=', $service_id)->where('date', '>=', date('Y-m-d'))->where('is_interpreter', '=', 0)->orderBy('start_time')->get();
        $adhoc_interpreter = self::where('service_id','=', $service_id)->where('date', '>=', date('Y-m-d'))->where('is_interpreter', '=', 1)->orderBy('start_time')->get();

        $adhocs['regular'] = self::getHoursFromCollection($adhoc_regular);
        $adhocs['interpreter'] = self::getHoursFromCollection($adhoc_interpreter);
        return $adhocs;
    }

    /**
     * Get hours from an array of Available Adhocs
     *
     * @param array $collection
     * @return array array of Available Adhoc hours and options
     */
    public function getHoursFromCollection($collection)
    {
        $hours_available = [];
        $options = [];
        if(!empty($collection)){
            $in_a_row = 1;
            $duration = $options['duration'] = $collection->pluck('duration')[0]; // Duration and time length is the same for all times in same date
            $time_length = $options['time_length'] = $collection->pluck('time_length')[0]; // Duration and time length is the same for all times in same date
            $selected_times = $collection->pluck('start_time')->toArray();
            foreach($selected_times as $start_time) {
                $next = $start_time + $time_length;
                if(in_array($next, $selected_times)) {
                    $in_a_row++; //Count the number of blocks that are next to each other
                } else {
                    $initial_time = ($start_time + $time_length) - ($in_a_row * $time_length);
                    $resultant = ($time_length * $in_a_row) / $duration; // FIt this number of times in range of selected times
                    if($resultant >= 1) { // Selected value fits inside selected time
                        $hours_available[] = self::splitDuration($initial_time, $duration, floor($resultant));
                    }
                    $in_a_row = 1;
                }
            }
        }
        return ['hours' => collect($hours_available)->flatten(), 'options' => $options];
    }

    /**
     * Split duration of appointments from initial time duration and the number of repetitions
     *
     * @param int $initial_time
     * @param int $duration
     * @param int $repetitions
     * @return array Array of hours
     */
    public function splitDuration($initial_time, $duration, $repetitions)
    {
        $hours = [];
        for ($rep=0; $rep < $repetitions; $rep++) {
            $hours[] = $initial_time + ($duration * $rep);
        }
        return $hours;
    }
}
