CREATE DATABASE IF NOT EXISTS iot_data;
USE iot_data;

CREATE TABLE IF NOT EXISTS sensor_readings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    device_uuid VARCHAR(100),
    sht30_humidity FLOAT,
    pm10 FLOAT,
    pm2_5 FLOAT,
    temperature FLOAT,
    sound_db FLOAT,
    ip_address VARCHAR(45),
    sht30_temperature FLOAT,
    voc FLOAT,
    humidity FLOAT,
    pm1_0 FLOAT,
    sound_vpp FLOAT,
    co2 FLOAT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE sensor_readings ADD COLUMN device_uuid VARCHAR(100) NULL AFTER id;

-- Dummy data for testing
INSERT INTO sensor_readings (device_uuid, sht30_humidity, pm10, pm2_5, temperature, sound_db, ip_address, sht30_temperature, voc, humidity, pm1_0, sound_vpp, co2) VALUES 
('factory-01', 45.1, 12, 5, 23.5, 55.2, '192.168.0.101', 23.1, 15, 46.0, 2, 0.02, 450),
('factory-02', 55.8, 45, 20, 21.0, 60.5, '192.168.0.102', 20.8, 100, 56.5, 12, 0.05, 800),
('factory-03', 30.5, 5, 1, 25.4, 40.1, '192.168.0.103', 25.0, 5, 31.0, 0, 0.01, 410),
('factory-04', 70.2, 80, 55, 18.5, 75.8, '192.168.0.104', 18.1, 250, 71.0, 35, 0.08, 1200),
('factory-05', 40.0, 15, 8, 22.2, 50.0, '192.168.0.105', 22.0, 20, 41.0, 4, 0.03, 500);
