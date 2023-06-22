<?php

require_once('db_connection.php');

$requestMethod = $_SERVER["REQUEST_METHOD"];

session_start();

switch($requestMethod) {
    case 'GET':
        if (isset($_GET['reservations'])) {
            if (!isset($_GET['table_id']) || !isset($_GET['date'])) {
                header('HTTP/1.0 400 Bad Request');
                echo json_encode(['error' => 'Bad request. All parameters must be set']);
                break;
            }

            $table_id = mysqli_escape_string($connection, $_GET['table_id']);
            $date = mysqli_escape_string($connection, $_GET['date']);

            $sql = "SELECT * FROM reservations WHERE table_id = ? AND date = ?";

            if($stmt = mysqli_prepare($connection, $sql)){
                mysqli_stmt_bind_param($stmt, "is", $table_id, $date);
                if(mysqli_stmt_execute($stmt)){
                    $result = mysqli_stmt_get_result($stmt);
                    $reservations = [];
                    while($reservation = mysqli_fetch_assoc($result)) {
                        $reservations[] = $reservation;
                    }
                    echo json_encode(['reservations' => $reservations]);
                } else {
                    echo json_encode(['error' => mysqli_error($connection)]);
                }
            }
            break;
        }


        // Check table availability
        if (!isset($_GET['seats'])) {
            header('HTTP/1.0 400 Bad Request');
            echo json_encode(['error' => 'Bad request. All parameters must be set']);
            break;
        }
    
        $seats = mysqli_escape_string($connection, $_GET['seats']);
    
        $sql = "SELECT tables.id, tables.seats
                FROM tables WHERE tables.seats >= ?";
    
        if($stmt = mysqli_prepare($connection, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $seats);
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
                $tables = [];
                while($table = mysqli_fetch_assoc($result)) {
                    $tables[] = $table;
                }
                echo json_encode(['tables' => $tables]);
            } else {
                echo json_encode(['error' => mysqli_error($connection)]);
            }
        }
        break;    

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            
            $tableId = $data['tableId'];
            $name = $data['name'];
            $email = $data['email'];
            $date = $data['date'];
            $time = $data['time'];
            $endTime = $data['end_time'];
            
            $sql = "INSERT INTO reservations (table_id, name, email, date, time, end_time) VALUES (?, ?, ?, ?, ?, ?)";

            if ($stmt = mysqli_prepare($connection, $sql)) {
                mysqli_stmt_bind_param($stmt, "isssss", $tableId, $name, $email, $date, $time, $endTime);
                if (mysqli_stmt_execute($stmt)) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'error' => mysqli_error($connection)]);
                }
            }            
}
?>

