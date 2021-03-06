<?php
/**
 * cloudflareDNSupdate
 * cf_functions.php
 *
 * @author Markus <markus@santmark.fi>
 * @copyright Markus 2015
 * @version 1.0
 * @license GPL
 */

include(dirname(__FILE__) . "/config.php"); // get auth and domain data

function cfGetPublicIP() {
  $ip_json = json_decode(file_get_contents("https://api.ipify.org/?format=json"), true);

  if ($ip_json != null  && !filter_var($ip_json["ip"], FILTER_VALIDATE_IP) === false)
    return $ip_json["ip"];
  else
    return null;
}

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

function cfSetDomainARecord($domain, $recid, $ip, $name) {

  global $config_auth;

  $data = array(
    'a' => "rec_edit",
    'tkn' => $config_auth['tkn'],
    'email' => $config_auth['email'],
    'z' => $domain,
    'type' => "A",
    'id' => $recid,
    'content' => $ip,
    'ttl' => "600",
    'name' => $name,
    'service_mode' => "1"

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
  return json_decode($output, true);
}

?>
