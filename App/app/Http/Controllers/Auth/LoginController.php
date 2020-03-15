<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LoginController extends Controller
{

    protected $_meta = [];
    protected $_metaTagSocials = [];
    protected $_title = 'login';

    public function __construct()
    {
        $this->_meta = [
            'key' => 'description',
            'value' => $this->description
        ];

        $this->setTitle( $this->_title );
        $this->setMetaTags( $this->_meta );

        $this->middleware('guest')->except('logout');
    }

    private function initialMetaTagSocial() {
        $this->_metaTagSocials[] = [ 'social' => 'facebook', 'property' => 'fb:app_id', 'content' => env('FACEBOOK_APP_ID')];
        $this->_metaTagSocials[] = [ 'social' => 'facebook', 'property' => 'og:url', 'content' => route('login.form')];
        $this->_metaTagSocials[] = [ 'social' => 'facebook', 'property' => 'og:title', 'content' => $this->title . env( 'PROJECT_NAME' )];
        $this->_metaTagSocials[] = [ 'social' => 'facebook', 'property' => 'og:description', 'content' => $this->description];
        $this->_metaTagSocials[] = [ 'social' => 'facebook', 'property' => 'og:image', 'content' => asset( self::PATH_IMAGES . $this->logoSocial)];

        $this->_metaTagSocials[] = [ 'social' => 'twitter', 'property' => 'twitter:url', 'content' => route('login.form')];
        $this->_metaTagSocials[] = [ 'social' => 'twitter', 'property' => 'twitter:title', 'content' => $this->title . env( 'PROJECT_NAME' )];
        $this->_metaTagSocials[] = [ 'social' => 'twitter', 'property' => 'twitter:description', 'content' => $this->description];
        $this->_metaTagSocials[] = [ 'social' => 'twitter', 'property' => 'twitter:image', 'content' => asset( self::PATH_IMAGES . $this->logoSocial)];
    }

    public function showLoginForm() {
        $this->initialMetaTagSocial();
        $this->setMetaTagSocial( $this->_metaTagSocials );

        $data = [
            'activeSide' => 'login',
            'title' => $this->title,
            'metaTags' => $this->metaTags,
            'metaTagSocials' => $this->metaTagSocials
        ];
        return view( 'classimax.pages.login', $data );
    }

    public function login() {

        $credentials = $this->validate( request(), [
            'email' => 'email|required|string',
            'password' => 'required|string'
        ]);

        if ( Auth::attempt( $credentials ) ) {
            $session = Auth::user();
            if( $session->status === 1 || $session->status === 3 ) {
                return redirect()->route('dashboard');
            } else {
                Auth::logout();

                return back()->withErrors( ['email' => 'Por favor, valida tu cuenta.'] )
                    ->withInput( request(['email']) );
            }
        };

        return back()->withErrors( ['email' => trans( 'auth.failed' )] )
            ->withInput( request(['email']) );
    }

    public function logout() {
        Auth::logout();

        return redirect()->route( 'home.index' );
    }
}
