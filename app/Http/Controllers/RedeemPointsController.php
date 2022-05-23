<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;

class RedeemPointsController extends Controller
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

        $currency = DB::select('SELECT * FROM vas_client_session WHERE vas_client_id = ?', [$user_id ]);
        $currencyId = (int) $currency[0]->currency_id;

        $vas_cos_user = DB::table('vas_client_centre')->where([
            ['vas_client_id', '=', $user_id], 
            ['vas_centre_id', '=', $vas_centre_id],
            ['currency_id', '=', $currencyId]
        ])->get();

        $vas_cos_id = (int)$vas_cos_user[0]->vas_cos_id;

        $products = DB::table('vas_products')->where([
            ['quantity', '>', 0],
            ['vas_centre_id', '=', $vas_centre_id], 
            ['vas_cos_id', '=', $vas_cos_id],
            ['status', '=', 1]
        ])->orderBy('id', 'DESC')->paginate(40);
        
        return view('redeem')
        ->with('products', $products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = Auth::user()->id;
        $vas_centre_id = Auth::user()->vas_centre_id;

        $currency = DB::select('SELECT * FROM vas_client_session WHERE vas_client_id = ? AND vas_centre_id = ?', [$user_id, $vas_centre_id]);
        $currencyId = (count($currency) > 0) ? (int) $currency[0]->currency_id : 270;

        $this->validate($request, [
            'points' => 'numeric|required',
        ]);

            
        $req_points = $request->input('points');
        
        $db_points = DB::table('vas_client_centre')->where([
                ['vas_client_id', '=', $user_id], 
                ['vas_centre_id', '=', $vas_centre_id],
                ['currency_id', '=', $currencyId]
            ])->get();

        $key_name = DB::table('vas_cos_action')->where('key', '=', 'amount_from_points')->get();
       
        $action_id = (int)$key_name[0]->id;
        
        $points = (int)$db_points[0]->point;
      
        $cos_id = (int)$db_points[0]->vas_cos_id;

         $products = DB::table('vas_products')->where([
            ['quantity', '>', 0],
            ['vas_centre_id', '=', $vas_centre_id], 
            ['vas_cos_id', '=', $cos_id],
            ['status', '=', 1]
        ])->orderBy('id', 'DESC')->paginate(40);

        $vas_cos = DB::table('vas_rules')
                ->join('vas_cos', 'vas_rules.cos_id', '=', 'vas_cos.id')
                ->join('vas_cos_promotions', 'vas_cos.promotion_class_id', '=', 'vas_cos_promotions.id')
                ->where('vas_rules.vas_centre_id', '=', $vas_centre_id)
                ->get();

        
        //get type of channel id

        if($points >= $req_points){
            
            $db_cos_id = DB::table('vas_rules')->where([
                ['vas_centre_id', '=', $vas_centre_id], 
                ['cos_id', '=', $cos_id],
                ['cos_action_id', '=', $action_id],
                ['status', '=', 1]
            ])->get();

            if(count($db_cos_id) === 0){
                //no rule set for this category
                \Session::flash('danger', 'Sorry, points conversion is disabled at the moment.');
                return back();
            }
              
            $effect_action = (int)$db_cos_id[0]->effect_action;
           
            $effect_value = (int)$db_cos_id[0]->effect_value;
            $min_value = (int)$db_cos_id[0]->min_value;

            if($min_value > $req_points){
                
                \Session::flash('warning', 'Minimum points redeemable should be '. $min_value);
                return back()
                ->with('req_points', $req_points)
                ->with('products', $products)
                ->with('vas_cos', $vas_cos);
            }
        
            if($effect_action != 0){
                $converted_balance = ($req_points / $min_value) * $effect_value;
                \Session::flash('convert', $converted_balance);
                return view('redeem')
                ->with('req_points', $req_points)
                ->with('products', $products)
                ->with('vas_cos', $vas_cos);
            }else{
                $converted_balance = $effect_value;
                \Session::flash('convert', $converted_balance);
                return view('redeem')
                ->with('req_points', $req_points)
                ->with('products', $products)
                ->with('vas_cos', $vas_cos);
            }
        }else{
           
            \Session::flash('danger', 'Oops, you tried to convert points that are above your balance.');
             return back()
             ->with('req_points', $req_points)
             ->with('products', $products)
             ->with('vas_cos', $vas_cos);
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
