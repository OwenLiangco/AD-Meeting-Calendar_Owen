<?php
require_once __DIR__ . '/../../bootstrap.php';
require_once UTILS_PATH . 'auth.util.php';
require_once __DIR__ . '/../../layouts/DashboardLayout.php';

if (!Auth::check()) {
    header("Location: /index.php");
    exit;
}

$user = Auth::user();

renderDashboardLayout(function () use ($user) {
    ?>
    <h2>Hello, <?= htmlspecialchars($user['first_name']) ?> 👋</h2>
    <p>This is your dashboard page.</p>
    <?php
});


