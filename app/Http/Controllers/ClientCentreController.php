<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;

class ClientCentreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $vas_centre_id = Auth::user()->vas_centre_id;

        $amount = DB::select('SELECT * FROM currencies JOIN vas_client_centre ON vas_client_centre.currency_id = currencies.id WHERE vas_client_centre.vas_client_id = ? AND vas_client_centre.vas_centre_id',[$user_id, $vas_centre_id]);
        
        //fetch default currency
        $default_currency = DB::select('SELECT * FROM currencies JOIN vas_client_session ON currencies.id = vas_client_session.currency_id  WHERE vas_client_session.vas_client_id = ? AND vas_client_session.vas_centre_id = ?',[$user_id, $vas_centre_id]);
        
        return view('client_centre')
        ->with('default_currency', $default_currency)
        ->with('amount', $amount);
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
        $center_id = Auth::user()->vas_centre_id;

        $client_id = Auth::user()->id;        
        
        if(isset($request->currency))
        {
            //Run ajax call
            $query = DB::select('SELECT vas_client_centre.id as id, vas_client_centre.`limit` FROM vas_client_centre JOIN currencies ON currencies.id = vas_client_centre.currency_id WHERE vas_client_centre.vas_centre_id = ? AND vas_client_centre.vas_client_id = ? AND currencies.iso_code = ?', [$center_id, $client_id, $request->currency]);
            return [
                "currency"  =>  $request->currency,
                "limit"     =>  $query[0]->limit,
                "id"        => $query[0]->id
            ];
        }else{

            if(isset($request->limit)){
                //Process code from modal
                DB::table('vas_client_centre')->where([
                    ['id', '=', $request->clientCentreId]
                ])->update(['limit' => $request->limit]);

                return back()->with('success', 'Transaction limit has been updated.');

            }else{
                //Change default currency
                $curr_id = (int)$request->currency_id;
            
                DB::table('vas_client_session')->where([
                    ['vas_client_id', '=', $client_id],
                    ['vas_centre_id', '=', $center_id]
                ])->update(['currency_id' => $curr_id ]);
                return back()->with('success', 'Default Transaction Updated.');
            }
            
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
