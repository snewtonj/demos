<?php

//For a production script it would be better to include the apiKey in from a file outside the web root to enhance security.
$rpx_api_key = 'engage rpx key';

$token = $_GET['token'];

if (strlen($token) == 40) {

  $post_data = array('token' => $token,
                     'apiKey' => $rpx_api_key,
                     'format' => 'json',
                     'extended' => 'true');

  $curl = curl_init();
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_URL, 'https://rpxnow.com/api/v2/auth_info');
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
  curl_setopt($curl, CURLOPT_HEADER, false);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($curl, CURLOPT_FAILONERROR, true);
  $result = curl_exec($curl);
  if ($result == false) {
    // generate some json with the error
   echo '{ "errorMessage" :  "' . curl_error($curl) . '" , "errorCode" : "' . curl_errno($curl) . '" }';
    //echo "\n".'HTTP code: ' . curl_errno($curl);
    //echo "\n"; var_dump($post_data);
  }
  curl_close($curl);


  echo $result;
} else {
  // Gracefully handle the missing or malformed token. Hook this into your native error handling system.
  echo '{ "message" : "Authentication canceled." }';
}
