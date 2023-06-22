<?php
require_once('auth.php');

session_start();


$username = $password = "";
$username_err = $password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }

    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    if(empty($username_err) && empty($password_err)){
        if(login($username, $password)) {
            header("location: index.php");
        } else {
            echo "Invalid username or password.";
        }
    }
}
?>

<?php include("header.php") ?>

<body>
    <div id="booking-container">
        <h2>Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div>
                <label for="username">Username</label>
                <input type="text" name="username" id="seatsInput">
                <span><?php echo $username_err; ?></span>
            </div>    
            <div>
                <label for="password">Password</label>
                <input type="password" name="password" id="seatsInput">
                <span><?php echo $password_err; ?></span>
            </div>
            <div>
                <input type="submit" value="Login" id="checkAvailabilityButton">
            </div>
        </form>
    </div>
</body>
</html>
