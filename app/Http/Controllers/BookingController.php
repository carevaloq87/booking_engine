<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Client;
use Exception;

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
            $data = $this->getData($request);
            $data['client_id'] = $this->findOrCreateClient($data['first_name'],
                                                        $data['last_name'],
                                                        $data['contact']);

            Booking::create($data);

            return redirect()->route('home')
                            ->with('success_message', 'Booking Status was successfully added!');

        } catch (Exception $exception) {

            return back()->withInput()
                        ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!' . $exception->getMessage()]);
        }

    }

    public function findOrCreateClient($first_name, $last_name, $contact)
    {
        $client = Client::getByName(trim($first_name), trim($last_name));
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
            'service_id' => 'required',
            'is_interpreter' => 'required',
            'date' => 'required',
            'start_hour' => 'required',
            'resource_id' => 'required',
            'time_length' => 'required',
            'comment' => 'string|nullable',
            'int_language' => 'nullable',
            'first_name' => 'string|required',
            'last_name' => 'string|required',
            'contact' => 'string|nullable'
        ];

        $data = $request->validate($rules);
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
}
