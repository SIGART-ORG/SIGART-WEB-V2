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
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view( 'login.register' );
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
            $customerLogin->remember_token = $token;

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
