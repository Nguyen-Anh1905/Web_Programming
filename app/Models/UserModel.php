<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Enums\MemberTier;
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
        $stmt = $this->db->prepare(
            'SELECT id, name, email, phone, address, dob, gender, member_points, member_tier, role, created_at
             FROM users WHERE id = :id LIMIT 1',
        );
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        return $row !== false ? $row : null;
    }

    /**
     * Insert a new user and return the new auto-increment ID.
     */
    public function create(
        string  $name,
        string  $email,
        string  $passwordHash,
        Role    $role         = Role::CUSTOMER,
        ?string $phone        = null,
        ?string $address      = null,
        ?string $dob          = null,
        ?string $gender       = null,
        int     $memberPoints = 0,
    ): int {
        $stmt = $this->db->prepare(
            'INSERT INTO users (name, email, role, password_hash, phone, address, dob, gender, member_points)
             VALUES (:name, :email, :role, :password_hash, :phone, :address, :dob, :gender, :member_points)',
        );
        $stmt->execute([
            ':name'          => $name,
            ':email'         => $email,
            ':role'          => $role->value,
            ':password_hash' => $passwordHash,
            ':phone'         => $phone !== '' ? $phone : null,
            ':address'       => $address !== '' ? $address : null,
            ':dob'           => $dob !== '' ? $dob : null,
            ':gender'        => $gender !== '' ? $gender : null,
            ':member_points' => $memberPoints,
        ]);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Return all customers (role = customer), newest first.
     * @return array<int, array<string, mixed>>
     */
    public function getAllCustomers(): array
    {
        $stmt = $this->db->prepare(
            "SELECT id, name, email, phone, address, dob, gender, member_points, member_tier, role, created_at
             FROM users WHERE role = 'customer' ORDER BY created_at DESC",
        );
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // ── Server-side pagination & sorting ────────────────────────────────────

    /**
     * Allowed sort columns – WHITELIST to prevent SQL Injection in ORDER BY.
     */
    private const SORT_WHITELIST = [
        'name', 'email', 'phone', 'dob', 'gender',
        'member_points', 'member_tier', 'created_at',
    ];

    /**
     * Count total customers matching an optional keyword.
     */
    public function countAllCustomers(?string $keyword = null): int
    {
        if ($keyword !== null && $keyword !== '') {
            $like = '%' . $keyword . '%';
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM users
                 WHERE role = 'customer'
                   AND (name LIKE :kw1 OR email LIKE :kw2 OR phone LIKE :kw3)",
            );
            $stmt->execute([':kw1' => $like, ':kw2' => $like, ':kw3' => $like]);
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE role = 'customer'");
            $stmt->execute();
        }

        return (int) $stmt->fetchColumn();
    }

    /**
     * Return one page of customers, sorted and optionally filtered.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getCustomersPaginated(
        int     $limit,
        int     $offset,
        string  $sortCol = 'created_at',
        string  $sortDir = 'desc',
        ?string $keyword = null,
    ): array {
        // Sanitise sort column & direction via whitelists
        if (!in_array($sortCol, self::SORT_WHITELIST, true)) {
            $sortCol = 'created_at';
        }
        $sortDir = strtolower($sortDir) === 'asc' ? 'ASC' : 'DESC';

        $where  = "WHERE role = 'customer'";
        $params = [];

        if ($keyword !== null && $keyword !== '') {
            $like     = '%' . $keyword . '%';
            $where   .= ' AND (name LIKE :kw1 OR email LIKE :kw2 OR phone LIKE :kw3)';
            $params[':kw1'] = $like;
            $params[':kw2'] = $like;
            $params[':kw3'] = $like;
        }

        // ORDER BY uses whitelisted column/direction – safe to interpolate
        $sql  = "SELECT id, name, email, phone, address, dob, gender,
                        member_points, member_tier, role, created_at
                 FROM users $where
                 ORDER BY $sortCol $sortDir
                 LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);

        // Bind LIMIT / OFFSET as integers (PDO cannot bind these as named params inside LIMIT)
        $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val, PDO::PARAM_STR);
        }

        $stmt->execute();

        return $stmt->fetchAll();
    }


    /**
     * Update a customer's profile fields and optionally password.
     * Returns true if a row was actually changed.
     */
    public function updateCustomer(
        int     $id,
        string  $name,
        string  $email,
        ?string $passwordHash,
        ?string $phone        = null,
        ?string $address      = null,
        ?string $dob          = null,
        ?string $gender       = null,
        int     $memberPoints = 0,
    ): bool {
        $sets   = 'name = :name, email = :email, phone = :phone, address = :address,
                   dob = :dob, gender = :gender, member_points = :member_points';
        $params = [
            ':name'          => $name,
            ':email'         => $email,
            ':phone'         => $phone !== '' ? $phone : null,
            ':address'       => $address !== '' ? $address : null,
            ':dob'           => $dob !== '' ? $dob : null,
            ':gender'        => $gender !== '' ? $gender : null,
            ':member_points' => $memberPoints,
            ':id'            => $id,
            ':role'          => Role::CUSTOMER->value,
        ];

        if ($passwordHash !== null) {
            $sets                    .= ', password_hash = :password_hash';
            $params[':password_hash'] = $passwordHash;
        }

        $stmt = $this->db->prepare("UPDATE users SET $sets WHERE id = :id AND role = :role");
        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }

    /**
     * Delete a customer by ID (only if role = customer).
     * Returns true if a row was deleted.
     */
    public function deleteCustomer(int $id): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM users WHERE id = :id AND role = 'customer'",
        );
        $stmt->execute([':id' => $id]);

        return $stmt->rowCount() > 0;
    }

    // -----------------------------------------------------------------------
    // Member tier helper
    // -----------------------------------------------------------------------

    /**
     * Derive the MemberTier enum value from a raw points integer.
     * Useful when you already have the points value and don't want an extra query.
     */
    public static function tierFromPoints(int $points): MemberTier
    {
        return MemberTier::fromPoints($points);
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
