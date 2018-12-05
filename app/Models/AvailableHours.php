<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvailableHours extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'available_hours';

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
                  'day_week',
                  'time_length',
                  'start_time',
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
     * Get hours by service ID
     *
     * @param int Service iD
     * @return array hours previously selected in a service
     */
    public function getHoursByServiceId($service_id)
    {
        $service = Service::findOrFail($service_id);
        $available_hours = $service->availableHours;
        $service_days = [];
        foreach($available_hours as $hour) {
            if(!$hour->is_interpreter) {
                $service_days['regular']['days'][] = date('D', strtotime($hour->day_week)) . '-' . $hour->start_time;
                $service_days['regular']['time_length'] = $hour->time_length;
                $service_days['regular']['time_name'] = converHourToTextOrNumber($hour->time_length);
            }
            if($hour->is_interpreter) {
                $service_days['interpreter']['days'][] = date('D', strtotime($hour->day_week)) . '-' . $hour->start_time;
                $service_days['interpreter']['time_length'] = $hour->time_length;
                $service_days['interpreter']['time_name'] = converHourToTextOrNumber($hour->time_length);
            }
        }
        return $service_days;
    }

}
