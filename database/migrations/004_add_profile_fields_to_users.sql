-- Migration: 004_add_profile_fields_to_users.sql
-- Adds optional profile fields and member tier to the users table.
--
-- member_tier thresholds:
--   dong  (Đồng) :      0 – 499  points
--   bac   (Bạc)  :    500 – 1 999 points
--   vang  (Vàng) :  2 000 – 4 999 points
--   vip   (VIP)  :  5 000+        points

ALTER TABLE `users`
    ADD COLUMN `phone`         VARCHAR(20)  NULL DEFAULT NULL AFTER `email`,
    ADD COLUMN `address`       VARCHAR(500) NULL DEFAULT NULL AFTER `phone`,
    ADD COLUMN `dob`           DATE         NULL DEFAULT NULL AFTER `address`,
    ADD COLUMN `gender`        ENUM('male','female','other') NULL DEFAULT NULL AFTER `dob`,
    ADD COLUMN `member_points` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `gender`,
    ADD COLUMN `member_tier`   ENUM('dong','bac','vang','vip')
        GENERATED ALWAYS AS (
            CASE
                WHEN `member_points` >= 5000 THEN 'vip'
                WHEN `member_points` >= 2000 THEN 'vang'
                WHEN `member_points` >= 500  THEN 'bac'
                ELSE 'dong'
            END
        ) STORED AFTER `member_points`;
