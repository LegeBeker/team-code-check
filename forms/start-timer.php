<?php
date_default_timezone_set('Europe/Amsterdam');

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../");
}

$person = $_POST['person'];
$type = $_POST['type'];
$branch = $_POST['branch'];

// Connect to database
$servername = "";
$username = "";
$password = "";
$dbname = "";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$startTime = date('Y-m-d H:i:s');
$sql = "INSERT INTO time_report (person, type, branch, start) VALUES ('$person', '$type', '$branch', '$startTime')";
$result = mysqli_query($conn, $sql);

if ($result) {
    header("Location: ..//");
} else {
    // an error occurred
    echo "Error stopping timer: " . mysqli_error($conn);
}
