<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use App\Models\ServiceProvider;
use App\Http\Controllers\Controller;
use Exception;

class ServiceProvidersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
	public function __construct()
	{
        $this->middleware('permission:service_provider-list');
        $this->middleware('permission:service_provider-create', ['only' => ['create','store']]);
        $this->middleware('permission:service_provider-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:service_provider-delete', ['only' => ['destroy']]);
	    $this->middleware('auth');
	}

    /**
     * Display a listing of the service providers.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $serviceProviders = ServiceProvider::paginate(25);

        return view('service_providers.index', compact('serviceProviders'));
    }

    /**
     * Show the form for creating a new service provider.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {


        return view('service_providers.create');
    }

    /**
     * Store a new service provider in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {

            $data = $this->getData($request);

            $service_provider = ServiceProvider::create($data);

            $log = new Log();
            $log->record('CREATE', 'service provider', $service_provider->id,  $service_provider);
            return redirect()->route('service_providers.service_provider.index')
                             ->with('success_message', 'Service Provider was successfully added!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Display the specified service provider.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $serviceProvider = ServiceProvider::findOrFail($id);

        return view('service_providers.show', compact('serviceProvider'));
    }

    /**
     * Show the form for editing the specified service provider.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $serviceProvider = ServiceProvider::findOrFail($id);


        return view('service_providers.edit', compact('serviceProvider'));
    }

    /**
     * Update the specified service provider in the storage.
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

            $service_provider = ServiceProvider::findOrFail($id);
            $service_provider->update($data);

            $log = new Log();
            $log->record('UPDATE', 'service provider', $service_provider->id,  $service_provider);
            return redirect()->route('service_providers.service_provider.index')
                             ->with('success_message', 'Service Provider was successfully updated!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Remove the specified service provider from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $service_provider = ServiceProvider::findOrFail($id);
            $service_provider->delete();

            $log = new Log();
            $log->record('DELETE', 'service provider', $service_provider->id,  $service_provider);
            return redirect()->route('service_providers.service_provider.index')
                             ->with('success_message', 'Service Provider was successfully deleted!');

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
            'name' => 'required|string|min:1|max:255',
            'contact_name' => 'required|string|min:1',
            'phone' => 'required|string|min:10',
            'email' => 'required',

        ];

        $data = $request->validate($rules);

        return $data;
    }

}
