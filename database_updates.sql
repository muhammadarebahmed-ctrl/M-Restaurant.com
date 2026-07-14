-- =================================================================
--  M-Restaurant — Database Update
--  Run this AFTER importing the original "restaurant (2).sql" file.
-- =================================================================

-- 1) Phone numbers now store the dial code and local number separately.
ALTER TABLE `users`
  ADD COLUMN `country_code` VARCHAR(6) NOT NULL DEFAULT '+251' AFTER `phone`;

-- 2) Orders can now be marked "paid" once an uploaded payment proof is
--    approved by an admin (previously only pending/delivered/cancelled).
ALTER TABLE `orders`
  MODIFY `status` ENUM('pending','paid','delivered','cancelled','') NOT NULL DEFAULT 'pending';

-- 3) Payment proofs uploaded by customers and reviewed by admins.
CREATE TABLE IF NOT EXISTS `payments` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `order_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `proof_image` VARCHAR(255) NOT NULL,
  `reference_note` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reviewed_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 4) A log of every SMS notice the system has sent (or attempted to send).
CREATE TABLE IF NOT EXISTS `sms_logs` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `phone` VARCHAR(30) NOT NULL,
  `message` TEXT NOT NULL,
  `status` ENUM('sent','failed') NOT NULL,
  `response` TEXT,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
