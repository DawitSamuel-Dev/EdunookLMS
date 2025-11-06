<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Redirect if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html");
    exit;
}

// Fallback name if session missing
if (!isset($_SESSION['firstname'])) {
    $_SESSION['firstname'] = 'Student';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Dashboard | EduNook</title>

    <!-- Corrected path to CSS (since dashboard.php is inside /public/) -->
    <link rel="stylesheet" href="../assets/css/dashboard.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">

        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo-section">
                <!-- Corrected image path -->
                <img src="../assets/images/logo.png" alt="EduNook Logo" class="sidebar-logo">
                <h2 class="brand-name">EduNook</h2>
            </div>

            <nav class="nav-menu">
                <a href="#"><span>📘</span> Courses</a>
                <a href="payment.php"><span>💳</span> Payments</a>
                <a href="#"><span>📁</span> Resources</a>
                <a href="#"><span>👥</span> Community</a>
                <a href="logout.php" class="logout"><span>↩️</span> Logout</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">

            <!-- Top Navigation Cards -->
            <section class="top-nav-cards">
                <div class="nav-card">
                    <div class="nav-card-icon" aria-hidden="true">📖</div>
                    <span class="nav-card-text">My Courses</span>
                </div>

                <div class="nav-card">
                    <div class="nav-card-icon" aria-hidden="true">⭐</div>
                    <span class="nav-card-text">Progress</span>
                </div>

                <div class="nav-card">
                    <div class="nav-card-icon" aria-hidden="true">💬</div>
                    <span class="nav-card-text">Community</span>
                </div>
            </section>

            <!-- Welcome Section -->
            <h1>Welcome, <span class="highlight"><?php echo htmlspecialchars($_SESSION['firstname']); ?></span>!</h1>
            <h2>Glad to see you back at <span class="highlight">EduNook</span></h2>

            <!-- Center Logo (fixed path) -->
            <img src="../assets/images/logo.png" alt="EduNook Logo" class="main-logo">

            <!-- Tagline / slogan -->
            <p class="tagline">Empowering Minds Through Learning</p>
        </main>
    </div>
</body>
</html>
