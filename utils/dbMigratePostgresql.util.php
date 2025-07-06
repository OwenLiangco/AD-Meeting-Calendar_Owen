<?php
declare(strict_types=1);

// 1) Composer autoload
require 'vendor/autoload.php';

// 2) Composer bootstrap
require 'bootstrap.php';

// 3) envSetter
require_once __DIR__ . '/envSetter.util.php';

// ------------------------------------------------------------------
//  Database connection
// ------------------------------------------------------------------
$pgConfig = [
    'host' => $typeConfig['pg_host'],
    'port' => $typeConfig['pg_port'],
    'db'   => $typeConfig['pg_db'],
    'user' => $typeConfig['pg_user'],
    'pass' => $typeConfig['pg_pass'],
];

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

// ------------------------------------------------------------------
//  1.  Drop old tables
// ------------------------------------------------------------------
echo "Dropping old tables…\n";
foreach ([
    'projects',
    'users',
] as $table) {
    $pdo->exec("DROP TABLE IF EXISTS {$table} CASCADE;");
    echo "✅ Dropped (if existed): {$table}\n";
}

// ------------------------------------------------------------------
//  2.  Re‑apply schemas (use absolute path)
// ------------------------------------------------------------------
$schemaFiles = [
    BASE_PATH . '/database/user.model.sql',      // add more files if needed
];

foreach ($schemaFiles as $schema) {
    echo "Applying schema from {$schema}…\n";

    $sql = file_get_contents($schema);
    if ($sql === false) {
        throw new RuntimeException("❌ Could not read {$schema}");
    }
    $pdo->exec($sql);
    echo "✅ Creation Success from {$schema}\n";
}

echo "🎉 Migration Finished\n";

