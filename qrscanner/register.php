<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'attendanceList';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $passwordInput = $_POST['password'];

    // Hash password before saving
    $hashedPassword = password_hash($passwordInput, PASSWORD_DEFAULT);

    // Insert into teachers table
    $stmt = $conn->prepare("INSERT INTO teachers (username, password) VALUES (?, ?)");
    if ($stmt) {
        $stmt->bind_param("ss", $username, $hashedPassword);
        if ($stmt->execute()) {
            $success = "✅ Registered Successfully!";
        } else {
            $error = "❌ Username already exists!";
        }
        $stmt->close();
    } else {
        $error = "❌ Error preparing SQL.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register | QR Attendance</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f0f2f5;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .login-container {
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 400px;
    }
    .login-container h2 {
      text-align: center;
      margin-bottom: 25px;
    }
    input[type="text"], input[type="password"] {
      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 8px;
    }
    button {
      width: 100%;
      padding: 12px;
      background: #3498db;
      border: none;
      border-radius: 8px;
      color: white;
      font-size: 16px;
      cursor: pointer;
    }
    button:hover {
      background: #2980b9;
    }
    .error, .success {
      text-align: center;
      margin-bottom: 10px;
      font-weight: bold;
    }
    .error {
      color: red;
    }
    .success {
      color: green;
    }
    .switch-link {
      text-align: center;
      margin-top: 15px;
    }
    .switch-link a {
      text-decoration: none;
      color: #3498db;
    }
    .switch-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="login-container">
  <h2>Register New Teacher</h2>

  <?php 
    if (!empty($success)) echo "<div class='success'>$success</div>";
    if (!empty($error)) echo "<div class='error'>$error</div>";
  ?>

  <form method="POST" action="">
    <input type="text" name="username" placeholder="Enter Username" required />
    <input type="password" name="password" placeholder="Enter Password" required />
    <button type="submit">Register</button>
  </form>

  <div class="switch-link">
    <a href="index.php">⬅ Back to Login</a>
  </div>
</div>

</body>
</html>
