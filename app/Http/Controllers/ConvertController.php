<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;

class ConvertController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    

    public function index()
    {
        
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
            'amount' => 'required',
            'points' => 'required'
        ]);
        
        $user_id = Auth::user()->id;
        //get vas centre id
        $vas_centre_id = Auth::user()->vas_centre_id;
        $currency = DB::select('SELECT * FROM vas_client_session WHERE vas_client_id = ? AND vas_centre_id = ?', [$user_id, $vas_centre_id]);
        $currencyId = (count($currency) > 0) ? (int) $currency[0]->currency_id : 270;

        $vas_cos = DB::select('SELECT * FROM vas_client_centre WHERE vas_client_id = ? AND vas_centre_id = ? AND currency_id = ?', [$user_id , $vas_centre_id, $currencyId]);
        
        $vas_cos_id = $vas_cos[0]->vas_cos_id;

        $reference = rand(0, 1000000);
        $ref = 'Redeem'.$reference;
        // //update points record in the db
        $balance = DB::select("SELECT `applyCosRule`($request->points, 'amount_from_points', $vas_cos_id, $user_id, $vas_centre_id,  $currencyId, 0, 1, 0,'$ref') AS ruleEffects");
        
        $redeemed = json_decode($balance[0]->ruleEffects);
        if(!empty($redeemed->earned->amount)){
            
            DB::table('vas_transaction_analysis')->where([
                ['id', '=', $redeemed->logId]
            ])->update(['status' => 1]);

            \Session::flash('success', 'Your points have been successfully credited to your account.');
            return back();
        }else{
            \Session::flash('danger', 'Failed to convert points');
            return back();
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
