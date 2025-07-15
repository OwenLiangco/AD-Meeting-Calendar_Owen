<?php
declare(strict_types=1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="/assets/css/example.css">
</head>
<body>
    <div class="container">
        <h2>Database Connection Checker</h2>
        <?php
        include_once __DIR__ . '/handlers/mongodbChecker.handler.php';
        include_once __DIR__ . '/handlers/postgreChecker.handler.php';
        ?>

        <hr>
        <h3>Login</h3>
        <form method="POST" action="/handlers/auth.handler.php">
            <input type="text" name="username" required placeholder="Username">
            <input type="password" name="password" required placeholder="Password">
            <button type="submit">Login</button>
        </form>

        <?php if (isset($_GET['error'])): ?>
            <p class="error">❌ Invalid username or password.</p>
        <?php endif; ?>
    </div>
</body>
</html>


