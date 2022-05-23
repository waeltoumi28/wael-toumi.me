@extends('layouts.app')

@section('content')
<div class="app-title">
        <div>
          <h1><i class="fa fa-edit"></i>ContiPay</h1>
          <p>Your E-Commerce payment solution</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item">Client Centre</li>
          <li class="breadcrumb-item"><a href="#">Set Transaction Settings</a></li>
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
          <div>
            <h3 class="tile tile-title custom-heading heading-background" style="margin-bottom: 3px;">Client Centre</h3>
            <div class="tile-body tile">
              <table class="table" style="margin-top: 35px;">
                <thead class="thead-dark">
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Currency</th>
                    <th scope="col">Transaction Limit</th>
                    <th></th>
                    <th scope="col" style="text-align: right">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $counter = 0; ?>
                  @if(count($amount) > 0)
                    @foreach($amount as $amnt)
                    <tr>
                      <th scope="row">{{++$counter}}</th>
                      <td>{{$amnt->local_curr}}</td>
                      <td>{{$amnt->limit}}</td>
                      <td style="text-align: right"></td>
                      <td style="text-align: right">
                        {!! Form::open(['action' => 'ClientCentreController@store', 'method' => 'POST', 'class' => 'form-group']) !!}
                        {{Form::hidden('currency_id', $amnt->currency_id , ['class' => ''])}}
                        <a class="btn btn-danger" onclick="fetchCurrencyLimit(`<?php print $amnt->local_curr; ?>`, `client_centre`)" value="{{$amnt->local_curr}}" data-toggle="modal" data-target="#setLimit"><i class="fa fa-money" aria-hidden="true"></i>Set Limit</a>
                        <button class="btn <?php echo ($amnt->local_curr == $default_currency[0]->local_curr) ? 'green' : 'btn-warning'; ?>" type="submit">
                          <?php 
                            if(count($default_currency) > 0) {
                              echo ($amnt->local_curr == $default_currency[0]->local_curr) ? '<i class="fa fa-check-circle" aria-hidden="true"></i> Current Default' : '<i class="fa fa-cogs" aria-hidden="true"></i>Set as Default';
                            }else{
                              echo'<i class="fa fa-cogs" aria-hidden="true"></i>Set as Default';
                            }   
                          ?>
                        </button>
                        {!! Form::close() !!}
                      </td>
                    </tr>
                    @endforeach
                  @endif
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>

         <!----------------------------------------------./ MODAL TO SET TRANSACTION LIMIT ------------------------------------------------->
      <!-- Modal -->
    <div class="modal fade" id="setLimit" tabindex="-1" role="dialog" aria-labelledby="setLimitLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="setLimitLabel">Change Transaction Limit</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <!---------------------------------------------------./ FORM CONTROL -------------------------------------------------------------->
          {!! Form::open(['action' => 'ClientCentreController@store', 'method' => 'POST', 'class' => 'form-group']) !!}
          <div class="modal-body">
                <div class="form-group">
                  <label for="clientCentreId">Currency ISO Code</label>
                  <input type="text" class="form-control" id="currencyCode" name="currencyCode" value="">
                  <input type="hidden" class="form-control" id="clientCentreId" name="clientCentreId"  value="">
                </div>
                <div class="form-group">
                  <label for="limit">Limit</label>
                  <input type="text" class="form-control" id="limit" name="limit" value="">
                </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn green">Update Transaction Limit</button>
          </div>
          {!! Form::close() !!}
          <!---------------------------------------------------./ END FORM CONTROL ---------------------------------------------------------->
        </div>
      </div>
    </div>
    <script>
      function fetchCurrencyLimit(currency, endPoint)
      {
        var token = "{{ csrf_token() }}";
        $.ajax({
          url:endPoint,
          type: 'POST',
          data:{
            _token: token,
            currency:currency
            },
          success: function(response){
            $("#currencyCode").val(response.currency);
            $("#clientCentreId").val(response.id);
            $("#limit").val(response.limit);
            
          }
        });
      }
    </script>
@endsection