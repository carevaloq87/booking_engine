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



}
