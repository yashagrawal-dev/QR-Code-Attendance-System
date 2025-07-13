<?php
$success = "";
$error = "";
$attendanceMarked = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "attendanceList");
    if ($conn->connect_error) {
        $error = "Connection failed: " . $conn->connect_error;
    } else {
        $roll = $conn->real_escape_string($_POST['roll_no']);
        $status = "Present";

        // Get name and course from Students table
        $studentQuery = "SELECT Name, Course FROM Students WHERE RollNo = '$roll' LIMIT 1";
        $studentResult = $conn->query($studentQuery);

        if ($studentResult && $studentResult->num_rows > 0) {
            $student = $studentResult->fetch_assoc();
            $name = $conn->real_escape_string($student['Name']);
            $course = $conn->real_escape_string($student['Course']);

            $date = strtoupper(date('d-M-Y'));

            // Insert attendance
            $insert = "INSERT INTO Attendance (Date, Rollno, Name, Course, Status)
                       VALUES ('$date', '$roll', '$name', '$course', '$status')";

            if ($conn->query($insert) === TRUE) {
                $success = "Attendance marked as Present.";
                $attendanceMarked = true;
            } else {
                $error = "Error: " . $conn->error;
            }
        } else {
            $error = "Roll number not found.";
        }
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Mark Attendance</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      padding: 40px;
      text-align: center;
    }
    .form-container {
      max-width: 400px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    input {
      width: 100%;
      padding: 12px;
      margin: 15px 0;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    button {
      padding: 12px;
      width: 100%;
      background: #3498db;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    button:hover {
      background: #2980b9;
    }
    .success, .error {
      margin-top: 20px;
      font-weight: bold;
    }
    .success {
      color: green;
    }
    .error {
      color: red;
    }
  </style>
</head>
<body>

  <div class="form-container">
    <h2>Mark Your Attendance</h2>

    <?php if ($success): ?>
      <div class="success"><?= $success ?></div>
    <?php elseif ($error): ?>
      <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <?php if (!$attendanceMarked): ?>
      <form method="POST">
        <input type="text" name="roll_no" placeholder="Enter Roll Number" required>
        <button type="submit">Mark Present</button>
      </form>
    <?php endif; ?>
  </div>

</body>
</html>
