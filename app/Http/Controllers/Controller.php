<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Redirect;

class Controller extends BaseController
{
    public function __construct()
    {
        $this->middleware('guest:web')->except('postLogout');
    }
    protected $redirectTo = '/home';
    public function login(Request $request)
    {

        $username = $request->input('username');
        $password = $request->input('password');
        error_log($username);
        error_log("masuk controller");
        if (Auth::attempt(['username' => $username, 'password' => $password])) {

            // validation successful!
            // redirect them to the secure section or whatever
            // return Redirect::to('secure');
            // for now we'll just echo success (even though echoing in a controller is bad)
             error_log('error login');

            return redirect('/home');
        } else {

            // validation not successful, send back to form 
            return redirect('/');
        }
    }
    public function postLogout(Request $request)
    {
        error_log('logpit');
        $request->session()->invalidate();

        return redirect('/login');
    }
}
