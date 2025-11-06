<?php
// payment/paypal_process.php
session_start();
require_once __DIR__ '/config/db_config.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../login.html');
    exit;
}

// Ensure POST values
$course_id   = isset($_POST['course_id']) ? (int)$_POST['course_id'] : 0;
$course_name = $_POST['course_name'] ?? 'Course';
$amount      = $_POST['amount'] ?? '0.00';
$user_id     = (int)($_SESSION['user_id'] ?? 0);

// Basic server-side validation
if ($course_id <= 0 || $amount <= 0 || $user_id <= 0) {
    echo "Invalid payment request.";
    exit;
}

/**
 * PAYPAL SANDBOX SETTINGS
 * Replace the placeholders below with your sandbox business email.
 * You provided API credentials earlier; do NOT leave them here.
 */
$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
$paypal_business_email = "zuzu72424@gmail.com"; // e.g. sb-xxxxxx@business.example.com

// Return URLs (adjust domain to your live testing URL)
$base = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
$return_url = $base . dirname($_SERVER['REQUEST_URI']) . "/success.php";
$cancel_url = $base . dirname($_SERVER['REQUEST_URI']) . "/cancel.php";

// Insert a pending payments record first (optional but useful to track)
$stmt = $conn->prepare("INSERT INTO payments (student_id, course_id, amount, payment_status, paypal_email) VALUES (?, ?, ?, 'pending', ?)");
$temp_paypal_email = NULL;
$stmt->bind_param("iids", $user_id, $course_id, $amount, $temp_paypal_email); // paypal_email left NULL for now
$stmt->execute();
$payment_insert_id = $stmt->insert_id;
$stmt->close();

// Build an auto-submitted HTML form that posts to PayPal Sandbox
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Redirecting to PayPal...</title></head>
<body>
  <p>Redirecting to PayPal Sandbox — please wait...</p>
  <form id="paypalForm" action="<?php echo htmlspecialchars($paypal_url); ?>" method="post">
    <input type="hidden" name="business" value="<?php echo htmlspecialchars($paypal_business_email); ?>">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="item_name" value="<?php echo htmlspecialchars($course_name); ?>">
    <input type="hidden" name="item_number" value="<?php echo htmlspecialchars($payment_insert_id); ?>"> <!-- local payment id -->
    <input type="hidden" name="amount" value="<?php echo htmlspecialchars($amount); ?>">
    <input type="hidden" name="currency_code" value="USD">

    <!-- Custom: pass our local payment id so we can map later -->
    <input type="hidden" name="custom" value="<?php echo htmlspecialchars($payment_insert_id); ?>">

    <!-- Return/cancel -->
    <input type="hidden" name="return" value="<?php echo htmlspecialchars($return_url); ?>">
    <input type="hidden" name="cancel_return" value="<?php echo htmlspecialchars($cancel_url); ?>">
  </form>
  <script>document.getElementById('paypalForm').submit();</script>
</body>
</html>
