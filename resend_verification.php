<?php
require_once 'config/db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $newToken = random_int(100000, 999999);

    $sql = "UPDATE students SET token='$newToken' WHERE email='$email'";
    if ($conn->query($sql)) {
        $link = "https://dawitedunooklms.eagletechafrica.com/verify_email.php?token=$newToken";
        $subject = "Resend Verification - Dawit LMS";
        $message = "Hi, here is your new verification code: $newToken\n\nClick below to verify:\n$link";

        @mail($email, $subject, $message);

        echo "<script>alert('Verification link resent successfully. Check your inbox.');
        window.location='verify_email.php';</script>";
    } else {
        echo "<script>alert('Email not found. Please register again.');
        window.location='register.html';</script>";
    }
}
?>
