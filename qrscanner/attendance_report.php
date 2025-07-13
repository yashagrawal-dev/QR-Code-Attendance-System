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

// Check if a date is selected
if (empty($selectedDate)) {
  $selectedDate = date('Y-m-d'); // today's date
}

$selectedDate = isset($_GET['date']) ? $_GET['date'] : '';

$selectedDateFormatted = '';
if (!empty($selectedDate)) {
    $dateObj = DateTime::createFromFormat('Y-m-d', $selectedDate);
    if ($dateObj) {
        $selectedDateFormatted = $dateObj->format('d-M-Y'); // Example: 28-Apr-2025
    }
}

// Prepare query
if (!empty($selectedDateFormatted)) {
    $sql = "SELECT Date, Rollno, Name, Course, Status FROM Attendance WHERE Date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selectedDateFormatted);
} else {
    $sql = "SELECT Date, Rollno, Name, Course, Status FROM Attendance ORDER BY Date ASC";
    $stmt = $conn->prepare($sql);
}

// Execute and get result
$stmt->execute();
$result = $stmt->get_result();

?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Attendance Report | QR Attendance</title>
  <style>
    /* your existing CSS here */
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
      max-width: 900px;
      margin: 30px auto;
      background: white;
      padding: 20px;
      border-radius: 6px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    form {
      text-align: center;
      margin-bottom: 20px;
    }

    input[type="date"] {
      padding: 8px;
      font-size: 16px;
    }

    input[type="submit"] {
      padding: 8px 16px;
      font-size: 16px;
      background-color: #333;
      color: white;
      border: none;
      cursor: pointer;
    }

    input[type="submit"]:hover {
      background-color: #555;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      padding: 10px;
      border-bottom: 1px solid #ccc;
      text-align: left;
    }

    th {
      background-color: #f5f5f5;
    }

    .status-present {
      color: green;
      font-weight: bold;
    }

    .status-absent {
      color: red;
      font-weight: bold;
    }

    .back-link {
      text-align: center;
      margin-top: 20px;
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

  <div class="header">Attendance Report</div>

  <div class="container">
    <h2>Daily Attendance Summary</h2>

    <!-- Date Selection Form -->
    <form method="GET" action="">
      <label for="date">Select Date: </label>
      <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($selectedDate); ?>">
      <input type="submit" value="Filter">
    </form>

    <table>
      <thead>
        <tr>
          <th>Sr No</th>
          <th>Date</th>
          <th>Roll Number</th>
          <th>Name</th>
          <th>Course</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
      <?php
        if ($result->num_rows > 0) {
            $sr_no = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$sr_no}</td>
                        <td>{$row['Date']}</td>
                        <td>{$row['Rollno']}</td>
                        <td>{$row['Name']}</td>
                        <td>{$row['Course']}</td>
                        <td>" . 
                            ($row['Status'] == 'Present' ? '<span class="status-present">Present</span>' : '<span class="status-absent">Absent</span>') . 
                        "</td>
                    </tr>";
                $sr_no++;
            }
        } else {
            echo "<tr><td colspan='6'>No attendance records found for this date.</td></tr>";
        }

        $stmt->close();
        $conn->close();
      ?>
      </tbody>
    </table>

    <div class="back-link">
      <a href="dashboard.php">⬅ Back to Dashboard</a>
    </div>
  </div>

</body>
</html>