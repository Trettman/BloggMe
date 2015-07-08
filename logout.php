<?php
    
    require("common.php");
    
    unset($_SESSION["user"]);
    unset($_SESSION["id"]);
    unset($_SESSION["profile_picture"]);
    
    header("Location: login");
    die("Redirecting to login page");

?>