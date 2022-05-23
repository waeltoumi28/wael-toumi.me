@extends('layouts.app')

@section('content')
<div class="app-title">
        <div>
          <h1><i class="fa fa-edit"></i>Newsletter</h1>
          <p>All your notifications</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item">Newsletter</li>
          <li class="breadcrumb-item"><a href="#">Notifications</a></li>
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

        <div class="alert alert-info" role="alert">
            Post has been successfully deleted...
        </div>

          <div class="card">
            <div class="card-body">
                <div class="text-right">
                    <a href="{{URL::to('/')}}/newsletter" class="btn btn-primary"><i class="fa fa-arrow-left fa-xl"></i>Back to Feeds</a>&nbsp;&nbsp;
                </div>
                </div><br/>
            </div>
          </div>
        </div>
      </div>
@endsection