<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Client;
use App\Models\BookingStatus;
use App\Models\Booking;
use App\Models\ServiceAvailability;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException as ValidationException;

use Exception;

class ApiController extends Controller
{

    /**
     * Get Availability for a specific service and date range
     *
     * @param int $service_id
     * @param string  $start_date 'YYYY-MM-DD'
     * @param string $end_date 'YYYY-MM-DD'
     * @return void
     */
    public function getServiceAvailability($service_id, $start_date, $end_date)
    {
        $service = Service::findOrFail($service_id);
        $serviceAvailability = new ServiceAvailability($service, $start_date, $end_date);
        return response()->json($serviceAvailability->get());
    }
    /**
     * Create a booking
     *
     * @param Request $request
     * @return void
     */
    public function storeBooking(Request $request)
    {
        try {
            $data = $this->getBookingData($request);
            $data['client_id'] = Client::findOrCreate(  $data['first_name'],
                                                        $data['last_name'],
                                                        $data['contact']);
            $bookingStatus = BookingStatus::where('name', 'Pending')->firstOrFail();
            $data['booking_status_id'] = $bookingStatus->id;
            $this->checkBookingDuplicity($data);
            $booking=Booking::create($data);
            return response()->json($booking->id);

        } catch (Exception $exception) {
            return response()->json(['error'=>$exception instanceof ValidationException?
                                            implode(" ",array_flatten($exception->errors())) :
                                            $exception->getMessage()],400);
        }
    }
    /**
     * Delete a booking
     *
     * @param Int $bo_id Booking Id to delete
     * @return void
     */
    public function deleteBooking($bo_id)
    {
        try {
            $booking_obj = new Booking();
            $booking = $booking_obj->findOrFail($bo_id);
            return response()->json($booking->delete());

        } catch (Exception $exception) {
            return response()->json(['error'=>$exception instanceof ValidationException?
                                            implode(" ",array_flatten($exception->errors())) :
                                            $exception->getMessage()]);
        }
    }

    /**
     * Update a booking
     *
     * @param Request $request
     * @return void
     */
    public function updateBooking(Request $request, $bo_id)
    {
        try {
            $booking_obj = new Booking();
            $booking = $booking_obj->updateBooking($request->all(), $bo_id);
            return response()->json($booking);

        } catch (Exception $exception) {
            return response()->json(['error'=>$exception instanceof ValidationException?
                                            implode(" ",array_flatten($exception->errors())) :
                                            $exception->getMessage()]);
        }
    }

    /**
     * Get the request's data from the request.
     *
     * @param Illuminate\Http\Request\Request $request
     * @return array
     */
    protected function getBookingData(Request $request)
    {

        $rules = [
            'service_id'        => 'required',
            'is_interpreter'    => 'required',
            'date'              => 'required',
            'start_hour'        => 'required',
            'resource_id'       => 'required',
            'time_length'       => 'required',
            'comment'           => 'string|nullable',
            'int_language'      => 'nullable',
            'first_name'        => 'string|required',
            'last_name'         => 'string|required',
            'contact'           => 'string|nullable',
            'data'              => 'nullable',
            'created_by'        => 'nullable'
        ];
        $customMessages = [
            'required' => 'The :attribute field is required.'
        ];
        $data = $request->validate($rules, $customMessages);
        $data['day'] = date('D', strtotime($request['date']));
        return $data;
    }

    /**
     * Get the booking by services id and date
     *
     * @param string $services
     * @return void
     */
    public function getBookingsByServiceId($services, $start_date, $end_date)
    {
        try {
            $bookings = Booking::with('client')
                                ->with('bookingstatus')
                                ->with('resource')
                                ->with('service')
                                ->whereIn('service_id', explode(",",$services))
                                ->whereBetween ('date', [$start_date, $end_date])
                                ->get();
            return response()->json($bookings);
        } catch (Exception $exception) {
            return response()->json(['error'=>$exception instanceof ValidationException?
                                            implode(" ",array_flatten($exception->errors())) :
                                            $exception->getMessage()]);
        }
    }
    /**
     * Check if exist a booking with similar resource, service date and hour
     *
     * @param array $data
     * @return void
     */
    private function checkBookingDuplicity($data)
    {
        $booking = Booking::where('date', $data['date'])
                            ->where('service_id', $data['service_id'])
                            ->where('resource_id', $data['resource_id'])
                            ->where('start_hour',$data['start_hour'])
                            ->first();
        if (isset($booking)) {
            throw new Exception('Error. Duplicate booking');
        }
    }

    /**
     * Get the booking status by name
     *
     * @param string $booking_status
     * @return void
     */
    public function getBookingStatusByName($booking_status)
    {
        try {
            $booking_status =  BookingStatus::where('name', $booking_status)->firstOrFail();;
            return response()->json($booking_status);
        } catch (Exception $exception) {
            return response()->json(['error'=>$exception instanceof ValidationException?
                                            implode(" ",array_flatten($exception->errors())) :
                                            $exception->getMessage()], 400);
        }
    }
    /**
     * Get all booking status
     *
     * @return void
     */
    public function getAllBookingStatus()
    {
        try {
            return BookingStatus::all();
        } catch (Exception $exception) {
            return response()->json(['error'=>$exception instanceof ValidationException?
            implode(" ",array_flatten($exception->errors())) :
            $exception->getMessage()], 400);
        }
    }
    /**
     * Get all services
     *
     * @return void
     */
    public function getAllServices()
    {
        try {
            return Service::with('serviceprovider')->get();
        } catch (Exception $exception) {
            return response()->json(['error'=>$exception instanceof ValidationException?
            implode(" ",array_flatten($exception->errors())) :
            $exception->getMessage()], 400);
        }
    }
    /**
     * Get services by service provider name
     *
     * @return array
     */
    public function getServiceBySPName($service_provider_name)
    {
        try {
            $service_provider =
            $services = Service::with('serviceprovider')
                                ->whereHas('serviceprovider' , function($query) use ($service_provider_name){
                                    $query->where("name",'LIKE', '%'.$service_provider_name.'%');
                                })->get();
            return $services;
        } catch (Exception $exception) {
            return response()->json(['error'=>$exception instanceof ValidationException?
            implode(" ",array_flatten($exception->errors())) :
            $exception->getMessage()], 400);
        }
    }

    /**
     * Get future bookings by service provider name and date
     *
     * @param String $service_provider_name
     * @param Date $start_date
     * @param Date $end_date
     * @return Array Bookings made by the office in the specified date
     */
    public function getBookingsBySPNameAndDate($service_provider_name, $start_date, $end_date)
    {
        try {
            $booking_obj = new Booking();
            $services = Service::with('serviceprovider')
                                ->whereHas('serviceprovider' , function($query) use ($service_provider_name){
                                    $query->where("name",'LIKE', '%'.$service_provider_name.'%');
                                })->get();

            $bookings = [];
            foreach ($services as $service) {
                $appts = $booking_obj->getFutureBookingsInfoByServiceAndDate($service->id, $start_date, $end_date);
                if($appts){
                    foreach($appts as $appointment){
                        $time = $appointment->start_hour;
                        $appointment->time = sprintf('%02d', floor($time / 60)) . ':' . sprintf('%02d', ($time % 60)); //Transform amount of minutes to hours and minutes
                        $appointment->ServiceName = $service->name;
                        $appointment->ServiceProviderName = $service->serviceprovider->name;
                        $appointment->data = json_decode($appointment->data);
                        $appointment->date = explode(' ', $appointment->date)[0];
                        $bookings[] = $appointment;
                    }
                }
            }
            return $bookings;
        } catch (Exception $exception) {
            return response()->json(['error'=>$exception instanceof ValidationException?
            implode(" ",array_flatten($exception->errors())) :
            $exception->getMessage()], 400);
        }
    }

    /**
     * Get bookings by Orbit's User ID
     *
     * @param Int $created_by User Id of Orbit
     * @return Array Bookings made by the user provided
     */
    public function getBookingsByOBUserId($created_by)
    {
        try {
            $booking_obj = new Booking();
            $appts = $booking_obj-> getBookingsByOBUserId($created_by);
            if($appts){
                foreach($appts as $appointment){
                    $time = $appointment->start_hour;
                    $appointment->time = sprintf('%02d', floor($time / 60)) . ':' . sprintf('%02d', ($time % 60)); //Transform amount of minutes to hours and minutes
                    $appointment->ServiceName = $appointment['service']['name'];
                    $appointment->data = json_decode($appointment->data);
                    $appointment->date = explode(' ', $appointment->date)[0];
                    unset( $appointment['service']);
                    $bookings[] = $appointment;
                }
            }
            return $bookings;
        } catch (Exception $exception) {
            return response()->json(['error'=>$exception instanceof ValidationException?
            implode(" ",array_flatten($exception->errors())) :
            $exception->getMessage()], 400);
        }

    }

    /**
     * Get All Bookings in a date
     *
     * @param date $date
     * @return void
     */
    public function getBookingsByDate($date)
    {
        try {
            $bookings = Booking::with('client')
                                ->where('date', $date)->get();
            return response()->json($bookings);
        } catch (Exception $exception) {
            return response()->json(['error'=>$exception instanceof ValidationException?
            implode(" ",array_flatten($exception->errors())) :
            $exception->getMessage()], 400);
        }
    }

}
