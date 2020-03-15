<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    protected $_meta = [];
    protected $_metaTagSocials = [];
    protected $_title = '';

    private function initialMetaTagSocial( $route ) {
        $this->_metaTagSocials[] = [ 'social' => 'facebook', 'property' => 'fb:app_id', 'content' => env('FACEBOOK_APP_ID')];
        $this->_metaTagSocials[] = [ 'social' => 'facebook', 'property' => 'og:url', 'content' => $route ];
        $this->_metaTagSocials[] = [ 'social' => 'facebook', 'property' => 'og:title', 'content' => $this->title . env( 'PROJECT_NAME' )];
        $this->_metaTagSocials[] = [ 'social' => 'facebook', 'property' => 'og:description', 'content' => $this->description];
        $this->_metaTagSocials[] = [ 'social' => 'facebook', 'property' => 'og:image', 'content' => asset( self::PATH_IMAGES . $this->logoSocial)];

        $this->_metaTagSocials[] = [ 'social' => 'twitter', 'property' => 'twitter:url', 'content' => $route ];
        $this->_metaTagSocials[] = [ 'social' => 'twitter', 'property' => 'twitter:title', 'content' => $this->title . env( 'PROJECT_NAME' )];
        $this->_metaTagSocials[] = [ 'social' => 'twitter', 'property' => 'twitter:description', 'content' => $this->description];
        $this->_metaTagSocials[] = [ 'social' => 'twitter', 'property' => 'twitter:image', 'content' => asset( self::PATH_IMAGES . $this->logoSocial)];
    }

    public function termsAndConditions() {
        $this->_title = 'Términos y condiciones.';
        $this->_meta = [
            'key' => 'description',
            'value' => $this->description
        ];

        $this->setTitle( $this->_title );
        $this->setMetaTags( $this->_meta );
        $this->initialMetaTagSocial( route('tyc'));
        $this->setMetaTagSocial( $this->_metaTagSocials );

        $data = [
            'activeSide' => 'tyc',
            'title' => $this->title,
            'metaTags' => $this->metaTags,
            'metaTagSocials' => $this->metaTagSocials
        ];
        return view('classimax.pages.tyc', $data);
    }

    public function aboutUs() {
        $this->_title = '¿Quíenes Somos?';
        $this->_meta = [
            'key' => 'description',
            'value' => $this->description
        ];

        $this->setTitle( $this->_title );
        $this->setMetaTags( $this->_meta );
        $this->initialMetaTagSocial( route('about-us'));
        $this->setMetaTagSocial( $this->_metaTagSocials );

        $data = [
            'activeSide' => 'about-us',
            'title' => $this->title,
            'metaTags' => $this->metaTags,
            'metaTagSocials' => $this->metaTagSocials
        ];
        return view('classimax.pages.about-us', $data);
    }
}
