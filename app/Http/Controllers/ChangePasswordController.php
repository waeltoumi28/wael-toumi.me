<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Illuminate\Hashing\AbstractHasher;

class ChangePasswordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('password');
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
            'user_id' => 'required',
            'current_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required',
        ]);

        
        $current_pwd = AbstractHasher::customHash($request->current_password);
        
        $db_pwd = DB::table('vas_client')->where('id', '=', $request->user_id)->get();
    
        if($current_pwd === $db_pwd[0]->password){
            $new_pwd = AbstractHasher::customHash($request->new_password);
            $confirm_pwd = AbstractHasher::customHash($request->confirm_password);

            if($new_pwd === $confirm_pwd){
                DB::table('vas_client')
                ->where('id', $request->user_id)
                ->update(['password' => $new_pwd]);
                return back()->with('success', 'Password successfully changed.');
            }else{
                return back()->with('warning', 'Passwords confirmation failed validation!!!');
            }

        }else{
            return back()->with('warning', 'Passwords do not match!!!');
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
        
    }
}
