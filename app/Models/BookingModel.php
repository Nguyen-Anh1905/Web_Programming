<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class BookingModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Lấy toàn bộ lịch sử đặt phòng của một khách hàng, mới nhất trước.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getByUserId(int $userId): array
    {
        $stmt = $this->db->prepare(
            'SELECT id, room_name, check_in, check_out, total_price, status, created_at
               FROM bookings
              WHERE user_id = :uid
              ORDER BY check_in DESC'
        );
        $stmt->execute([':uid' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
