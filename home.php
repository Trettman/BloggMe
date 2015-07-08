<?php

    require("common.php");
    
    $result;

    if(empty($_SESSION["user"])){
        header("Location: login");
        die("Redirecting to login page");
    }

    function getFriendIds(){
        
        $friend_ids = array();
        
        $query = "SELECT b FROM friends WHERE a = ?";
        $stmt = $GLOBALS["conn"]->prepare($query);
        if(!$stmt){
            echo $GLOBALS["conn"]->error;
        }
        $stmt->bind_param("i", $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_array(MYSQLI_ASSOC)){
            $id = $row["b"];
            array_push($friend_ids, $id);
        }
        
        return $friend_ids;
    }

    function listFriends(){
        
        foreach(getFriendIds() as $id){
            $query = "SELECT full_name, city, country, profile_pic FROM users WHERE id = ?";
            $stmt = $GLOBALS["conn"]->prepare($query);
            if(!$stmt){
                echo $GLOBALS["conn"]->error;
            }
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while($row = $result->fetch_array(MYSQLI_ASSOC)){
                $full_name = $row["full_name"];
                $city = $row["city"];
                $country = $row["country"];
                
                if($row["profile_pic"] != null){
                    $profile_pic_link = $row["profile_pic"];
                } ?>                
                
                <div class="friend">
                    <?php if($row["profile_pic"] == null){ ?>
                        <a href="profile?id=<?php echo htmlentities($id) ?>">
                            <img class="profilePic" src="/BloggMe/icons/Profile_pic.png">
                        </a>
                    <?php } else{ ?>
                        <a href="profile?id=<?php echo htmlentities($id) ?>">
                            <img class="profilePic" src="<?php echo htmlentities($profile_pic_link) ?>" >
                        </a>
                    <?php } ?>
                    <a class="profileLink" href="profile?id=<?php echo htmlentities($id) ?>"><?php echo htmlentities($full_name) ?></a><br>
                    <span class="city"><?php echo htmlentities($city . ", " . $country) ?></span>
                </div>    
      <?php }
        }        
    }

?>

<html>
    <?php
        require("header.php");
    ?>
        <div id="container">
            <div id="friends">
                <h3>Your friends</h2>
                <?php listFriends(); ?>
            </div>
        </div>
        <?php
            require("footer.php");
        ?>
    </body>
</html>