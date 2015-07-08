<?php

    require("common.php");

    $errors = array();
    $success_message = "";
    
    if(isset($_SESSION["user"])){
        echo $_SESSION["user"];
    }
    
    //If the user clicks the log in button, the login function will execute
    if(isset($_POST["login"])){
        login();
    }
    
    //If the user clicks the register button, the register function will execute
    if(isset($_POST["register"])){
        register();
    }
    
    function login(){        

        if(attemptLogin($_POST["loginEmail"], $_POST["loginPassword"])){
            $_SESSION["user"] = $_POST["loginEmail"];
            $GLOBALS["conn"]->close();
            header("Location: home");
        } else{
            $GLOBALS["errors"]["loginError"] = "Login failed.";
        }
        
    }
    
    function register(){

        if(empty($_POST["first_name"]) || empty($_POST["last_name"])){
            $GLOBALS["errors"]["noName"] = "You need to enter your first and last name";
        } if(empty($_POST["country"])){
            $GLOBALS["errors"]["noCountry"] = "You need to enter the country that you live in";
        } if(empty($_POST["city"])){
            $GLOBALS["errors"]["noCity"] = "You need to enter the city that you live in";
        } if(empty($_POST["password"])){
            $GLOBALS["errors"]["noPassword"] = "You need to enter a password";
        } if($_POST["password"] !== $_POST["password2"]){
            $GLOBALS["errors"]["passwordMatch"] = "Passwords don't match";
        } if(empty($_POST["email"])){
            $GLOBALS["errors"]["noEmail"] = "You need to enter your email address";
        } if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $GLOBALS["errors"]["notValidEmail"] = "You need to enter a valid email address";
        } if(empty($_POST["phone"])){
            $GLOBALS["errors"]["noPhone"] = "You need to enter your phone number";
        }

        //Checking if the email has already registered
        if(exists("email", $_POST["email"])){
            $GLOBALS["errors"]["emailRegistered"] = "A user with that email has already registered";
        }
        
        if(empty($GLOBALS["errors"])){

            //I shouldn't use this hash function, but I will for now. I should really use password_hash()
            $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
            $country = $_POST["country"];
            $city = $_POST["city"];
            $full_name = $_POST["first_name"] . " " . $_POST["last_name"];
            $email = $_POST["email"];
            $phone = $_POST["phone"];
            
            $query = "INSERT INTO users (country, city, password, full_name, email, phone) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $GLOBALS["conn"]->prepare($query);
            if(!$stmt){
                echo "Error: ". $GLOBALS["conn"]->error;
            }
            $stmt->bind_param("sssssi", $country, $city, $password, $full_name, $email, $phone);
            $stmt->execute();
            
            $stmt->close();
        
            $GLOBALS["success_message"] = "Registration successful!";
        }
    }
    
    function exists($column, $value){

        $query = "SELECT * FROM users WHERE " . $column . " = ?";
        $stmt = $GLOBALS["conn"]->prepare($query);
        $stmt->bind_param("s", $value);
        $stmt->execute();
        $stmt->store_result();
        $count = $stmt->num_rows;

        return ($count > 0) ? true : false;
    }
    
    function attemptLogin($email, $password){

        $query = "SELECT password, id FROM users WHERE email = ?";
        $stmt = $GLOBALS["conn"]->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $row = $result->fetch_array(MYSQLI_ASSOC);
        
        if(password_verify($password, $row["password"])){
            $_SESSION["user_id"] = $row["id"];
            return true;
        } else{
            return false;
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="/BloggMe/css/login.css">
        <title>BloggMe login</title>
    </head>
    <body>
        <header>
            <div id="logo">
                BloggMe
                <img src="/BloggMe/icons/Blogg.png">
                Jag vet, dåligt namn...
            </div>
        </header>
        <body>
            <img id="people" src="/BloggMe/icons/People.png">
            <div id="main">
                <form action="login" method="post">
                    <div id="signIn">
                        <h3>Log in to your account</h3>
                             
                        <label>Email</label>
                        <input type="text" name="loginEmail" placeholder="Email" size="50" value="<?php if(isset($_POST["loginEmail"])){ echo htmlentities($_POST["loginEmail"]); } ?>">
                             
                        <label>Password</label>
                        <input type="password" name="loginPassword" placeholder="Password" size="50" value="">
                        <br>
                        <input type="submit" name="login" value="Log in">
                        <span class="error"><?php if(isset($errors["loginError"])){ echo $errors["loginError"]; }?></span>
                    </div>
                </form>
                <form action="login" method="post">                                        
                    <div id="signUp">
                        <h3>Don't have an account? Sign up!</h3>
                             
                        <label>Name<span class="required">*</span></label> 
                        <input type="text" name="first_name" placeholder="First name" size="19" value="">
                        <input type="text" name="last_name" placeholder="Last name" size="25" value="">
                        <span class="error"><?php if(isset($errors["noName"])){ echo $errors["noName"]; } ?> &zwnj;</span> <!-- &zwnj; is a lazy solution for a stupid problem-->
                                                
                        <label>Country<span class="required">*</span></label>
                        <input type="text" name="country" placeholder="Country" size="50" value="">
                        <span class="error"></span>
                        
                        <label>City<span class="required">*</span></label>
                        <input type="text" name="city" placeholder="City" size="50" value="">
                        <span class="error"></span>
                            
                        <label>Email<span class="required">*</span></label>                        
                        <input type="text" name="email" placeholder="Email" size="50" value="">
                        <span class="error"><?php   if(isset($errors["noEmail"])){
                                                        echo $errors["noEmail"];
                                                    } else if(isset($errors["emailRegistered"])){
                                                        echo $errors["emailRegistered"];
                                                    }?></span>

                        <label>Password<span class="required">*</span></label>                     
                        <input type="password" name="password" placeholder="Password" size="50" value="">
                        <span class="error"><?php   if(isset($errors["noPassword"])){
                                                        echo $errors["noPassword"];
                                                    } else if(isset($errors["passwordMatch"])){
                                                        echo $errors["passwordMatch"];
                                                    }?> </span>
    
                        <label>Confirm password<span class="required">*</span></label>           
                        <input type="password" name="password2" placeholder="Confirm password" size="50" value="">

                        <label>Phone number<span class="required">*</span></label>                        
                        <input type="text" name="phone" placeholder="Phone number" size="50" value="">
                        <span class="error"><?php if(isset($errors["noPhone"])){ echo $errors["noPhone"]; } ?></span>
    
                        <br>
                        <input type="submit" name="register" value="Create your account">
                        <span id="success"><?php if($success_message !== ""){ echo $success_message; } ?></span>
                    </div>
                </form>
            </div>
        </body>
        <footer>
            <div class="wrapper">
                <p id="educational">
                    This site is made for educational purposes only.
                </p>
                <p id="creator">
                    This site is made by Otto Sellerstam
                </p>
            </div>
        </footer>
    </body>
</html>