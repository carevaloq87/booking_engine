<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Service;
use App\Models\Resource;
use App\Models\ServiceProvider;
use App\Models\ServiceAvailability;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        $data = $this->getData($request);
        try {
            self::validateServiceProvider($request);

            $service=Service::create($data);
            if (isset($data['resources'])) {
                $service->resources()->sync($data['resources']);
            }

            $log = new Log();
            $log->record('CREATE', 'service', $service->id,  $service);
            return redirect()->route('services.service.index')
                            ->with('success_message', 'Service was successfully added!');

        } catch (Exception $exception) {

            return back()->withInput()
                        ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }
    /**
     * Validate if the selected resources belong to the service provider
     *
     * @param Request $request
     * @return void
     */
    private function validateServiceProvider($request)
    {
        if($request['resources'] && $request['service_provider_id']) {
            foreach ($request['resources'] as $key => $resource) {
                $resource_obj = Resource::findOrFail($resource);
                if($resource_obj->service_provider_id != $request['service_provider_id']) {
                    throw new Exception("Error. One or more resources do not belong to the selected service provider");
                }
            }
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
        $data = $this->getData($request);
        try {
            self::validateServiceProvider($request);

            $service = Service::findOrFail($id);
            $service->update($data);
            if (isset($data['resources'])) {
                $service->resources()->sync($data['resources']);
            } else {
                $service->resources()->sync([]);
            }
            $log = new Log();
            $log->record('UPDATE', 'service', $service->id,  $service);
            return redirect()->route('services.service.index')
                            ->with('success_message', 'Service was successfully updated!');

        } catch (Exception $exception) {

            return back()->withInput()
                        ->withErrors(['unexpected_error' => $exception->getMessage()]);
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
            $log = new Log();
            $log->record('DELETE', 'service', $service->id,  $service);
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
     * Get resource by service provicer id
     *
     * @param int $id
     * @return void
     */
    public function getServicesByResourceServiceProvider($id)
    {
        $resource = Resource::findOrFail($id);
        return Service::getServicesByResourceServiceProvider($resource);
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
            'email' => 'nullable|email',
            'description' => 'string|min:1|max:1000|nullable',
            'duration' => 'required|numeric|min:10|max:480',
            'listed_duration' => 'string|min:1',
            'interpreter_duration' => 'required|numeric|min:10|max:480',
            'listed_interpreter_duration' => 'string|min:1',
            'spaces' => 'string|min:1|nullable',
            'service_provider_id' => 'required',
            'resources' => 'nullable',
            'color' => 'nullable',
        ];

        $data = $request->validate($rules);

        return $data;
    }
    /**
     * Get Avaialability by Id
     *
     * @param int $id Service id
     * @return void
     */
    public function getAvailabilityById($id)
    {
        $start = request('start');
        $end = request('end');
        $service = Service::findOrFail($id);
        $serviceAvailability = new ServiceAvailability($service, $start, $end);
        return $serviceAvailability->get();
    }

}
