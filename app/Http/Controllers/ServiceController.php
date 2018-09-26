<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Resource;
use Illuminate\Http\Request;
use App\Models\ServiceProvider;
use App\Http\Controllers\Controller;
use Exception;

class ServiceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
	public function __construct()
	{
        $this->middleware('permission:service-list');
        $this->middleware('permission:service-create', ['only' => ['create','store']]);
        $this->middleware('permission:service-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:service-delete', ['only' => ['destroy']]);
	    $this->middleware('auth');
	}

    /**
     * Display a listing of the services.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $services = Service::getServicesByUserServiceProvider();

        return view('services.index', compact('services'));
    }

    /**
     * Show the form for creating a new service.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        $serviceProviders = ServiceProvider::getServideProvidersByCurrentUser();
        return view('services.create', compact('serviceProviders'));
    }

    /**
     * Store a new service in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {

            $data = $this->getData($request);

            $service=Service::create($data);
            if (isset($data['resources'])) {
                $service->resources()->sync($data['resources']);
            }

            return redirect()->route('services.service.index')
                             ->with('success_message', 'Service was successfully added!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified service.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $service = Service::with('serviceprovider')->findOrFail($id);
        $sa = new \App\Models\ServiceAvailability($service);
        //return $sa->get();
        //dd($sa->getServiceAvailability(), $sa->getResourcesAvailability(), $sa->get());
        return view('services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified service.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $serviceProviders = ServiceProvider::getServideProvidersByCurrentUser();
        return view('services.edit', compact('service','serviceProviders'));
    }

    /**
     * Update the specified service in the storage.
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

            $service = Service::findOrFail($id);
            $service->update($data);
            if (isset($data['resources'])) {
                $service->resources()->sync($data['resources']);
            } else {
                $service->resources()->sync([]);
            }  

            return redirect()->route('services.service.index')
                             ->with('success_message', 'Service was successfully updated!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Remove the specified service from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $service = Service::findOrFail($id);
            $service->delete();

            return redirect()->route('services.service.index')
                             ->with('success_message', 'Service was successfully deleted!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }
    /**
     * Get resource by service id 
     * (used in Vue)
     *
     * @param int $id service id
     * @return Object
     */
    public function getResources($id)
    {
        $service = Service::findOrFail($id);
        return $service->resources;
    }

    /**
     * Return services filtered by user serv
     *
     * @return void
     */
    public function getServicesByUserServiceProvider()
    {
        return Service::getServicesByUserServiceProvider();
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
            'name' => 'string|min:1|max:255|nullable',
            'phone' => 'string|min:1|nullable',
            'email' => 'nullable',
            'description' => 'string|min:1|max:1000|nullable',
            'duration' => 'string|min:1',
            'listed_duration' => 'string|min:1',
            'interpreter_duration' => 'string|min:1',
            'listed_interpreter_duration' => 'string|min:1',
            'spaces' => 'string|min:1|nullable',
            'service_provider_id' => 'nullable',
            'resources' => 'nullable',

        ];

        $data = $request->validate($rules);

        return $data;
    }

}
