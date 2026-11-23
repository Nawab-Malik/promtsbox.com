<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';

$configDb = database_config();
$installedLock = dirname(__DIR__) . '/storage/installed.lock';

if (is_file($installedLock) && !isset($_GET['force'])) {
    echo 'Already installed. Delete storage/installed.lock only if you need to reinstall.';
    exit;
}

/**
 * @return list<string>
 */
function install_sql_statements(string $sql): array
{
    $sql = preg_replace('/^\s*--.*$/m', '', $sql) ?? $sql;
    $parts = preg_split('/;\s*\n/', $sql) ?: [];
    $out = [];
    foreach ($parts as $part) {
        $part = trim($part);
        if ($part !== '') {
            $out[] = $part;
        }
    }

    return $out;
}

function install_run_sql_file(PDO $pdo, string $path): void
{
    $contents = file_get_contents($path);
    if ($contents === false || trim($contents) === '') {
        return;
    }
    foreach (install_sql_statements($contents) as $statement) {
        $pdo->exec($statement);
    }
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
            $createDb = (bool) ($configDb['create_database'] ?? false);
            $port = (int) ($configDb['port'] ?? 3306);

            if ($createDb) {
                $pdo = new PDO(
                    sprintf('mysql:host=%s;port=%d;charset=utf8mb4', $configDb['host'], $port),
                    $configDb['username'],
                    $configDb['password'],
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
                $pdo->exec(
                    'CREATE DATABASE IF NOT EXISTS `' . $configDb['database'] . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci'
                );
                $pdo->exec('USE `' . $configDb['database'] . '`');
            } else {
                $pdo = new PDO(
                    sprintf(
                        'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
                        $configDb['host'],
                        $port,
                        $configDb['database']
                    ),
                    $configDb['username'],
                    $configDb['password'],
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
            }

            $base = dirname(__DIR__) . '/database';
            install_run_sql_file($pdo, $base . '/schema.sql');
            install_run_sql_file($pdo, $base . '/seed.sql');
            $pagesUpdate = $base . '/update_pages_content.sql';
            if (is_file($pagesUpdate)) {
                install_run_sql_file($pdo, $pagesUpdate);
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
        <p class="hint">Hostinger: create empty MySQL DB in hPanel, set <code>config/database.local.php</code>, then run this once.</p>
        <p class="hint">Database: <code><?= htmlspecialchars((string) ($configDb['database'] ?? '')) ?></code> on <code><?= htmlspecialchars((string) ($configDb['host'] ?? '')) ?></code></p>
    <?php endif; ?>
</main>
</body>
</html>