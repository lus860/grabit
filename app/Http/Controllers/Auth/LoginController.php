<?php

namespace App\Http\Controllers\Auth;

use App\Repositories\UserRepository;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

use App\Models\User_activation as Activation;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {
        logout as performLogout;
    }
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    protected $user;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $user)
    {
        $this->middleware('guest', ['except' => 'logout']);
        $this->user = $user;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);
        if ($this->attemptLogin($request)) {
            if (auth()->user()->is_activated == '0') {
                auth()->logout();
                return back()->with('warning', "First please activate your account.");
            }

            $response = $this->user->attachRole(auth()->user());
            if ($response == 1){
                return redirect()->to('/backend/admin');
            }
            return redirect()->to('/welcome');
        } else {
            return back()->with('error', 'Your username or password are wrong.');
        }
    }

    /**
     * Override logout
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        $this->performLogout($request);
//        return redirect('/cms');
        return redirect('/');

    }

    public function checkRole($user){

    }

    /**
     * @param Activation $activation
     * @param $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function userActivation(Activation $activation, $token)
    {
        $check = $activation->where('token', $token)->first();
        if (!is_null($check)) {
            $user = $this->user->find($check->id_user);
            if ($user->is_activated == 1) {
                return redirect()->to('login')
                    ->with('success', "User are already actived.");
            }
            $user->update(['is_activated' => 1]);
            $activation->where('token', $token)->delete();
            return redirect()->to('login')
                ->with('success', "User activated successfully.");
        }
        return redirect()->to('login')
            ->with('warning', "Your token is invalid.");
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }
}
