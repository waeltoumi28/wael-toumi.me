<?php
    date_default_timezone_set('Africa/Harare');
    
    include_once('dbconnect.php');
    $vas_centre_id = $_REQUEST['vas_centre_id'];
    $user_id= $_REQUEST['user_id'];
    $id = $_REQUEST['id'];
    $today = date('Y-m-d h:i:s');

    $update = $db->query("UPDATE  vas_notifications  SET status_id = 3, date_read = '$today' WHERE vas_centre_id = '$vas_centre_id'  AND id = '$id' AND vas_client_id  = '$user_id' AND channel_id =  3 AND status_id = 2")or die(mysqli_error()); 
    
    
    $select = $db->query("SELECT id FROM  vas_notifications WHERE status_id = 2 AND  vas_centre_id = '$vas_centre_id'  AND vas_client_id  = '$user_id' AND channel_id =  3")or die(mysqli_error()); 
   

    $count = $select->num_rows;

    echo "<script> $('#unread_status').html('$count'); $('#$id').css('color','#37474f');  $('#$id').css('font-weight','600'); </script>";
?>