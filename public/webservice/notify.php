<?php
    
$raw_post = file_get_contents('php://input');
$response = json_decode($raw_post);
file_put_contents("response.txt", json_encode($response));
error_reporting(E_ALL);
$connect = new PDO("mysql:host=102.177.192.63;dbname=contipay", "contipay", "16743598");
$query = $connect->prepare("SELECT * FROM  vas_transaction_analysis WHERE reference = ?");
$query->execute(array($_GET['ref']));
$result = $query->fetch(PDO::FETCH_OBJ);
    if(isset($response->status)){
        if($response->status == 'Paid' && $result->status == 0){
            $currQuery = $connect->prepare("SELECT * FROM  currencies WHERE id = ?");
            $currQuery->execute(array($result->currency_id));
            $currency = $currQuery->fetch(PDO::FETCH_OBJ);
            $vasQuery = $connect->prepare("SELECT * FROM  vas_client_centre WHERE vas_client_id = ? AND currency_id = ? AND vas_centre_id = ?");
            $vasQuery->execute(array($result->vas_client_id, $result->currency_id, $result->vas_centre_id));
            $vas = $vasQuery->fetch(PDO::FETCH_OBJ);
            $sql = "UPDATE vas_client_wallet as wallet join vas_client_wallet_types as walTyp on wallet.vas_client_wallet_type_id = walTyp.id SET wallet.credits = (wallet.credits + $response->amount) WHERE wallet.vas_client_centre_id = ? AND walTyp.`key` = ?";
            $update = $connect->prepare($sql);
            $update->execute(array($vas->id, "electronic"));
            $time = time();
            $authKey = 'N2M2V2hWRWZYcVkrSjFWc0lxSFY3UT09';
            $authPass = 'QONwOJOJ';
            $centre =  22;
            $header = array(
                'Authorisation:' . base64_encode($authKey.$authPass.$time),
                'Content-Type: application/json',
                'timestamp: ' . $time
            );
            
            $api_url = "https://api-dev.contipay.co.zw/request/loyalty/clientTopUp";
            $client = curl_init($api_url);

            $form_data = array(
                "clientId"          =>  $result->vas_client_id,
                "currency"          =>  $currency->iso_code,
                "classOfServiceId"  =>  $vas->vas_cos_id,            
                "amount"            =>  $response->amount,
                "reference"         => $response->reference
            );

            $form_data = json_encode($form_data);

            curl_setopt($client, CURLOPT_POST, true);
            curl_setopt($client, CURLOPT_POSTFIELDS, $form_data);
            curl_setopt($client, CURLOPT_HEADER, false); 
            curl_setopt($client, CURLOPT_HTTPHEADER, $header); 
            curl_setopt($client, CURLOPT_RETURNTRANSFER, true); 
            $awardResponse = curl_exec($client);
            $awardResult = json_decode($awardResponse);
            $complete = $connect->prepare("UPDATE vas_transaction_analysis SET status = 1, contipayRef = ?, action_id = ?  WHERE id = ?");
            $complete->execute(array($response->contiPayRef, 3,  $result->id));
            var_dump($awardResponse, $awardResult);
        }
    }




?>
