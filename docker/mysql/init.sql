-- Create database if not exists
CREATE DATABASE IF NOT EXISTS paytr_pos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user if not exists
CREATE USER IF NOT EXISTS 'paytr_user'@'%' IDENTIFIED BY 'paytr_password';

-- Grant privileges
GRANT ALL PRIVILEGES ON paytr_pos.* TO 'paytr_user'@'%';
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY 'root_password';

-- Flush privileges
FLUSH PRIVILEGES;

-- Set timezone
SET GLOBAL time_zone = '+00:00';
