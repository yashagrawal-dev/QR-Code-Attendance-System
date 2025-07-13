<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Mark Attendance | QR Attendance</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #eaeaea;
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
      margin: 30px auto;
      padding: 20px;
      background-color: white;
      border-radius: 6px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }

    input[type="text"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    button {
      width: 100%;
      padding: 10px;
      background-color: #3498db;
      color: white;
      border: none;
      font-size: 16px;
      border-radius: 4px;
      cursor: pointer;
    }

    .message {
      text-align: center;
      margin: 10px 0;
      font-weight: bold;
    }
  </style>
</head>
<body>

<?php

session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$success = "";
$error = "";
$attendanceMarked = false;

// ✅ Check if already marked in this session (from same device/browser)
if (isset($_SESSION['attendance_done']) && $_SESSION['attendance_done'] === true) {
    $success = "✅ Attendance already marked from this device.";
    $attendanceMarked = true;
}
elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "attendanceList";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        $error = "Connection failed: " . $conn->connect_error;
    } else {
        $roll = $conn->real_escape_string($_POST['roll_no']);
        $date = strtoupper(date('d-M-Y'));

        // ✅ Check if already marked
        $checkQuery = "SELECT * FROM Attendance WHERE Rollno = '$roll' AND Date = '$date'";
        $checkResult = $conn->query($checkQuery);

        if ($checkResult && $checkResult->num_rows > 0) {
            $success = "✅ Attendance for today is already marked for this Roll Number.";
            $attendanceMarked = true;
            $_SESSION['attendance_done'] = true; // mark session too
        }
        else
        { 
            // Get name and course from Students table
            $query = "SELECT Name, Course FROM Students WHERE RollNo = '$roll' LIMIT 1";
            $result = $conn->query($query);

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $name = $conn->real_escape_string($row['Name']);
                $course = $conn->real_escape_string($row['Course']);

                // Today's date in d-M-Y format
                $status = "Present";

                $sql = "INSERT INTO Attendance (Date, Rollno, Name, Course, Status) 
                        VALUES ('$date', '$roll', '$name', '$course', '$status')";

                if ($conn->query($sql) === TRUE) {
                    $success = "Attendance marked for $name ($roll)";
                    $attendanceMarked = true;
                    $_SESSION['attendance_done'] = true; // mark as completed
                } else {
                    $error = "Error: " . $conn->error;
                }
            } else {
                $error = "Roll number not found.";
            }
        }
        $conn->close();
    }
}
?>

  <div class="header">Mark Attendance</div>

  <div class="container">
    <h2>Attendance Form</h2>
    <?php if ($success): ?>
      <div class="success"><?= $success ?></div>
    <?php elseif ($error): ?>
      <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <?php if (!$attendanceMarked): ?>
    <form method="POST" action="">

      <label for="roll_no">Roll Number:</label>
      <input type="text" id="roll_no" name="roll_no" placeholder="Enter roll number" required>

      <label for="status">Status:</label>
      <select id="status" name="status">
        <option value="Present">Present</option>
      </select>

      <button type="submit">Mark Attendance</button>
    </form>
    <?php endif; ?>
  </div>


</body>
</html>
