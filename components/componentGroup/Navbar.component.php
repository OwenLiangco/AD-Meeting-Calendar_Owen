<?php
declare(strict_types=1);

require_once __DIR__ . '/../../bootstrap.php';
require_once UTILS_PATH . 'auth.util.php';

if (!Auth::check()) {
    header("Location: /index.php");
    exit;
}

$user = Auth::user();
?>

<nav class ="navbar">
    <div>
        <span>Role: <?= htmlspecialchars($user['role']) ?></span>
    </div>
    <form method="POST" action="/handlers/logout.handler.php">
        <button type="submit" class = "logout-btn">Logout</button>
    </form>
</nav>
