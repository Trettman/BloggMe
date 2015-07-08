<?php

    require("common.php");
    
    $me = $_POST["my_id"];
    $friend = $_POST["friend_id"];

    $query = "DELETE FROM friends WHERE a = ? AND b = ?";
    $stmt = $GLOBALS["conn"]->prepare($query);

    $stmt->bind_param("ii", $me, $friend);
    $stmt->execute();
    
    $stmt->close();
    
?>