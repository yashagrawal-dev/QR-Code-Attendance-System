<?php

// Generate a unique session ID (you can store it in DB later if needed)
$session_id = uniqid();

// URL students will open
$link = "http://localhost/attendacedashboard/qrscanner/mark_attendance.php?session=$session_id";

// Save QR code image
$qr_file = "qrcodes/$session_id.png";
if (!is_dir("qrcodes")) {
    mkdir("qrcodes");
}
QRcode::png($link, $qr_file);

// Display QR code
echo "<h2>Scan this QR Code to mark attendance</h2>";
echo "<img src='$qr_file'>";
?>
