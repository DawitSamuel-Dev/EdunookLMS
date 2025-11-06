<?php
// payment.php
session_start();
require_once 'config/db_config.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.html');
    exit;
}

// only students should use this page
$role = strtolower($_SESSION['role_name'] ?? 'student');
if ($role !== 'student') {
    header('Location: admin-dashboard.php');
    exit;
}

// fetch courses (adapt column names if your courses table differs)
$courses = [];
$res = $conn->query("SELECT course_id, COALESCE(course_title, course_name, title) AS title, COALESCE(price, fee, 0.00) AS price FROM courses");
if ($res) {
    while ($r = $res->fetch_assoc()) {
        $courses[] = $r;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Payments | EduNook</title>
  <!-- Use same CSS as dashboard. If path differs on your host, adjust accordingly. -->
  <link rel="stylesheet" href="assets/css/dashboard.css">
  <style>
    .payments-wrap { width: 100%; max-width: 980px; margin: 20px auto; }
    .course-card { background:white; padding:18px; border-radius:10px; box-shadow:0 6px 18px rgba(0,0,0,0.06); margin-bottom:14px; display:flex; justify-content:space-between; align-items:center; }
    .course-info { display:flex; flex-direction:column; }
    .course-title { font-weight:700; color:#0f172a; font-size:1.05rem; }
    .course-price { color:#2563eb; font-weight:600; margin-top:6px; }
    .pay-btn { background:#ffc439; color:#111; border:none; padding:10px 14px; border-radius:8px; font-weight:600; cursor:pointer; }
    .note { font-size:0.9rem; color:#6b7280; margin-top:10px; }
  </style>
</head>
<body>
  <div class="dashboard-container">
    <?php
    // reuse the sidebar markup from dashboard.php (so page layout matches)
    // For simplicity we include the same sidebar HTML (you can refactor later).
    ?>
    <aside class="sidebar">
      <div class="logo-section">
        <img src="assets/images/logo.png" alt="Logo" class="sidebar-logo">
        <h2 class="brand-name">EduNook</h2>
      </div>
      <nav class="nav-menu">
        <a href="dashboard.php"><span>🏠</span> Dashboard</a>
        <a href="payment.php" class="active"><span>💳</span> Payments</a>
        <a href="logout.php" class="logout"><span>↩️</span> Logout</a>
      </nav>
    </aside>

    <main class="main-content">
      <div class="payments-wrap">
        <h1>Course Payments</h1>
        <p class="note">This is PayPal Sandbox (test) integration. Use sandbox buyer account to complete a test purchase.</p>

        <?php if (count($courses) === 0): ?>
          <div class="course-card"><div>No courses found. Please contact admin.</div></div>
        <?php else: foreach ($courses as $c): ?>
          <div class="course-card">
            <div class="course-info">
              <div class="course-title"><?php echo htmlspecialchars($c['title']); ?></div>
              <div class="course-price">$<?php echo number_format((float)$c['price'], 2); ?></div>
            </div>

            <form action="payment/paypal_process.php" method="POST">
              <input type="hidden" name="course_id" value="<?php echo (int)$c['course_id']; ?>">
              <input type="hidden" name="course_name" value="<?php echo htmlspecialchars($c['title']); ?>">
              <input type="hidden" name="amount" value="<?php echo number_format((float)$c['price'], 2, '.', ''); ?>">
              <button class="pay-btn" type="submit">Pay with PayPal (Sandbox)</button>
            </form>
          </div>
        <?php endforeach; endif; ?>

      </div>
    </main>
  </div>
</body>
</html>
