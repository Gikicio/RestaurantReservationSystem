<?php
require_once('auth.php');
require_once('db_connection.php');

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $sql = "UPDATE reservations SET table_id = ?, name = ?, email = ?, date = ?, time = ? WHERE id = ?";
    if($stmt = mysqli_prepare($connection, $sql)){
        mysqli_stmt_bind_param($stmt, "sssssi", $_POST['table_id'], $_POST['name'], $_POST['email'], $_POST['date'], $_POST['time'], $_POST['id']);
        if(mysqli_stmt_execute($stmt)){
            header('Location: reservations.php');
            exit;
        } else{
            echo "Something went wrong. Please try again later.";
        }
    }
    mysqli_stmt_close($stmt);
} else {
    $sql = "SELECT * FROM reservations WHERE id = ?";
    if($stmt = mysqli_prepare($connection, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $_GET['id']);
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            if(mysqli_num_rows($result) == 1){
                $reservation = mysqli_fetch_assoc($result);
            } else{
                echo "No reservation found with that ID.";
                exit;
            }
        } else{
            echo "Something went wrong. Please try again later.";
        }
    }
    mysqli_stmt_close($stmt);
}

include('header.php');
?>

<h1>Edit Reservation</h1>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <input type="hidden" name="id" value="<?php echo $reservation['id']; ?>">
    <div>
        <label for="table_id">Table ID</label>
        <input type="text" id="table_id" name="table_id" value="<?php echo $reservation['table_id']; ?>" required>
    </div>
    <div>
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="<?php echo $reservation['name']; ?>" required>
    </div>
    <div>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?php echo $reservation['email']; ?>" required>
    </div>
    <div>
        <label for="date">Date</label>
        <input type="date" id="date" name="date" value="<?php echo $reservation['date']; ?>" required>
    </div>
    <div>
        <label for="time">Time</label>
        <input type="time" id="time" name="time" value="<?php echo $reservation['time']; ?>" required>
    </div>
    <div>
        <input type="submit" value="Update Reservation">
    </div>
</form>

<?php include('footer.php'); ?>
