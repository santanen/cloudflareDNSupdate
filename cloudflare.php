<?php

include(dirname(__FILE__) . "/config.php"); // get auth and domain data
include(dirname(__FILE__) . "/cf_functions.php");

$publicIP = getPublicIP();
if ($publicIP == null) {
  fwrite(STDERR, "\nCould not retrieve public IP. Exiting...\n");
  exit;
}

foreach ($config_domains as $domain) {

  // get DNS records for domain
  $domainData = cfGetDomainData($domain);

  if ($domainData["result"] != "success") {
    fwrite(STDERR, "\nFailed to get domain data. Exiting....\n");
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

  if ($publicIP != $cf_ip) {
    $updateResult = setDomainARecord($domain, $cf_recid, $publicIP, $cf_name);
    if ($updateResult["result"] != "success") {
      fwrite (STDERR, "Failed to update domain data (" . $domain . " " . $cf_redic . " " . $publicIP . "). Exiting....\n");
      exit;
    }
    else {
      fwrite (STDOUT, "Updated domain data (" . $domain . " " . $cf_redic . " " . $publicIP . ").\n");
    }
  }
}
?>
