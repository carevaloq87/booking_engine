<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\BookingStatus;
use Exception;
use Illuminate\Validation\ValidationException as ValidationException;

class BookingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
	public function __construct()
	{
        $this->middleware('permission:booking-list');
        $this->middleware('permission:booking-create', ['only' => ['create','store']]);
	    $this->middleware('auth');
	}

    /**
     * Display a listing of the booking
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        return view('bookings.create');
    }

    /**
     * Store a new booking status in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {
            $booking = $this->createBooking($request);
            $log = new Log();
            $log->record('CREATE', 'booking', $booking->id,  $booking);
            return redirect()->back()
                            ->with('success_message', 'Booking was successfully created!');

        } catch (Exception $exception) {
            return back()->withInput()
                        ->withErrors(['unexpected_error' =>
                        $exception instanceof ValidationException? implode(" ",array_flatten($exception->errors())) :
                        $exception->getMessage()]);
        }

    }

    public function findOrCreateClient($first_name, $last_name, $contact)
    {
        if(!filter_var(trim($contact), FILTER_VALIDATE_EMAIL)){
            $contact= preg_replace('/\D+/', '', $contact);
        }
        $client = Client::getByNameAndContact($first_name, $last_name, $contact);
        if(!isset($client)){
            $client = Client::create([
                'first_name' => $first_name,
                'last_name'  => $last_name,
                'contact'    => $contact
            ]);
        }
        return $client->id;
    }

    /**
     * Get the request's data from the request.
     *
     * @param Illuminate\Http\Request\Request $request
     * @return array
     */
    protected function getData(Request $request)
    {

        $rules = [
            'service_id'        => 'required',
            'is_interpreter'    => 'required',
            'date'              => 'required',
            'start_hour'        => 'required',
            'resource_id'       => 'nullable',
            'time_length'       => 'nullable',
            'comment'           => 'string|nullable',
            'int_language'      => 'nullable',
            'first_name'        => 'string|required',
            'last_name'         => 'string|required',
            'contact'           => 'string|nullable',
            'data'              => 'nullable',
        ];
        $customMessages = [
            'required' => 'The :attribute field is required.'
        ];
        $data = $request->validate($rules, $customMessages);
        $data['day'] = date('D', strtotime($request['date']));
        return $data;
    }

    /**
     * Get service Bookings by date
     *
     * @param Request $request should include as parameters (sv_id = service id, start = start date, end = end date)
     * @return Json
     */
    public function getBookingsByDate(Request $request)
    {
        $booking_obj = new Booking();
        return $booking_obj->getFutureBookingsByServiceAndDate($request->sv_id, $request->start, $request->end);
    }
    /**
     * create a booking
     *
     * @param Request $request
     * @return void
     */
    public function createBooking(Request $request)
    {
        $data = $this->getData($request);
        $data['client_id'] = Client::findOrCreate(  $data['first_name'],
                                                    $data['last_name'],
                                                    $data['contact']);
        $bookingStatus = BookingStatus::where('name', 'Pending')->firstOrFail();
        $data['booking_status_id'] = $bookingStatus->id;
        $this->checkBookingDuplicity($data);
        return Booking::create($data);
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
