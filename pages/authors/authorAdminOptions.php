<?php
@session_start();
if(isset($_SESSION["token"])){
    if(isset($_GET["id"])){
        include "editAuthor.php";
        include "deleteAuthor.php";
    } else {
        include "newAuthor.php";
    }
}
?>