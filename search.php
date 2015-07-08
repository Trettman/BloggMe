<?php

    require("common.php");

    $result;
    $search = false;
    
    if(empty($_SESSION["user"])){
        header("Location: login");
        die("Redirecting to login page");
    }
    
    //Collect
    if(!empty($_POST["search"])){
        search();
    } else{
        $GLOBALS["output"] = "<div id='noResults'><strong>Search failed.</strong><br>You need to enter a search query.</div>";
        $count = 1;
    }
    
    function search(){

        $searchq  = $_POST["search"];
        //$searchq = preg_replace("#[^0-9a-z]#i", "", $searchq);

        $query = "SELECT id, full_name, city, country, profile_pic FROM users WHERE full_name LIKE CONCAT('%', ?, '%')";
        $stmt = $GLOBALS["conn"]->prepare($query);
        if(!$stmt){
            echo $GLOBALS["conn"]->error;
        }
        $stmt->bind_param("s", $searchq);
        $stmt->execute();
        $GLOBALS["result"] = $stmt->get_result();
        
        $GLOBALS["count"] = $GLOBALS["result"]->num_rows;
        
        if($GLOBALS["count"] !== 0){
            $GLOBALS["search"] = true;           
        }
    }
    
    function listResults(){
        
        if($GLOBALS["search"]){ ?>
            <div id="container">
                <?php if(isset($GLOBALS["result"])){
                     while($row = $GLOBALS["result"]->fetch_array(MYSQLI_ASSOC)){ 
                        $id = $row["id"];
                        $full_name = $row["full_name"];
                        $city = $row["city"];
                        $country = $row["country"];
                                    
                        if($row["profile_pic"] != null){
                            $profile_pic_link = $row["profile_pic"];
                        } ?>
                                    
                        <div class="result">
                            <?php if($row["profile_pic"] == null){ ?>
                                <a href="profile?id=<?php echo htmlentities($id) ?>">
                                    <img class="profilePic" src="/BloggMe/icons/Profile_pic.png">
                                </a>
                            <?php } else{ ?>
                                <a href="profile?id=<?php echo htmlentities($id) ?>">
                                    <img class="profilePic" src="<?php echo htmlentities($profile_pic_link) ?>">
                                </a>
                            <?php } ?>
                            <a class="profileLink" href="profile?id=<?php echo htmlentities($id) ?>"><?php echo htmlentities($full_name) ?></a><br>
                            <span class="city"><?php echo htmlentities($city . ", " . $country) ?></span>
                        </div>
                    <?php } 
                } ?>
            </div>
        <?php } else{ ?>
            <div id="noResults">
                <strong>Search failed.</strong><br>
                You need to enter a search query.
            </div>
        <?php }
    }

?>

<!DOCTYPE html>
<html>
    <?php
        require("header.php");
    ?>
        <!-- This shows all the search results -->
        <?php
            if(isset($count)){
                if($count == 0){ ?>
                    <div id="noResults">
                        <strong>The search didn't yield any results.</strong><br>
                        Check your spelling or try another search word.
                    </div>
          <?php } else{
                    listResults();
                }
            }
            require("footer.php");
        ?>
    </body>
</html>