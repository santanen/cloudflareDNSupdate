<?php

include("jsonformat.php");

$ch = curl_init();
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, "https://www.cloudflare.com/api_json.html");
curl_setopt($ch, CURLOPT_POST, true);

$fields = array(
  'a' => "rec_load_all",
  'tkn' => "d87a9d00adb5af035cece0adddd023a6e6db3",
  'email' => "markus@santmark.fi",
  'z' => "santanen.org"
);

curl_setopt($ch,CURLOPT_POSTFIELDS, $fields);
$output = curl_exec($ch);

curl_close($ch);
printf(json_format($output));
//echo htmlentities($output);
?>
