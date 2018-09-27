<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        $this->middleware('permission:booking_status-list');
        $this->middleware('permission:booking_status-create', ['only' => ['create','store']]);

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
}
