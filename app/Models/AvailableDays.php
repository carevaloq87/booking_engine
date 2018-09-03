<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvailableDays extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'available_days';

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
                  'available_date',
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

    protected $current_year;
    protected $next_year;

    /**
     * Create a new calendar instance.
     *
     * @return void
     */
	public function __construct()
	{
        $this->current_year = date('Y');
        $this->next_year = date('Y', strtotime('+1 year'));
    }

    /**
     * Get the service for this model.
     */
    public function service()
    {
        return $this->belongsTo('App\Models\Service','service_id');
    }

    /**
     * Get days by service ID
     *
     * @param int Service Dd
     * @return array Days previously selected in a service
     */
    public function getDaysByServiceId($service_id)
    {
        $service = Service::findOrFail($service_id);
        $available_days = $service->availableDays;
        $service_dates = [];
        foreach($available_days as $date) {
            $is_current_year = date('Y', strtotime($date->available_date)) == $this->current_year;
            if(!$date->is_interpreter && $is_current_year) {
                $service_dates['selected_current'][] = date('M-d', strtotime($date->available_date));
            }
            if($date->is_interpreter && $is_current_year) {
                $service_dates['selected_current_interpreter'][] = date('M-d', strtotime($date->available_date));
            }
            if(!$date->is_interpreter && !$is_current_year) {
                $service_dates['selected_next'][] = date('M-d', strtotime($date->available_date));
            }
            if($date->is_interpreter && !$is_current_year) {
                $service_dates['selected_next_interpreter'][] = date('M-d', strtotime($date->available_date));
            }
        }
        return $service_dates;
    }
}
