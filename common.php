<?php

    $dbusername = "sellers_bloggme";
    $dbpassword = "[9w,Ks@]ENE_";
    $servername = "localhost";
    $dbname = "BloggMe";
    
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
            
    if($conn->connect_error){
        die("Connection to MySQL server failed: " . $conn->connect_error);
    }
            
    //Enables MySQL to work with characters such as å, ä or ö
    $conn->set_charset('utf8');
    
    /* This tells the browser that I'm working with UTF-8 and that it
       should submit information back to me using UTF-8 */
    header("Content-Type: text/html; charset=utf-8");
    
    //Checks if a session is already started before it attempts to start one
    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }
    
    function isFriend($me, $friend){
        $query = "SELECT * FROM friends WHERE a = ? AND b = ?";
        $stmt = $GLOBALS["conn"]->prepare($query);
        $stmt->bind_param("ii", $me, $friend);
        $stmt->execute();
        $stmt->store_result();
        $count = $stmt->num_rows;

        return ($count > 0) ? true : false;
    }

