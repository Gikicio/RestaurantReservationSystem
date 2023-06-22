<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$database = "Restaurant";

$connection = mysqli_connect($servername, $username, $password, $database);
if (mysqli_connect_error()) {
    echo mysqli_connect_error();
    exit();
}
?>
