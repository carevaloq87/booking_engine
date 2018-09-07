<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnavailableHours extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'unavailable_hours';

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
                  'length',
                  'start_time',
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
     * Get hours by resource ID
     *
     * @param int Resource ID
     * @return array hours previously selected in a resource
     */
    public function getHoursByResourceId($resource_id)
    {
        $resource = Resource::findOrFail($resource_id);
        $unavailable_hours = $resource->unavailableHours;
        $resource_days = [];
        foreach($unavailable_hours as $hour) {
            $resource_days['regular']['days'][] = date('D', strtotime($hour->day_week)) . '-' . $hour->start_time;
            $resource_days['regular']['time_length'] = $hour->length;
            $resource_days['regular']['time_name'] = self::converHourToTextOrNumber($hour->length);
        }
        return $resource_days;
    }

    /**
     * Convert text or numbers to hours in text or numbers
     *
     * @param string $hour
     * @return string int or string with hour
     */
    public static function converHourToTextOrNumber($hour)
    {
        $hours = [
            'quarter_hour' => 15,
            'half_hour' => 30,
            'hour' => 60,
            15 => 'quarter_hour',
            30 => 'half_hour',
            60 => 'hour',
        ];
        return $hours[$hour];
    }


}
