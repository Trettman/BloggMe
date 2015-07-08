<?php

    require("common.php");

    if(empty($_SESSION["user"])){
        header("Location: login");
        die("Redirecting to login page");
    }
    
    $profile_pic_path;
    
    if(isset($_POST["image_path"])){
        //Updates information
        $profile_pic_path = $_POST["image_path"];
        $_SESSION["profile_picture"] = $profile_pic_path;
        $id = $_SESSION["user_id"];
        
        $query =    "UPDATE users
                     SET profile_pic = ?
                     WHERE id = ?";
        $stmt = $GLOBALS["conn"]->prepare($query);
        if(!$stmt){
            echo "Error: ". $GLOBALS["conn"]->error;
        }
        $stmt->bind_param("si", $profile_pic_path, $id);
        $stmt->execute();
            
        $stmt->close();        
    }
    
    $query = "SELECT profile_pic FROM users WHERE id = ?";
    $stmt = $GLOBALS["conn"]->prepare($query);
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $row = $result->fetch_array(MYSQLI_ASSOC);
    
    $profile_pic_path = $row["profile_pic"];

?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="/css/main.css">
        <title>Eddit BloggMe account</title>
    </head>
    <body>
        <?php
            require("header.php");
        ?>
            <div id="container">
                <h2>Edit your information and settings</h2>
                <div id="edit">                    
                    <form action="eddit_account" method="post">
                        <label class="edit">Change profile picture</label><br>
                        <input type="text" name="image_path" placeholder="Paste link to image" size="50">
                        
                        <label class="edit">Change email</label><br>
                        <input type="text" name="email" placeholder="Email" size="50">
                        
                        <label class="edit">Change password</label><br>
                        <input type="password" name="password" placeholder="Password" size="50">
                        
                        <label class="edit">Confirm password</label><br>
                        <input type="password" name="password2" placeholder="Password" size="50">
                        
                        <label class="edit">Change city</label><br>
                        <input type="text" name="city" placeholder="City" size="50">
                        
                        <label class="edit">Change country</label><br>
                        <input type="text" name="country" placeholder="Country" size="50">
                        
                        <label class="edit">Change phone number</label><br>
                        <input type="text" name="phone" placeholder="Phone number" size="50">
                        <br>
                        <input type="submit" value="Edit" name="submit">
                    </form>
                </div>
                <div id="current">
                    <h3>Your current profile picture:</h3>
                    <?php if(isset($profile_pic_path)){ ?>
                        <img id="pic" src="<?php echo htmlentities($profile_pic_path); ?>"
                    <?php } else{ ?>
                        <img id="pic" src="/icons/Profile_pic.png">
                    <?php } ?>
                </div>
            </div>
        <?php
           require("footer.php");
        ?>
    </body>
</html>