<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>QR Code | QR Attendance</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #eaeaea;
      margin: 0;
      padding: 0;
    }

    .header {
      background-color: #333;
      color: white;
      padding: 10px 20px;
      text-align: center;
    }

    .container {
      max-width: 400px;
      margin: 40px auto;
      padding: 20px;
      background-color: white;
      border-radius: 8px;
      text-align: center;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    h2 {
      margin-bottom: 20px;
    }

    img {
      width: 200px;
      height: 200px;
      margin-bottom: 20px;
    }

    .note {
      font-size: 14px;
      color: #555;
      margin-bottom: 20px;
    }

    .back-link a {
      text-decoration: none;
      color: #333;
    }

    .back-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="header">QR Code for Attendance</div>

  <div class="container">
    <h2>Scan to Mark Attendance</h2>

    <!-- Dynamic QR Code -->
    <?php

    // Function to get the local IPv4 address dynamically
    function getLocalIp() {
        // Try to get the local IP address using the server environment variables
        $ip = null;

        // Check if SERVER_ADDR is available and is not an IPv6 address
        if (isset($_SERVER['SERVER_ADDR']) && filter_var($_SERVER['SERVER_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $ip = $_SERVER['SERVER_ADDR']; // Local IPv4 address
        }

        // If not found, run the 'hostname -I' command to get the IP address
        if (!$ip) {
            $ip = trim(shell_exec("hostname -I"));
            
            // Ensure that we only take the first IPv4 address (usually it's the one we want)
            $ip_array = explode(' ', $ip);
            foreach ($ip_array as $address) {
                if (filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                    $ip = $address;
                    break;
                }
            }
        }

        return $ip;
    }

    require_once 'phpqrcode-lib/qrlib.php'; // adjust path as needed

    // Generate a unique session ID (you can store it in DB later if needed)
    $session_id = uniqid();

    // Get the current IP
    $ip = getLocalIp();

    // URL students will open
    $link = "http://$ip/attendacedashboard/qrscanner/mark_attendance.php?session=$session_id";

    // Save QR code image
    $qr_file = "qrcodes/$session_id.png";
    if (!is_dir("qrcodes")) {
        mkdir("qrcodes");
    }
    QRcode::png($link, $qr_file);
    ?>

    <img src="<?php echo $qr_file; ?>" alt="QR Code">

    <div class="note">
      Show this QR code to students. They will scan it using their phone to mark attendance.
    </div>

    <div class="back-link">
      <a href="dashboard.php">⬅ Back to Dashboard</a>
    </div>
  </div>

</body>
</html>
