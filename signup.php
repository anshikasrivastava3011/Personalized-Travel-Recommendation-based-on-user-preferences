<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = htmlspecialchars($_POST['name']);
    $email    = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('Email already registered!'); window.location.href='login.html';</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $conn->insert_id;

            $to = $email;
            $subject = "Welcome to TravelMate!";
            $message = "
Hi $name,

🎉 Thank you for signing up with TravelMate!

We're excited to help you explore personalized travel destinations based on your preferences.

Start your journey here:
http://localhost/index.html

Happy travels!
- The TravelMate Team
";
            $headers = "From: travelmate.contactt@gmail.com\r\n";

            mail($to, $subject, $message, $headers);

            // Redirect to profile
            header("Location: profile.php");
            exit();
        } else {
            echo "<script>alert('Signup failed! Please try again.'); window.location.href='login.html';</script>";
        }

        $stmt->close();
    }

    $check->close();
    $conn->close();
}
?>
