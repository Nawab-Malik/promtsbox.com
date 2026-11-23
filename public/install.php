<?php

declare(strict_types=1);

$configDb = require dirname(__DIR__) . '/config/database.php';
$installedLock = dirname(__DIR__) . '/storage/installed.lock';

if (is_file($installedLock) && !isset($_GET['force'])) {
    echo 'Already installed. Delete storage/installed.lock only if you need to reinstall.';
    exit;
}

$error = null;
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $name = trim($_POST['display_name'] ?? 'Super Admin');
    $password = $_POST['password'] ?? '';
    if (strlen($password) < 10) {
        $error = 'Password must be at least 10 characters.';
    } else {
        try {
            $pdo = new PDO(
                'mysql:host=' . $configDb['host'] . ';charset=utf8mb4',
                $configDb['username'],
                $configDb['password'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            $pdo->exec('CREATE DATABASE IF NOT EXISTS `' . $configDb['database'] . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
            $pdo->exec('USE `' . $configDb['database'] . '`');

            $schema = file_get_contents(dirname(__DIR__) . '/database/schema.sql');
            if ($schema) {
                $pdo->exec($schema);
            }
            $seed = file_get_contents(dirname(__DIR__) . '/database/seed.sql');
            if ($seed) {
                $pdo->exec($seed);
            }

            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare(
                'INSERT INTO users (email, password_hash, display_name, role, status) VALUES (:e,:h,:n,"superadmin","active")'
            );
            $stmt->execute(['e' => strtolower($email), 'h' => $hash, 'n' => $name]);

            if (!is_dir(dirname(__DIR__) . '/storage')) {
                mkdir(dirname(__DIR__) . '/storage', 0755, true);
            }
            file_put_contents($installedLock, date('c'));

            $success = true;
        } catch (Throwable $e) {
            $error = $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Install PromptsBox</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="install-page">
<main class="install-card">
    <h1>PromptsBox Installer</h1>
    <?php if ($success): ?>
        <p class="success">Installed successfully. <a href="index.php">Open website</a> | <a href="index.php/admin/login">Admin login</a></p>
        <p><strong>Delete install.php</strong> on production after backup.</p>
    <?php else: ?>
        <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <form method="post">
            <label>Superadmin email<input type="email" name="email" required></label>
            <label>Display name<input type="text" name="display_name" value="Admin"></label>
            <label>Password (10+ chars)<input type="password" name="password" required></label>
            <button type="submit">Install database</button>
        </form>
        <p class="hint">Ensure MySQL is running in XAMPP and database name <code>promptsbox</code> in config/database.php.</p>
    <?php endif; ?>
</main>
</body>
</html>