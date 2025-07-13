<?php
// Database connection
$host = 'localhost'; // Change if using a different host
$user = 'root'; // Your DB user
$password = ''; // Your DB password
$dbname = 'attendanceList'; // Your DB name

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// Fetch students using a prepared statement
$sql = "SELECT RollNo, Name, Email, Course FROM Students"; // Modify column names as per your DB
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student List | QR Attendance</title>
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
      max-width: 800px;
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

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th, td {
      padding: 10px;
      text-align: left;
      border-bottom: 1px solid #ccc;
    }

    th {
      background-color: #f5f5f5;
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

  <div class="header">Student List</div>

  <div class="container">
    <h2>All Registered Students</h2>
    <table>
      <thead>
        <tr>
          <th>Sr. No</th>
          <th>Name</th>
          <th>Roll Number</th>
          <th>Email</th>
          <th>Course</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($result->num_rows > 0) {
            // Output each row with dynamic Sr. No
            $sr_no = 1; // Start the serial number from 1
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$sr_no}</td>
                        <td>{$row['Name']}</td>
                        <td>{$row['RollNo']}</td>
                        <td>{$row['Email']}</td>
                        <td>{$row['Course']}</td>
                    </tr>";
                $sr_no++; // Increment the serial number
            }
        } else {
            echo "<tr><td colspan='5'>No students found.</td></tr>";
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
