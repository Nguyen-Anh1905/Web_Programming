<?php

declare(strict_types=1);

namespace App\Core;

use App\Config\Database as DbConfig;
use PDO;
use PDOException;
use RuntimeException;

final class Database
{
    private static ?PDO $instance = null;

    private function __construct() {}

    public static function getInstance(): PDO
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            DbConfig::host(),
            DbConfig::port(),
            DbConfig::name(),
            DbConfig::charset(),
        );

        try {
            self::$instance = new PDO($dsn, DbConfig::user(), DbConfig::password(), [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            throw new RuntimeException('Database connection failed: ' . $e->getMessage());
        }

        return self::$instance;
    }
}
