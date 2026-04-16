CREATE DATABASE IF NOT EXISTS shortlink_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE shortlink_db;

-- Таблица ссылок
CREATE TABLE IF NOT EXISTS `links` (
                                       `id` int(11) NOT NULL AUTO_INCREMENT,
    `original_url` varchar(2048) NOT NULL,
    `short_code` varchar(10) NOT NULL,
    `clicks` int(11) DEFAULT 0,
    `created_at` int(11) NOT NULL,
    `updated_at` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `short_code` (`short_code`),
    KEY `idx_short_code` (`short_code`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Таблица логов кликов
CREATE TABLE IF NOT EXISTS `clicks_log` (
                                            `id` int(11) NOT NULL AUTO_INCREMENT,
    `link_id` int(11) NOT NULL,
    `ip_address` varchar(45) NOT NULL,
    `user_agent` varchar(255) DEFAULT NULL,
    `clicked_at` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `link_id` (`link_id`),
    CONSTRAINT `clicks_log_ibfk_1` FOREIGN KEY (`link_id`) REFERENCES `links` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;