<?php
session_start();

require_once('db_connection.php');


function register($username, $password, $first_name, $last_name, $email) {
  global $connection;

  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  $created_at = date('Y-m-d H:i:s');

  $sql = "INSERT INTO users (username, password, first_name, last_name, email, user_type, created_at) 
          VALUES (?, ?, ?, ?, ?, ?, ?)";

  if($stmt = mysqli_prepare($connection, $sql)){
      mysqli_stmt_bind_param($stmt, "sssssss", $param_username, $param_password, $param_first_name, 
              $param_last_name, $param_email, $param_user_type, $param_created_at); 
      
      $param_username = $username;
      $param_password = $hashed_password;
      $param_first_name = $first_name;
      $param_last_name = $last_name;
      $param_email = $email;
      $param_user_type = 'staff';
      $param_created_at = $created_at;
      
      if(mysqli_stmt_execute($stmt)){
          mysqli_stmt_close($stmt);
          return true;
      } else{
          mysqli_stmt_close($stmt);
          return false;
      }
  }
}

function login($username, $password) {
  global $connection;

    $sql = "SELECT * FROM users WHERE username = ?";
    if($stmt = mysqli_prepare($connection, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        if(mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if(mysqli_num_rows($result) == 1) {
                $user = mysqli_fetch_assoc($result);
                if(password_verify($password, $user['password'])) {
                    session_regenerate_id();
                    $_SESSION['loggedin'] = true;
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['username'] = $username;
                    $_SESSION['user_type'] = $user['user_type'];
                    $_SESSION['first_name'] = $user['first_name'];
                    $_SESSION['last_name'] = $user['last_name'];
                    $_SESSION['email'] = $user['email'];

                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($connection);
}

function logout() {
    session_start();
    $_SESSION = array();
    session_destroy();
}
?>
