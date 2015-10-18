<?php

include("config.php"); // get auth and domain data
include("cf_functions.php");
include("jsonformat.php");

$output = cfGetDomainData($config_domains[0]);

// printf(json_format($output));
print_r($output);

?>
