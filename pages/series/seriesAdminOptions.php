<?php
@session_start();
if(isset($_SESSION["token"])){
    if(isset($_GET["id"])){
        include "editSeries.php";
        include "deleteSeries.php";
    } else {
        include "newSeries.php";
    }
}
?>