<?php

namespace App\Http\Controllers;

use App\Models\CustomerLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth' );
    }

    public function confirmation( Request $request ) {
        $hash = $request->token;

        $customerLogin = CustomerLogin::where('remember_token', $hash)->first();

        if( $customerLogin ) {
            if( $customerLogin->status == 0 ) {

                $customerLogin->status = 3;
                $customerLogin->email_verified_at = date( 'Y-m-d H:i:s' );
                $customerLogin->remember_token = NULL;
                $customerLogin->save();
            }
        }

        return redirect()->route('home.index');
    }

    public function index() {

        $user = Auth::user();

        $data = [
            'joined' => date( 'd/m/Y', strtotime( $user->created_at ) )
        ];

        return view( 'classimax.pages.dashboard', $data );
    }

}
