<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class HolidaysController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
	public function __construct()
	{
        $this->middleware('permission:holiday-list');
        $this->middleware('permission:holiday-create', ['only' => ['create','store']]);
        $this->middleware('permission:holiday-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:holiday-delete', ['only' => ['destroy']]);
        $this->middleware('permission:holiday-get',['only'=>['getTwoYearDates']]);
	    $this->middleware('auth');
	}


    /**
     * Display a listing of the holidays.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $holiday_obj = new Holiday();
        $holidays = $holiday_obj->getTwoYearDates();
        return view('holidays.index', compact('holidays'));
    }

    /**
     * Show the form for creating a new holiday.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        return view('holidays.create');
    }

    /**
     * Store a new holiday in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {

            $data = $this->getData($request);

            Holiday::create($data);

            return redirect()->route('holidays.holiday.index')
                            ->with('success_message', 'Holiday was successfully added!');

        } catch (Exception $exception) {

            return back()->withInput()
                        ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request! '.$exception->getMessage()]);
        }
    }

    /**
     * Display the specified holiday.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $holiday = Holiday::findOrFail($id);
        $date  = date('Y-m-d',strtotime($holiday->date));
        $holiday->date = $date;
        return view('holidays.show', compact('holiday'));
    }

    /**
     * Show the form for editing the specified holiday.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $holiday = Holiday::findOrFail($id);
        $date  = date('Y-m-d',strtotime($holiday->date));
        $holiday->date = $date;
        return view('holidays.edit', compact('holiday'));
    }

    /**
     * Update the specified holiday in the storage.
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

            $holiday = Holiday::findOrFail($id);
            $holiday->update($data);

            return redirect()->route('holidays.holiday.index')
                            ->with('success_message', 'Holiday was successfully updated!');

        } catch (Exception $exception) {

            return back()->withInput()
                        ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request! '. $exception->getMessage()]);
        }
    }

    /**
     * Remove the specified holiday from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $holiday = Holiday::findOrFail($id);
            $holiday->delete();

            return redirect()->route('holidays.holiday.index')
                             ->with('success_message', 'Holiday was successfully deleted!');

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
            'date' => 'required|unique:holidays',
            'description' => 'string|nullable|min:0',
        ];

        $data = $request->validate($rules);


        return $data;
    }
    /**
     * Get holiday by id
     *
     * @param int $id
     * @return void
     */
    public function getDateById($id)
    {
        try {
            $holiday = Holiday::findOrFail($id);
            $date  = date('Y-m-d',strtotime($holiday->date));
            return $date;

        } catch (Exception $exception) {

            return back()->withInput()
                        ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }
    /**
     * Get the holidays for the current and next year.
     *
     * @return void
     */
    public function getTwoYearDates() 
    {
        try {
            $holiday_obj = new Holiday();
            $holiday =  $holiday_obj->getTwoYearDates();
            return $holiday;

        } catch (Exception $exception) {

            return back()->withInput()
                        ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

}
