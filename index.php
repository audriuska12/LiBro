<?php
include "api/jwt.php";
$ch = curl_init();
$admin = "admin";
$options = [CURLOPT_URL=>"localhost/LiBro/api/auth.php", CURLOPT_RETURNTRANSFER=>true, CURLOPT_POST=>1, CURLOPT_POSTFIELDS=>http_build_query(["username"=>$admin, "password"=>$admin])];
curl_setopt_array($ch, $options);
$rez = curl_exec($ch);
curl_close($ch);
echo $rez;
?>