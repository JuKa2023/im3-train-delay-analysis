-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS im3;

-- Use the database
USE im3;

-- Create tables
CREATE TABLE IF NOT EXISTS stationtable (
    id INT AUTO_INCREMENT PRIMARY KEY,
    destination VARCHAR(255) NOT NULL,
    departure VARCHAR(255) NOT NULL,
    departure_time_stamp INT NOT NULL,
    departure_time DATETIME NOT NULL,
    delay INT DEFAULT 0,
    platform VARCHAR(50),
    INDEX idx_departure_time (departure_time),
    INDEX idx_delay (delay)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS weather_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    location VARCHAR(255) NOT NULL,
    temperature_celsius FLOAT,
    wind_speed FLOAT,
    rain FLOAT,
    weather_code INT,
    showers FLOAT,
    snowfall FLOAT,
    wind_gusts_10m FLOAT,
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_timestamp (timestamp),
    INDEX idx_location (location)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create user and grant privileges
CREATE USER IF NOT EXISTS 'im3_user'@'%' IDENTIFIED BY 'im3_password';
GRANT ALL PRIVILEGES ON im3.* TO 'im3_user'@'%';
FLUSH PRIVILEGES; 