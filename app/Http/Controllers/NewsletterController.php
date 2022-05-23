<?php

namespace App\Http\Controllers;
date_default_timezone_set('Africa/Harare');

use Illuminate\Http\Request;
use Auth;
use DB;

class NewsletterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get cos id
        $vas_centre_id = Auth::user()->vas_centre_id;
        //get user id
        $user_id = Auth::user()->id;
        
        //get type of channel id
        $dashboard_id = 3;
        
        $newsfeed = DB::table('vas_notifications')
        ->join('vas_campaigns', 'vas_campaigns.id', '=', 'vas_notifications.campaign_id')
        ->join('vas_notification_channel', 'vas_notification_channel.id', '=', 'vas_notifications.channel_id')
        ->join('vas_notification_status', 'vas_notification_status.id', '=', 'vas_notifications.status_id')
        ->where([
            ['vas_notifications.channel_id', '=', $dashboard_id],
            ['vas_campaigns.vas_centre_id', '=', $vas_centre_id],
            ['vas_notifications.vas_client_id', '=', $user_id],
            ['vas_notifications.status_id', '!=', 4]
        ])
        ->select('vas_notifications.*', 'vas_campaigns.topic', 'vas_campaigns.body', 'vas_notification_status.name')
        ->orderBy('vas_notifications.id', 'DESC')
        ->paginate(25);

        //set onload checked func
        $channels = DB::table('vas_client_subscriptions')->where([
            ['vas_client_id', '=', $user_id],
            ['vas_centre_id', '=', $vas_centre_id]
        ])->get();
            
        return view('newsletter')
        ->with('newsfeed', $newsfeed)
        ->with('channels', $channels);
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
       $user_id = Auth::user()->id;
       $center_id = Auth::user()->vas_centre_id;

       if($request->custom === 'social'){ //
           //whatsapp configuration
            if($request->whatsapp == null){
                DB::table('vas_client_subscriptions')->where([
                    ['vas_client_id', '=', $user_id],
                    ['vas_centre_id', '=', $center_id],
                    ['channel_id', '=', 1]
                ])->update(['subscribed' => 0]);
            }

            if($request->whatsapp == "on"){
                DB::table('vas_client_subscriptions')->where([
                    ['vas_client_id', '=', $user_id],
                    ['vas_centre_id', '=', $center_id],
                    ['channel_id', '=', 1]
                ])->update(['subscribed' => 1]);
            }

            //email configuration
            if($request->email == null){
                DB::table('vas_client_subscriptions')->where([
                    ['vas_client_id', '=', $user_id],
                    ['vas_centre_id', '=', $center_id],
                    ['channel_id', '=', 2]
                ])->update(['subscribed' => 0]);
            }

            if($request->email == "on"){
                DB::table('vas_client_subscriptions')->where([
                    ['vas_client_id', '=', $user_id],
                    ['vas_centre_id', '=', $center_id],
                    ['channel_id', '=', 2]
                ])->update(['subscribed' => 1]);
            }

            //sms configuration
            if($request->txt_msg == null){
                DB::table('vas_client_subscriptions')->where([
                    ['vas_client_id', '=', $user_id],
                    ['vas_centre_id', '=', $center_id],
                    ['channel_id', '=', 4]
                ])->update(['subscribed' => 0]);
            }

            if($request->txt_msg == "on"){
                DB::table('vas_client_subscriptions')->where([
                    ['vas_client_id', '=', $user_id],
                    ['vas_centre_id', '=', $center_id],
                    ['channel_id', '=', 4]
                ])->update(['subscribed' => 1]);
            }

            return back()->with('success', 'New Configurations Successfully Saved...');
       
       }elseif($request->custom === 'contact'){ //update contact information

            $this->validate($request, [
                'app_num' => 'required',
                'email_addr' => 'required',
                'text_message' => 'required'
            ]);
            
            //insert into db
            if($request->app_num !== ""){
                DB::table('vas_client_subscriptions')->where([
                    ['vas_client_id', '=', $user_id],
                    ['vas_centre_id', '=', $center_id],
                    ['channel_id', '=', 1]
                ])->update(['value' => $request->app_num]);
            }

            if($request->email_addr !== ""){
                DB::table('vas_client_subscriptions')->where([
                    ['vas_client_id', '=', $user_id],
                    ['vas_centre_id', '=', $center_id],
                    ['channel_id', '=', 2]
                ])->update(['value' => $request->email_addr]);
            }

            if($request->text_message !== ""){
                DB::table('vas_client_subscriptions')->where([
                    ['vas_client_id', '=', $user_id],
                    ['vas_centre_id', '=', $center_id],
                    ['channel_id', '=', 4]
                ])->update(['value' => $request->text_message]);
            }

            return back()->with('success', 'New Configurations Successfully Saved...');

        }elseif($request->custom === 'news'){ // open the newsletter msg n mark as read
            
            DB::table('vas_notifications')
            ->where([
                ['vas_centre_id', '=', $center_id],
                ['id', '=', $request->trans_id],
                ['vas_client_id', '=', $user_id],
                ['channel_id', '=', 3],
                ['status_id', '=', 2]
            ])
            ->update(['status_id' => 3, 'date_read' => now()]);
        
            
            return back()->with('alert', 'show');
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
        return $request;
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
