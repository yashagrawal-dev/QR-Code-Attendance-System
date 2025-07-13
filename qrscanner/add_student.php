<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Add Student | QR Attendance</title>
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
      padding: 20px;
      max-width: 500px;
      margin: auto;
      background: white;
      border: 1px solid #ccc;
      border-radius: 6px;
      margin-top: 30px;
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }

    input[type="text"], input[type="email"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    button {
      padding: 10px 20px;
      background-color: #3498db;
      border: none;
      color: white;
      font-size: 16px;
      border-radius: 4px;
      cursor: pointer;
      width: 100%;
    }

    button:hover {
      background-color: #2c80b4;
    }

    .back-link {
      text-align: center;
      margin-top: 15px;
    }

    .back-link a {
      color: #333;
      text-decoration: none;
    }

    .back-link a:hover {
      text-decoration: underline;
    }

    .success {
      background-color: #d4edda;
      color: #155724;
      padding: 10px;
      border-radius: 4px;
      margin-bottom: 15px;
    }

    .error {
      background-color: #f8d7da;
      color: #721c24;
      padding: 10px;
      border-radius: 4px;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>

<?php
$success = "";
$error = "";

// Only run if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database credentials
    $servername = "localhost";
    $username = "root";
    $password = ""; // or your password
    $dbname = "attendanceList"; // change this to your DB name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        $error = "Connection failed: " . $conn->connect_error;
    } else {
        // Escape inputs
        $name = $conn->real_escape_string($_POST['name']);
        $roll = $conn->real_escape_string($_POST['roll']);
        $email = $conn->real_escape_string($_POST['email']);
        $course = $conn->real_escape_string($_POST['course']);

        // Insert query
        $sql = "INSERT INTO Students (RollNo, Name, Email, Course) VALUES ('$roll', '$name', '$email', '$course')";

        if ($conn->query($sql) === TRUE) {
            $success = "Student added successfully!";
        } else {
            $error = "Error: " . $conn->error;
        }

        $conn->close();
    }
}
?>

  <div class="header">Add Student</div>

  <div class="container">
    <h2>Student Information</h2>

    <?php if ($success): ?>
      <div class="success"><?= $success ?></div>
    <?php elseif ($error): ?>
      <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <label for="name">Full Name:</label>
      <input type="text" id="name" name="name" placeholder="Enter full name" required>

      <label for="roll">Roll Number:</label>
      <input type="text" id="roll" name="roll" placeholder="Enter roll number" required>

      <label for="email">Email:</label>
      <input type="email" id="email" name="email" placeholder="Enter email" required>

      <label for="course">Course:</label>
      <input type="text" id="course" name="course" placeholder="Enter course name" required>

      <button type="submit">Add Student</button>
    </form>

    <div class="back-link">
      <a href="dashboard.php">⬅ Back to Dashboard</a>
    </div>
  </div>

</body>
</html>
