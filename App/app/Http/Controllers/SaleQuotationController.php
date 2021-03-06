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
            $row->expiration = $saleQuotation->date_expiration ? date( 'd/m/Y', strtotime( $saleQuotation->date_expiration ) ) : '---';
            $row->subTotal = $saleQuotation->subtot_sale;
            $row->discount = $saleQuotation->tot_dscto;
            $row->discountPorc = $saleQuotation->porc_dscto;
            $row->total = $saleQuotation->tot_gral;
            $row->execution = $saleQuotation->execution_time_days;
            $row->showButton = ( strtotime( $saleQuotation->date_expiration ) > now()->timestamp ) ? true : false;
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

    public function show( $id ) {
        $response = [
            'status' => false,
            'saleQuotation' => []
        ];

        $saleQuotation = SaleQuotation::find( $id );
        if( $saleQuotation ) {
            $response['status'] = true;
            $data = new \stdClass();
            $data->id = $saleQuotation->id;
            $data->document = $saleQuotation->num_serie . '-' . $saleQuotation->num_doc;
            $data->approved = $saleQuotation->date_reply_second ? date( 'd/m/Y', strtotime( $saleQuotation->date_reply_second ) ) : '---';
            $data->expiration = $saleQuotation->date_expiration ? date( 'd/m/Y', strtotime( $saleQuotation->date_expiration ) ) : '---';
            $data->subTotal = number_format( $saleQuotation->subtot_sale, 2 );
            $data->igv = number_format( $saleQuotation->tot_igv, 2 );
            $data->discount = $saleQuotation->tot_dscto;
            $data->discountPorc = $saleQuotation->porc_dscto;
            $data->total = number_format( $saleQuotation->tot_gral, 2 );
            $data->execution = $saleQuotation->execution_time_days;
            $data->warranty = $saleQuotation->warranty_num;
            $data->showButton = ( strtotime( $saleQuotation->date_expiration ) > now()->timestamp ) ? true : false;
            $serviceRequest = $saleQuotation->serviceRequest;
            $data->serviceRequest = new \stdClass();
            $data->serviceRequest->id = $serviceRequest->id;
            $data->serviceRequest->title = $serviceRequest->description;
            $data->serviceRequest->document = $serviceRequest->num_request;
            $data->serviceRequest->send = $serviceRequest->date_send ? date( 'd/m/Y', strtotime( $serviceRequest->date_send ) ) : '---';

            $saleQuotationDetails = [];
            $details = $saleQuotation->saleQuotationDetails->where( 'status', 1 );
            foreach ( $details as $detail ) {
                $row = new \stdClass();
                $row->id = $detail->id;
                $row->description = $detail->description;
                $row->quantity = $detail->quantity;
                $row->workforce = $detail->workforce;
                $row->total_products = $detail->total_products;
                $row->sub_total = number_format( $detail->workforce + $detail->total_products, 2 );
                $row->products = [];

                if( $detail->includes_products === 1 ) {
                    $products = $detail->quotationDetailProducts->where( 'status', 1 );
                    foreach ( $products as $product ) {
                        $presentation = $product->presentation;
                        $row2 = new \stdClass();
                        $row2->id = $product->id;
                        $row2->presentation = $presentation ? $presentation->sku . ' - ' . $presentation->description : '';
                        $row2->quantity = $product->quantity;
                        $row2->priceUnit = number_format( $product->price, 2 );
                        $row2->subTotal = number_format( $product->quantity * $product->price, 2 );
                        $row->products[] = $row2;
                    }
                }

                $saleQuotationDetails[] = $row;
            }
            $data->items = $saleQuotationDetails;

            $response['saleQuotation'] = $data;
        }


        return response()->json($response);
    }
}
