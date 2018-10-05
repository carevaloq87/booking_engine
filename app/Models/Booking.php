<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bookings';

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
                            'day',
                            'start_hour',
                            'time_length',
                            'comment',
                            'is_interpreter',
                            'int_language',
                            'resource_id',
                            'service_id',
                            'client_id',
                            'booking_status_id'
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
     * Get the resource for this model.
     */
    public function resource()
    {
        return $this->belongsTo('App\Models\Resource','resource_id');
    }

    public function getFutureBookingsByService($service_id)
    {
        return $this->where('service_id', $service_id)
                    ->where('date', '>=', date('Y-m-d'))
                    ->get()
                    ->all();
    }
}