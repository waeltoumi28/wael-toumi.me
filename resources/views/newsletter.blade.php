<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
?>

@extends('layouts.app')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-edit"></i>Newsletter</h1>
        <p>Stay informed with our weekly Newsletters</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item">Feeds</li>
        <li class="breadcrumb-item"><a href="#">Newsletter</a></li>
    </ul>
    </div>
    <div class="row">
    <div class="clearix"></div>
        <div class="col-md-12">
            @if(count($errors) > 0)
                @foreach($errors->all() as $error)
                    <div class="alert alert-danger">
                        {{$error}}
                    </div>
                @endforeach
            @endif

            @if(session()->has('success'))
                <div class="alert alert-info" role="alert">
                    {{ session()->get('success') }}
                </div>
            @endif
            <h3 class="tile tile-title custom-heading heading-background" style="margin-bottom: 3px;">Newsletter and Configurations</h3>
            <div class="tile">
                <div class="tile-body">
                    <!--./Tabs-->
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item mr-2 col-sm-12 col-md-3">
                            <a class="nav-link heading-background active bgbt" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Newsletter</a>
                        </li>
                        <li class="nav-item mr-2 col-sm-12 col-md-3">
                            <a class="nav-link heading-background bgbt" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Change Channel</a>
                        </li>
                        <li class="nav-item mr-2 col-sm-12 col-md-3">
                            <a class="nav-link heading-background bgbt" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Change Contact Details</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                <div id="display_results"></div>
                                
                                <script>
                                        $(function(){
                                            $(".activate_read").click(function(){
                                                var vas_centre_id = $(this).attr("vas_centre_id");
                                                var user_id= $(this).attr("user_id");
                                                var id = $(this).attr("id");
                                                $.post("jquery/update_notifications_read.php",{vas_centre_id:vas_centre_id,user_id:user_id,id:id},function(data){
                                                        $("#display_results").html(data);
                                                });
                                            });
                                           
                                        });
                                        
                                        </script>
                            <!---./Newsletter--->
                            
                            @if(count($newsfeed))
                                @foreach($newsfeed as $feed)
                                        <div class="accordion" id="accordionExample">
                                        <div class="card">
                                            <div class="card-header">
                                                    <input type="hidden"  name="custom" value="news"/>
                                                    <input type="hidden"  name="trans_id" value="{{$feed->id}}"/>
                                                    <h2 class="mb-0">
                                                    <button class="btn btn-link btn-block activate_read" vas_centre_id="{{Auth::user()->vas_centre_id}}" user_id="{{Auth::user()->id}}" id="{{$feed->id}}" style="<?php if($feed->name === 'read'){
                                                        echo "color: #37474f; font-weight: 600;"; 
                                                    }?>" type="button" data-toggle="collapse" data-target="#feed{{$feed->id}}" aria-expanded="true" aria-controls="collapseOne">
                                                            <span style="float: left"> {{strtoupper($feed->topic)}}</span> <span style="float: right">{{$feed->date_delivered}}</span>
                                                        </button>
                                                    </h2>
                                            </div>


                                        
                                            <div id="feed{{$feed->id}}" class="collapse 
                                                    @if(session()->has('alert'))
                                                        {{ session()->get('alert') }}
                                                    @endif
                                                " data-parent="#accordionExample">
                                            <div class="card-body">
                                               {{$feed->body}}
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                <!-----./End Newsletter----->
                                @endforeach
                                <br />
                                <div>{{ $newsfeed->links() }}</div>
                            @endif
                        </div>
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                            <!-----./Change Channel----->
                            <h4>Select which channel to receive our Weekly Newsletter from.</h4>
                            <hr />
                            <form method="POST" action="{{action('NewsletterController@store')}}">
                                {{csrf_field()}}
                                    @if(count($channels))
                                        <input type="hidden"  name="custom" value="social"/>
                                        @foreach($channels as $channel)
                                            @if($channel->channel_id === 1)
                                                <div class="custom-control custom-checkbox mt-2 mb-2">
                                                    <input type="checkbox" class="custom-control-input" name="whatsapp" id="customCheck3" <?php if($channel->subscribed == 1) { echo "checked"; } ?>>
                                                    <label class="custom-control-label" for="customCheck3">Whatsapp</label>
                                                </div>
                                            @elseif($channel->channel_id === 2)
                                                <div class="custom-control custom-checkbox mt-2 mb-2">
                                                    <input type="checkbox" class="custom-control-input" name="email" id="customCheck2" <?php if($channel->subscribed == 1) { echo "checked"; } ?>>
                                                    <label class="custom-control-label" for="customCheck2">Email</label>
                                                </div>
                                            @elseif($channel->channel_id === 3)
                                                <div class="custom-control custom-checkbox mt-2 mb-2">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck1" <?php if($channel->subscribed == 1) { echo "checked"; } ?> disabled>
                                                    <label class="custom-control-label" for="customCheck1">Dashboard</label>
                                                </div>
                                            @elseif($channel->channel_id === 4)
                                                <div class="custom-control custom-checkbox mt-2 mb-2">
                                                    <input type="checkbox" class="custom-control-input" name="txt_msg" id="customCheck4" <?php if($channel->subscribed == 1) { echo "checked"; } ?>>
                                                    <label class="custom-control-label" for="customCheck4">Text Message</label>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                <hr />
                                <button class="btn btn-danger" type="submit">Save Changes</button>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                            <!------./Change Contact Details-->
                            <div class="row">
                                <div class="col-md-7 col-sm-12">
                                    <hr />
                                    <form method="POST" action="{{action('NewsletterController@store')}}">
                                        {{csrf_field()}}
                                        @if(count($channels))
                                        <input type="hidden"  name="custom" value="contact"/>
                                            @foreach($channels as $channel)
                                                @if($channel->channel_id == 1)
                                                    <div class="form-group">
                                                        <label for="formGroupExampleInput">Whatsapp Number</label>
                                                        <input type="text" class="form-control" id="formGroupExampleInput" name="app_num" value="{{$channel->value}}">
                                                    </div>
                                                @elseif($channel->channel_id == 2)
                                                    <div class="form-group">
                                                        <label for="formGroupExampleInput2">Email Address</label>
                                                        <input type="email" class="form-control" id="formGroupExampleInput2" name="email_addr" value="{{$channel->value}}">
                                                    </div>
                                                @elseif($channel->channel_id == 4)
                                                <div class="form-group">
                                                    <label for="formGroupExampleInput3">Text Message Number</label>
                                                    <input type="text" class="form-control" id="formGroupExampleInput3" name="text_message" value="{{$channel->value}}">
                                                </div>
                                                @endif
                                            @endforeach
                                            <hr />
                                            <button class="btn btn-danger" type="submit">Save Changes</button>
                                        @endif
                                    </form>
                                </div>
                            </div>
                            <!-----./End Change Contact Details-->
                        </div>
                    </div>
                    <!---./End Tabs--->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection