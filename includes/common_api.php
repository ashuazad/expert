<?php


if(isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS'])){
    define( API_BASE_URL , 'https://'.$_SERVER['HTTP_HOST'] );
}else{
    define( API_BASE_URL , 'http://'.$_SERVER['HTTP_HOST'] );
}

function getRequestInput(){
    return  json_decode(file_get_contents('php://input'), true);
}

function getEmailFormat( $data = array() ){
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => API_BASE_URL."/ajax/getEmailFormatt.php",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => json_encode($data),
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: application/json"
      ),
    ));
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      return $response;
    }
}