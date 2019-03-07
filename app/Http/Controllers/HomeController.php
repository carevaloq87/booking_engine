<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Set the service provider for new users.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function setServiceProvider(Request $request)
    {
        try {
            $user = Auth::user();
            $data = [
                'name'  => empty(trim($request->name,' ')) ?
                            $user->name :
                            filter_var($request->name, FILTER_SANITIZE_STRING),
                'service_provider_id' => $request->service_provider_id
            ];
            if($user->roles()) {
                $user->removeRole($user->roles()->first()->id);
            }
            $role = \App\Models\Role::where('name','Standard')->first();
            $user->assignRole($role->id);
            $user->update($data);

            return redirect()->route('office.index')
                            ->with('success_message', 'User profile was updated');

        } catch (Exception $exception) {

            return back()->withInput()
                        ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }
}
