<?php
$host = "localhost";
$user = "moviehub_user";
$pass = "Moviehub@123";
$db   = "moviehub_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
