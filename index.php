<?php
session_start();
require_once('auth.php');
require_once('db_connection.php');

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST['logout'])){
        logout();
        header('Location: index.php');
        exit;
    }
}

$sql = "SELECT * FROM tables";
$result = mysqli_query($connection, $sql);

if($result === false) {
    echo mysqli_error($connection);
} else {
    $tables = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

?>



<!DOCTYPE html>
<html>
<head>
    <title>Restaurant Reservation</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>

<?php include("header.php") ?>

<div id="floorplan">
    <?php foreach ($tables as $table): ?>
    <div class="table-container">
        <div class="table" 
            data-id="<?php echo $table['id']; ?>" 
            data-seats="<?php echo $table['seats']; ?>">
            Table <?php echo $table['id']; ?>
            <div class="table-schedule" style="display: none;">
            </div>
        </div>
        <div class="chairs">
            <?php for($i = 0; $i < $table['seats']; $i++): ?>
                <div class="chair"></div>
            <?php endfor; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div id="booking-container">

    <form id="booking-form">
        <label for="seatsInput">Number of Seats:</label>
        <input type="number" id="seatsInput" name="seats">
        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required>
        <input type="hidden" id="time" name="time">
        <input type="hidden" id="end_time" name="end_time">
        <input type="hidden" id="username" name="username" value="<?php echo $_SESSION['username']; ?>">
        <input type="hidden" id="email" name="email" value="<?php echo $_SESSION['email']; ?>">
        <input type="button" value="Check Availability" id="checkAvailabilityButton">
        <input type="button" value="Confirm Reservation" id="confirmReservationButton" style="display:none">
    </form>

    <div id="table-schedule-container">
        <div class="table-schedule"> </div>
    </div>
</div>

<script src="script.js"></script>

</body>
</html>
