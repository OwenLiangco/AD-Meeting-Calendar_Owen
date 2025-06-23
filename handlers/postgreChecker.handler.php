<?php

require_once UTILS_PATH . 'envSetter.util.php';

$conn_string = sprintf(
    "host=%s port=%s dbname=%s user=%s password=%s",
    $typeConfig['pg_host'],
    $typeConfig['pg_port'],
    $typeConfig['pg_db'],
    $typeConfig['pg_user'],
    $typeConfig['pg_pass']
);

$dbconn = pg_connect($conn_string);

if (!$dbconn) {
    echo "❌ Connection Failed: ", pg_last_error() . "  <br>";
    exit();
} else {
    echo "✅ PostgreSQL Connection  <br>";
    pg_close($dbconn);
}
