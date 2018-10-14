<?php
session_start();
if($_SERVER['REQUEST_METHOD'] != 'POST'){
    http_response_code(400);
    die;
} else {
    if(!isset($_POST["username"])){
        http_response_code(400);
        die;
    } else if (!isset($_POST["password"])){
        http_response_code(400);
        die;
    }
    $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);
    $ch = curl_init();
    curl_setopt_array($ch, [CURLOPT_URL=>"localhost/LiBro/api/auth", CURLOPT_POST=>1, CURLOPT_POSTFIELDS=>"username=$username&password=$password", CURLOPT_RETURNTRANSFER=>TRUE]);
    $resp = curl_exec($ch);
    if(curl_getinfo($ch, CURLINFO_RESPONSE_CODE) == 200){
        $_SESSION["token"]=$resp;
        http_response_code(200);
        echo $resp;
    } else {
        http_response_code(403);
        die;
    }
    curl_close($ch);
} ?>