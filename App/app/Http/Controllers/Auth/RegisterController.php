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

    protected function validatorFirst(array $data)
    {
        $range = 8;
        if( (int)$data['typeDocument'] === 2 ) {
            $range = 11;
        }

        $validator = [
            'typeDocument' => ['required', 'numeric'],
            'document' => ['required', 'string', 'min:' . $range, 'max:' . $range, 'unique:customers,document'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:customer_login,email'],
        ];

        return Validator::make($data, $validator);
    }

    protected function validatorSecond(array $data)
    {

        $validator = [
            'password' => 'required|min:8',
            'confirm' => 'required|min:8|same:password',
        ];

        return Validator::make($data, $validator);
    }

    public function firstStep( Request $request ) {

        $typeDocument = $request->typeDocument;
        $document = $request->document;
        $email = $request->email;

        $data = [
            'typeDocument' => $typeDocument,
            'document' => $document,
            'email' => $email,
        ];

        $validator = $this->validatorFirst( $data );

        $response = [
            'status' => false,
            'msg' => 'No se pudo realizar el registro.'
        ];

        if ( $validator->fails() ) {
            $msg = $this->formatMessagesErrors( $validator->errors(), [
                'typeDocument', 'document', 'email'
            ]);
            $response['msg'] = $msg;
            return response()->json( $response, 200);
        }

        $idCustomer = 0;
        $customerName = '';

        $apiToken = env( 'APISPERU' );
        $urlApi = 'https://dniruc.apisperu.com/api/v1/';

        if( $typeDocument === 1 ) {
            $urlApi .= 'dni/' . $document . '?token=' . $apiToken;
        } else {
            $urlApi .= 'ruc/' . $document . '?token=' . $apiToken;
        }

        $apiRespuest = file_get_contents($urlApi);
        $apiRespuestJson = json_decode( $apiRespuest, false );

        if( !empty( $apiRespuest ) ) {
            switch ($typeDocument) {
                case 1:
                    if (!empty($apiRespuestJson->nombres)) {
                        $customers = new Customer();
                        $customers->name = $apiRespuestJson->nombres;
                        $customers->lastname = $apiRespuestJson->apellidoPaterno . ' ' . $apiRespuestJson->apellidoMaterno;
                        $customers->type_document = 1;
                        $customers->document = $document;
                        $customers->email = $email;
                        $customers->status = '3';
                        if ($customers->save()) {
                            $idCustomer = $customers->id;
                            $customerName = $apiRespuestJson->nombres;
                        }
                    }
                    break;
                case 2:
                    if (!empty($apiRespuestJson->razonSocial)) {
                        $customers = new Customer();
                        $customers->name = $apiRespuestJson->razonSocial;
                        $customers->lastname = $apiRespuestJson->nombreComercial;
                        $customers->type_document = 2;
                        $customers->document = $document;
                        $customers->email = $email;
                        $customers->status = '3';
                        $customers->type_person = 2;
                        $customers->address = $apiRespuestJson->direccion;
                        if ($customers->save()) {
                            $idCustomer = $customers->id;
                            $customerName = $apiRespuestJson->razonSocial;
                        }
                    }
                    break;
            }
        } else {
            $response['msg'] = 'No se encontro registros.';
        }

        if( $idCustomer > 0 ) {
            $token = Str::random(60);

            $customerLogin = new CustomerLogin();
            $customerLogin->name = $customerName;
            $customerLogin->email = $email;
            $customerLogin->password = '';
            $customerLogin->customers_id = $idCustomer;
            $customerLogin->valid_code = $token;
            if( $customerLogin->save() ) {

                $subject = $customerName . ', te damos la bienvenida a tu nueva cuenta en D\'Pintart';
                $vars = [
                    'subject'   => $subject,
                    'name'      => $customerName,
                    'urlConfirmation' => url()->route('confirmation', $token)
                ];

                $this->sendMail( $email, $subject, 'registration-first', $vars );

                $response['status'] = true;
                $response['msg'] = 'Se te envió un correo para verificar tu cuenta.';
            }
        }

        return response()->json( $response, 200);
    }

    public function secondStep( Request $request ) {
        $response = [
            'status' => false,
            'msg' => 'No se pudo realizar el registro.'
        ];

        $password = $request->password;
        $confirm = $request->confirm;
        $token = $request->token;

        $data = [
            'password' => $password,
            'confirm' => $confirm,
            'token' => $token
        ];

        $validator = $this->validatorSecond( $data );

        if ( $validator->fails() ) {
            $msg = $this->formatMessagesErrors( $validator->errors(), [
                'typeDocument', 'document', 'email'
            ]);
            $response['msg'] = $msg;
            return response()->json( $response, 200);
        }

        $customerLogin = CustomerLogin::where('valid_code', $token)->first();

        if( $customerLogin && $customerLogin->status === 0 ) {

            $customerLogin->status = 3;
            $customerLogin->password = Hash::make( $password );
            $customerLogin->email_verified_at = date( 'Y-m-d H:i:s' );
            $customerLogin->valid_code = NULL;

            if( $customerLogin->save() ) {
                $subject = $customerLogin->name . ', ¡Empieza a usar D\'Pintart!';
                $vars = [
                    'subject' => $subject,
                    'name' => $customerLogin->name,
                    'urlConfirmation' => url()->route('login.form')
                ];

                $this->sendMail($customerLogin->email, $subject, 'registration-second', $vars);
                $response['status'] = true;
                $response['msg'] = 'Registro exitoso, ya puedes ingresar a su cuenta.';
            } else {
                $response['msg'] = 'No se pudieron actualizar tus registros.';
            }
        }

        return response()->json( $response, 200);
    }

    public function confirmation( Request $request ) {
        $hash = $request->token;

        $customerLogin = CustomerLogin::where('valid_code', $hash)->first();

        if( $customerLogin && $customerLogin->status === 0 ) {

            $data = [
                'activeSide' => 'register',
                'token' => $hash,
            ];
            return view( 'classimax.pages.register-second', $data );

        } else {
            return redirect()->route('home.index');
        }
    }

    private function formatMessagesErrors( $errors, $inputs ) {

        $formartMessages = [];

        foreach ( $inputs as $input ) {
            foreach ($errors->get( $input ) as $message) {
                $formartMessages[] = $message;
            }
        }

        return implode(' ', $formartMessages );
    }

}
