<?php
// get_data.php
header('Content-Type: application/json');
require_once 'db_connect.php';

try {
    // 1. If 'list' parameter is present, return all unique device UUIDs
    if (isset($_GET['list'])) {
        $stmt = $pdo->query("SELECT DISTINCT device_uuid FROM sensor_readings WHERE device_uuid IS NOT NULL ORDER BY created_at DESC");
        $devices = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo json_encode($devices);
        exit;
    }

    // 2. Fetch data
    $outputData = [];

    if (isset($_GET['uuid'])) {
        // Specific Device
        $stmt = $pdo->prepare("SELECT * FROM sensor_readings WHERE device_uuid = :uuid ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([':uuid' => $_GET['uuid']]);
        $rows = $stmt->fetchAll();
    } else {
        // All Devices (Latest per device)
        // Using a subquery to get max ID for each device
        $sql = "SELECT * FROM sensor_readings WHERE id IN (
                    SELECT MAX(id) 
                    FROM sensor_readings 
                    WHERE device_uuid IS NOT NULL 
                    GROUP BY device_uuid
                ) ORDER BY device_uuid ASC";
        $stmt = $pdo->query($sql);
        $rows = $stmt->fetchAll();
    }

    if ($rows) {
        foreach ($rows as $data) {
            // Map DB columns to the JSON format specific keys
            // Adjusting output keys to match the user's JSON example exactly
            $outputData[] = [
                "SHT30_Humidity" => (float) $data['sht30_humidity'],
                "PM10" => (float) $data['pm10'],
                "PM2.5" => (float) $data['pm2_5'],
                "Temperature" => (float) $data['temperature'],
                "Sound_dB" => (float) $data['sound_db'],
                "ip" => $data['ip_address'],
                "SHT30_Temperature" => (float) $data['sht30_temperature'],
                "VOC" => (float) $data['voc'],
                "Humidity" => (float) $data['humidity'],
                "PM1.0" => (float) $data['pm1_0'],
                "Sound_Vpp" => (float) $data['sound_vpp'],
                "CO2" => (float) $data['co2'],
                "uuid" => $data['device_uuid'],
                "timestamp" => $data['created_at']
            ];
        }
    }

    echo json_encode($outputData);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>