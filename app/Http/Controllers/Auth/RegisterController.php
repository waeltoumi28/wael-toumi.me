<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use DB;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
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
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'cell' => ['required'],
            'country_code' => ['required'],
            'status' => ['required'],
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

        $country = DB::table('vas_countries')->where('phonecode', '=', $data['country_code'])->get();

        $verify = DB::select('SELECT * FROM vas_client WHERE vas_centre_id = 7 AND cell = ?', [$data['cell']]);

        $registerData = [
            'vas_centre_id' => 7,
            'first_name' => $data['firstname'],
            'last_name' => $data['lastname'],
            'cell' => $data['cell'],
            'email' => null,
            'national_id' => null,
            'status' => $data['status'],
            'country_id' => $country[0]->id,
        ];
        if(count($verify) == 0){
            $user = User::create(
                $registerData
            );
    
            return $user;
        }else{
            echo '<script>window.location.href = "register?error=User+is+already+registered.";</script>';
        } 
        
    }
}
