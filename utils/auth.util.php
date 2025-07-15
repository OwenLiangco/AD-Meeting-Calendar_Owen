<?php
declare(strict_types=1);

// Start session if it hasn't started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/envSetter.util.php';

class Auth
{
    // ——— Login Function ———
    public static function login(string $username, string $password): bool
    {
        // Connect to PostgreSQL
        $pgConfig = [
            'host' => $GLOBALS['typeConfig']['pg_host'],
            'port' => $GLOBALS['typeConfig']['pg_port'],
            'db'   => $GLOBALS['typeConfig']['pg_db'],
            'user' => $GLOBALS['typeConfig']['pg_user'],
            'pass' => $GLOBALS['typeConfig']['pg_pass'],
        ];

        $dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";
        try {
            $pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        } catch (PDOException $e) {
            return false;
        }

        // Check if user exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) return false;

        // Compare password
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user'] = [
                'id'       => $user['id'],
                'username' => $user['username'],
                'role'     => $user['role'],
                'name'     => $user['first_name'] . ' ' . $user['last_name'],
            ];
            return true;
        }

        return false;
    }

    // ——— Get User Info ———
    public static function user(): array|null
    {
        return $_SESSION['user'] ?? null;
    }

    // ——— Check if Logged In ———
    public static function check(): bool
    {
        return isset($_SESSION['user']);
    }

    // ——— Logout ———
    public static function logout(): void
    {
        // Clear session data
        $_SESSION = [];
        session_destroy();

        // Clear cookies if set
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
    }
}
