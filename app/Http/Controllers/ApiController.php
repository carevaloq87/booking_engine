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
            'data'              => 'nullable'
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
    public function checkBookingDuplicity($data)
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

}
