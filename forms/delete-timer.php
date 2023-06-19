<?php
date_default_timezone_set('Europe/Amsterdam');

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../");
}

// retrieve the timer ID and comment from the POST request
$id = $_POST['id'];

// Connect to database
$servername = "";
$username = "";
$password = "";
$dbname = "";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// update the timers table with the end time and comment for the selected timer
$sql = "UPDATE time_report SET deleted=1 WHERE id=$id";
$result = mysqli_query($conn, $sql);

if ($result) {
    header("Location: ../");
} else {
    // an error occurred
    echo "Error stopping timer: " . mysqli_error($conn);
}
