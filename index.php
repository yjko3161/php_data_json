<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IoT Sensor Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.7);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --accent-color: #38bdf8;
            --success-color: #4ade80;
            --warning-color: #facc15;
            --danger-color: #f87171;
            --glass-border: 1px solid rgba(255, 255, 255, 0.05);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-primary);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-image:
                radial-gradient(circle at 10% 20%, rgba(56, 189, 248, 0.1) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(74, 222, 128, 0.05) 0%, transparent 40%);
        }

        header {
            padding: 2rem;
            text-align: center;
            backdrop-filter: blur(10px);
            border-bottom: var(--glass-border);
        }

        h1 {
            margin: 0;
            font-weight: 600;
            font-size: 1.5rem;
            letter-spacing: -0.025em;
            background: linear-gradient(to right, #38bdf8, #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .status-bar {
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: var(--text-secondary);
            display: flex;
            justify-content: center;
            gap: 1rem;
            align-items: center;
        }

        .pulse {
            width: 8px;
            height: 8px;
            background-color: var(--success-color);
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 10px var(--success-color);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(74, 222, 128, 0.4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(74, 222, 128, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(74, 222, 128, 0);
            }
        }

        main {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            flex: 1;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .card {
            background: var(--card-bg);
            border-radius: 1rem;
            border: var(--glass-border);
            padding: 1.5rem;
            backdrop-filter: blur(12px);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
            border-color: rgba(56, 189, 248, 0.3);
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-color), transparent);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .card:hover::before {
            opacity: 1;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .card-title {
            font-size: 0.875rem;
            color: var(--text-secondary);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .card-icon {
            color: var(--accent-color);
            font-size: 1.25rem;
        }

        .card-value {
            font-size: 2.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .card-unit {
            font-size: 1rem;
            color: var(--text-secondary);
            font-weight: 400;
        }

        .card-footer {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            font-size: 0.75rem;
            color: var(--text-secondary);
            display: flex;
            justify-content: space-between;
        }

        /* Specific card colors based on type */
        .card.temp .card-value {
            color: #fdba74;
        }

        /* Orange */
        .card.humidity .card-value {
            color: #7dd3fc;
        }

        /* Blue */
        .card.air .card-value {
            color: #86efac;
        }

        /* Green */
        .card.noise .card-value {
            color: #fca5a5;
        }

        /* Red */

        /* Loading state */
        .loading .card-value {
            opacity: 0.5;
            animation: pulse-text 1.5s infinite;
        }

        @keyframes pulse-text {
            0% {
                opacity: 0.5;
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 0.5;
            }
        }

        footer {
            padding: 1.5rem;
            text-align: center;
            color: var(--text-secondary);
            font-size: 0.875rem;
            background: rgba(15, 23, 42, 0.5);
        }
    </style>
</head>

<body>

    <header>
        <h1>Environment Monitor</h1>
        <div class="header-controls">
            <select id="device-select" onchange="changeDevice()">
                <option value="">Loadings Devices...</option>
            </select>
        </div>
        <div class="status-bar">
            <span><span class="pulse"></span> Live Connection</span>
            <span id="last-update">Last update: --:--:--</span>
        </div>
    </header>

    <main>
        <div class="dashboard-grid" id="dashboard">
            <!-- Cards will be injected here by JS -->
        </div>
    </main>

    <style>
        /* Add style for select box */
        .header-controls {
            margin-top: 1rem;
        }
        select {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            font-family: inherit;
            cursor: pointer;
            outline: none;
        }
        select:hover {
            border-color: var(--accent-color);
        }
    </style>

    <footer>
        &copy; 2026 IoT Sensor Dashboard. All rights reserved.
    </footer>

    <script>
        let currentDevice = null;
        
        // Configuration for display mapping
        // Keys match the JSON keys from get_data.php
        const sensorConfig = [
            { key: 'Temperature', label: 'Temperature', unit: '°C', icon: '🌡️', type: 'temp' },
            { key: 'Humidity', label: 'Humidity', unit: '%', icon: '💧', type: 'humidity' },
            { key: 'PM2.5', label: 'PM 2.5', unit: 'µg/m³', icon: '🌫️', type: 'air' },
            { key: 'PM10', label: 'PM 10', unit: 'µg/m³', icon: '🏭', type: 'air' },
            { key: 'Sound_dB', label: 'Noise Level', unit: 'dB', icon: '🔊', type: 'noise' },
            { key: 'CO2', label: 'CO2 Level', unit: 'ppm', icon: '☁️', type: 'air' },
            { key: 'VOC', label: 'VOCs', unit: 'ppb', icon: '🧪', type: 'air' },
            { key: 'SHT30_Temperature', label: 'SHT30 Temp', unit: '°C', icon: '🌡️', type: 'temp' },
            { key: 'SHT30_Humidity', label: 'SHT30 Hum', unit: '%', icon: '💧', type: 'humidity' },
            { key: 'ip', label: 'Device IP', unit: '', icon: '🌐', type: 'neutral' },
            { key: 'uuid', label: 'Device ID', unit: '', icon: '🆔', type: 'neutral' }
        ];

        async function init() {
            await fetchDevices();
            fetchData();
            setInterval(fetchData, 5000);
        }

        async function fetchDevices() {
            try {
                // Fetch list of UUIDs
                const response = await fetch('get_data?list=1');
                const devices = await response.json();
                
                const select = document.getElementById('device-select');
                select.innerHTML = '';
                
                if (devices.length === 0) {
                    const opt = document.createElement('option');
                    opt.text = "No Devices Found";
                    select.add(opt);
                    return;
                }

                devices.forEach(dev => {
                    const opt = document.createElement('option');
                    opt.value = dev;
                    opt.text = dev || "Unknown Device";
                    select.add(opt);
                });

                // Set initial device (first one)
                if (!currentDevice && devices.length > 0) {
                    currentDevice = devices[0];
                    select.value = currentDevice;
                }
            } catch (error) {
                console.error('Error fetching device list:', error);
            }
        }

        function changeDevice() {
            const select = document.getElementById('device-select');
            currentDevice = select.value;
            fetchData(); // Refresh immediately
        }

        async function fetchData() {
            if (!currentDevice) return;

            try {
                // Fetch specific device data
                const response = await fetch(`get_data?uuid=${encodeURIComponent(currentDevice)}`);
                const data = await response.json();
                
                // Handle single object response (get_data.php now returns [ {...} ])
                // But my get_data.php returns [item] or []
                const item = (data && data.length > 0) ? data[0] : null;

                if (item) {
                    updateDashboard(item);
                    updateTimestamp(item.timestamp);
                }
            } catch (error) {
                console.error('Error fetching data:', error);
                
                // Show error on screen only if dashboard is empty
                const container = document.getElementById('dashboard');
                if (container.children.length === 0) {
                     container.innerHTML = `<div style="grid-column: 1/-1; text-align: center; color: var(--danger-color); padding: 2rem; background: rgba(248, 113, 113, 0.1); border-radius: 1rem; border: 1px solid var(--danger-color);">
                        <h3>Connection Error</h3>
                        <p>Failed to load JSON data. Please check the following:</p>
                        <ul style="text-align: left; display: inline-block;">
                            <li>Check console (F12) for detailed errors.</li>
                            <li>Ensure database credentials in <code>config.php</code> are correct.</li>
                            <li>Verify <code>get_data.php</code> returns valid JSON (no extra whitespace).</li>
                        </ul>
                        <br><br>
                        <small>${error.message}</small>
                    </div>`;
                }
            }
        }

        function updateTimestamp(ts) {
            const date = new Date(ts.replace(/-/g, "/")); // Compatibility fix for Safari/iOS
            document.getElementById('last-update').textContent = `Last update: ${date.toLocaleTimeString()}`;
        }

        function createCard(config, value) {
            const div = document.createElement('div');
            div.className = `card ${config.type}`;
            div.innerHTML = `
                <div class="card-header">
                    <span class="card-title">${config.label}</span>
                    <span class="card-icon">${config.icon}</span>
                </div>
                <div class="card-value">${value}</div>
                <div class="card-unit">${config.unit}</div>
            `;
            return div;
        }

        function updateDashboard(data) {
            const container = document.getElementById('dashboard');
            // Clear if switching devices (simplest way to ensure cleaner state)
            // Or use efficient updating. For now, efficient update by key.
            
            // Render
            sensorConfig.forEach(config => {
                let card = container.querySelector(`.card[data-key="${config.key}"]`);
                
                if (data.hasOwnProperty(config.key)) {
                    const val = data[config.key];
                    
                    if (!card) {
                        card = createCard(config, val);
                        card.dataset.key = config.key;
                        container.appendChild(card);
                    } else {
                        const valueEl = card.querySelector('.card-value');
                        // Simple animation if value changed
                        if (valueEl.textContent != val) {
                            valueEl.textContent = val;
                            valueEl.style.color = '#fff'; // Flash white temporarily
                            setTimeout(() => valueEl.style.color = '', 300);
                        }
                    }
                }
            });
        }

        // Start
        init();

    </script>
</body>

</html>