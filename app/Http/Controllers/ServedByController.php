<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServedBy;
use App\Models\Resource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class ServedByController extends Controller
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
     * Display a listing of the served bies.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $servedBy = ServedBy::with('service','resource')->paginate(25);
//dd($servedBy);
        return view('served_by.index', compact('servedBy'));
    }

    /**
     * Show the form for creating a new served by.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        $services = Service::pluck('name','id')->all();
        $resources = Resource::pluck('name','id')->all();

        return view('served_by.create', compact('services','resources'));
    }

    /**
     * Store a new served by in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {

            $data = $this->getData($request);

            ServedBy::create($data);

            return redirect()->route('served_by.served_by.index')
                             ->with('success_message', 'Served By was successfully added!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Display the specified served by.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $servedBy = ServedBy::with('service','resource')->findOrFail($id);

        return view('served_by.show', compact('servedBy'));
    }

    /**
     * Show the form for editing the specified served by.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $servedBy = ServedBy::findOrFail($id);
        $services = Service::pluck('name','id')->all();
        $resources = Resource::pluck('name','id')->all();

        return view('served_by.edit', compact('servedBy','services','resources'));
    }

    /**
     * Update the specified served by in the storage.
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

            $servedBy = ServedBy::findOrFail($id);
            $servedBy->update($data);

            return redirect()->route('served_by.served_by.index')
                             ->with('success_message', 'Served By was successfully updated!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Remove the specified served by from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $servedBy = ServedBy::findOrFail($id);
            $servedBy->delete();

            return redirect()->route('served_by.served_by.index')
                             ->with('success_message', 'Served By was successfully deleted!');

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
            'service_id' => 'required',
            'resource_id' => 'required',

        ];


        $data = $request->validate($rules);




        return $data;
    }

}
