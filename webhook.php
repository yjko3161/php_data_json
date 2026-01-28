<?php
// webhook.php
header('Content-Type: application/json');
require_once 'db_connect.php';

// 1. Get the Webhook ID from URL (passed via via .htaccess)
$webhook_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$webhook_id) {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'Missing Webhook ID']);
    exit;
}

// 1-1. If accessed via Browser (GET), show friendly message
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo "<h1>Webhook is Ready 🚀</h1>";
    echo "<p>Target Device UUID: <strong>" . htmlspecialchars($webhook_id) . "</strong></p>";
    echo "<p>Send POST requests with JSON data to this URL.</p>";
    exit;
}

// 2. Read the raw POST data (JSON)
$json_input = file_get_contents('php://input');
$data = json_decode($json_input, true);

// Handle array of objects (if sent as [{}]) or single object {}
if (isset($data[0])) {
    $data = $data[0];
}

if (!$data) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid JSON']);
    exit;
}

// 3. Insert into Database
try {
    // Prepare SQL based on the keys we expect (matching schema.sql)
    // Note: We use the existing schema. We are not checking the UUID for now, 
    // but typically you would verify if this UUID belongs to a valid user/device.

    $stmt = $pdo->prepare("INSERT INTO sensor_readings (
        device_uuid, sht30_humidity, pm10, pm2_5, temperature, sound_db, ip_address, 
        sht30_temperature, voc, humidity, pm1_0, sound_vpp, co2
    ) VALUES (
        :uuid, :sht30_humidity, :pm10, :pm2_5, :temperature, :sound_db, :ip, 
        :sht30_temperature, :voc, :humidity, :pm1_0, :sound_vpp, :co2
    )");

    $stmt->execute([
        ':uuid' => $webhook_id,
        ':sht30_humidity' => $data['SHT30_Humidity'] ?? 0,
        ':pm10' => $data['PM10'] ?? 0,
        ':pm2_5' => $data['PM2.5'] ?? 0,
        ':temperature' => $data['Temperature'] ?? 0,
        ':sound_db' => $data['Sound_dB'] ?? 0,
        ':ip' => $data['ip'] ?? '',
        ':sht30_temperature' => $data['SHT30_Temperature'] ?? 0,
        ':voc' => $data['VOC'] ?? 0,
        ':humidity' => $data['Humidity'] ?? 0,
        ':pm1_0' => $data['PM1.0'] ?? 0,
        ':sound_vpp' => $data['Sound_Vpp'] ?? 0,
        ':co2' => $data['CO2'] ?? 0
    ]);

    echo json_encode(['status' => 'success', 'message' => 'Data received', 'uuid' => $webhook_id]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>