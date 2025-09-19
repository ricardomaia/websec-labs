-- =============================================================================
-- WebSec Labs - Database Initialization Script
-- =============================================================================
-- This script sets up the database for web security testing and learning
-- It contains intentionally vulnerable configurations for educational purposes
-- DO NOT USE IN PRODUCTION ENVIRONMENTS
-- =============================================================================

-- Drop and recreate the main database
DROP DATABASE IF EXISTS `websec_labs`;
CREATE DATABASE `websec_labs`;
USE `websec_labs`;

-- =============================================================================
-- USER TABLE - Contains test user accounts
-- =============================================================================
-- This table stores user credentials for authentication testing
-- Passwords are stored as MD5 hashes (intentionally weak for demonstration)

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(200) NOT NULL COMMENT 'MD5 hash - intentionally weak',
  `email` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- =============================================================================
-- USER CREDENTIALS - Test accounts for security labs
-- =============================================================================
-- WARNING: These are test credentials for educational purposes only!
--
-- User Accounts:
-- 1. admin / password123 (Admin account for privilege escalation tests)
-- 2. foo / test (Regular user account)
-- 3. bar / admin (User with weak password)
--
-- All passwords are hashed using MD5 (intentionally insecure)
-- =============================================================================

INSERT INTO `user` (`id`, `username`, `password`, `email`) VALUES
-- admin:password123 (MD5: 482c811da5d5b4bc6d497ffa98491e38 - FIXED: was wrong hash)
(1, 'admin', '482c811da5d5b4bc6d497ffa98491e38', 'admin@webseclabs.local'),

-- foo:test (MD5: 098f6bcd4621d373cade4e832627b4f6)
(2, 'foo', '098f6bcd4621d373cade4e832627b4f6', 'foo_21@webseclabs.local'),

-- bar:admin (MD5: 21232f297a57a5a743894a0e4a801fc3)
(3, 'bar', '21232f297a57a5a743894a0e4a801fc3', 'b.bar@webseclabs.local');

-- =============================================================================
-- PRODUCT TABLE - Sample e-commerce data
-- =============================================================================
-- This table contains product information for testing SQL injection,
-- XSS, and other web application vulnerabilities

DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL COMMENT 'Using DECIMAL for precise currency values',
  `category` varchar(100) DEFAULT 'Electronics',
  `stock` int DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Sample product data for testing
INSERT INTO `product` (`id`, `name`, `description`, `price`, `category`, `stock`) VALUES
(1, 'Pen Drive 8GB', 'Portable USB flash drive with 8GB storage capacity. Perfect for file transfers and backup.', 100.00, 'Electronics', 25),
(2, 'HD Externo 1TB', 'External hard drive with 1TB capacity. USB 3.0 interface for fast data transfer.', 199.99, 'Storage', 15),
(3, 'Teclado Numérico', 'Compact numeric keypad for enhanced productivity. Plug and play USB connection.', 24.99, 'Accessories', 50),
(4, 'Mouse Óptico', 'Ergonomic optical mouse with high precision tracking. Compatible with all operating systems.', 35.50, 'Accessories', 30),
(5, 'Webcam HD', 'High definition webcam with built-in microphone. Perfect for video calls and streaming.', 89.90, 'Electronics', 20);

-- =============================================================================
-- COMMENTS TABLE - For testing XSS and input validation
-- =============================================================================
-- This table stores user comments and reviews, useful for XSS testing

DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `comment` text NOT NULL,
  `rating` int DEFAULT 5 CHECK (`rating` >= 1 AND `rating` <= 5),
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_user` (`user_id`),
  KEY `fk_product` (`product_id`),
  CONSTRAINT `fk_comments_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_comments_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Sample comments for testing
INSERT INTO `comments` (`user_id`, `product_id`, `comment`, `rating`) VALUES
(2, 1, 'Excelente produto! Muito útil para transferir arquivos.', 5),
(3, 2, 'Ótima capacidade de armazenamento, recomendo!', 4),
(2, 3, 'Teclado compacto e funcional.', 4);

-- =============================================================================
-- ADMIN PANEL TABLE - For testing authorization bypass
-- =============================================================================

DROP TABLE IF EXISTS `admin_settings`;
CREATE TABLE `admin_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `setting_name` varchar(100) NOT NULL,
  `setting_value` text,
  `description` varchar(255),
  `modified_by` int,
  `modified_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_name` (`setting_name`),
  KEY `fk_modified_by` (`modified_by`),
  CONSTRAINT `fk_admin_settings_user` FOREIGN KEY (`modified_by`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Default admin settings
INSERT INTO `admin_settings` (`setting_name`, `setting_value`, `description`, `modified_by`) VALUES
('site_name', 'WebSec Labs', 'Nome do site', 1),
('maintenance_mode', 'false', 'Modo de manutenção do site', 1),
('max_login_attempts', '3', 'Máximo de tentativas de login', 1),
('session_timeout', '3600', 'Timeout da sessão em segundos', 1);

-- =============================================================================
-- CREATE VIEWS FOR TESTING
-- =============================================================================

-- View for user profiles (excluding sensitive password data)
CREATE VIEW `user_profiles` AS
SELECT
    `id`,
    `username`,
    `email`,
    'HIDDEN' as `password_status`
FROM `user`;

-- View for product catalog
CREATE VIEW `product_catalog` AS
SELECT
    p.`id`,
    p.`name`,
    p.`description`,
    p.`price`,
    p.`category`,
    p.`stock`,
    COALESCE(AVG(c.`rating`), 0) as `avg_rating`,
    COUNT(c.`id`) as `review_count`
FROM `product` p
LEFT JOIN `comments` c ON p.`id` = c.`product_id`
GROUP BY p.`id`, p.`name`, p.`description`, p.`price`, p.`category`, p.`stock`;

-- =============================================================================
-- SETUP COMPLETION
-- =============================================================================

-- Display setup information
SELECT 'Database setup completed successfully!' as 'Status';
SELECT 'WebSec Labs - Educational Security Testing Environment' as 'Environment';
SELECT '⚠️  WARNING: This contains intentional vulnerabilities for learning!' as 'Security Notice';

-- Show user accounts created
SELECT
    'User Accounts Created:' as 'Info',
    COUNT(*) as 'Total Users'
FROM `user`;

-- Show test credentials summary
SELECT
    username as 'Username',
    'password123' as 'Password (admin)',
    email as 'Email'
FROM `user` WHERE username = 'admin'
UNION ALL
SELECT
    username as 'Username',
    'test' as 'Password (foo)',
    email as 'Email'
FROM `user` WHERE username = 'foo'
UNION ALL
SELECT
    username as 'Username',
    'admin' as 'Password (bar)',
    email as 'Email'
FROM `user` WHERE username = 'bar';

-- =============================================================================
-- END OF INITIALIZATION SCRIPT
-- =============================================================================