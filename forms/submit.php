<?php
date_default_timezone_set('Europe/Amsterdam');

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../");
}

// Retrieve form data
$person = $_POST['person'];
$type = $_POST['type'];
$start_time = $_POST['start_time'];
$end_time = $_POST['end_time'];
$branch = $_POST['branch'];
$comment = $_POST['comment'];

// Connect to database
$servername = "";
$username = "";
$password = "";
$dbname = "";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert data into table
if ($end_time == '') {
    $sql = "INSERT INTO time_report (person, type, start, branch, comment) VALUES ('$person', '$type', '$start_time', '$branch', '$comment')";
} else {
    $sql = "INSERT INTO time_report (person, type, start, end, branch, comment) VALUES ('$person', '$type', '$start_time', '$end_time', '$branch', '$comment')";
}
if ($conn->query($sql) === TRUE) {
    header("Location: ../");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
