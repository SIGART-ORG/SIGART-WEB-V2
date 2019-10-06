<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use App\Models\ServiceRequestDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function getServiceRequest() {

        $ServiceRequestClass = new ServiceRequest();

        $numPerpage = 10;

        $data = $ServiceRequestClass->listData( $numPerpage, 1 );

        return response()->json(['serviceRequest' => $data]);

    }

    public function generateServiceRequest( Request $request ) {
        $details = $request->details;

        if( ! empty( $details ) && is_array( $details ) && count( $details ) ) {
            $userSession = Auth::user();

            $ServiceRequestClass = new ServiceRequest();
            $ServiceRequestClass->sites_id      = 1;
            $ServiceRequestClass->customers_id  = $userSession->customers_id;
            $ServiceRequestClass->date_reg      = date( 'Y-m-d' );
            $ServiceRequestClass->date_aproved  = date( 'Y-m-d' );
            $ServiceRequestClass->description   = 'Solicitud de servicio generado el ' . date( 'd/m/Y' );

            if( $ServiceRequestClass->save() ) {
                foreach( $details as $detail ) {
                    $ServiceRequestDetailClass = new ServiceRequestDetail();
                    $ServiceRequestDetailClass->service_requests_id = $ServiceRequestClass->id;
                    $ServiceRequestDetailClass->name                = Str::limit( $detail['item'], 17, '...' );
                    $ServiceRequestDetailClass->description         = $detail['item'];
                    $ServiceRequestDetailClass->quantity            = $detail['count'];
                    $ServiceRequestDetailClass->assumed_customer    = 0;

                    $ServiceRequestDetailClass->save();
                }
            }

            return response()->json([
                'status'    => true,
                'message'   => 'OK'
            ]);
        }

        return response()->json([
            'status'    => false,
            'message'   => 'No se pudo generar la solicitud de servicio.'
        ]);
    }
}
