<?php
include "api/jwt.php";
var_dump( TokenEngine::readToken(TokenEngine::makeToken(["text"=>"This is the token content"])));
?>