<?php

namespace App\Http\Controllers;

use App\Models\SaleQuotation;
use App\Models\ServiceRequest;
use App\Models\ServiceRequestDetail;
use App\Models\SiteVoucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    const PATH_UPLOAD_SR = 'uploads/service-request/';

    public function getServiceRequest() {

        $ServiceRequestClass = new ServiceRequest();

        $numPerpage = 10;

        $data = $ServiceRequestClass->listData( $numPerpage );

        foreach ( $data as $idx => $item ) {
            $data[ $idx ]->dateRegFormat = date( 'd/m/Y', strtotime( $item->date_reg ) );
            $data[ $idx ]->dateSendFormat = ( ! empty( $item->date_send ) ? date( 'd/m/Y H:i:s', strtotime( $item->date_send ) ) : '' );
        };

        return response()->json(['serviceRequest' => $data]);

    }

    public function generateServiceRequest( Request $request ) {
        $id         = $request->id;
        $name       = $request->name;
        $details    = $request->details;

        $SiteVoucherClass = new SiteVoucher();
        $typeVoucher = 2;

        if( ! empty( $details ) && is_array( $details ) && count( $details ) ) {
            $userSession = Auth::user();

            if( ! empty( $id ) && $id > 0 ) {

                $ServiceRequestClass = ServiceRequest::where( 'id', $id )
                    ->where( 'customers_id', $userSession->customers_id )
                    ->whereIn( 'is_send', [ 0, 2 ])
                    ->first();

                if( ! empty( $ServiceRequestClass ) && $ServiceRequestClass->id > 0 ) {

                    $ServiceRequestClass->description = $name;

                } else {

                    return response()->json([
                        'status'    => false,
                        'message'   => 'No se pudo editar la solicitud de servicio.'
                    ]);

                }
            } else {
                $correlative = $SiteVoucherClass->getNumberVoucherSite( $typeVoucher );

                $ServiceRequestClass = new ServiceRequest();
                $ServiceRequestClass->sites_id = 1;
                $ServiceRequestClass->type_vouchers_id = $typeVoucher;
                $ServiceRequestClass->date_emission = date('Y-m-d');
                $ServiceRequestClass->num_request = $correlative['correlative'];
                $ServiceRequestClass->customers_id = $userSession->customers_id;
                $ServiceRequestClass->date_reg = date('Y-m-d');
                $ServiceRequestClass->date_aproved = date('Y-m-d');
                $ServiceRequestClass->description = $name;
            }

            if( $request->hasFile( 'attachment' ) ) {
                $ServiceRequestClass->attachment = $this->uploadAttachment( $request->file( 'attachment' ) );
            }

            if( $ServiceRequestClass->save() ) {

                $SiteVoucherClass->increaseCorrelative( $typeVoucher );

                if( $id > 0 && $ServiceRequestClass->id > 0 ) {
                    ServiceRequestDetail::where( 'service_requests_id', $id )
                        ->whereIn( 'status', [ 0, 1 ])
                        ->update( ['status' => 2] );
                }

                foreach( $details as $item ) {

                    $detail = json_decode( $item );
                    if( $id > 0 && $detail->id > 0 ) {

                        $ServiceRequestDetailClass = ServiceRequestDetail::where( 'id', $detail->id )->first();
                        $ServiceRequestDetailClass->name                = Str::limit( $detail->item, 17, '...' );
                        $ServiceRequestDetailClass->description         = $detail->item;
                        $ServiceRequestDetailClass->quantity            = $detail->count;
                        $ServiceRequestDetailClass->status            = 1;

                    } else {

                        $ServiceRequestDetailClass = new ServiceRequestDetail();
                        $ServiceRequestDetailClass->service_requests_id = $ServiceRequestClass->id;
                        $ServiceRequestDetailClass->name                = Str::limit( $detail->item, 17, '...' );
                        $ServiceRequestDetailClass->description         = $detail->item;
                        $ServiceRequestDetailClass->quantity            = $detail->count;
                        $ServiceRequestDetailClass->assumed_customer    = 0;

                    }

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

    private function uploadAttachment( $file ) {
        $destinationPath = public_path(self::PATH_UPLOAD_SR );
        $newImage = 'service-request-' . time() . '.' . $file->getClientOriginalExtension();
        $file->move( $destinationPath, $newImage );

        return asset( self::PATH_UPLOAD_SR . $newImage);
    }

    public function edit( Request $request ) {

        $response = [
            'status'            => false,
            'serviceRequest'    => []
        ];

        $user = Auth::user();

        $serviceRequestClass = new ServiceRequest();
        $serviceRequestDetailClass = new ServiceRequestDetail();

        $serviceRequest = $serviceRequestClass::where( $serviceRequestClass::TABLE_NAME . '.status', 1 )
            ->whereIn( $serviceRequestClass::TABLE_NAME . '.is_send', [0,2] )
            ->where( $serviceRequestClass::TABLE_NAME . '.id', $request->id )
            ->where( $serviceRequestClass::TABLE_NAME . '.customers_id', $user->customers_id )
            ->first();

        if ( $serviceRequest ) {

            $serviceRequestDetail = $serviceRequestDetailClass::where( $serviceRequestDetailClass::TABLE_NAME . '.status', 1 )
                ->where( $serviceRequestDetailClass::TABLE_NAME . '.service_requests_id', $serviceRequest->id )
                ->select( 'id', 'description', 'quantity' )
                ->get();

            $arrDetail = [];

            foreach ( $serviceRequestDetail as $detail ) {
                $arrDetail[] = $detail;
            }

            $response['status'] = true;
            $response['serviceRequest'] = [
                'id'        => $serviceRequest->id,
                'dateReg'   => $serviceRequest->date_reg,
                'isSend'    => $serviceRequest->is_send,
                'name'      => $serviceRequest->description,
                'detail'    => $arrDetail,
                'attachment'=> $serviceRequest->attachment
            ];

        }

        return response()->json( $response );
    }

    public function send( Request $request ) {

        $id = $request->id;

        $serviceRequest = ServiceRequest::where('status', 1)
            ->where('id', $id)
            ->where( 'is_send', '!=', 1 )
            ->first();

        if( ! empty( $serviceRequest ) ) {

            $serviceRequest->is_send = 1;
            $serviceRequest->date_send = date( 'Y-m-d H:i:s' );
            $serviceRequest->save();

            return response()->json([
                'status' => true
            ]);
        }

        return response()->json([
            'status' => false
        ]);
    }

    public function delete( Request $request ) {

        $user = Auth::user();
        $id = $request->id;

        $serviceRequest = ServiceRequest::where( 'status', 1 )
            ->where( 'id', $id )
            ->where( 'is_send', '!=', 1 )
            ->first();

        if( ! empty( $serviceRequest ) ) {
            $serviceRequest->status = 2;
            $serviceRequest->save();

            SaleQuotation::where( 'service_requests_id', $id )
                ->where( 'status', '!=', 2 )
                ->update([
                    'status' => 3,
                    'is_approved_customer' => 3,
                    'customer_login_id' => $user->id,
                    'date_approved_customer' => date( 'Y-m-d H:i:s' )
                ]);

            return response()->json([
                'status' => true
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'No se puede eliminar esta solicitud de servicio.'
        ]);

    }
}
