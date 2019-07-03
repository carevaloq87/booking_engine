<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use SimpleSAML_Auth_Simple;
use Spatie\Permission\Contracts\Role;

class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $user->save();
        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        if ($request->remember_me) {
            $token = $tokenResult->token;
            $token->expires_at = Carbon::now()->addWeeks(10);
            $token->save();
        }
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    /**
     * Login with VLA credentials
     *
     * @param Request $request
     * @return void
     */
    public function loginVLA(Request $request) {
        $simple_SAML = new SimpleSAML_Auth_Simple(env('SIMPLESML_SP'));
        $simple_SAML->requireAuth();
        $attributes = $simple_SAML->getAttributes();
        $name = $attributes['name'][0];
        if( !Auth::check() ) {
            if (isset($attributes['mail'][0]) && $attributes['mail'][0] != '') {
                $email = $attributes['mail'][0];
                $user = User::where('email',$email)->first();
                if (!$user) { // create the user if does not exist;
                    $user = User::create([
                            'name'     => $name,
                            'email'    => $attributes['mail'][0],
                            'password' => bcrypt(substr(str_shuffle(MD5(microtime())), 0, 16))
                    ]);
                    $role = \App\Models\Role::where('name','Authenticated')->first();
                    $user->assignRole($role->id);
                    $user->save();
                }
                Auth::login($user);
                if($user->service_provider_id == null) {
                    $service_providers = ServiceProvider::pluck('name','id')->all();
                    return view('welcome', compact('service_providers', 'name'));
                } else {
                    return redirect()->route('office.index');
                }

            }
        } else {
            $user = $user = Auth::user();
            if($user->service_provider_id == null) {
                $service_providers = ServiceProvider::pluck('name','id')->all();
                return view('welcome', compact('service_providers', 'name'));
            } else {
                return redirect()->route('office.index');
            }
        }

    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->delete();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}