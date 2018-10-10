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

    public function getFutureBookingsByServiceAndDate($service_id, $start, $end)
    {
        $start = explode('T', $start)[0]; // The format that the Fullcalendar eg: 2018-08-08T10:10:12
        $end = explode('T', $end)[0]; // The format that the Fullcalendar eg: 2018-08-08T10:10:12
        return self::parseBookingsDates( $this->where('service_id', $service_id)
                    ->where('date', '>=', $start)
                    ->where('date', '<=', $end)
                    ->get()
                    ->all());
    }

    /**
     * Transform Booking dates to same data structure as service availability
     *
     * @return array
     */
    public function parseBookingsDates($bookings)
    {
        $booked_dates = [];
        foreach($bookings as $booking){
            $start_time = $booking['start_hour'];
            $duration = $booking['time_length'];
            $resource = $booking['resource_id'];
            $date = date('Y-m-d', strtotime($booking['date']));
            $booked_dates[$date][$start_time]['date'] =  $date;
            $booked_dates[$date][$start_time]['week_day'] =  $booking['day'];
            $booked_dates[$date][$start_time]['times'][$resource] =  [
                'start_time' => $start_time,
                'duration' => $duration,
            ];
        }
        return $booked_dates;
    }
}