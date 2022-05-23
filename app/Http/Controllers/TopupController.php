<?php
 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ClientWallet;
use App\History;
use Auth;
use DB;
use GuzzleHttp\Client;
use function GuzzleHttp\json_encode;

class TopupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('topup');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'client_id' => 'required',
            'amount' => 'numeric|required',
            'currency' => 'required'
        ]);

         //get cos id
         $vas_centre_id = Auth::user()->vas_centre_id;
          
        function redirect(string $location, int $wait, bool $force)
        {
            if(headers_sent())
            {

                print '<meta http-equiv="refresh" content="' . $wait . '; url=' . $location . '">';

            }
                else
            {
                sleep($wait);
                exit(header("Location: $location", $force, 303));
            }
        }
        
        $time = time();
        $authKey = $_ENV['APP_API_KEY'];
        $authPass = $_ENV['APP_API_PASS'];
        $centre = $_ENV['APP_API_CENTRE_ID'];
        $header = array(
            'Authorisation:' . base64_encode($authKey.$authPass.$time),
            'Content-Type: application/json',
            'timestamp: ' . $time
        );
        $amount =  $request->input('amount');
        $currency = $request->input('currency');
        $randomNum = (rand(1, 1000000));
        $curr = DB::table('currencies')
            ->where("iso_code", "=", $currency)
            ->get();
        //save into the db
       $analysis_table = DB::table('vas_transaction_analysis')->insertGetId([
            'vas_client_id' => $request->client_id,
            'vas_centre_id' => $vas_centre_id,
            'amount' => $amount,
            'currency_id' => (int)$curr[0]->id,
            'reference' => $randomNum
        ]);

        //contipay redirect
    
        $params = array(
            "timestamp" => $time,
            "returnUrl" => url('/')."/webservice/notify.php?ref=$randomNum",

            "request" => array(
                "method"  => "method",
                "type" => "charge",
                "description" => "Online Portal Topup of $currency $amount",
                "amount" => $amount,
                "reference" => $randomNum,
                "center" => $centre,
                "currency" => $currency,
                "successUrl" => url('/')."/success",
                "cancelUrl" => url('/')."/cancel"
             )
        );
        

        $req = json_encode($params);
  
        $url = $_ENV['APP_API_URL'] . "request/redirect/";
        // send API post request
        $exec = curl_init($url);

        curl_setopt($exec, CURLOPT_POST, TRUE);

        curl_setopt($exec, CURLOPT_POSTFIELDS, $req);

        curl_setopt($exec, CURLOPT_HEADER, FALSE);

        curl_setopt($exec, CURLOPT_RETURNTRANSFER, TRUE);

        curl_setopt($exec, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        curl_setopt($exec, CURLOPT_HTTPHEADER, $header);

        curl_setopt($exec, CURLOPT_ENCODING, "");

        curl_setopt($exec, CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt($exec, CURLOPT_SSL_VERIFYPEER, false);

        $post['post'] = curl_exec($exec);

        $post['info'] = curl_getinfo($exec);

        $post['error'] = curl_error($exec);

        try {
            $response = json_decode($post['post']);
             
            if($response->code == 0){
                    redirect($response->url, 0, true);    
                }

        } catch (Exception $e){
            dd($e);
        }
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
