<?php

namespace App\Http\Controllers;

use App\Models\SaleQuotation;
use Illuminate\Http\Request;

class SaleQuotationController extends Controller
{
    public function index() {

    }

    public function listData( Request $request ) {

        $user = Auth()->user();
        $customerId = $user->customers_id;
        $type = $request->type;

        $statusIds = [6];

        switch ( $type ) {
            case 'approved':
                $statusIds = [8];
                break;
            case 'cancel':
                $statusIds = [0,2,5,7,9];
                break;
        }

        $response = [
            'status' => true,
            'data' => [],
            'pagination' => []
        ];

        $salesQuotations = SaleQuotation::whereIn( 'status', $statusIds )
            ->where( 'customers_id', $customerId )
            ->orderBy( 'date_reply_second', 'desc' )
            ->paginate( 20 );

        foreach ( $salesQuotations as $saleQuotation ) {
            $row = new \stdClass();
            $row->id = $saleQuotation->id;
            $row->document = $saleQuotation->num_serie . '-' . $saleQuotation->num_doc;
            $row->approved = $saleQuotation->date_reply_second ? date( 'd/m/Y', strtotime( $saleQuotation->date_reply_second ) ) : '---';
            $row->start = $saleQuotation->date_start ? date( 'd/m/Y', strtotime( $saleQuotation->date_start ) ) : '---';
            $row->end = $saleQuotation->date_end ? date( 'd/m/Y', strtotime( $saleQuotation->date_end ) ) : '---';
            $row->subTotal = $saleQuotation->subtot_sale;
            $row->discount = $saleQuotation->tot_dscto;
            $row->discountPorc = $saleQuotation->porc_dscto;
            $row->total = $saleQuotation->tot_gral;

            $serviceRequest = $saleQuotation->serviceRequest;
            $row->serviceRequest = new \stdClass();
            $row->serviceRequest->id = $serviceRequest->id;
            $row->serviceRequest->title = $serviceRequest->description;
            $row->serviceRequest->document = $serviceRequest->num_request;
            $row->serviceRequest->send = $serviceRequest->date_send ? date( 'd/m/Y', strtotime( $serviceRequest->date_send ) ) : '---';

            $response['data'][] = $row;
        }

        $response['pagination'] = [
            'total' => $salesQuotations->total(),
            'current_page' => $salesQuotations->currentPage(),
            'per_page' => $salesQuotations->perPage(),
            'last_page' => $salesQuotations->lastPage(),
            'from' => $salesQuotations->firstItem(),
            'to' => $salesQuotations->lastItem()
        ];

        return response()->json( $response );
    }

    public function action( Request $request ) {
        $id = $request->id ? $request->id : 0;
        $action = $request->action ? $request->action : '';

        $response = [
            'status' => false,
            'msg' => 'No se pudo realizar la operación'
        ];

        switch ( $action ) {
            case 'aproval':
                $response = $this->aproval( $id );
                break;
            case 'cancel' :
                $response = $this->cancel( $id );
                break;
        }

        return response()->json( $response );
    }

    private function aproval( $id ) {
        $user = Auth()->user();
        $customerId = $user->customers_id;

        $response = [
            'status' => false,
            'msg' => 'No se pudo realizar la operación'
        ];

        $saleQuotation = SaleQuotation::findOrfail( $id );
        if( $saleQuotation->status === 6 ) {
            $saleQuotation->status = 8;
            $saleQuotation->is_approved_customer = 1;
            $saleQuotation->customer_login_id = $customerId;
            $saleQuotation->date_approved_customer = date( 'Y-m-d H:i:s' );

            if( $saleQuotation->save() ){
                $serviceRequest = $saleQuotation->serviceRequest;
                $serviceRequest->status = 4;
                if(  $serviceRequest->save() ) {
                    $response['status'] = true;
                    $response['msg'] = 'OK';
                }
            }
        }

        return $response;
    }

    private function cancel( $id ) {
        $user = Auth()->user();
        $customerId = $user->customers_id;

        $response = [
            'status' => false,
            'msg' => 'No se pudo realizar la operación'
        ];

        $saleQuotation = SaleQuotation::findOrfail( $id );
        if( $saleQuotation->status === 6 ) {
            $saleQuotation->status = 7;
            $saleQuotation->is_approved_customer = 2;
            $saleQuotation->customer_login_id = $customerId;
            $saleQuotation->date_approved_customer = date( 'Y-m-d H:i:s' );

            if( $saleQuotation->save() ) {
                $response['status'] = true;
                $response['msg'] = 'OK';
            }
        }

        return $response;
    }
}
