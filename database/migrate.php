<?php

declare(strict_types=1);

/**
 * Database Migration Runner
 * 
 * Usage:
 *   php database/migrate.php
 *   php database/migrate.php --fresh    (drop & re-create all tables)
 */

define('BASE_PATH', dirname(__DIR__));

// ── Load .env ────────────────────────────────────────────────────────────────

$envFile = BASE_PATH . '/.env';
if (!is_file($envFile)) {
    exit("[ERROR] .env file not found. Copy .env.example to .env first.\n");
}

foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    $line = trim($line);
    if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
        continue;
    }
    [$key, $value] = explode('=', $line, 2);
    $value = trim(trim($value), '"\'');
    $_ENV[trim($key)] = $value;
}

function env(string $key, string $default = ''): string
{
    return $_ENV[$key] ?? $default;
}

// ── Connect ───────────────────────────────────────────────────────────────────

$host     = env('DB_HOST', '127.0.0.1');
$port     = env('DB_PORT', '3306');
$dbName   = env('DB_NAME', 'crm_system');
$user     = env('DB_USER', 'root');
$password = env('DB_PASSWORD', '');

$isFresh = in_array('--fresh', $argv ?? [], true);

echo "\n=== CRM System — Migration Runner ===\n\n";
echo "  Host     : $host:$port\n";
echo "  Database : $dbName\n";
echo "  User     : $user\n";
if ($isFresh) {
    echo "  Mode     : FRESH (all tables will be dropped!)\n";
}
echo "\n";

try {
    // Connect without database first (to allow CREATE DATABASE)
    $pdo = new PDO(
        "mysql:host=$host;port=$port;charset=utf8mb4",
        $user,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "[OK] Database '$dbName' ready.\n";

    // Switch to target database
    $pdo->exec("USE `$dbName`");

    // Drop all tables if --fresh
    if ($isFresh) {
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        foreach ($tables as $table) {
            $pdo->exec("DROP TABLE IF EXISTS `$table`");
            echo "[DROPPED] Table '$table'\n";
        }
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
        echo "\n";
    }

} catch (PDOException $e) {
    exit("[ERROR] Cannot connect to MySQL: " . $e->getMessage() . "\n");
}

// ── Run migrations ────────────────────────────────────────────────────────────

$migrationDir = __DIR__ . '/migrations';
$files        = glob($migrationDir . '/*.sql');

if (empty($files)) {
    exit("[WARN] No migration files found in database/migrations/\n");
}

sort($files); // run in filename order (001, 002, ...)

$ran     = 0;
$skipped = 0;

foreach ($files as $file) {
    $filename = basename($file);
    $sql      = file_get_contents($file);

    if ($sql === false || trim($sql) === '') {
        echo "[SKIP] $filename (empty file)\n";
        $skipped++;
        continue;
    }

    try {
        // Remove single-line comments before executing
        $cleanSql = preg_replace('/--[^\n]*/', '', $sql);
        
        // Split on semicolons and execute each statement
        $statements = array_filter(
            array_map('trim', explode(';', $cleanSql)),
            fn(string $s) => $s !== ''
        );

        foreach ($statements as $stmt) {
            $pdo->exec($stmt);
        }

        echo "[OK] $filename\n";
        $ran++;
    } catch (PDOException $e) {
        echo "[ERROR] $filename — " . $e->getMessage() . "\n";
        exit(1);
    }
}

// ── Summary ───────────────────────────────────────────────────────────────────

echo "\n✓ Done. Ran: $ran | Skipped: $skipped\n\n";
