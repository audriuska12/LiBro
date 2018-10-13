<?php
$access = $_SERVER["REQUEST_METHOD"];

if ($access == 'POST'){
    if(isset($_POST["token"])){
        include "jwt.php";
        if (!($tok = TokenEngine::readToken($_POST["token"]))){
            http_response_code(403);
            die;
        } else {
            if (isset($tok->user) && isset($tok->expires) && strtotime($tok->expires) >= time()){
                include "connection.php";
                $query = "SELECT id FROM users WHERE id = ?";
                $sql = mysqli_prepare($conn, $query);
                $sql->bind_param('i', $tok->user);
                $sql->execute();
                $sql->bind_result($id);
                if($sql->fetch()){
                    $payload = ["user"=> $id, "expires"=>date("Y-m-d H:i:s", strtotime("+1 hour"))];
                    $tok = TokenEngine::makeToken($payload);
                    http_response_code(200);
                    echo $tok;
                    die;
                } else {
                    http_response_code(403);
                    die;
                }
            } else {
                http_response_code(403);
                die;
            }
        }
    }
    else if(isset($_POST["username"])){
        $username = base64_encode($_POST["username"]);
    } else {
        http_response_code(400);
        die;
    }
    if(isset($_POST["password"])){
        $password = base64_encode($_POST["password"]);
    } else {
        http_response_code(400);
        die;
    }
    include "connection.php";
    $query = "SELECT id FROM users WHERE username = ? AND password = ?";
    $sql = mysqli_prepare($conn, $query);
    $sql->bind_param('ss', $username, $password);
    $sql->execute();
    $sql->bind_result($id);
    if($sql->fetch()){
        include "jwt.php";
        $payload = ["user"=> $id, "expires"=>date("Y-m-d H:i:s", strtotime("+1 hour"))];
        $tok = TokenEngine::makeToken($payload);
        http_response_code(200);
        echo $tok;
        die;
    } else {
        http_response_code(403);
        die;
    }
} else {
    http_response_code(405);
}