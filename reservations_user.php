<?php
session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header("location: login.php");
    exit;
}

require_once('db_connection.php');

$email = $_SESSION['email'];
$sql = "SELECT * FROM reservations WHERE email = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $email);

if($stmt->execute()) {
    $result = $stmt->get_result();
    $reservations = $result->fetch_all(MYSQLI_ASSOC);
} else {
    echo "Error: " . $stmt->error;
}

$connection->close();
?>

<?php include('header.php'); ?>

<h2>Your Reservations:</h2>
<table class="reservations-table">
    <tr>
        <th>Table ID</th>
        <th>Date</th>
        <th>Time</th>
    </tr>
    <?php foreach ($reservations as $reservation): ?>
    <tr>
        <td><?php echo $reservation['table_id']; ?></td>
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
