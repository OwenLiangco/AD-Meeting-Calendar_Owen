<?php
declare(strict_types=1);

require_once __DIR__ . '/../utils/auth.util.php';

class AuthHandler
{
    public static function loginFromRequest(): void
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (Auth::login($username, $password)) {
            header("Location: /pages/DashboardPage/index.php");
            exit;
        }

        header("Location: /index.php?error=1");
        exit;
    }

    public static function logout(): void
    {
        Auth::logout();
        header("Location: /index.php");
        exit;
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    AuthHandler::loginFromRequest();
}

