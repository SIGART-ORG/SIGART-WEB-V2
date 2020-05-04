<?php

namespace App\Http\Controllers;

use App\Helpers\FormatUbigeo;
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

    public function loadDepartamentV2() {

        $data = Departament::orderBy('name', 'asc')
            ->select('id', 'name')
            ->get();

        $departaments = [];
        foreach ( $data as $dep ) {
            $row = new \stdClass();
            $row->id = FormatUbigeo::complete( $dep->id );
            $row->name = $dep->name;
            $departaments[] = $row;
        }
        return ['departaments' => $departaments];
    }

    public function loadProvince( $departament ) {

        $departament = FormatUbigeo::complete( $departament );

        return Province::where( 'departament_id', $departament )
            ->orderBy( 'name' )
            ->get();

    }

    public function loadProvinceV2( $departament ) {

        $departament = FormatUbigeo::complete( $departament );

        $data = Province::orderBy('name', 'asc')
            ->where('departament_id', $departament)
            ->select('id', 'name')
            ->get();

        $provinces = [];
        foreach ( $data as $pro ) {
            $row = new \stdClass();
            $row->id = FormatUbigeo::complete( $pro->id );
            $row->name = $pro->name;
            $provinces[] = $row;
        }
        return ['provinces' => $provinces];

    }

    public function loadDistrict( $departament, $province ) {

        $departament = FormatUbigeo::complete( $departament );
        $province = FormatUbigeo::complete( $province );

        return District::where( 'departament_id', $departament )
            ->where( 'province_id', $province )
            ->orderBy( 'name' )
            ->get();

    }

    public function loadDistrictV2( $departament, $province ) {

        $departament = FormatUbigeo::complete( $departament );
        $province = FormatUbigeo::complete( $province );

        $data = District::orderBy('name', 'asc')
            ->where('departament_id', $departament)
            ->where('province_id', $province)
            ->select('id', 'name')
            ->get();

        $districts = [];
        foreach ( $data as $dis ) {
            $row = new \stdClass();
            $row->id = FormatUbigeo::complete( $dis->id );
            $row->name = $dis->name;
            $districts[] = $row;
        }
        return ['districts' => $districts];

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
