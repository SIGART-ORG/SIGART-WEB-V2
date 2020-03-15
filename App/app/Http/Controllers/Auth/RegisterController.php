<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerLogin;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $_meta = [];
    protected $_metaTagSocials = [];
    protected $_title = 'Regístrate';

    public function __construct()
    {
        $this->_meta = [
            'key' => 'description',
            'value' => $this->description
        ];

        $this->setTitle( $this->_title );
        $this->setMetaTags( $this->_meta );
        $this->middleware('guest');
    }

    private function initialMetaTagSocial() {
        $this->_metaTagSocials[] = [ 'social' => 'facebook', 'property' => 'fb:app_id', 'content' => env('FACEBOOK_APP_ID')];
        $this->_metaTagSocials[] = [ 'social' => 'facebook', 'property' => 'og:url', 'content' => route('login.register')];
        $this->_metaTagSocials[] = [ 'social' => 'facebook', 'property' => 'og:title', 'content' => $this->title . env( 'PROJECT_NAME' )];
        $this->_metaTagSocials[] = [ 'social' => 'facebook', 'property' => 'og:description', 'content' => $this->description];
        $this->_metaTagSocials[] = [ 'social' => 'facebook', 'property' => 'og:image', 'content' => asset( self::PATH_IMAGES . $this->logoSocial)];

        $this->_metaTagSocials[] = [ 'social' => 'twitter', 'property' => 'twitter:url', 'content' => route('login.register')];
        $this->_metaTagSocials[] = [ 'social' => 'twitter', 'property' => 'twitter:title', 'content' => $this->title . env( 'PROJECT_NAME' )];
        $this->_metaTagSocials[] = [ 'social' => 'twitter', 'property' => 'twitter:description', 'content' => $this->description];
        $this->_metaTagSocials[] = [ 'social' => 'twitter', 'property' => 'twitter:image', 'content' => asset( self::PATH_IMAGES . $this->logoSocial)];
    }

    public function showRegistrationForm()
    {
        $this->initialMetaTagSocial();
        $this->setMetaTagSocial( $this->_metaTagSocials );

        $data = [
            'activeSide' => 'register',
            'title' => $this->title,
            'metaTags' => $this->metaTags,
            'metaTagSocials' => $this->metaTagSocials
        ];
        return view( 'classimax.pages.register', $data );
    }

    public function register( Request $request ) {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'passwordConfirmation' => $request->passwordConfirmation,
        ];

        $validator = $this->validator( $data );

        if ( $validator->fails() ) {

            return redirect()->route('login.register' )
                ->withErrors( $validator )
                ->withInput( request(['email', 'name']));
        }

        if( $this->create( $data ) ) {
            return redirect()->route('home.index' );
        }

    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:customer_login,email'],
            'password' => ['required', 'string', 'min:8'],
            'passwordConfirmation' => ['required', 'string', 'min:8', 'same:password'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        $customers = new Customer();
        $customers->name = '';
        $customers->lastname = '';
        $customers->document = '';
        $customers->email = $data['email'];
        $customers->status = '3';

        if( $customers->save() ) {

            $token = Str::random(60);

            $customerLogin = new CustomerLogin();
            $customerLogin->name = $data['name'];
            $customerLogin->email = $data['email'];
            $customerLogin->password = Hash::make($data['password']);
            $customerLogin->customers_id = $customers->id;
            $customerLogin->valid_code = $token;

            if( $customerLogin->save() ) {

                $subject = $data['name'] . ', te damos la bienvenida a tu nueva cuenta en D\'Pintart';
                $vars = [
                    'subject'   => $subject,
                    'name'      => $data['name'],
                    'urlConfirmation' => url()->route('confirmation', $token)
                ];

                $this->sendMail( $data['email'], $subject, 'registration-customer', $vars );

                return true;
            }
        }

        return false;
    }

}
