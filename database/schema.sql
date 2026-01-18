-- Lens Calculator Database Schema
-- Created: November 10, 2025

-- Database creation
CREATE DATABASE IF NOT EXISTS lens_calculator;
USE lens_calculator;

-- Category Types Table
CREATE TABLE IF NOT EXISTS category_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Equipment Types Table
CREATE TABLE IF NOT EXISTS equipment_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(100) NOT NULL UNIQUE,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Equipment Details Table
CREATE TABLE IF NOT EXISTS equipment_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    type_id INT NOT NULL,
    model VARCHAR(150) NOT NULL,
    name VARCHAR(200) NOT NULL,
    value DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    description TEXT,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES category_types(id) ON DELETE CASCADE,
    FOREIGN KEY (type_id) REFERENCES equipment_types(id) ON DELETE CASCADE,
    INDEX idx_category (category_id),
    INDEX idx_type (type_id),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Templates Table
CREATE TABLE IF NOT EXISTS templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    labor_hours DECIMAL(5, 2) DEFAULT 0.00,
    hourly_rate DECIMAL(10, 2) DEFAULT 0.00,
    margin_percentage DECIMAL(5, 2) DEFAULT 0.00,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Template Equipment Table (Junction table for many-to-many relationship)
CREATE TABLE IF NOT EXISTS template_equipment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    template_id INT NOT NULL,
    equipment_id INT NOT NULL,
    quantity INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (template_id) REFERENCES templates(id) ON DELETE CASCADE,
    FOREIGN KEY (equipment_id) REFERENCES equipment_details(id) ON DELETE CASCADE,
    UNIQUE KEY unique_template_equipment (template_id, equipment_id),
    INDEX idx_template (template_id),
    INDEX idx_equipment (equipment_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Packages Table (for saved customer packages)
CREATE TABLE IF NOT EXISTS packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    package_name VARCHAR(200) NOT NULL,
    client_name VARCHAR(200),
    template_id INT NULL,
    labor_hours DECIMAL(5, 2) DEFAULT 0.00,
    hourly_rate DECIMAL(10, 2) DEFAULT 0.00,
    margin_percentage DECIMAL(5, 2) DEFAULT 0.00,
    equipment_total DECIMAL(10, 2) DEFAULT 0.00,
    labor_total DECIMAL(10, 2) DEFAULT 0.00,
    subtotal DECIMAL(10, 2) DEFAULT 0.00,
    margin_amount DECIMAL(10, 2) DEFAULT 0.00,
    final_total DECIMAL(10, 2) DEFAULT 0.00,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (template_id) REFERENCES templates(id) ON DELETE SET NULL,
    INDEX idx_client (client_name),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Package Equipment Table (for saved package equipment)
CREATE TABLE IF NOT EXISTS package_equipment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    package_id INT NOT NULL,
    equipment_id INT NOT NULL,
    quantity INT DEFAULT 1,
    unit_value DECIMAL(10, 2) NOT NULL,
    total_value DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE,
    FOREIGN KEY (equipment_id) REFERENCES equipment_details(id) ON DELETE CASCADE,
    INDEX idx_package (package_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Access Logs Table (for tracking page visits)
CREATE TABLE IF NOT EXISTS access_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT,
    request_uri VARCHAR(255),
    accessed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_ip (ip_address),
    INDEX idx_accessed (accessed_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
