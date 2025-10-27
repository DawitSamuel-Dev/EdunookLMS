<?php
session_start();
require_once __DIR__ . '/config/db_config.php';

// Ensure only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

// Fetch Admin Info
$adminName = $_SESSION['full_name'] ?? 'Admin';

// Fetch Dashboard Metrics
$totalUsers = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];
$verifiedUsers = $conn->query("SELECT COUNT(*) AS count FROM users WHERE email_verified = 1")->fetch_assoc()['count'];
$suspendedUsers = $conn->query("SELECT COUNT(*) AS count FROM users WHERE status = 'suspended'")->fetch_assoc()['count'];
$activeCourses = $conn->query("SELECT COUNT(*) AS count FROM courses")->fetch_assoc()['count'];
$studentCount = $conn->query("SELECT COUNT(*) AS count FROM users WHERE role = 'student'")->fetch_assoc()['count'];

// Recent users (last 5 signups)
$recentUsers = $conn->query("SELECT full_name, email, joined_at FROM users ORDER BY joined_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | EduNook LMS</title>
  <link rel="stylesheet" href="assets/css/admin-dashboard.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
  <div class="dashboard-container">
    <aside class="sidebar">
      <div class="logo">
        <img src="assets/images/logo.png" alt="Logo">
        <h2>EduNook Admin</h2>
      </div>
      <nav>
        <ul>
          <li><a href="admin-dashboard.php" class="active">📊 Dashboard</a></li>
          <li><a href="#">👥 Manage Students</a></li>
          <li><a href="#">📘 Courses</a></li>
          <li><a href="#">🧾 Assignments</a></li>
          <li><a href="#">🛠 Settings</a></li>
          <li><a href="logout.php">🚪 Logout</a></li>
        </ul>
      </nav>
    </aside>

    <main class="main-content">
      <header>
        <h1>Welcome back, <?php echo htmlspecialchars($adminName); ?> 👋</h1>
        <p>System overview and statistics</p>
      </header>

      <section class="stats-grid">
        <div class="card">
          <h3><?php echo $totalUsers; ?></h3>
          <p>Total Users</p>
        </div>
        <div class="card">
          <h3><?php echo $studentCount; ?></h3>
          <p>Students</p>
        </div>
        <div class="card">
          <h3><?php echo $verifiedUsers; ?></h3>
          <p>Verified Emails</p>
        </div>
        <div class="card">
          <h3><?php echo $suspendedUsers; ?></h3>
          <p>Suspended Users</p>
        </div>
        <div class="card">
          <h3><?php echo $activeCourses; ?></h3>
          <p>Active Courses</p>
        </div>
      </section>

      <section class="recent-section">
        <h2>Recent Registrations</h2>
        <table>
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Joined At</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $recentUsers->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($row['full_name']); ?></td>
              <td><?php echo htmlspecialchars($row['email']); ?></td>
              <td><?php echo htmlspecialchars($row['joined_at']); ?></td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </section>
    </main>
  </div>
</body>
</html>
