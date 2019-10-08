<?php

namespace App\Http\Controllers;

use App\Models\CustomerLogin;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
        return view( 'classimax.pages.dashboard' );
    }

    public function settings() {

        $userSession = Auth::user();

        $current = Str::slug('Mis Solicitudes');

        $ServiceRequestClass = new ServiceRequest();

        $url = [
            [
                'id'        => Str::slug('Mis Solicitudes'),
                'name'      => 'Mis Solicitudes',
                'count'     => $ServiceRequestClass->countData( 1 ),
                'href'      => '#',
                'icon'      => 'fa-clipboard'
            ],
            [
                'id'        => Str::slug('Cotizaciones por aprobar'),
                'name'      => 'Cotizaciones por aprobar',
                'count'     => 23,
                'href'      => '#',
                'icon'      => 'fa-bolt'
            ],
            [
                'id'        => Str::slug('Mis Cotizaciones'),
                'name'      => 'Mis Cotizaciones',
                'count'     => 23,
                'href'      => '#',
                'icon'      => 'fa-calculator'
            ],
            [
                'id'        => Str::slug('Mis cotizaciones archivadas'),
                'name'      => 'Mis cotizaciones archivadas',
                'count'     => 12,
                'href'      => '#',
                'icon'      => 'fa-clipboard'
            ],
            [
                'id'        => Str::slug('Cerrar Sesión'),
                'name'      => 'Cerrar Sesión',
                'count'     => 0,
                'href'      => '#',
                'icon'      => 'fa-cog'
            ],
            [
                'id'        => Str::slug('Eliminar cuenta'),
                'name'      => 'Eliminar cuenta',
                'count'     => 0,
                'href'      => '#',
                'icon'      => 'fa-power-off'
            ]
        ];

        $user = [
            'id'        => $userSession->id,
            'name'      => $userSession->name,
            'joined'    => date( 'd/m/Y', strtotime( $userSession->created_at ) ),
            'profile'   => route( 'profile' ),
            'avatar'    => 'https://ui-avatars.com/api/?name=' . Str::slug( $userSession->name, '+' ) . '&rounded=true&background=0D8ABC&color=fff&size=150'
        ];

        $settings = [
            'user'  => $user,
            'urls'  => $url,
            'current'   => $current
        ];

        return response()->json( $settings );
    }

    public function isCompleteRegister() {

    }

}
