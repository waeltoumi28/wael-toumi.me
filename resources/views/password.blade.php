@extends('layouts.app')

@section('content')
<div class="app-title">
        <div>
          <h1><i class="fa fa-edit"></i>Settings</h1>
          <p>Configuration Page</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item">Settings</li>
          <li class="breadcrumb-item"><a href="#">Change Password</a></li>
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

          @if(session()->has('warning'))
            <div class="alert alert-info" role="alert">
                {{ session()->get('warning') }}
            </div>
          @endif
        
          <div class="shaddow">
            <h3 class="tile-title tile custom-heading heading-background" style="margin-bottom: 3px">Change Password</h3>
            <div class="tile-body tile">
                <?php
                $client_id = Auth::user()->id;
                ?>
                {!! Form::open(['action' => 'ChangePasswordController@store', 'method' => 'POST', 'class' => 'form-group']) !!}
                {{Form::hidden('client_id', $client_id , ['class' => ''])}}
                <input type="hidden" name="user_id" value="{{$client_id }}">
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Enter Current Password">
                </div>
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="New Password">
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password">
                </div>
                <button type="submit" class="btn green"><i class="fa fa-unlock-alt"></i> Change Password</button>
                {!! Form::close() !!}
            </div>
          </div>
        </div>

      </div>
@endsection