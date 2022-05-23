<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Session;
use Form;
use Auth;
?>

@extends('layouts.app')

@section('content')
<?php
  function convertToPoints($price, $cos_id, $centre_id){
    $rule = DB::table('vas_rules')->where([
            ['vas_centre_id', '=', 3], 
            ['cos_id', '=', 1],
            ['cos_action_id', '=', 6],
            ['status', '=', 1]
        ])->get();
  
    $effect_action = (int)$rule[0]->effect_action;
    $effect_value = $rule[0]->effect_value;
    $min_value = $rule[0]->min_value;

    if($effect_action > 0){
      $bal = ($effect_value / $min_value) * $price;
      return (int)$bal;
    }
      
  }

?>

<div class="app-title">
        <div>
          <h1><i class="fa fa-edit"></i>Redeem Points</h1>
          <p>Convert your points to money</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item">Loyalty Points Redeemer</li>
          <li class="breadcrumb-item"><a href="#">Redeem Points</a></li>
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
        @if($message = Session::get('warning'))
            <div class="alert alert-danger">
                {{$message}}
            </div>
        @endif
        @if($message = Session::get('danger'))
            <div class="alert alert-danger">
                {{$message}}
            </div>
        @endif
        @if(session()->has('success'))
        <div class="alert alert-info" role="alert">
            {{ session()->get('success') }}
        </div>
        @endif
        <div class="row">
          <div class="tile col-md-12">
            <h3 class="tile-title">Products on Promotion</h3>
            <div class="tile-body">
                <div class="row">
                @if(count($products) > 0)
                @foreach($products as $product)
                  <div class="col-sm-3 products text-center">
                    <div class="product-wrapper">
                      <img src="{{$product->image_url}}" class="img-fluid img-responsive"/>
                      <h1>{{$product->name}}  <br>
                      <span class="description">{{$product->description}}</span><br>
                      <span class="value">${{$product->price}} or <?php print convertToPoints($product->price, $product->vas_cos_id, $product->vas_centre_id) ?> Points</span></h1>
                    </div>
                    {!! Form::open(['action' => 'WalletPaymentController@store', 'method' => 'POST', 'class' => 'wallet-form']) !!}
                      <input type="hidden" name="product_id" value="{{$product->id}}">
                      <input type="hidden" name="client_id" value="{{Auth::user()->id}}">
                      <input type="hidden" name="currency" value="ZWD">
                      <input type="hidden" name="wallet_type" value="credits">
                      <button class="btn btn-block btn-primary">Pay Using Wallet</button>
                    {!! Form::close() !!}

                    {!! Form::open(['action' => 'WalletPaymentController@store', 'method' => 'POST', 'class' => 'wallet-form']) !!}
                      <input type="hidden" name="product_id" value="{{$product->id}}">
                      <input type="hidden" name="client_id" value="{{Auth::user()->id}}">
                      <input type="hidden" name="currency" value="ZWD">
                      <input type="hidden" name="wallet_type" value="points">
                      <button class="btn btn-block btn-info">Pay Using Points</button>
                    {!! Form::close() !!}
                  </div>
                  @endforeach
                @endif
              </div>
            </div>
          </div>
          
        </div>
        </div>
      </div>

@endsection