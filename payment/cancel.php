<?php
// payment/cancel.php
session_start();
require_once __DIR__ . '/../config/db_config.php';

// If PayPal passes 'custom' we can mark pending or failed
$local_payment_id = isset($_GET['custom']) ? (int)$_GET['custom'] : 0;
if ($local_payment_id > 0) {
    $stmt = $conn->prepare("UPDATE payments SET payment_status = 'failed' WHERE id = ?");
    $stmt->bind_param("i", $local_payment_id);
    $stmt->execute();
    $stmt->close();
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Payment Cancelled</title></head>
<body>
  <div style="max-width:800px;margin:40px auto;font-family:Inter, sans-serif">
    <h1>Payment Cancelled</h1>
    <p>You cancelled the payment process. No charge was made.</p>
    <p><a href="../payment.php">Try Again</a> | <a href="../dashboard.php">Return to Dashboard</a></p>
  </div>
</body>
</html>
