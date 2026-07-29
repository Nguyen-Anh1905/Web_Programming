<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Member tier levels determined by member_points.
 *
 * Thresholds (synced with the MySQL GENERATED column in migration 004):
 *   DONG :      0 –   499 points
 *   BAC  :    500 – 1 999 points
 *   VANG :  2 000 – 4 999 points
 *   VIP  :  5 000+       points
 */
enum MemberTier: string
{
    case DONG = 'dong';
    case BAC  = 'bac';
    case VANG = 'vang';
    case VIP  = 'vip';

    /** Human-readable Vietnamese label. */
    public function label(): string
    {
        return match ($this) {
            self::DONG => '🥉 Đồng',
            self::BAC  => '🥈 Bạc',
            self::VANG => '🥇 Vàng',
            self::VIP  => '💎 VIP',
        };
    }

    /** CSS hex colour for badge display. */
    public function color(): string
    {
        return match ($this) {
            self::DONG => '#cd7f32',   // copper/bronze
            self::BAC  => '#a8b5c4',   // silver
            self::VANG => '#f0c040',   // gold
            self::VIP  => '#a78bfa',   // violet / premium
        };
    }

    /** Minimum points required to reach this tier. */
    public function minPoints(): int
    {
        return match ($this) {
            self::DONG =>    0,
            self::BAC  =>  500,
            self::VANG => 2000,
            self::VIP  => 5000,
        };
    }

    /**
     * Derive the correct tier from a raw points integer.
     * Mirrors the CASE expression in the MySQL GENERATED column.
     */
    public static function fromPoints(int $points): self
    {
        return match (true) {
            $points >= 5000 => self::VIP,
            $points >= 2000 => self::VANG,
            $points >= 500  => self::BAC,
            default         => self::DONG,
        };
    }
}
