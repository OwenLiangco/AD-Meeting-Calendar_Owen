<?php 
declare(strict_types=1);

// 1) Composer autoload
require 'vendor/autoload.php';

// 2) Composer bootstrap
require 'bootstrap.php';

// 3) envSetter
require_once __DIR__ . '/envSetter.util.php';

// ——— Load dummy data ———
$users = require DUMMIES_PATH . 'users.staticData.php';

// Prepare config array
$pgConfig = [
    'host' => $typeConfig['pg_host'],
    'port' => $typeConfig['pg_port'],
    'db'   => $typeConfig['pg_db'],
    'user' => $typeConfig['pg_user'],
    'pass' => $typeConfig['pg_pass'],
];

// ——— Connect to PostgreSQL ———
$dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";

try {
    $pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    echo "✅ Connected to PostgreSQL\n";
} catch (PDOException $e) {
    echo "❌ Connection Failed: " . $e->getMessage() . "\n";
    exit(1);
}

// ——— Apply schemas ———
$modelFiles = [
    'user.model.sql',
    'meeting.model.sql',
    'meeting_users.model.sql',
    'tasks.model.sql',
];

foreach ($modelFiles as $modelFile) {
    $path = "database/{$modelFile}";
    echo "Applying schema from {$path}…\n";

    $sql = file_get_contents($path);

    if ($sql === false) {
        throw new RuntimeException("❌ Could not read {$path}");
    } else {
        echo "✅ Creation Success from {$path}\n";
    }

    $pdo->exec($sql);
}

// ——— TRUNCATE tables ———
echo "Truncating tables…\n";

$tables = ['meeting_users', 'tasks', 'meetings', 'users'];

foreach ($tables as $table) {
    $pdo->exec("TRUNCATE TABLE {$table} RESTART IDENTITY CASCADE;");
    echo "✅ Truncated table: {$table}\n";
}

// ——— Seeding users ———
echo "Seeding users…\n";

$stmt = $pdo->prepare("
    INSERT INTO users (
        username,
        email,
        role,
        first_name,
        last_name,
        password_hash
    ) VALUES (
        :username,
        :email,
        :role,
        :first_name,
        :last_name,
        :password_hash
    )
");

foreach ($users as $u) {
    $stmt->execute([
        ':username'      => $u['username'],
        ':email'         => $u['email'],
        ':role'          => $u['role'],
        ':first_name'    => $u['first_name'],
        ':last_name'     => $u['last_name'],
        ':password_hash' => password_hash($u['password'], PASSWORD_DEFAULT),
    ]);
}

echo "✅ PostgreSQL seeding complete!\n";
echo "🎉 Seeder Finished\n";
