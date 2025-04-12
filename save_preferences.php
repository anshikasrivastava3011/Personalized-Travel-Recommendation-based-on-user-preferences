<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $activity  = $_POST['activity'];
    $budget    = $_POST['budget'];
    $location  = $_POST['location'];
    $duration  = $_POST['duration'];
    $climate   = $_POST['climate'];
    $visa_free = $_POST['visa'];  // form field is still named 'visa'
    $user_id   = $_SESSION['user_id'];

    $stmt = $conn->prepare("UPDATE users SET activity=?, budget=?, location=?, duration=?, climate=?, visa_free=? WHERE id=?");
    $stmt->bind_param("ssssssi", $activity, $budget, $location, $duration, $climate, $visa_free, $user_id);

    if ($stmt->execute()) {
        header("Location: profile.php"); // Redirect to profile page
        exit();
    } else {
        echo "Failed to update preferences.";
    }

    $stmt->close();
    $conn->close();
}
?>
