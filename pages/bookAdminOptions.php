<?php
    @session_start();
    if(isset($_SESSION["token"])){
        if(isset($_GET["id"])){
            include "editBook.php";
            include "deleteBook.php";
        } else {
            include "newBook.php";
        }
    }
?>