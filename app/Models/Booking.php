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
     * Get the client for this model
     */
    public function client()
    {
        return $this->belongsTo('App\Models\Client','client_id');
    }
    /**
     * Get the resource for this model.
     */
    public function resource()
    {
        return $this->belongsTo('App\Models\Resource','resource_id');
    }
    /**
     * Get the Boking status for this model.
     */
    public function bookingStatus()
    {
        return $this->belongsTo('App\Models\BookingStatus','booking_status_id');
    }    
    /**
     * Get the bookings from today by service id
     *
     * @param int $service_id
     * @return void
     */
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
        return self::formatBookingsAsAvailability( $this->where('service_id', $service_id)
                    ->where('date', '>=', $start)
                    ->where('date', '<=', $end)
                    ->get()
                    ->all());
    }

    public function formatBookingsAsAvailability($bookings)
    {
        $bookings = self::parseBookingsDates($bookings);
        $output = [];

        foreach($bookings as $date => $hours){
            foreach($hours as $hour => $booking_info){
                $slots = $booking_info['times'];
                foreach($slots as $resource_id => $slot){
                    $output[$date][$hour][] = [
                        'start_time'=> $slot['start_time'],
                        'duration'=>  $slot['duration'],
                        'resource_id'=> $resource_id
                    ];
                }
            }
        }

        return $output;

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
            $is_interpreter = $booking['is_interpreter'];
            $date = date('Y-m-d', strtotime($booking['date']));
            $booked_dates[$date][$start_time]['date'] =  $date;
            $booked_dates[$date][$start_time]['is_interpreter'] =  $is_interpreter;
            $booked_dates[$date][$start_time]['week_day'] =  $booking['day'];
            $booked_dates[$date][$start_time]['times'][$resource] =  [
                'start_time' => $start_time,
                'duration' => $duration,
            ];
        }
        return $booked_dates;
    }

    /**
     * Update booking
     *
     * @param array $data array with booking information
     * @param int $bo_id booking ID
     * @return Booking Booking object updated
     */
    public function updateBooking($data, $bo_id)
    {
        $booking_obj = new Booking();
        $booking = $booking_obj->findOrFail($bo_id);

        $booking['date'] = (isset($data['date']) && $data['date'] != '' ? $data['date'] : $booking['date']);
        $booking['day'] = (isset($data['date']) && $data['date'] != '' ? date('D', strtotime($data['date'])) : $booking['day']);
        $booking['start_hour'] = (isset($data['start_hour']) && $data['start_hour'] != '' ? $data['start_hour'] : $booking['start_hour']);
        $booking['time_length'] = (isset($data['time_length']) && $data['time_length'] != '' ? $data['time_length'] : $booking['time_length']);
        $booking['comment'] = isset($data['comment']) ? $data['comment'] : "";
        $booking['is_interpreter'] = isset($data['is_interpreter']) ? $data['is_interpreter'] : 0;
        $booking['int_language'] = isset($data['int_language']) ? $data['int_language'] : "";
        $booking['resource_id'] = (isset($data['resource_id']) && $data['resource_id'] != '' ? $data['resource_id'] : $booking['resource_id']);
        $booking['service_id'] = (isset($data['service_id']) && $data['service_id'] != '' ? $data['service_id'] : $booking['service_id']);
        $booking['booking_status_id'] = (isset($data['booking_status_id']) && $data['booking_status_id'] != '' ? $data['booking_status_id'] : $booking['booking_status_id']);

        //Needs to add client information too

        $booking->save();
        return $booking;
    }
}