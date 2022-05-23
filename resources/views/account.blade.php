@extends('layouts.app')
@section('content')
     <div class="app-title">
        <div>
          <h1><i class="fa fa-th-list"></i> Account Logs</h1>
          <p>All your account logs in one place</p>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item">Account</li>
          <li class="breadcrumb-item active"><a href="#">Logs</a></li>
        </ul>
      </div>
        @if(session()->has('success'))
          <div class="alert alert-info" role="alert">
              {{ session()->get('success') }}
          </div>
        @endif
      <div class="row">
        <div class="col-md-12 col-sm-12">
          <div class="tile">
            <div class="tile-body">
              <table class="table table-hover table-bordered table-responsive" id="sampleTable">
                <thead>
                  <tr>
                    <th>Reference</th>
                    <th>Amount</th>
                    <th>Provider</th>
                    <th>Description</th>
                    <th>Customer Level</th>
                    <th>Points Earned</th>
                    <th>Discount Earned</th>
                    <th>Bonus Earned</th>
                    <th>Date</th>
                  </tr>
                </thead>
                <tbody>
                @if(count($logs) > 0)
                  @foreach($logs as $log)
                  <tr class="tr-mobile">
                    <td data-label="Reference:">{{$log->Reference}}</td>
                    <td data-label="Currency:">{{$log->Currency . ' ' . number_format((float)$log->Amount, 2, '.', '')}}</td>
                    <td data-label="Provider:">{{$log->Provider}}</td>
                    <td data-label="Description:">{{$log->Description}}</td>
                    <td data-label="CustomerLevel:">{{$log->CustomerLevel}}</td>
                    <td data-label="Points Earned:">{{$log->PointsEarned}} Points</td>
                    <td data-label="Discount Earned:">{{$log->DiscountEarned}}</td>
                    <td data-label="Bonus Earned:">{{number_format((float)$log->BonusEarned, 2, '.', '')}}</td>
                    <td data-label="Date:">{{$log->Date}}</td>
                  </tr>
                  @endforeach
                @endif
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
@endsection