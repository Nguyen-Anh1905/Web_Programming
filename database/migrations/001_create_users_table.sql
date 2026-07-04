-- Migration: 001_create_users_table.sql
-- Run this FIRST before any other migration.

CREATE TABLE IF NOT EXISTS `users` (
    `id`            BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `name`          VARCHAR(255)     NOT NULL,
    `email`         VARCHAR(255)     NOT NULL,
    `password_hash` VARCHAR(255)     NOT NULL,
    `created_at`    TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`    TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
