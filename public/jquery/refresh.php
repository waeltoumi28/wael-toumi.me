<?php
    include_once('dbconnect.php');
        date_default_timezone_set('Africa/Harare');
        
        $vas_centre_id = $_REQUEST['vas_centre_id'];
        
        $user_id = $_REQUEST['user_id'];  
        
        $today = date('Y-m-d h:i:s');
            
        $db->query("UPDATE vas_notifications SET status_id = 2, date_delivered = '$today' where vas_centre_id = '$vas_centre_id' AND vas_client_id =  '$user_id' AND  channel_id = 3 AND status_id = 1")or die(mysqli_error());
        
        $o = $db->query("SELECT * FROM  vas_notifications JOIN vas_campaigns JOIN vas_notification_channel on vas_campaigns.id = vas_notifications.campaign_id AND vas_notification_channel.id = vas_notifications.channel_id WHERE vas_notifications.channel_id = 3 AND vas_campaigns.vas_centre_id =  '$vas_centre_id'   AND vas_notifications.vas_client_id = '$user_id' AND vas_notifications.status_id  =  2")or die(mysqli_error()); 
       
        $count = $o->num_rows; 

        echo "<script> $('#unread_status').html('$count'); </script>";

    ?> 