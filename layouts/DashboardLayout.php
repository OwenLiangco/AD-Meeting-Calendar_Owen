<?php
declare(strict_types=1);

function renderDashboardLayout(callable $contentRenderer): void {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Dashboard</title>
        <link rel="stylesheet" href="/../../pages/DashboardPage/assets/css/style.css">
    </head>
    <body>
        <?php include_once __DIR__ . '/../components/componentGroup/Navbar.component.php'; ?>

        <main style="padding: 2rem;">
            <?php $contentRenderer(); ?>
        </main>
    </body>
    </html>
    <?php
}
