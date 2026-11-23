<?php

declare(strict_types=1);

/**
 * Copy to database.local.php (gitignored) on Hostinger — do not commit real passwords.
 */
return [
    'host' => 'localhost',
    'port' => 3306,
    'database' => 'u123456789_promptsbox',
    'username' => 'u123456789_prompts',
    'password' => 'YOUR_MYSQL_PASSWORD',
    'charset' => 'utf8mb4',
    'create_database' => false,
];