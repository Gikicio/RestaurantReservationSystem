<?php
require_once('auth.php');

$username = $password = $confirm_password = $first_name = $last_name = $email = "";
$username_err = $password_err = $confirm_password_err = $first_name_err = $last_name_err = $email_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else {
        $username = trim($_POST["username"]);
    }

    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }
    
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    if(empty(trim($_POST["first_name"]))){
        $first_name_err = "Please enter a first name.";
    } else {
        $first_name = trim($_POST["first_name"]);
    }
    
    if(empty(trim($_POST["last_name"]))){
        $last_name_err = "Please enter a last name.";
    } else {
        $last_name = trim($_POST["last_name"]);
    }

    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter an email.";
    } else {
        $email = trim($_POST["email"]);
    }
    
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($first_name_err) && empty($last_name_err) && empty($email_err)){
        if(register($username, $password, $first_name, $last_name, $email)) {
            header("location: login.php");
        } else {
            echo "Something went wrong. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="path_to_your_css_file.css">
</head>
<body>
<div class="form-container">
    <h2>Register</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div>
            <label>Username</label>
            <input type="text" name="username">
            <span><?php echo $username_err; ?></span>
        </div>
        <div>
            <label>First Name</label>
            <input type="text" name="first_name">
            <span><?php echo $first_name_err; ?></span>
        </div>    
        <div>
            <label>Last Name</label>
            <input type="text" name="last_name">
            <span><?php echo $last_name_err; ?></span>
        </div>
        <div>
            <label>Email</label>
            <input type="text" name="email">
            <span><?php echo $email_err; ?></span>
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password">
            <span><?php echo $password_err; ?></span>
        </div>
        <div>
            <label>Confirm Password</label>
            <input type="password" name="confirm_password">
            <span><?php echo $confirm_password_err; ?></span>
        </div>
        <div>
            <input class="form-submit" type="submit" value="Register">
        </div>
    </form>
</div>
</body>
</html>

