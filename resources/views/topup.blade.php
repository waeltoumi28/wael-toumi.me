@extends('layouts.app')

@section('content')
<div class="app-title">
        <div>
          <h1><i class="fa fa-edit"></i>ContiPay</h1>
          <p>Your E-Commerce payment solution</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item">Topup</li>
          <li class="breadcrumb-item"><a href="#">Topup Account</a></li>
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
          <div class="">
            <h3 class="tile tile-title custom-heading heading-background" style="margin-bottom: 3px;">Topup Account</h3>
            <div class="tile-body tile">
            <?php
              $client_id = Auth::user()->id;
            ?>
              {!! Form::open(['action' => 'TopupController@store', 'method' => 'POST', 'class' => 'row']) !!}
              {{Form::hidden('client_id', $client_id , ['class' => ''])}}
                <div class="form-group col-md-3 col-sm-6">
                  <label class="" for="exampleInputAmount">Amount</label>
                  <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                    <input class="form-control" id="exampleInputAmount" name="amount" type="text" placeholder="Amount">
                    <div class="input-group-append"><span class="input-group-text">.00</span></div>
                  </div>
                </div>
                <div class="form-group col-md-3 col-sm-6">
                  <label class="control-label">Currency</label>
                  <select class="form-control" name="currency">
                    <optgroup label="Select Currency">
                      <option value="ZWD"><strong>ZWD&nbsp;&nbsp;</strong>Zimbabwe Dollar</option>
                      <option value="ZAR"><strong>ZAR&nbsp;&nbsp;</strong>Rand</option>
                      <option value="BWP"><strong>BWP&nbsp;&nbsp;</strong>Pula</option>
                      <option value="USD"><strong>USD&nbsp;&nbsp;</strong>Dollars</option>
                      <option value="EUR"><strong>EUR&nbsp;&nbsp;</strong>Euro</option>
                      <option value="GBP"><strong>GBP&nbsp;&nbsp;</strong>Pounds</option>
                    </optgroup>
                  </select>
                </div>
                <div class="form-group col-md-4 align-self-end col-sm-6">
                  <button class="btn green" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Topup</button>
                </div>
              {!! Form::close() !!}
            </div>
          </div>
        </div>

      </div>
@endsection