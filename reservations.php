<?php
require_once('auth.php');
require_once('db_connection.php');

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['user_type'] !== 'staff'){
    header("location: login.php");
    exit;
}

$sql = "SELECT * FROM reservations";
$result = mysqli_query($connection, $sql);
if($result === false) {
    echo mysqli_error($connection);
} else {
    $reservations = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$connection->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Restaurant Reservation</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<?php include('header.php'); ?>
<h2>Reservations:</h2>
<table class="reservations-table">
    <tr>
        <th>Table ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Date</th>
        <th>Time</th>
        <th>Action</th>
    </tr>
    <?php foreach ($reservations as $reservation): ?>
    <tr>
        <td><?php echo $reservation['table_id']; ?></td>
        <td><?php echo $reservation['name']; ?></td>
        <td><?php echo $reservation['email']; ?></td>
        <td><?php echo $reservation['date']; ?></td>
        <td><?php echo $reservation['time']; ?></td>
        <td>
            <a class="links" href="edit_reservation.php?id=<?php echo $reservation['id']; ?>">Edit</a>
            <a class="links" href="delete_reservation.php?id=<?php echo $reservation['id']; ?>">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
