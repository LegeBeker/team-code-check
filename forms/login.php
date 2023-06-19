<?php
date_default_timezone_set('Europe/Amsterdam');

$servername = "";
$username = "";
$password = "";
$dbname = "";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'];
$userpass = $_POST['password'];

// combat sql injection
$username = mysqli_real_escape_string($conn, $username);
$userpass = mysqli_real_escape_string($conn, $userpass);

// sha512 hash password with salt
$userpass = hash('sha512', $userpass . 'sagrada');

$query = "SELECT * FROM users WHERE username = '$username' AND password = '$userpass'";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    session_start();

    // set last login in db
    $query = "UPDATE users SET last_login = NOW() WHERE username = '$username'";

    $conn->query($query);

    $_SESSION['username'] = strtolower($username);
    header("Location: ../");
} else {
    header("Location: ../login-page.php?login-error=1");
}
