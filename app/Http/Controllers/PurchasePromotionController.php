<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;

class PurchasePromotionController extends Controller
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

        //get cos id
        $vas_centre_id = Auth::user()->vas_centre_id;
        $currency = DB::select('SELECT * FROM vas_client_session WHERE vas_client_id = ? AND vas_centre_id = ?', [$user_id, $vas_centre_id]);
        $currencyId = (count($currency) > 0) ? (int) $currency[0]->currency_id : 270;

        $vas_cos_user = DB::table('vas_client_centre')->where([
            ['vas_client_id', '=', $user_id], 
            ['vas_centre_id', '=', $vas_centre_id],
            ['currency_id', '=', $currencyId]
        ])->get();

        $vas_cos_id = (int)$vas_cos_user[0]->vas_cos_id;

        $products = DB::table('vas_products')->where([
            ['vas_centre_id', '=', $vas_centre_id], 
            ['vas_cos_id', '=', $vas_cos_id],
            ['status', '=', 1]
        ])->get();
        
    
        $key = DB::table('vas_cos_action')->where('key', '=', 'amount_from_points')->get();
        $key_id = (string)$key[0]->id;
        //fetch from vas rules
        $obj = DB::table('vas_rules')->where([
            ['vas_centre_id', '=', $vas_centre_id], 
            ['cos_id', '=', $vas_cos_id],
            ['cos_action_id', '=', $key_id],
            ['status', '=', 1]
        ])->get();

        return view('redeem')
        ->with('products', $products)
        ->with('obj', $obj);
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
