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
    public function getServiceHours(Request $request)
    {
        $calendar = new Calendar();
        return $calendar->getServiceHours($request);
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
     * Return calendar files
     *
     * @return JSON
     */
    public function getResourceDays(Request $request)
    {
        $calendar = new Calendar();
        return $calendar->getResourceDays($request);
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
     * Save available hours for a service
     *
     * @param Request $request
     * @return array
     */
    public function storeHours(Request $request)
    {
        try {
            $data = $this->getHoursData($request);

            $calendar = new Calendar();
            $calendar->saveHoursInService($data);
            return $data;

        } catch (Exception $exception) {
            return back()->withInput()
                        ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Return calendar files
     *
     * @return JSON
     */
    public function getResourceHours(Request $request)
    {
        $calendar = new Calendar();
        return $calendar->getResourceHours($request);
    }

    /**
     * Save adhoc days and hours for a service
     *
     * @param Request $request
     * @return array
     */
    public function storeAdhoc(Request $request)
    {
        try {
            $data = $this->getAdhocData($request);

            $calendar = new Calendar();
            $calendar->saveAdhocInService($data);
            return $data;

        } catch (Exception $exception) {
            return back()->withInput()
                        ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }


    /**
     * Save unavailable hours for a resource
     *
     * @param Request $request
     * @return array
     */
    public function storeResourceHours(Request $request)
    {
        try {
            $data = $this->getHoursData($request);

            $calendar = new Calendar();
            $calendar->saveHoursInResource($data);
            return $data;

        } catch (Exception $exception) {
            return back()->withInput()
                        ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Save unavailable days for a resource
     *
     * @param Request $request
     * @return array
     */
    public function storeResourceDays(Request $request)
    {
        try {
            $data = $this->getData($request);
            $calendar = new Calendar();
            $calendar->saveDaysInResource($data);
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

    /**
     * Get the request's data from the request.
     *
     * @param Illuminate\Http\Request\Request $request
     * @return array
     */
    protected function getHoursData(Request $request)
    {
        $rules = [
            'id' => 'required|min:1',
            'hours' => 'required'
        ];
        $data = $request->validate($rules);

        return $data;
    }

    /**
     * Get the request's data from the request.
     *
     * @param Illuminate\Http\Request\Request $request
     * @return array
     */
    protected function getAdhocData(Request $request)
    {
        $rules = [
            'id' => 'required|min:1',
            'hours' => 'required'
        ];
        $data = $request->validate($rules);

        return $data;
    }
}
