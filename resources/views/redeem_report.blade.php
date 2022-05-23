@extends('layouts.app')

@section('content')

<div class="app-title">
        <div>
          <h1><i class="fa fa-th-list"></i> Redeem Logs</h1>
          <p>All your redeemed products and points logs and in one place</p>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item">Redeem Report</li>
          <li class="breadcrumb-item active"><a href="#">Logs</a></li>
        </ul>
      </div>
        @if(session()->has('success'))
          <div class="alert alert-info" role="alert">
              {{ session()->get('success') }}
          </div>
        @endif
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body">
              <table class="table table-hover table-bordered table-responsive" id="sampleTable">
                <thead>
                  <tr>
                    <th>Reference</th>
                    <th>Amount</th>
                    <th>Description</th>
                    <th>Customer Level</th>
                    <th>Promotional Group</th>
                    <th>Branch Name</th>
                    <th>Branch Address</th>
                    <th>Quantity</th>
                    <th>Points Used</th>
                    <th>Date</th>
                  </tr>
                </thead>
                <tbody>
                @if(count($results) > 0)
                @foreach($results as $res)
                  <tr class="tr-mobile">
                    <td data-label="Reference:">{{$res->Reference}}</td>
                    <td data-label="Currency:">{{$res->Currency . ' ' . number_format((float)$res->Amount, 2, '.', '')}}</td>
                    <td data-label="Description:">{{$res->Description}}</td>
                    <td data-label="Customer Level:">{{$res->CustomerLevel}}</td>
                    <td data-label="Promotion Group:">{{$res->PromotionGroup}}</td>
                    <td data-label="Branch Name:">{{$res->BranchName}}</td>
                    <td data-label="Branch Address:">{{$res->BranchAddress}}</td>
                    <td data-label="Quantity:">{{$res->Quantity}}</td>
                    <td data-label="Points Earned:">{{$res->PointsEarned}}</td>
                    <td data-label="Date:">{{$res->Date}}</td>
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