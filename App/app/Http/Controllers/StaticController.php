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
    protected $_meta = [];
    protected $_metaTagSocials = [];
    protected $_title = '#Covid-19';
    protected $_description = 'Siempre estamos cerca de ti, en donde te encuentres y a la hora que lo necesites.';

    public function __construct()
    {
        $this->_meta = [
            'key' => 'description',
            'value' => $this->_description
        ];

        $this->setTitle( $this->_title );
        $this->setMetaTags( $this->_meta );
    }

    private function initialMetaTagSocial() {
        $this->_metaTagSocials[] = [ 'social' => 'facebook', 'property' => 'fb:app_id', 'content' => env('FACEBOOK_APP_ID')];
        $this->_metaTagSocials[] = [ 'social' => 'facebook', 'property' => 'og:url', 'content' => route('contact-us')];
        $this->_metaTagSocials[] = [ 'social' => 'facebook', 'property' => 'og:title', 'content' => $this->title . env( 'PROJECT_NAME' )];
        $this->_metaTagSocials[] = [ 'social' => 'facebook', 'property' => 'og:description', 'content' => $this->_description];
        $this->_metaTagSocials[] = [ 'social' => 'facebook', 'property' => 'og:image', 'content' => asset( self::PATH_IMAGES . $this->logoSocial)];

        $this->_metaTagSocials[] = [ 'social' => 'twitter', 'property' => 'twitter:url', 'content' => route('contact-us')];
        $this->_metaTagSocials[] = [ 'social' => 'twitter', 'property' => 'twitter:title', 'content' => $this->title . env( 'PROJECT_NAME' )];
        $this->_metaTagSocials[] = [ 'social' => 'twitter', 'property' => 'twitter:description', 'content' => $this->_description];
        $this->_metaTagSocials[] = [ 'social' => 'twitter', 'property' => 'twitter:image', 'content' => asset( self::PATH_IMAGES . $this->logoSocial)];
    }

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

    public function covid() {
        $this->initialMetaTagSocial();
        $this->setMetaTagSocial( $this->_metaTagSocials );

        $data = [
            'activeSide' => 'covid19',
            'title' => $this->title,
            'metaTags' => $this->metaTags,
            'metaTagSocials' => $this->metaTagSocials
        ];

        return view( 'classimax.pages.covid', $data );
    }
}
