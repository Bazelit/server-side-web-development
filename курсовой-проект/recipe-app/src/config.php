<?php
// Simple config for development — edit as needed
define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_PORT', getenv('DB_PORT') ?: '5432');
define('DB_NAME', getenv('DB_NAME') ?: 'recipes_db');
define('DB_USER', getenv('DB_USER') ?: 'recipes_user');
define('DB_PASS', getenv('DB_PASS') ?: 'recipes_pass');

// Admin password for simple admin panel (for coursework only)
define('ADMIN_PASSWORD', getenv('ADMIN_PASSWORD') ?: 'admin123');

?>
