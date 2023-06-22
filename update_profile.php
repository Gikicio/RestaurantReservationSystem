<?php
require_once('auth.php');
session_start();

$first_name = $last_name = $email = $password = "";
$first_name_err = $last_name_err = $email_err = $password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if(empty($first_name)){
        $first_name_err = "Please enter your first name.";
    }

    if(empty($last_name)){
        $last_name_err = "Please enter your last name.";
    }

    if(empty($email)){
        $email_err = "Please enter your email.";
    }

    if(empty($first_name_err) && empty($last_name_err) && empty($email_err)){
        $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?";

        $params = [$first_name, $last_name, $email];

        if (!empty($password)) {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $sql .= ", password = ?";
            $params[] = $password;
        }

        $sql .= " WHERE id = ?";
        $params[] = $_SESSION['id'];

        if($stmt = mysqli_prepare($connection, $sql)){
            mysqli_stmt_bind_param($stmt, str_repeat('s', count($params)), ...$params);
            if(mysqli_stmt_execute($stmt)){
                $_SESSION['first_name'] = $first_name;
                $_SESSION['last_name'] = $last_name;
                $_SESSION['email'] = $email;

                header("location: profile.php");
                exit;
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($connection);
}
?>
