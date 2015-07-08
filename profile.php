<?php

    require("common.php");

    if(empty($_SESSION["user"])){
        header("Location: login");
        die("Redirecting to login page");
    }

    $name;
    $email;
    $country;
    $city;
    $phone;
    $profile_pic_path;
    $id = $_GET["id"];
    
    $query = "SELECT full_name, email, country, city, profile_pic, phone FROM users WHERE id = ?";
    $stmt = $GLOBALS["conn"]->prepare($query);
    
    if(!$stmt){
        echo $GLOBALS["conn"]->error;
    }
    
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
        
    $GLOBALS["count"] = $result->num_rows;

    if($GLOBALS["count"] !== 0){
        $row = $result->fetch_array(MYSQLI_ASSOC);
        
        $name = $row["full_name"];
        $email = $row["email"];
        $country = $row["country"];
        $city = $row["city"];
        $phone = $row["phone"];
        $profile_pic_path = $row["profile_pic"];
    }
    
?>

<html>
    <?php
        require("header.php");
    ?>
        <div id="container">
            <div id="topColor"></div>
            <?php if($profile_pic_path == null){ ?>
                <img id="profilePic" src="/BloggMe/icons/Profile_pic.png">
            <?php } else { ?>
                <img id="profilePic" src="<?php echo htmlentities($profile_pic_path); ?>">
            <?php } ?>
            
            <span id="name"><?php echo $name . "<br>"; ?></span>
            
            <?php if($_SESSION["user"] === $email){ ?>
                <a class="accountLink" href="eddit_account">Eddit account</a>
            <?php } ?>
            
            <form method="post" id="form-friend">
                <input type="hidden" name="my_id" value="<?php echo $_SESSION["user_id"] ?>">
                <input type="hidden" name="friend_id" value="<?php echo $id ?>">
                <?php if($_SESSION["user"] !== $email && !isFriend($_SESSION["user_id"], $id)){ ?>
                    <input id="addFriend" class="accountLink" type="submit" name="addFriend" value="Add Friend">
                <?php } elseif($_SESSION["user"] !== $email && isFriend($_SESSION["user_id"], $id)){ ?>
                    <input id="removeFriend" class="accountLink" type="submit" name="removeFriend" value="Remove Friend">
                <?php } ?>
            </form>
            
            <hr>
            
            <ul id="info">
                <li>
                    Lives in: <?php echo $city . ", " . $country; ?>
                </li>
                <li>
                    Email: <?php echo $email; ?>
                </li>
                <li>
                    Phone number: <?php echo $phone; ?>
                </li>
            </ul>
            
        </div>
        <?php
            require("footer.php");
        ?>
    </body>
</html>