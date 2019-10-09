<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function show() {

        $user = Auth::user();

        $customer = Customer::where( 'status', '!=', 2 )
            ->where( 'id', $user->customers_id )
            ->first();

        $response = [
            'status'    => false,
            'message'   => 'Error'
        ];

        if( ! empty( $customer ) && $customer->id > 0 ) {

            $district = $customer->district_id > 0 ? (int) $customer->district_id : 150101;

            $ubigeo = \FormatUbigeo::ubigeo( $district );

            $response = [
                'status'            => true,
                'message'           => 'ok',
                'typePerson'        => $customer->type_person,
                'name'              => $customer->name,
                'lastname'          => $customer->lastname,
                'businessName'      => $customer->business_name,
                'typeDocument'      => $customer->type_document,
                'document'          => $customer->document,
                'legalRep'          => $customer->legal_representative,
                'typeDocumentLp'    => $customer->type_document_lp,
                'documentLp'        => $customer->document_lp,
                'address'           => $customer->address,
                'departamentId'     => ! empty( $ubigeo->departament_id ) ? $ubigeo->departament_id : 150000,
                'provinceId'        => ! empty( $ubigeo->province_id ) ? $ubigeo->province_id : 150100,
                'districtId'        => $district,
            ];
        }

        return response()->json( $response );
    }

    public function update( Request $request ) {

        $user = Auth::user();

        $customer = Customer::findOrFail( $user->customers_id );
        $customer->type_person = $request->customer['typePerson'];
        $customer->name = $request->customer['name'];
        $customer->lastname = $request->customer['lastname'];
        $customer->business_name = $request->customer['businessName'];
        $customer->type_document = $request->customer['typeDocument'];
        $customer->document = $request->customer['document'];
        $customer->legal_representative = $request->customer['legalRep'];
        $customer->type_document_lp = $request->customer['typeDocumentLp'];
        $customer->document_lp  = $request->customer['documentLp'];
        $customer->address  = $request->customer['address'];
        $customer->district_id  = $request->customer['districtId'];
        $customer->status = 1;

        if( $customer->save() ) {

            $cl = CustomerLogin::find( $user->id );
            $cl->status = 1;
            $cl->save();

            return response()->json([
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false
            ]);
        }

    }
}
