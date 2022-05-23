<?php 
session_start();
error_reporting(0);
$logged_in_user_id = $_SESSION['logged_in_user_id'];
$db = new mysqli("102.177.192.63", "contipay", "16743598", "contipay");
$vas_centre_id = 7;

$currency_id = $db->query("SELECT currency_id FROM vas_client_session  WHERE vas_client_id = '$logged_in_user_id' AND  vas_centre_id = '$vas_centre_id'")->fetch_object()->currency_id; 


function newDatea($pete, $u){

    return date("Y-m-d",strtotime(date($pete,strtotime(date($pete)))."+$u days"));

  }


@$range = $_REQUEST['range'];
@$seven = $_REQUEST['seven'];


 
if(empty($range)){
	$range = 'Daily';
}

if(empty($seven)){
	$seven = 7;
}
 

//------------------------------------smart engineering---------------------------------------
$today = date('Y-m-d');
$month_now = date('m', strtotime($today));
$week_now = date('W', strtotime($today));
$day_now = date('d', strtotime($today));
$year_now = date('Y', strtotime($today));  
$x_axis = array();
$y_axis = array();
$data = array();

  
  $x = 1;




 if($range == 'Daily'){ 

  //-------------------------Daily---------------------------------- 
  $first_date = newDatea($today, 1 - $seven);
  for($i=0; $i < $seven; $i++){  
  		$next_date = newDatea($first_date,$i);
  		array_push($x_axis,$next_date); 
     
  //------------------------------------ Shit Goes Here ---------------------------------------------

    $transaction_analysis = $db->query("SELECT * FROM `vas_transaction_analysis` WHERE `date`  between '$next_date 00:00:00' and '$next_date 23:59:59' and vas_client_id = $logged_in_user_id and vas_centre_id = $vas_centre_id and status = 1 AND currency_id = '$currency_id'") or die('error'); 
    
    $total_topups = 0;
    $total_transactions = 0;
    $total_promotional_sales = 0;
    $total_redemptions = 0;
   $temp = array();
   
     while($fetch_ta = $transaction_analysis->fetch_array()){
       if($fetch_ta['action'] == 'topup'){
           $total_topups += $fetch_ta['amount'];
         }
         if($fetch_ta['action'] == 'transact'){
           $total_transactions += $fetch_ta['amount'];
         } 
         if($fetch_ta['action'] == 'purchase'){
           $total_promotional_sales += $fetch_ta['amount'];
         }  

         if($fetch_ta['action'] == 'redeem'){
           $total_redemptions += $fetch_ta['amount'];
         }   
     }
     $temp = array(
       "Transactions" => $next_date,
       "Topups" => $total_topups,
       "Card Transactions" => $total_transactions,
       "Promotional Sales" => $total_promotional_sales,
       "Redemptions" => $total_redemptions
   );
array_push($data,$temp);
 
  }  
  $x_axis = json_encode($x_axis);
  $data = json_encode($data);

  //------------------------------------ Shit Goes Here ---------------------------------------------

      
  //-------------------------Daily----------------------------------


}else if($range == 'Weekly'){  

 //------------------------- Weekly --------------------------------- 
   $first_date = newDatea($today,$seven * (-7));
 //  echo "First date ". $first_date . "<br>";
   $wk = 0;
  for($i=1; $i < $seven * 7; $i+=7){   
  	 $wk += 1;
     $date1 = newDatea($first_date,$i);
     $date2 = newDatea($first_date,$i + 7);
     $pets = $week_now - 7 + $wk;
     array_push($x_axis,'Week '.$pets); 
      
  //------------------------------------ Shit Goes Here ---------------------------------------------

   $transaction_analysis = $db->query("SELECT * FROM `vas_transaction_analysis` WHERE `date`  between '$date1' and '$date2' and vas_client_id = $logged_in_user_id and vas_centre_id = $vas_centre_id and status = 1  AND currency_id = '$currency_id'")or die('error'); 
    $total_topups = 0;
    $total_transactions = 0;
    $total_promotional_sales = 0;
    $total_redemptions = 0;
   $temp = array();
   
     while($fetch_ta = $transaction_analysis->fetch_array()){
       if($fetch_ta['action'] == 'topup'){
           $total_topups += $fetch_ta['amount'];
         }
         if($fetch_ta['action'] == 'transact'){
           $total_transactions += $fetch_ta['amount'];
         } 
         if($fetch_ta['action'] == 'purchase'){
           $total_promotional_sales += $fetch_ta['amount'];
         }  

         if($fetch_ta['action'] == 'redeem'){
           $total_redemptions += $fetch_ta['amount'];
         }   
     }
     $temp = array(
       "Transactions" => 'Week '.$pets,
       "Topups" => $total_topups,
       "Card Transactions" => $total_transactions,
       "Promotional Sales" => $total_promotional_sales,
       "Redemptions" => $total_redemptions
   );
array_push($data,$temp);
 
  }  
  $x_axis = json_encode($x_axis);
  $data = json_encode($data);

  //------------------------------------ Shit Goes Here ---------------------------------------------


      

  //------------------------- Weekly ---------------------------------

}else if($range == 'Monthly'){ 

     //------------------------- Monthly --------------------------------- 
  $today = date('Y-m-').'30'; 
 // echo "MONTH ". date('M',strtotime($today)). "<br/>";
  $month_array = array('Jan','Feb','Mar','Apr','May','Jun','July','Aug','Sept','Oct','Nov','Dec');

 
   $first_date = newDatea($today,$seven * (-30));
   //echo "First date ". $first_date . "<br>";
   $mth = 0;
  for($i=0; $i < $seven * 30; $i+=30){   
  	 $mth += 1;
     $date1 = newDatea($first_date,$i);
     $date2 = newDatea($first_date,$i + 30); 
    // $pets = $month_now - 30 + $mth; 
$imonth = $month_now - $seven + $mth -1;
if($imonth < 0){
	$imonth =  12 + $imonth;
}

// echo " month now ". $month_now . "==". $mth. " month   ". $imonth . "  -  ". $month_array[$imonth] . "<br/>";
  array_push($x_axis,$month_array[$imonth]); 

  //------------------------------------ Shit Goes Here ---------------------------------------------

  $transaction_analysis = $db->query("SELECT * FROM `vas_transaction_analysis` WHERE `date`  between '$date1' and '$date2'  and vas_client_id = $logged_in_user_id and vas_centre_id = $vas_centre_id and status = 1  AND currency_id = '$currency_id'")or die('error'); 
  $total_topups = 0;
  $total_transactions = 0;
  $total_promotional_sales = 0;
  $total_redemptions = 0;
 $temp = array();
 
   while($fetch_ta = $transaction_analysis->fetch_array()){
     if($fetch_ta['action'] == 'topup'){
         $total_topups += $fetch_ta['amount'];
       }
       if($fetch_ta['action'] == 'transact'){
         $total_transactions += $fetch_ta['amount'];
       } 
       if($fetch_ta['action'] == 'purchase'){
         $total_promotional_sales += $fetch_ta['amount'];
       }  

       if($fetch_ta['action'] == 'redeem'){
         $total_redemptions += $fetch_ta['amount'];
       }   
   }
   $temp = array(
     "Transactions" => $month_array[$imonth],
     "Topups" => $total_topups,
     "Card Transactions" => $total_transactions,
     "Promotional Sales" => $total_promotional_sales,
     "Redemptions" => $total_redemptions
 );
array_push($data,$temp);

}  
$x_axis = json_encode($x_axis);
$data = json_encode($data);

//------------------------------------ Shit Goes Here ---------------------------------------------

  //-------------------------  Monthly --------------------------------

}else{

//----------------------------------Yearly -----------------------------------------------------

  $today = date('Y-m-d'); 
 // echo "Year ". date('Y',strtotime($today)). "<br/>";  
  // echo " <br/>  year now ".$year_now ."<br/>"; 
  
  for($i=0; $i < $seven; $i++){ 
  	 $next_year = $year_now - $seven + $i + 1;

     $date1 = $next_year.'-01-01';
     $date2 = $next_year.'-12-31'; 
      array_push($x_axis,$next_year); 
      
  //------------------------------------ Shit Goes Here ---------------------------------------------

  $transaction_analysis = $db->query("SELECT * FROM `vas_transaction_analysis` WHERE `date`  between '$date1' and '$date2'  and vas_client_id = $logged_in_user_id and vas_centre_id = $vas_centre_id and status = 1 AND currency_id = '$currency_id'")or die('error'); 
  $total_topups = 0;
  $total_transactions = 0;
  $total_promotional_sales = 0;
  $total_redemptions = 0;
 $temp = array();
 
   while($fetch_ta = $transaction_analysis->fetch_array()){
     if($fetch_ta['action'] == 'topup'){
         $total_topups += $fetch_ta['amount'];
       }
       if($fetch_ta['action'] == 'transact'){
         $total_transactions += $fetch_ta['amount'];
       } 
       if($fetch_ta['action'] == 'purchase'){
         $total_promotional_sales += $fetch_ta['amount'];
       }  

       if($fetch_ta['action'] == 'redeem'){
         $total_redemptions += $fetch_ta['amount'];
       }   
   }
   $temp = array(
     "Transactions" =>$next_year,
     "Topups" => $total_topups,
     "Card Transactions" => $total_transactions,
     "Promotional Sales" => $total_promotional_sales,
     "Redemptions" => $total_redemptions
 );
array_push($data,$temp);

}  
$x_axis = json_encode($x_axis);
$data = json_encode($data);

//------------------------------------ Shit Goes Here ---------------------------------------------


//---------------------------------Yearly -----------------------------------------------------
}
 
?>
 <div id="topup_bar_graph_transactions" style="height:500px;"></div> 

 <script type="text/javascript"> 
var div_tag_transactions = document.getElementById('topup_bar_graph_transactions');
var myChart2_transactions = echarts.init(div_tag_transactions);
option_transactions = {
    legend: {},
    tooltip: {},
    dataset: {
        dimensions:["Transactions","Topups","Card Transactions","Promotional Sales","Redemptions"],
        source: <?php echo $data; ?>
    },
    xAxis: {type: 'category'},
    yAxis: {},
    // Declare several bar series, each will be mapped
    // to a column of dataset.source by default.
    series: [
        {type: 'bar'},
        {type: 'bar'},
        {type: 'bar'},
        {type: 'bar'}
    ]
};


myChart2_transactions.setOption(option_transactions); 
</script>
