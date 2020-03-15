<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    protected $_meta = [];
    protected $_metaTagSocials = [];
    protected $_title = 'Contáctanos';
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

    public function dashboard() {

        $this->initialMetaTagSocial();
        $this->setMetaTagSocial( $this->_metaTagSocials );

        $data = [
            'activeSide' => 'contact-us',
            'title' => $this->title,
            'metaTags' => $this->metaTags,
            'metaTagSocials' => $this->metaTagSocials
        ];

        return view('classimax.pages.contact-us', $data);
    }
}
