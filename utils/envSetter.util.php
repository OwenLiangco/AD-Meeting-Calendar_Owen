<?php
declare(strict_types=1);

// Correct path to vendor (because this file is inside /utils/)
require __DIR__ . '/../vendor/autoload.php';

// Define BASE_PATH only if not already defined (to avoid warning)
if (!defined('BASE_PATH')) {
    define('BASE_PATH', realpath(__DIR__ . '/..'));
}

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

// Distribute the data using array key
$typeConfig = [
    'pg_host'    => $_ENV['PG_HOST'],
    'pg_port'    => $_ENV['PG_PORT'],
    'pg_db'      => $_ENV['PG_DB'],
    'pg_user'    => $_ENV['PG_USER'],
    'pg_pass'    => $_ENV['PG_PASS'],
    'mongo_uri'  => $_ENV['MONGO_URI'],
    'mongo_db'   => $_ENV['MONGO_DB'],
];

