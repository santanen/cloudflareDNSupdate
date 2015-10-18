<?php

include("config.php");

function cfGetDomainData($domain) {

  global $config_auth;

  $data = array(
    'a' => "rec_load_all",
    'tkn' => $config_auth['tkn'],
    'email' => $config_auth['email'],
    'z' => $domain
  );

  return httpPost($data);
}

function httpPost($data) {

  global $cfUrl;

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_URL, $cfUrl);
  curl_setopt($ch, CURLOPT_POST, true);

  curl_setopt($ch,CURLOPT_POSTFIELDS, $data);

  $output = curl_exec($ch);
  curl_close($ch);
  return json_decode($output);
}

?>
