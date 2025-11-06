<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Redirect if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html");
    exit;
}

// Load DB config
require_once __DIR__ . '/config/db_config.php';

// Fetch all courses for students (view only)
$courses = [];
$stmt = $conn->prepare("SELECT course_id, title, category, level, created_at FROM courses ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
$courses = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Courses | EduNook</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body>
<div class="dashboard-container">

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo-section">
            <img src="../assets/images/logo.png" alt="EduNook Logo" class="sidebar-logo">
            <h2 class="brand-name">EduNook</h2>
        </div>

        <nav class="nav-menu">
            <a href="dashboard.php"><span>🏠</span> Dashboard</a>
            <a href="student-courses.php" class="active"><span>📘</span> Courses</a>
            <a href="payment.php"><span>💳</span> Payments</a>
            <a href="#"><span>📁</span> Resources</a>
            <a href="#"><span>👥</span> Community</a>
            <a href="logout.php" class="logout"><span>↩️</span> Logout</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">

        <h1>Available Courses</h1>

        <table style="width:100%; background:white; border-collapse:collapse; border-radius:10px; overflow:hidden;">
            <thead>
                <tr style="background:#1e3a8a; color:white;">
                    <th style="padding:12px;">Title</th>
                    <th style="padding:12px;">Category</th>
                    <th style="padding:12px;">Level</th>
                    <th style="padding:12px;">Created</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($courses)): ?>
                <?php foreach ($courses as $c): ?>
                    <tr style="border-bottom:1px solid #eee;">
                        <td style="padding:12px;"><?= htmlspecialchars($c['title']) ?></td>
                        <td style="padding:12px;"><?= htmlspecialchars($c['category']) ?></td>
                        <td style="padding:12px;"><?= htmlspecialchars($c['level']) ?></td>
                        <td style="padding:12px;"><?= htmlspecialchars($c['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4" style="padding:12px; text-align:center;">No courses available yet.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>

    </main>
</div>
</body>
</html>
