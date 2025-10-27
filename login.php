<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config/db_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.html');
    exit;
}

$email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$password = trim($_POST['password'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Invalid email'); window.location='login.html';</script>";
    exit;
}

// ✅ Adjusted query to handle role_name safely
$sql = "SELECT u.user_id, u.full_name, u.email, u.password, u.email_verified, u.status,
               u.role_id, r.role_name
        FROM users u
        LEFT JOIN roles r ON u.role_id = r.role_id
        WHERE u.email = ? LIMIT 1";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Database error: " . $conn->error);
}

$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // ✅ Email verification check
    if ((int)$user['email_verified'] === 0) {
        echo "<script>alert('Please verify your email before logging in.'); window.location='verify_email.php';</script>";
        exit;
    }

    // ✅ Account status check
    if ($user['status'] !== 'active') {
        echo "<script>alert('Account suspended or inactive. Contact admin.'); window.location='login.html';</script>";
        exit;
    }

    // ✅ Verify password
    if (password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['loggedin']   = true;
        $_SESSION['user_id']    = (int)$user['user_id'];
        $_SESSION['full_name']  = htmlspecialchars($user['full_name'], ENT_QUOTES, 'UTF-8');
        $_SESSION['email']      = $user['email'];
        $_SESSION['role_id']    = (int)$user['role_id'];

        // If role_name is missing, default to student
        $_SESSION['role_name']  = strtolower($user['role_name'] ?? 'student');

        // Extract first name
        $parts = preg_split('/\s+/', trim($_SESSION['full_name']));
        $_SESSION['firstname'] = htmlspecialchars($parts[0] ?? 'Student', ENT_QUOTES, 'UTF-8');

        // ✅ Debug log (you can check this via file or browser console)
        // error_log("LOGIN ROLE: " . $_SESSION['role_name'] . " (ID: " . $_SESSION['role_id'] . ")");

        // ✅ Redirect based on role_name
        $role = $_SESSION['role_name'];
        if ($role === 'admin' || $role === 'super_admin') {
            header('Location: admin-dashboard.php');
            exit;
        } else {
            header('Location: dashboard.php');
            exit;
        }

    } else {
        echo "<script>alert('Incorrect password.'); window.location='login.html';</script>";
        exit;
    }
} else {
    echo "<script>alert('No account found with that email.'); window.location='login.html';</script>";
    exit;
}

$stmt->close();
$conn->close();
?>
