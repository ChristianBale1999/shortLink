CREATE DATABASE IF NOT EXISTS shortlink_db;
USE shortlink_db;

CREATE TABLE IF NOT EXISTS links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    original_url VARCHAR(2048) NOT NULL,
    short_code VARCHAR(10) NOT NULL UNIQUE,
    clicks INT DEFAULT 0,
    created_at INT NOT NULL,
    updated_at INT NOT NULL,
    INDEX idx_short_code (short_code)
    );

CREATE TABLE IF NOT EXISTS clicks_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    link_id INT NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent VARCHAR(255),
    clicked_at INT NOT NULL,
    INDEX idx_link_id (link_id),
    FOREIGN KEY (link_id) REFERENCES links(id) ON DELETE CASCADE
    );

SHOW TABLES;