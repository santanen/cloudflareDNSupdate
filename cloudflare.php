<?php

include("config.php"); // get auth and domain data
include("cf_functions.php");
include("jsonformat.php");

$publicIP = getPublicIP();
if ($publicIP != null)
  echo ("\nThis server's current public IP is: " . $publicIP . "\n");
else {
  echo "\nCould not retrieve public IP. Exiting...\n";
  exit;
}

foreach ($config_domains as $domain) {

  // get DNS records for domain
  $domainData = cfGetDomainData($domain);

  if ($domainData["result"] != "success") {
    echo ("\nFailed to get domain data. Exiting....\n");
    exit;
  }

  // loop through DNS records to retrieve A-record
  foreach ($domainData["response"]["recs"]["objs"] as $entry) {
    if ( $entry["type"] == "A" ) {
      $cf_ip = $entry["content"];
      $cf_recid = $entry["rec_id"];
      $cf_name = $entry["name"];
      break;
    }
  }

  // printf(json_format($output));
  // print_r($domainData);
  echo ("\n" . $domain . ":");
  echo ("\n\tIP: " . $cf_ip);
  echo ("\n\tRec_id: " . $cf_recid);

  echo ("\n\tAction needed: ");
  if ($publicIP == $cf_ip)
    echo ("none\n");
  else {
    echo ("updating... ");
    $updateResult = setDomainARecord($domain, $cf_recid, $publicIP, $cf_name);
    if ($updateResult["result"] != "success") {
      echo ("failed to update domain data. Exiting....\n");
      exit;
    }
    else {
      echo ("done!\n");
    }
  }
}
?>
