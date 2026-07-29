-- Migration: 005_create_bookings_table.sql

CREATE TABLE IF NOT EXISTS `bookings` (
    `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`     BIGINT UNSIGNED NOT NULL,
    `room_name`   VARCHAR(255)    NOT NULL,
    `check_in`    DATE            NOT NULL,
    `check_out`   DATE            NOT NULL,
    `total_price` DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    `status`      VARCHAR(50)     NOT NULL DEFAULT 'pending',
    `created_at`  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    CONSTRAINT `fk_bookings_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
