<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm() {
        return view( 'login.index' );
    }

    public function login() {

        $credentials = $this->validate( request(), [
            'email' => 'email|required|string',
            'password' => 'required|string'
        ]);

        if ( Auth::attempt( $credentials ) ) {
            return redirect()->route( 'dashboard' );
        };

        return back()->withErrors( ['email' => trans( 'auth.failed' )] )
            ->withInput( request(['email']) );
    }

    public function logout() {
        Auth::logout();

        return redirect()->route( 'home.index' );
    }
}
