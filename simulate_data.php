<?php
// simulate_data.php
header('Content-Type: application/json');
require_once 'db_connect.php';

$devices = [
    "factory-01",
    "factory-02",
    "office-main",
    "warehouse-east",
    "server-room"
];

$results = [];

try {
    $stmt = $pdo->prepare("INSERT INTO sensor_readings (
        device_uuid, sht30_humidity, pm10, pm2_5, temperature, sound_db, ip_address, 
        sht30_temperature, voc, humidity, pm1_0, sound_vpp, co2
    ) VALUES (
        :uuid, :sht30_humidity, :pm10, :pm2_5, :temperature, :sound_db, :ip, 
        :sht30_temperature, :voc, :humidity, :pm1_0, :sound_vpp, :co2
    )");

    foreach ($devices as $uuid) {
        $data = [
            ':uuid' => $uuid,
            ':sht30_humidity' => rand(3000, 6000) / 100,
            ':pm10' => rand(0, 50),
            ':pm2_5' => rand(0, 30),
            ':temperature' => rand(180, 280) / 10,
            ':sound_db' => rand(400, 700) / 10,
            ':ip' => '192.168.1.' . rand(2, 254),
            ':sht30_temperature' => rand(180, 280) / 10,
            ':voc' => rand(0, 20),
            ':humidity' => rand(30, 60),
            ':pm1_0' => rand(0, 10),
            ':sound_vpp' => rand(10, 100) / 1000,
            ':co2' => rand(350, 1000)
        ];

        $stmt->execute($data);
        $results[] = $uuid;
    }

    echo json_encode(['status' => 'success', 'message' => 'Inserted data for ' . count($results) . ' devices', 'devices' => $results]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>