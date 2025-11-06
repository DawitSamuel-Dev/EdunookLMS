<?php
// admin_payments.php
session_start();
require_once 'config/db_config.php';

// ensure logged and admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.html');
    exit;
}
$role = strtolower($_SESSION['role_name'] ?? '');
if (!in_array($role, ['admin','super_admin'])) {
    echo "<script>alert('Access denied'); window.location='dashboard.php';</script>";
    exit;
}

// fetch payments + student + course (left join to be safe)
$sql = "SELECT p.*, u.full_name AS student_name, c.course_title AS course_title, c.course_name AS course_name
        FROM payments p
        LEFT JOIN users u ON p.student_id = u.user_id
        LEFT JOIN courses c ON p.course_id = c.course_id
        ORDER BY p.created_at DESC";
$res = $conn->query($sql);

?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin - Payments</title>
  <link rel="stylesheet" href="assets/css/dashboard.css">
  <style>
    .admin-wrap { max-width:1100px; margin:30px auto; }
    table { width:100%; border-collapse:collapse; background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 6px 18px rgba(0,0,0,0.06); }
    th, td { padding:12px 14px; border-bottom:1px solid #f3f4f6; text-align:left; }
    thead th { background:#1e3a8a; color:#fff; }
    .status-pill { padding:6px 10px; border-radius:999px; font-weight:600; color:#fff; display:inline-block; }
    .s-completed { background:#10b981; } .s-pending { background:#f59e0b; } .s-failed { background:#ef4444; }
  </style>
</head>
<body>
  <div class="dashboard-container">
    <!-- Reuse sidebar (admin may have separate layout; this is minimal) -->
    <aside class="sidebar">
      <div class="logo-section">
        <img src="assets/images/logo.png" class="sidebar-logo" alt="logo">
        <h2 class="brand-name">EduNook Admin</h2>
      </div>
      <nav class="nav-menu">
        <a href="admin-dashboard.php"><span>📊</span> Dashboard</a>
        <a href="admin_payments.php" class="active"><span>💳</span> Payments</a>
        <a href="logout.php" class="logout"><span>↩️</span> Logout</a>
      </nav>
    </aside>

    <main class="main-content">
      <div class="admin-wrap">
        <h1>Payments History</h1>
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Student</th>
              <th>Course</th>
              <th>Amount</th>
              <th>PayPal Email</th>
              <th>Txn ID</th>
              <th>Status</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($res && $res->num_rows): $i=1; while ($row = $res->fetch_assoc()): ?>
            <tr>
              <td><?php echo $i++; ?></td>
              <td><?php echo htmlspecialchars($row['student_name'] ?? 'Unknown'); ?></td>
              <td><?php echo htmlspecialchars($row['course_title'] ?? $row['course_name'] ?? 'N/A'); ?></td>
              <td>$<?php echo number_format((float)$row['amount'], 2); ?></td>
              <td><?php echo htmlspecialchars($row['paypal_email'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($row['transaction_id'] ?? ''); ?></td>
              <td>
                <?php
                  $status = $row['payment_status'] ?? 'pending';
                  $cls = $status === 'completed' ? 's-completed' : ($status === 'failed' ? 's-failed' : 's-pending');
                ?>
                <span class="status-pill <?php echo $cls; ?>"><?php echo ucfirst($status); ?></span>
              </td>
              <td><?php echo htmlspecialchars($row['created_at']); ?></td>
            </tr>
            <?php endwhile; else: ?>
            <tr><td colspan="8">No payments yet.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</body>
</html>
