<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\ServiceAvailability;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BookingController;
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
    public function getAvailability($service_id, $start_date, $end_date)
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
            $booking_obj = new BookingController();
            $booking = $booking_obj->createBooking($request);
            return response()->json($booking->id);

        } catch (Exception $exception) {
            return response()->json(['error'=>$exception instanceof ValidationException?
                                            implode(" ",array_flatten($exception->errors())) :
                                            $exception->getMessage()]);
        }
    }

}
