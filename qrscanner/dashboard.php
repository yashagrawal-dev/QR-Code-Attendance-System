<?php
// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'attendanceList';

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// Fetch total students
$studentResult = $conn->query("SELECT COUNT(*) AS total_students FROM Students");
$studentData = $studentResult->fetch_assoc();
$totalStudents = $studentData['total_students'];

$selectedDate = isset($_GET['date']) ? $_GET['date'] : '';

$selectedDateFormatted = '';
if (!empty($selectedDate)) {
    $dateObj = DateTime::createFromFormat('Y-m-d', $selectedDate);
    if ($dateObj) {
        $selectedDateFormatted = $dateObj->format('d-M-Y'); // Example: 28-Apr-2025
    }
}

// Fetch attendance count
if (!empty($selectedDateFormatted)) {
    // Fetch attendance records for selected date
    $attendanceStmt = $conn->prepare("SELECT COUNT(*) AS total_attendance FROM Attendance WHERE Date = ?");
    $attendanceStmt->bind_param("s", $selectedDateFormatted);
    $attendanceStmt->execute();
    $attendanceResult = $attendanceStmt->get_result();
    $attendanceData = $attendanceResult->fetch_assoc();
    $totalAttendance = $attendanceData['total_attendance'];
    $attendanceStmt->close();
} else {
    // Fetch total attendance without date filter
    $attendanceResult = $conn->query("SELECT COUNT(*) AS total_attendance FROM Attendance");
    $attendanceData = $attendanceResult->fetch_assoc();
    $totalAttendance = $attendanceData['total_attendance'];
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard | QR Attendance</title>
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
      padding: 20px;
    }

    .box {
      background: white;
      padding: 15px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
    }

    .box h3 {
      margin: 0 0 10px 0;
    }

    .nav-links a {
      display: inline-block;
      margin: 5px 10px 0 0;
      padding: 8px 12px;
      background: #3498db;
      color: white;
      text-decoration: none;
      font-size: 14px;
    }

    .nav-links a:hover {
      background: #2c80b4;
    }

    .logout {
      float: right;
      font-size: 14px;
      color: white;
    }
  </style>
</head>
<body>

  <div class="header">
    QR Attendance Dashboard
    <a class="logout" href="index.php">Logout</a>
  </div>

  <div class="container">
    <div class="box">
      <h3>Welcome Admin 👋</h3>
      <p>This is your dashboard summary.</p>
    </div>

    <div class="box">
      <h3>Total Students:</h3>
      <p><?php echo $totalStudents; ?></p> <!-- Dynamic Students Count -->
    </div>

    <div class="box">
      <h3>
        <?php
        if (!empty($selectedDateFormatted)) {
          echo "Attendance Records for: " . htmlspecialchars($selectedDateFormatted);
        } else {
          echo "Total Attendance Records (All Dates)";
        }
        ?>
      </h3>
      <p><?php echo $totalAttendance; ?></p>
    </div>

    <div class="box nav-links">
      <h3>Quick Links:</h3>
      <a href="add_student.php">Add Student</a>
      <a href="student_list.php">View Students</a>
      <a href="mark_attendance.php">Mark Attendance</a>
      <a href="attendance_report.php">View Report</a>
      <a href="qr_page.php" class="btn">Generate QR for Attendance</a>
    </div>
  </div>

</body>
</html>
