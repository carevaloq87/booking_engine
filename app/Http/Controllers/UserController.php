<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use DB;
use Exception;
use Hash;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
	public function __construct()
	{
        $this->middleware('permission:users-list');
        $this->middleware('permission:users-create', ['only' => ['create','store']]);
        $this->middleware('permission:users-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:users-delete', ['only' => ['destroy']]);
	    $this->middleware('auth');
	}

    /**
     * Display a listing of the users.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $users = User::paginate(25);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new 
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a new user in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {
            //validate the password.

            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password'  => 'required|confirmed',
                'roles' => 'required'
            ]);

            //create and save the user
            $user = User::create($this->getUserFields($request));

            $user->assignRole($request->input('roles'));
            /*
            $user
	            ->roles()
	            ->attach(Role::where('id',  $request->role )->first());
            */
            return redirect()->route('users.index')
                             ->with('success_message', 'User was successfully added!');

        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified 
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified 
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $userRole = $user->roles->pluck('name','name')->all();

        return view('users.edit', compact('user','roles','userRole'));
    }

    /**
     * Update the specified user in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        try {

            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,'.$id,
                //'password' => 'same:confirm-password',
                'roles' => 'required'
            ]);

            $user = User::findOrFail($id);
            $user->update($this->getUserFields($request));
            /*
            $user->roles()
                 ->sync( Role::where('id',  $request->role )
                 ->first());
            */

            DB::table('model_has_roles')->where('model_id',$id)->delete();
            $user->assignRole($request->input('roles'));

            return redirect()->route('users.index')
                             ->with('success_message', 'User was successfully updated!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }
    /**
     * Return sanitize  user fields
     *
     * @param Request $request
     * @return void
     */
    public function getUserFields(Request $request)
    {
        return [
                'name'  => filter_var($request->name, FILTER_SANITIZE_STRING),
                'email' => filter_var($request->email, FILTER_VALIDATE_EMAIL),
                'password' => bcrypt($request->password)
            ];
    }

    /**
     * Remove the specified user from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->route('users.index')
                             ->with('success_message', 'User was successfully deleted!');

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
            'name' => 'required|nullable|string|min:0|max:255',
            'email' => 'required',
            'password' => 'required',

        ];


        $data = $request->validate($rules);




        return $data;
    }

}
