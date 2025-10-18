<?php
require_once 'config/db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 🧱 Honeypot check
    if (!empty($_POST['website'])) {
        http_response_code(400);
        exit("Invalid submission detected.");
    }

    // Collect and sanitize inputs
    $firstname = trim($_POST['firstname']);
    $lastname  = trim($_POST['lastname']);
    $email     = trim($_POST['email']);
    $gender    = trim($_POST['gender']);
    $dob       = trim($_POST['dob']);
    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Generate a unique token for email verification
    $token = random_int(100000, 999999);

    // Insert into database with verification fields
    $sql = "INSERT INTO students (firstname, lastname, email, password, gender, dob, token, email_verified, active)
            VALUES ('$firstname', '$lastname', '$email', '$password', '$gender', '$dob', '$token', 0, 1)";

    if ($conn->query($sql) === TRUE) {
        // Prepare verification email
        $verify_link = "https://dawitedunooklms.eagletechafrica.com/verify_email.php?token=" . $token;
        $subject = "Verify Your Email - Dawit LMS";
        $message = "Hi $firstname,\n\nPlease verify your account using this code: $token\n\nOr click the link below:\n$verify_link\n\nThank you,\nDawit LMS Team";

        // Send email
        @mail($email, $subject, $message);

        // Redirect user to verification instruction page
        echo "<script>alert('Registration successful! Please check your email to verify your account.');
        window.location='verify_email.php';</script>";
        exit;
    } else {
        echo "Error: " . $conn->error;
    }

} else {
    // If not POST request
    header("Location: index.html");
    exit;
}
?>
