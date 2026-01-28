<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Sensor Simulator</title>
    <style>
        body {
            background-color: #111;
            color: #0f0;
            font-family: 'Courier New', Courier, monospace;
            padding: 2rem;
            text-align: center;
        }

        .status {
            font-size: 1.5rem;
            margin-bottom: 2rem;
        }

        .log {
            text-align: left;
            background: #222;
            padding: 1rem;
            border-radius: 5px;
            height: 300px;
            overflow-y: auto;
            border: 1px solid #333;
        }

        button {
            padding: 10px 20px;
            font-size: 1.2rem;
            cursor: pointer;
            background: #0f0;
            border: none;
            color: #000;
            font-weight: bold;
            margin: 10px;
        }

        button.stop {
            background: #f00;
            color: #fff;
        }
    </style>
</head>

<body>
    <h1>🤖 PHP Sensor Simulator</h1>
    <div class="status">
        Status: <span id="status-text">Stopped</span>
        <br>
        Next Run: <span id="countdown">--</span>s
    </div>

    <div>
        <button onclick="startSimulation()">▶ Start (Every 60s)</button>
        <button class="stop" onclick="stopSimulation()">⏹ Stop</button>
    </div>

    <h3>Activity Log:</h3>
    <div class="log" id="log"></div>

    <script>
        let intervalId = null;
        let countdownId = null;
        let nextRun = 60;

        function log(msg) {
            const div = document.getElementById('log');
            const time = new Date().toLocaleTimeString();
            div.innerHTML = `[${time}] ${msg}<br>` + div.innerHTML;
        }

        async function triggerData() {
            try {
                log("Sending data request...");
                const response = await fetch('simulate_data.php');
                const data = await response.json();
                if (data.status === 'success') {
                    log(`✅ Success: Added data for ${data.devices.length} devices.`);
                } else {
                    log(`❌ Error: ${data.message}`);
                }
            } catch (e) {
                log(`❌ Network Error: ${e.message}`);
            }
        }

        function startSimulation() {
            if (intervalId) return;

            triggerData(); // Run immediately once

            document.getElementById('status-text').style.color = '#0f0';
            document.getElementById('status-text').textContent = "RUNNING";

            nextRun = 60;
            updateCountdown();

            intervalId = setInterval(() => {
                triggerData();
                nextRun = 60;
            }, 60000); // 60 seconds

            countdownId = setInterval(() => {
                nextRun--;
                updateCountdown();
            }, 1000);
        }

        function stopSimulation() {
            if (!intervalId) return;
            clearInterval(intervalId);
            clearInterval(countdownId);
            intervalId = null;
            countdownId = null;
            document.getElementById('status-text').style.color = '#fff';
            document.getElementById('status-text').textContent = "Stopped";
            document.getElementById('countdown').textContent = "--";
            log("🛑 Simulation stopped.");
        }

        function updateCountdown() {
            document.getElementById('countdown').textContent = nextRun;
        }
    </script>
</body>

</html>