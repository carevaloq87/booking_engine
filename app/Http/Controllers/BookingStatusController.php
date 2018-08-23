<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookingStatus;
use App\Http\Controllers\Controller;
use Exception;

class BookingStatusController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
	public function __construct()
	{
        $this->middleware('permission:booking_status-list');
        $this->middleware('permission:booking_status-create', ['only' => ['create','store']]);
        $this->middleware('permission:booking_status-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:booking_status-delete', ['only' => ['destroy']]);
	    $this->middleware('auth');
	}

    /**
     * Display a listing of the booking status.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $bookingStatuses = BookingStatus::paginate(25);

        return view('booking_status.index', compact('bookingStatuses'));
    }

    /**
     * Show the form for creating a new booking status.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {


        return view('booking_status.create');
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

            BookingStatus::create($data);

            return redirect()->route('booking_status.booking_status.index')
                             ->with('success_message', 'Booking Status was successfully added!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Display the specified booking status.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $bookingStatus = BookingStatus::findOrFail($id);

        return view('booking_status.show', compact('bookingStatus'));
    }

    /**
     * Show the form for editing the specified booking status.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $bookingStatus = BookingStatus::findOrFail($id);


        return view('booking_status.edit', compact('bookingStatus'));
    }

    /**
     * Update the specified booking status in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        try {

            $data = $this->getData($request);

            $bookingStatus = BookingStatus::findOrFail($id);
            $bookingStatus->update($data);

            return redirect()->route('booking_status.booking_status.index')
                             ->with('success_message', 'Booking Status was successfully updated!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Remove the specified booking status from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $bookingStatus = BookingStatus::findOrFail($id);
            $bookingStatus->delete();

            return redirect()->route('booking_status.booking_status.index')
                             ->with('success_message', 'Booking Status was successfully deleted!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
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
            'name' => 'required|string|min:1',
            'description' => 'string|min:1|nullable',

        ];

        $data = $request->validate($rules);


        return $data;
    }

}
