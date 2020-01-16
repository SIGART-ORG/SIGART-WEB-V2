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

        $response = [
            'status' => true,
            'data' => [],
            'pagination' => []
        ];

        $salesQuotations = SaleQuotation::where( 'status', 6 )
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
}
