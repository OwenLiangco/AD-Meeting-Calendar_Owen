<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/envSetter.util.php';

class Auth
{
    // 1. Check if session is active
    private static function init(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // 2. Connect to DB
    private static function db(): PDO {
        $pgConfig = [
            'host' => $_ENV['PG_HOST'],
            'port' => $_ENV['PG_PORT'],
            'db'   => $_ENV['PG_DB'],
            'user' => $_ENV['PG_USER'],
            'pass' => $_ENV['PG_PASS'],
        ];

        $dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";
        return new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
    }

    // 3. Login Function
    public static function login(string $username, string $password): bool {
        self::init();
        $pdo = self::db();

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user'] = $user;
            return true;
        }

        return false;
    }

    // 4. Check if user is logged in
    public static function check(): bool {
        self::init();
        return isset($_SESSION['user']);
    }

    // 5. Return current user
    public static function user(): ?array {
        self::init();
        return $_SESSION['user'] ?? null;
    }

    // 6. Logout
    public static function logout(): void {
        self::init();
        session_destroy();
    }
}
