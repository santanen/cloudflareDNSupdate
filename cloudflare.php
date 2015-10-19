<?php

include(dirname(__FILE__) . "/config.php"); // get auth and domain data
include(dirname(__FILE__) . "/cf_functions.php");

$ipfile = dirname(__FILE__) . "/ipfile.txt";

$publicIP = cfGetPublicIP();
if ($publicIP == null) {
  fwrite(STDERR, "\nCould not retrieve public IP. Exiting...\n");
  exit;
}

// check if publicIP has changed since last run
if (($fh = fopen($ipfile, 'c+')) != false) {
  $ipOnFile = "";

  if (filesize($ipfile) > 0)
    $ipOnFile = fread($fh, filesize($ipfile));

  fclose($fh);

  if ($ipOnFile == $publicIP) {
    exit;
  }

  fwrite(STDOUT, "\nPublic IP has changed to " . $publicIP . ". Starting update of Cloudflare entries...\n");
  $fh = fopen($ipfile, 'w');
  fwrite($fh, $publicIP);
  fclose($fh);
}

// loop through all domains
foreach ($config_domains as $domain) {

  // get DNS records for domain
  $domainData = cfGetDomainData($domain);

  if ($domainData["result"] != "success") {
    fwrite(STDERR, "\nFailed to get domain data from Cloudflare. Exiting....\n");
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
    $updateResult = cfSetDomainARecord($domain, $cf_recid, $publicIP, $cf_name);
    if ($updateResult["result"] != "success") {
      fwrite (STDERR, "Failed to update domain data (" . $domain . " " . $cf_recid . " " . $publicIP . "). Exiting....\n");
      exit;
    }
    else {
      fwrite (STDOUT, "Updated domain data (" . $domain . " " . $cf_recid . " " . $publicIP . ").\n");
    }
  }
}
?>
