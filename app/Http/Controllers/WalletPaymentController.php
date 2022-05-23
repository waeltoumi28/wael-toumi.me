<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;

class WalletPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
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
    
    public function index()
    {
        //
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
    public function store(Request $request)
    {
    
        $this->validate($request, [
            'product_id' => 'numeric|required',
            'client_id' => 'numeric|required',
            'currency' => 'required',
            'wallet_type' => 'required',
            'price' => 'required',
            'points' => 'required',
        ]);

        //get cos id
        $vas_centre_id = Auth::user()->vas_centre_id;

        $authKey = $_ENV['APP_API_KEY'];
        $authPass = $_ENV['APP_API_PASS'];
        $time = time();
    
        $params = array(
            "productId"     =>  $request->input('product_id'),
            "clientId"      =>  $request->input('client_id'),
            "currency"      =>  $request->input('currency'),
            "walletType"    =>  $request->input('wallet_type'),
            "price"    =>  $request->input('price'),
            "points"    =>  $request->input('points'),
        );

        $req = json_encode($params);
        
        $header = array(
            'Authorisation:' . base64_encode($authKey.$authPass.$time),
            'Content-Type: application/json',
            "timestamp: $time"
        );
  
        $url =  $_ENV['APP_API_URL'] . "request/loyalty/redeemProduct";
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
            if($response->code == 1){
                //insert transaction record into db for accountability
                $iso_code = DB::table('currencies')->where('iso_code', '=', $request->currency)->get();

                $ref = (rand(100000, 9999999));
                $ref = 'PromoRef'. $ref;
                DB::table('vas_transaction_analysis')->insert(
                    ['vas_products_id' => $request->product_id, 
                    'quantity' => 1,
                    'vas_client_id' => Auth::user()->id,
                    'vas_centre_id' => $vas_centre_id,
                    'amount' => $request->price,
                    'points' => $request->points,
                    'reference' => $ref,
                    'currency_id' => $iso_code[0]->id,
                    'status' => 2,
                    'method' => $request->wallet_type,
                    'action' => 'purchase']
                );

                //update inventory record
                $quantity = DB::table('vas_products')->where('id', '=', $request->product_id)->get();
                $res = $quantity[0]->quantity - 1;
               
                DB::table('vas_products')->where('id', '=', $request->product_id)->update(['quantity' => $res]);
                
                \Session::flash('success', 'Your Purchase was successful. A notification will be sent to your phone.');
                return back();
            }else{
                \Session::flash('danger', $response->message);
                return back(); 
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
