<?php
require_once __DIR__ . '/../bootstrap.php';
require_once UTILS_PATH . 'auth.util.php';

Auth::logout();
header("Location: /index.php");
exit;
