<?php
require_once('auth.php');
require_once('db_connection.php');

if(!isset($_GET['id'])){
    echo "No reservation ID provided.";
    exit;
}

$sql = "DELETE FROM reservations WHERE id = ?";
if($stmt = mysqli_prepare($connection, $sql)){
    mysqli_stmt_bind_param($stmt, "i", $_GET['id']);
    if(mysqli_stmt_execute($stmt)){
        header('Location: reservations.php');
        exit;
    } else{
        echo "Something went wrong. Please try again later.";
    }
}
mysqli_stmt_close($stmt);
mysqli_close($connection);
?>
