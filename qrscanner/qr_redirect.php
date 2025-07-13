<?php
session_start();
// Your other PHP code...
?>
<?php
// Get session ID from the query string
$session = $_GET['session'] ?? '';

// Validate the session ID (e.g., check if it's alphanumeric and has a valid length)
if ($session !== '' && preg_match('/^[a-zA-Z0-9]+$/', $session)) {
    // Redirect to the actual attendance page
    header("Location: mark_attendance.php?session=" . urlencode($session));
    exit;
} else {
    // Error message if the session ID is invalid or missing
    echo "Invalid or missing session ID. Please ensure you have a valid session.";
}
?>
<?php
echo 'Session ID: ' . $_GET['session'];
?>


