<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $to = "travelmate.contactt@gmail.com"; // Receiver email
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $message = htmlspecialchars($_POST["message"]);

    $subject = "New Contact Message from $name";
    $body = "Name: $name\nEmail: $email\nMessage:\n$message";
    $headers = "From: $email\r\nReply-To: $email\r\n";

    if (mail($to, $subject, $body, $headers)) {
        // ✅ Success — Redirect with success flag
        header("Location: contact.html?success=1");
        exit();
    } else {
        // ❌ Failure — Redirect with error flag
        header("Location: contact.html?error=1");
        exit();
    }
}
?>
