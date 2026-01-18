-- Add access_logs table for tracking page visits
-- Run this to update your database

USE lens_calculator;

CREATE TABLE IF NOT EXISTS access_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT,
    request_uri VARCHAR(255),
    accessed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_ip (ip_address),
    INDEX idx_accessed (accessed_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
