<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Enums\Role;
use PDO;

final class UserModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // -----------------------------------------------------------------------
    // Users
    // -----------------------------------------------------------------------

    /**
     * Find a user record by email address.
     */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch();

        return $row !== false ? $row : null;
    }

    /**
     * Find a user record by primary key.
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id, name, email, role, created_at FROM users WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        return $row !== false ? $row : null;
    }

    /**
     * Insert a new user and return the new auto-increment ID.
     */
    public function create(string $name, string $email, string $passwordHash, Role $role = Role::CUSTOMER): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users (name, email, role, password_hash) VALUES (:name, :email, :role, :password_hash)',
        );
        $stmt->execute([
            ':name'          => $name,
            ':email'         => $email,
            ':role'          => $role->value,
            ':password_hash' => $passwordHash,
        ]);

        return (int) $this->db->lastInsertId();
    }

    // -----------------------------------------------------------------------
    // Refresh tokens
    // -----------------------------------------------------------------------

    /**
     * Persist a hashed refresh token linked to a user.
     */
    public function storeRefreshToken(int $userId, string $tokenHash, int $expiresAt): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO refresh_tokens (user_id, token_hash, expires_at)
             VALUES (:user_id, :token_hash, FROM_UNIXTIME(:expires_at))',
        );
        $stmt->execute([
            ':user_id'    => $userId,
            ':token_hash' => $tokenHash,
            ':expires_at' => $expiresAt,
        ]);
    }

    /**
     * Retrieve a refresh token row by its SHA-256 hash.
     */
    public function findRefreshToken(string $tokenHash): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM refresh_tokens WHERE token_hash = :token_hash LIMIT 1',
        );
        $stmt->execute([':token_hash' => $tokenHash]);
        $row = $stmt->fetch();

        return $row !== false ? $row : null;
    }

    /**
     * Delete a specific refresh token (used on logout or rotation).
     */
    public function deleteRefreshToken(string $tokenHash): void
    {
        $stmt = $this->db->prepare('DELETE FROM refresh_tokens WHERE token_hash = :token_hash');
        $stmt->execute([':token_hash' => $tokenHash]);
    }

    /**
     * Invalidate ALL refresh tokens for a user (e.g. logout all devices).
     */
    public function deleteAllRefreshTokens(int $userId): void
    {
        $stmt = $this->db->prepare('DELETE FROM refresh_tokens WHERE user_id = :user_id');
        $stmt->execute([':user_id' => $userId]);
    }
}
