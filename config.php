<?php
$host = "localhost";
$username = "root"; // use your MySQL username
$password = "";     // use your MySQL password
$dbname = "travelmate";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
