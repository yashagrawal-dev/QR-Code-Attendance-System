<?php
$conn = mysqli_connect("localhost", "root", "", "qr_attendance");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
