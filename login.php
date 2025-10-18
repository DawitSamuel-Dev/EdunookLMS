<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once 'config/db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT sid, firstname, password, email_verified, active FROM students WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Check if email verified
        if ($row['email_verified'] == 0) {
            echo "<script>alert('Please verify your email before logging in.'); window.location='verify_email.php';</script>";
            exit;
        }

        // Check if account is active
        if ($row['active'] == 0) {
            echo "<script>alert('Account suspended. Contact admin.'); window.location='login.html';</script>";
            exit;
        }

        // Verify password
        if (password_verify($password, $row['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['student_id'] = $row['sid'];
            $_SESSION['firstname'] = $row['firstname'];
            header("Location: dashboard.php");
            exit;
        } else {
            echo "<script>alert('Invalid password'); window.location='login.html';</script>";
        }
    } else {
        echo "<script>alert('Email not found'); window.location='login.html';</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Invalid request method'); window.location='login.html';</script>";
}
?>
