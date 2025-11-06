<?php
// payment/success.php
session_start();
require_once __DIR__ . '/../config/db_config.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../login.html');
    exit;
}

// PayPal returns various GET params — we expect 'custom' for our local payment id
$local_payment_id = isset($_GET['custom']) ? (int)$_GET['custom'] : 0;
$txn_id = $_GET['tx'] ?? null;      // may exist if PDT enabled
$status = $_GET['st'] ?? 'Completed'; // sometimes returned, default to Completed in sandbox
$amount = $_GET['amt'] ?? null;
$payer_email = $_GET['payer_email'] ?? null;

if ($local_payment_id <= 0) {
    echo "<h2>Thank you — but we couldn't map your payment. Please contact support.</h2>";
    exit;
}

// Update our payments table: set transaction_id, paypal_email, payment_status
$stmt = $conn->prepare("UPDATE payments SET transaction_id = ?, paypal_email = ?, payment_status = 'completed' WHERE id = ?");
$stmt->bind_param("ssi", $txn_id, $payer_email, $local_payment_id);
$stmt->execute();
$stmt->close();

?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Payment Successful</title></head>
<body>
  <div style="max-width:800px;margin:40px auto;font-family:Inter, sans-serif">
    <h1>Payment Successful ✅</h1>
    <p>Thank you. Your payment was recorded. Transaction ID: <?php echo htmlspecialchars($txn_id ?? 'N/A'); ?></p>
    <p><a href="../dashboard.php">Return to Dashboard</a></p>
  </div>
</body>
</html>
