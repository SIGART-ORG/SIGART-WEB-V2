<?php

namespace App\Http\Controllers;

use App\Models\Departament;
use App\Models\District;
use App\Models\Province;
use App\Models\TypeDocument;
use Illuminate\Http\Request;

class StaticController extends Controller
{
    public function loadDepartament() {

        return Departament::orderBy('name')->get();

    }

    public function loadProvince( $departament ) {

        $departament = \FormatUbigeo::complete( $departament );

        return Province::where( 'departament_id', $departament )
            ->orderBy( 'name' )
            ->get();

    }

    public function loadDistrict( $departament, $province ) {

        $departament = \FormatUbigeo::complete( $departament );
        $province = \FormatUbigeo::complete( $province );

        return District::where( 'departament_id', $departament )
            ->where( 'province_id', $province )
            ->orderBy( 'name' )
            ->get();

    }

    public function loadTypeDocument() {

        return TypeDocument::where( 'status', 1 )
            ->select('id', 'name')
            ->get();

    }

    public function loadTypePerson() {
        return [
            [
                'id' => 1,
                'name' => 'Persona Natural'
            ],
            [
                'id' => 2,
                'name'  => 'Persona Juridica'
            ]
        ];
    }
}
