<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class CalendarController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
	public function __construct()
	{
	    $this->middleware('auth');
	}

    /**
     * Return calendar files
     *
     * @return JSON
     */
    public function getServiceHours()
    {
        $calendar = new Calendar();
        return $calendar->getServiceHours();
    }

    /**
     * Return calendar files
     *
     * @return JSON
     */
    public function getServiceDays(Request $request)
    {
        $calendar = new Calendar();
        return $calendar->getServiceDays($request);
    }

    /**
     * Save available days for a service
     *
     * @param Request $request
     * @return array
     */
    public function storeDays(Request $request)
    {
        try {
            $data = $this->getData($request);
            $calendar = new Calendar();
            $calendar->saveDaysInService($data);
            return $data;

        } catch (Exception $exception) {
            return back()->withInput()
                        ->withErrors(['unexpected_error' => $exception->getMessage()]);
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
            'id' => 'required|min:1',
            'dates' => 'required'
        ];
        $data = $request->validate($rules);

        return $data;
    }
}
