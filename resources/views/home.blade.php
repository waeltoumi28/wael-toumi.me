
@extends('layouts.app')

@section('content')

<div class="app-title">

    <div>
      <h1><i class="fa fa-dashboard"></i> Welcome to AfricaBet</h1>
      @if(count($cash) > 0)
        <p>Gain <strong style="color: #a40b0b;">{{$cash[0]->remainingPoints}}</strong> points to go to the next membership level.</p>
      @endif
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
    </ul>
  </div>
  @if(session()->has('success'))
    <div class="alert alert-info" role="alert">
        {{ session()->get('success') }}
    </div>
  @endif
  <div class="row">
    <div class="col-md-3 col-lg-3 col-sm-6">
      <div class="widget-small primary coloured-icon"><i class="icon fa fa-money" aria-hidden="true"></i>
        <div class="info">
          <h4>Wallet Balance</h4>
          <?php 
            $balance = 0;
            foreach ($cash as $key) {
              # code...
              $balance += (float)$key->credits;
            }
          ?>
          
          <!---Start dropdown--->
          <div class="dropdown">
            <button class="dropbtn">
              <p class="balance"><strong><?php echo ($cash[0]->currencyId == 270) ? '$ZWL ' : '$USD'?> <?php echo number_format((float)$balance, 2, '.', ''); ?></strong></p>
            </button>
            <div class="dropdown-content">
              @if(count($cash) > 0)
                @foreach($cash as $item)
                  <a class="key">{{ucfirst($item->walletType)}}  <span>{{$item->currency . ' ' . number_format((float)$item->credits, 2, '.', '')}}</span></a>
                @endforeach
                <a class="divider key">Total <span><strong><?php echo ($cash[0]->currencyId == 270) ? '$ZWL ' : '$USD'?> <?php echo number_format((float)$balance, 2, '.', ''); ?></strong></span></a>
              @endif  
            </div>
          </div>
          <!---End dropdown--->
        </div>
      </div>
    </div>
    <div class="col-md-3 col-lg-3 col-sm-6">
      <div class="widget-small secondary coloured-icon"><i class="icon fa fa-star fa-3x"></i>
        <div class="info">
          <h4>Loyalty Points</h4>
            <p><b>{{ $cash[0]->points }}</b></p>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-lg-3 col-sm-6">
      <div class="widget-small warning coloured-icon"><i class="icon fa fa-files-o fa-3x"></i>
        <div class="info">
          <h4>Membership Level</h4>
          <p><b>{{$member_status[0]}}</b></p>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-lg-3 col-sm-6">
      <div class="widget-small danger coloured-icon"><i class="icon fa fa-thumbs-o-up fa-3x"></i>
        <div class="info">
            <h4>Group</h4>
            @if(count($promotion) > 0)
            <p><b>{{$promotion[0]->name}}</b></p>
            @endif
        </div>
      </div>
    </div>
  </div>
 <div class="row">
  <div class="col-md-12">
      <div class="mobile-hidden">
      <h3 class="tile tile-title custom-heading heading-background" style="margin-bottom: 3px;">Account Report</h3>
      <form method="post" class="tile" style="margin-bottom: 3px;">
      <div class="form-row">
      <div class="form-group col-md-3" style="margin-bottom: 0rem;"></div>
        <div class="form-group col-md-2 col-sm-4" style="margin-bottom: 0rem;">
          <input type="number" class="form-control" id="count_rows_transactions" value="7">
        </div>
        <div class="form-group col-md-4 col-sm-4" style="margin-bottom: 0rem;">
          <select id="range_transactions" class="form-control">
            <option value="Daily">Daily</option>
            <option value="Weekly">Weekly</option>
            <option value="Monthly">Monthly</option>
            <option value="Yearly">Yearly</option>
          </select>
        </div>
        <div class="form-group col-md-2 col-sm-4" style="margin-bottom: 0rem;">
          <input type="button" class="btn green btn-block" id="search_transactions" value="Filter">
        </div>
      </div>
      </form>
        <div class="tile tile-body" style="min-height:400px" id="display_results_transactions">
          
        </div>
      </div>
  </div>
  
<script>  $(function(){  $("#display_results_transactions").load("transFcnSections.php").show("slow");
    $("#search_transactions").click(function(){
      var range = $("#range_transactions").val(); 
      var seven = $("#count_rows_transactions").val(); 
      $("#display_results_transactions").load("transFcnSections.php?range="+range+"&seven="+seven).show("slow");
    }); 
  }); 
 </script>
 </div>
  <div class="row">
    <div class="col-md-6 col-sm-12">
      <div class="">
        <h4 class="tile tile-title custom-heading heading-background">
          Recent Transactions</h4>
            <div class="tile tile-body" style="min-height:380px">
              <table class="table table-hover table-bordered" >
                <thead>
                  <tr style="background-color: #fff !important;color: #333 !important;">
                    <th>Reference</th>
                    <th>Amount</th>
                    <th>Description</th>
                    <th>Bonus Earned</th>
                    <th>Points Earned</th>
                    <th>Date</th>
                  </tr>
                </thead>
                <tbody>
                  @if(count($logs) > 0)
                    @foreach($logs as $log)
                    <tr class="tr-mobile">
                      <td data-label="Reference:">{{$log->Reference}}</td>
                      <td data-label="Currency:">{{$log->Currency . ' ' . number_format((float)$log->Amount, 2, '.', '')}}</td>
                      <td data-label="Description:">{{$log->Description}}</td>
                      <td data-label="Bonus Earned:">{{number_format((float)$log->BonusEarned, 2, '.', '')}}</td>
                      <td data-label="Points Earned:">{{$log->PointsEarned}} Points</td>
                      <td data-label="Date:">{{$log->Date}}</td>
                    </tr>
                    @endforeach
                  @endif
                </tbody>
              </table>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
      <div class="">
        <h4 class="tile tile-title custom-heading heading-background">Recent Topups</h4>
        <div class="tile tile-body" style="min-height:380px">
          <table class="table table-hover table-bordered" >
            <thead>
              <tr style="background-color: #fff !important;color: #333 !important;">
                <th>Reference</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Bonus Earned</th>
                <th>Points Earned</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              @if(count($logged) > 0)
                @foreach($logged as $log)
                <tr class="tr-mobile">
                  <td data-label="Reference:">{{$log->Reference}}</td>
                  <td data-label="Amount:">{{$log->Currency . ' ' . number_format((float)$log->Amount, 2, '.', '')}}</td>
                  <td data-label="Description:">{{$log->Description}}</td>
                  <td data-label="Bonus Earned:">{{number_format((float)$log->BonusEarned, 2, '.', '')}}</td>
                  <td data-label="Points Earned:">{{$log->PointsEarned}} Points</td>
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
