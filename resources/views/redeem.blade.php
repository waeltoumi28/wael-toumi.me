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
            ['vas_centre_id', '=', $centre_id], 
            ['cos_id', '=', $cos_id],
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
          <div class="col-md-7 col-sm-12">
            <h3 class="tile-title tile heading-background custom-heading" style="margin-bottom: 3px;">Redeem Points</h3>
            <div class="tile-body tile">
              <div>
              {!! Form::open(['action' => 'RedeemPointsController@store', 'method' => 'POST', 'class' => 'row']) !!}
                <div class="form-group col-md-7">
                  <label for="exampleInputAmount">Points</label>
                  <div class="input-group">
                    <input class="form-control" id="exampleInputAmount" name="points" type="text" placeholder="Enter points you wish to convert e.g 300">
                  </div>
                </div>
                <div class="form-group col-md-2 align-self-end">
                  <button class="btn green" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Convert</button>
                </div>
              {!! Form::close() !!}
            </div>
            </div>
          </div>
          
          <div class="col-md-5 col-sm-12">
            <h3 class="tile-title tile heading-background custom-heading" style="margin-bottom: 3px;">Points Value</h3>
            <div class="tile-body tile">
              <div>
              {!! Form::open(['action' => 'ConvertController@store', 'method' => 'POST', 'class' => 'row']) !!}
                <div class="form-group col-md-7">
                    <label for="exampleInputAmount">Points</label>
                    @if($message = Session::get('convert'))
                  <div class="input-group">
                      <input class="form-control" id="exampleInputAmount" name="points" type="text" placeholder="<?php echo $req_points .' points = $'. number_format((float)$message, 2, '.', ''); ?>" disabled>
                    </div>
                  <div class="input-group">
                    <input class="form-control " name="points" type="hidden" Value="{{$req_points}}">
                  </div>
                  <div class="input-group">
                    <input class="form-control " name="amount" type="hidden" Value="{{$message}}">
                  </div>
                @else
                  <div class="input-group">
                      <input class="form-control" id="exampleInputAmount" name="points" type="text" placeholder="$ 0.00" disabled>
                    </div>
                @endif
                  
                </div>
                <div class="form-group col-md-2 align-self-end">
                  <button class="btn green" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Redeem Points</button>
                </div>
              {!! Form::close() !!}
            </div>
            </div>
          </div>

        </div>
        <div class="row">
          <div class="col-md-12">
            <h3 class="tile-title tile heading-background custom-heading" style="margin-bottom: 3px;">Redeemable Rewards</h3>
            <div class="tile-body tilenew">
                <div class="row">
                @if(count($products) > 0)
                @foreach($products as $product)
                  <div class="col-md-2 col-sm-6 products text-center tile">
                    <div class="product-wrapper">
                      <img src="{{$product->image_url}}" class="img-fluid img-responsive"/>
                    </div>
                    <hr />
                    <div class="maroon-effect">
                      <div class="info">
                        <h4 class="lessbold">{{$product->name}}  <br>
                        <span class="description">{{$product->description}}</span><br>
                        <span class="value"><?php print convertToPoints($product->price, $product->vas_cos_id, $product->vas_centre_id) ?> Points</span></h1>
                      </div>
                    </div>
                    {!! Form::open(['action' => 'WalletPaymentController@store', 'method' => 'POST', 'class' => 'wallet-form']) !!}
                      <input type="hidden" name="product_id" value="{{$product->id}}">
                      <input type="hidden" name="client_id" value="{{Auth::user()->id}}">
                      <input type="hidden" name="currency" value="ZWD">
                      <input type="hidden" name="wallet_type" value="points">
                      <input type="hidden" name="price" value="{{$product->price}}">
                      <input type="hidden" name="points" value="<?php print convertToPoints($product->price, $product->vas_cos_id, $product->vas_centre_id) ?>">
                      <button class="btn position-bottom green" >Pay Using Points</button>
                    {!! Form::close() !!}
                  </div>
                  @endforeach
                @endif
              </div>
            </div>
          </div>
          <div>{{$products->links()}}</div>
        </div>
        </div>
      </div>

@endsection
