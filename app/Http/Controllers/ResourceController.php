<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Resource;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\ServiceProvider;
use App\Http\Controllers\Controller;
use Exception;

class ResourceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
	public function __construct()
	{
        $this->middleware('permission:resource-list');
        $this->middleware('permission:resource-create', ['only' => ['create','store']]);
        $this->middleware('permission:resource-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:resource-delete', ['only' => ['destroy']]);
	    $this->middleware('auth');
	}

    /**
     * Display a listing of the resources.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $resources = Resource::getResourcesByUserServiceProvider();

        return view('resources.index', compact('resources'));
    }

    /**
     * Display a listing of the resources.
     *
     * @return Illuminate\View\View
     */
    public function list(Request $request)
    {
        $resources = Resource::getResourcesByUserServiceProviderTable($request);

        return $resources;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        $serviceProviders = ServiceProvider::getServideProvidersByCurrentUser();
        return view('resources.create', compact('serviceProviders'));
    }

    /**
     * Store a new resource in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $data = $this->getData($request);
        try {
            ServiceProvider::validateServiceProvider($request);
            $resource = Resource::create($data);
            if (isset($data['services'])) {
                $resource->services()->sync($data['services']);
            }
            $log = new Log();
            $log->record('CREATE', 'resource', $resource->id,  $resource);
            return redirect()->route('office.index')
                             ->with('success_message', 'Resource was successfully added!');

        } catch (Exception $exception) {

            return back()->withInput()
                        ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $resource = Resource::with('serviceprovider')->findOrFail($id);
        $serviceProviders = ServiceProvider::getServideProvidersByCurrentUser();

        return view('resources.show', compact('resource', 'serviceProviders'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $resource = Resource::findOrFail($id);
        $serviceProviders = ServiceProvider::getServideProvidersByCurrentUser();

        return view('resources.edit', compact('resource','serviceProviders'));
    }

    /**
     * Update the specified resource in the storage.
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
            ServiceProvider::validateServiceProvider($request);

            $resource = Resource::findOrFail($id);
            $resource->update($data);
            if (isset($data['services'])) {
                $resource->services()->sync($data['services']);
            } else {
                $resource->services()->sync([]);
            }
            $log = new Log();
            $log->record('UPDATE', 'resource', $resource->id,  $resource);
            return redirect()->route('office.index')
                             ->with('success_message', 'Resource was successfully updated!');

        } catch (Exception $exception) {

            return back()->withInput()
                        ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $resource = Resource::findOrFail($id);
            $resource->delete();
            $log = new Log();
            $log->record('DELETE', 'resource', $resource->id,  $resource);
            return redirect()->route('office.index')
                             ->with('success_message', 'Resource was successfully deleted!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }
    /**
     * Get services by resource id
     * (used in Vue)
     *
     * @param int $id resource id
     * @return Object
     */
    public function getServices($id)
    {
        $resource = Resource::findOrFail($id);
        return $resource->services;
    }
    /**
     * Return resources filtered by user serv
     *
     * @return void
     */
    public function getResourcesByUserServiceProvider()
    {
        return Resource::getResourcesByUserServiceProvider();
    }
    /**
     * Get resource by service provicer id
     *
     * @param int $id
     * @return void
     */
    public function getResourcesByServiceServiceProvider($id)
    {
        $service = Service::findOrFail($id);
        return Resource::getResourcesByServiceServiceProvider($service);
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
            'phone' => 'string|min:10|nullable',
            'email' => 'nullable|email',
            'service_provider_id' => 'nullable',
            'services' => 'nullable',

        ];


        $data = $request->validate($rules);
        return $data;
    }

}
