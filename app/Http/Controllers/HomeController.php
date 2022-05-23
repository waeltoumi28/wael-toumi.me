<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $vas_centre_id = Auth::user()->vas_centre_id;
        $status = Auth::user()->status;
       
        $currency = DB::select('SELECT * FROM vas_client_session WHERE vas_client_id = ? AND vas_centre_id = ?', [$user_id, $vas_centre_id]);
        
        $currencyId = (count($currency) > 0) ? (int) $currency[0]->currency_id : 270;

        /********REDIRECT TO EDDI'S API WHEN CLIENT SELF REGISTER IS 1 */
        $client_self_reg = DB::table('vas_centre')->where([
            ['id', '=', $vas_centre_id],
            ['client_self_register', '=', 1]
        ])->get();
       
        if(count($client_self_reg) > 0){
            $reg_value = (int) $client_self_reg[0]->client_self_register;
        }else{
            $reg_value = 0;
        }    

        if ($reg_value === 1 && $status == 0) {
            
            //header("Content-type:application/json");
                $time = time();
                // $authKey = "UzBRUjVkOEFXZ1cxUFZFSGdzRDlPQT09";
                // $authPass = "AfricaBet";
                $authKey = $_ENV['APP_API_KEY'];
                $authPass = $_ENV['APP_API_PASS'];

                $authorisation = base64_encode($authKey . $authPass . $time);
                $header = array( 
                		'Content-Type: application/json' ,
                              	'Authorisation: ' . $authorisation,
                                'timestamp: ' . $time
                                );

            $api_url = $_ENV['APP_API_URL'] . "request/loyalty/clientRegistration";
            // https://api-test.contipay.co.zw/request/loyalty/clientRegistration

            $client = curl_init($api_url);

            $form_data = array(
                "clientId" => $user_id,
                "vasCentreId" => $vas_centre_id
            );

            $form_data = json_encode($form_data);
            curl_setopt($client, CURLOPT_POST, true);
            curl_setopt($client, CURLOPT_POSTFIELDS, $form_data);
            curl_setopt($client, CURLOPT_HEADER, false);
            curl_setopt($client, CURLOPT_HTTPHEADER, $header);
            curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
	    //var_dump($response);die();            
            $response = curl_exec($client);
            #$response['error'] = curl_error($client); 
            $result = json_decode($response);
            #check if statement
            if ($result->code == 1) {
                Auth::logout();
                \Session::flash('success', 'Account successfully registered. You will receive an Whatsapp Message shortly. Please collect your tap card at any of our local branches.');
                return view('auth.login');
            }

        }

        /**** Start checking if user has access to the system*/

        if ($status === 0) {
            Auth::logout();
            \Session::flash('success', 'Your Account is now pending approval. A Whatsapp message will be sent to your phone.');
            return view('auth.login');
        } elseif ($status === 1) {
            //get number of promotions tht apply to this particular user
            $my_id = DB::table('vas_client_centre')->where([
                ['vas_client_id', '=', $user_id],
                ['vas_centre_id', '=', $vas_centre_id],
            ])->get();
          
            $vas_cos_id = (int) $my_id[0]->vas_cos_id;
              
            $query_return = DB::table('vas_rules')->where([
                ['vas_centre_id', '=', $vas_centre_id],
                ['cos_id', '=', $vas_cos_id]
            ])->get();

            if (count($query_return) == 0) {
                Auth::logout();
                \Session::flash('warning', 'AfricaBet is not running a Promotion for your category at the moment. Please check later.');

                return view('auth.login');
            }
 
            $centre_id = $_ENV['APP_API_CENTRE_ID'];

            $logs = DB::select('SELECT * FROM vas_promotions_report WHERE ClientId = ? AND vasCentreId = ? AND CurrencyId = ? AND `Action` = \'transact\' ORDER BY id DESC LIMIT 5', [$user_id, $vas_centre_id, $currencyId]);
            $logged = DB::select('SELECT * FROM vas_promotions_report WHERE ClientId = ? AND vasCentreId = ? AND CurrencyId = ? AND `Action` = \'topup\' ORDER BY id DESC LIMIT 5', [$user_id, $vas_centre_id, $currencyId]);
          
            #$logged = DB::select('SELECT trans.created_date as date, req.points as points, req.currency_id as currencyId , trans.payee, trans.amount as amount, trans.description, trans.provider_response, prov.name AS source FROM transactions AS trans JOIN vas_transaction_analysis AS req ON trans.reference = req.reference  JOIN providers as prov ON trans.provider_id = prov.id AND req.vas_client_id = ? AND trans.center_id = ? AND trans.`status` = 1 AND req.action = "topup"  WHERE req.currency_id = ? ORDER BY req.id DESC LIMIT 5;', [$user_id, $centre_id, $currencyId]);
                   
            //grade value code
            $promotion = DB::table('vas_cos')
                ->join('vas_cos_promotions', 'vas_cos.promotion_class_id', '=', 'vas_cos_promotions.id')
                ->where([
                    ['vas_cos.id', '=', $vas_cos_id],
                    ['vas_cos.deleted', '=', 0],
                    ['vas_cos.status', '=', 1]
                ])
                ->get();

            if (count($promotion) == 0) {
                Auth::logout();
                \Session::flash('warning', 'Oops, something unexpected happened.');
                return view('auth.login');
            }


            $member_status = DB::table('vas_cos')
                ->join('vas_client_centre', 'vas_cos.id', '=', 'vas_client_centre.vas_cos_id')
                ->where('vas_client_centre.vas_client_id', '=', $user_id)->pluck('name');

        } elseif ($status === 2) {
            Auth::logout();
            \Session::flash('warning', 'Your Account has been suspended.');
            return view('auth.login');
        } elseif ($status === 3) {
            Auth::logout();
            \Session::flash('warning', 'Your Account has been blocked.');
            return view('auth.login');
        }

        $cash = DB::select('SELECT * FROM vas_client_card WHERE clientId = ? AND currencyId = ? AND centreId = ?', [$user_id, $currencyId, $centre_id]);;
       
        return view('home')
            ->with('cash', $cash)
            ->with('logs', $logs)
            ->with('logged', $logged)
            ->with('promotion', $promotion)
            ->with('member_status', $member_status);
    }
}



