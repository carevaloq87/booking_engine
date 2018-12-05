<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnavailableAdhocs extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'unavailable_adhocs';

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
                'length',
                'start_time',
                'details',
                'duration',
                'type',
                'resource_id'
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
     * Get the resource for this model.
     */
    public function resource()
    {
        return $this->belongsTo('App\Models\Resource','resource_id');
    }

    /**
     * Get available Adhoc Hours by Resource
     *
     * @param int $service_id
     * @return array Array of regular and interpreter hours and options
     */
    public function getUnavailableAdhocHoursByResourceId($resource_id)
    {
        $adhoc_regular = self::where('resource_id','=', $resource_id)->where('date', '>=', date('Y-m-d'))->orderBy('start_time')->get();
        $adhocs['regular'] = self::getAdhocsFromCollection($adhoc_regular);
        return $adhocs;
    }

        /**
     * Get hours from an array of Available Adhocs
     *
     * @param Collection $collection
     * @return array array of Available Adhoc hours and options
     */
    public function getHoursFromCollection($collection)
    {
        $hours_available = [];
        $hours = [];
        $options = [];
        if(!empty($collection)){
            $in_a_row = 1;
            //$duration = $options['duration'] = $collection->pluck('duration')[0]; // Duration and time length is the same for all times in same date
            $time_length = $options['length'] = $collection->pluck('length')[0]; // Duration and time length is the same for all times in same date
            $options['details']=$collection->pluck('details')[0];
            $selected_times = $collection->pluck('start_time')->toArray();
            foreach($selected_times as $start_time) {
                $next = $start_time + $time_length;
                if(in_array($next, $selected_times)) {
                    $in_a_row++; //Count the number of blocks that are next to each other
                } else {
                    $initial_time = ($start_time + $time_length) - ($in_a_row * $time_length);
                    $resultant = ($time_length * $in_a_row) / $time_length; // FIt this number of times in range of selected times
                    if($resultant >= 1) { // Selected value fits inside selected time
                        $hours_available[] = self::splitDuration($initial_time, $time_length, floor($resultant));
                    }
                    $in_a_row = 1;
                }
            }
            $hours_available = collect($hours_available)->flatten();
            foreach ($hours_available as $value) {
                $hours[$value] = valueToHour($value, $options['length']);
            }
        }
        return ['hours' => $hours, 'options' => $options];
    }

    /**
     * Get hours Adhoc informatrion from a colleciton
     *
     * @param Collection $collection A collection of Available adhocs
     * @return array
     */
    public function getAdhocsFromCollection ($collection)
    {
        $hours_by_day = [];
        $by_date = [];
        if(!empty($collection)){
            //Map days
            $by_date = $collection->mapToGroups(function ($item, $key) {
                            return [$item['date'] => $item];
                        });
            //Transform minute values per day to strings of hours and duration
            foreach ($by_date as $day => $adhoc_info) {
                $hours_by_day[$day] = self::getHoursFromCollection($adhoc_info);
            }
        }
        ksort($hours_by_day);
        return $hours_by_day;
    }

    /**
     * Split duration of appointments from initial time duration and the number of repetitions
     *
     * @param int $initial_time
     * @param int $time_length
     * @param int $repetitions
     * @return array Array of hours
     */
    public function splitDuration($initial_time, $time_length, $repetitions)
    {
        $hours = [];
        for ($rep=0; $rep < $repetitions; $rep++) {
            $hours[] = $initial_time + ($time_length * $rep);
        }
        return $hours;
    }

    /**
     * Delete adhoc by service ID, date and is interpreter
     *
     * @param Request $data
     * @return boolean
     */
    public function deleteAdhoc($data)
    {
        $adhoc_info = explode('||', $data['adhoc']);
        $resource_id = $data['resource_id'];
        $result = false;
        if($adhoc_info[1] == 0) {
            $result = self::where('resource_id','=', $resource_id)
                            ->where('date', '=', $adhoc_info[0])
                            ->delete();
        }
        return $result;
    }


}
