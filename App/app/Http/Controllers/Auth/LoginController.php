<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LoginController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm() {
//        return view( 'classimax.pages.login' );
        return view( 'login.index' );
    }

    public function login() {

        $credentials = $this->validate( request(), [
            'email' => 'email|required|string',
            'password' => 'required|string'
        ]);

        if ( Auth::attempt( $credentials ) ) {
            $session = Auth::user();
            if( $session->status === 1 || $session->status === 3 ) {
                return redirect()->route('dashboard');
            } else {
                Auth::logout();

                return back()->withErrors( ['email' => 'Por favor, valida tu cuenta.'] )
                    ->withInput( request(['email']) );
            }
        };

        return back()->withErrors( ['email' => trans( 'auth.failed' )] )
            ->withInput( request(['email']) );
    }

    public function logout() {
        Auth::logout();

        return redirect()->route( 'home.index' );
    }
}
