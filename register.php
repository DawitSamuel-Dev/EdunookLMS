<?php
require_once 'config/db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 🧱 Honeypot check
    if (!empty($_POST['website'])) {
        http_response_code(400);
        exit("Invalid submission detected.");
    }

    // 🧹 Sanitize & validate inputs
    $firstname = htmlspecialchars(trim($_POST['firstname']), ENT_QUOTES, 'UTF-8');
    $lastname  = htmlspecialchars(trim($_POST['lastname']), ENT_QUOTES, 'UTF-8');
    $email     = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $gender    = htmlspecialchars(trim($_POST['gender']), ENT_QUOTES, 'UTF-8');
    $dob       = htmlspecialchars(trim($_POST['dob']), ENT_QUOTES, 'UTF-8');
    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // ✅ Server-side email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format'); window.location='register.html';</script>";
        exit;
    }

    // Generate a unique token for email verification
    $token = random_int(100000, 999999);

    // Insert into database with verification fields
    $sql = "INSERT INTO students (firstname, lastname, email, password, gender, dob, token, email_verified, active)
            VALUES (?, ?, ?, ?, ?, ?, ?, 0, 1)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $firstname, $lastname, $email, $password, $gender, $dob, $token);

    if ($stmt->execute()) {
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
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

} else {
    // If not POST request
    header("Location: index.html");
    exit;
}
?>
