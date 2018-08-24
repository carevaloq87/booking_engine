<?php

namespace App\Http\Controllers;

use App\Models\Resource;
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
        $resources = Resource::with('serviceprovider')->paginate(25);

        return view('resources.index', compact('resources'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        $serviceProviders = ServiceProvider::pluck('name','id')->all();

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
        try {

            $data = $this->getData($request);

            Resource::create($data);

            return redirect()->route('resources.resource.index')
                             ->with('success_message', 'Resource was successfully added!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
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

        return view('resources.show', compact('resource'));
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
        $serviceProviders = ServiceProvider::pluck('name','id')->all();

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
        try {

            $data = $this->getData($request);

            $resource = Resource::findOrFail($id);
            $resource->update($data);

            return redirect()->route('resources.resource.index')
                             ->with('success_message', 'Resource was successfully updated!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
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

            return redirect()->route('resources.resource.index')
                             ->with('success_message', 'Resource was successfully deleted!');

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
            'phone' => 'string|min:10|nullable',
            'email' => 'nullable',
            'service_provider_id' => 'nullable',

        ];


        $data = $request->validate($rules);




        return $data;
    }

}
