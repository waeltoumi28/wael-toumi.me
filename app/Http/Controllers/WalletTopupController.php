<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;

class WalletTopupController extends Controller
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
        $user_id = Auth::user()->id;
        $vas_centre_id = Auth::user()->vas_centre_id;
        $currency = DB::select('SELECT * FROM vas_client_session WHERE vas_client_id = ? AND vas_centre_id = ?', [$user_id, $vas_centre_id]);
        $currencyId = (count($currency) > 0) ? (int) $currency[0]->currency_id : 270;

        $centre_id = $_ENV['APP_API_CENTRE_ID'];
        
        #$join_log =  DB::select('SELECT  * FROM transactions AS trans JOIN vas_transaction_analysis AS req ON trans.reference = req.reference  JOIN providers as prov ON trans.provider_id = prov.id AND req.vas_client_id = ? AND trans.center_id = ? AND trans.`status` = ? AND req.action = "topup" WHERE req.currency_id = ? ORDER BY req.id DESC;', [$user_id, $centre_id, $status, $currencyId]);
        $logs = DB::select('SELECT * FROM vas_promotions_report WHERE ClientId = ? AND vasCentreId = ? AND CurrencyId = ? AND `Action` = \'topup\' ORDER BY `Date`', [$user_id, $vas_centre_id, $currencyId]);
        return view('analysis')->with('logs', $logs);
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
        //
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
