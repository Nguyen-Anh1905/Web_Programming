-- Migration: 003_add_role_to_users.sql
-- Requires 001_create_users_table.sql

ALTER TABLE `users`
    ADD COLUMN `role` ENUM('admin', 'customer') NOT NULL DEFAULT 'customer' AFTER `email`;
