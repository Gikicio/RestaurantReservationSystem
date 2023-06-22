<?php
require_once('auth.php');
session_start();

if(isset($_GET['reservation_id'])){
    require_once('db_connection.php');
    $reservation_id = $_GET['reservation_id'];
    $sql = "DELETE FROM reservations WHERE id = ? AND user_id = ?";
    if($stmt = mysqli_prepare($connection, $sql)){
        mysqli_stmt_bind_param($stmt, "ii", $reservation_id, $_SESSION['id']);
        if(mysqli_stmt_execute($stmt)){
            header("location: reservations.php");
            exit;
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($connection);
} else {
    header("location: reservations.php");
    exit;
}
?>
