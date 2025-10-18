<?php
$servername = "localhost";
$username = "u376937047_Adminedubook"; // XAMPP default user
$password = "J0988937599_a"; // leave empty unless you manually set one
$dbname = "u376937047_dawitsamuel_lms"; // your real database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
