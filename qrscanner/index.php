<?php
// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'attendanceList';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $passwordInput = $_POST['password'];

    // Search for teacher in database
    $stmt = $conn->prepare("SELECT * FROM teachers WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $teacher = $result->fetch_assoc();

        // Verify password
        if (password_verify($passwordInput, $teacher['password'])) {
            // Login successful
            session_start();
            $_SESSION['teacher_username'] = $teacher['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "❌ Invalid password!";
        }
    } else {
        $error = "❌ No such username found!";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login | QR Attendance</title>
  <style>
   body {
  font-family: 'Segoe UI', sans-serif;
  background: linear-gradient(135deg, #74ebd5, #acb6e5, #f9d423, #ff4e50);
  background-size: 400% 400%;
  animation: gradientMove 15s ease infinite;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  margin: 0;
}

/* Moving gradient animation */
@keyframes gradientMove {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
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
      background: #4caf50;
      border: none;
      border-radius: 8px;
      color: white;
      font-size: 16px;
      cursor: pointer;
    }
    button:hover {
      background: #45a049;
    }
    .error {
      color: red;
      text-align: center;
      margin-bottom: 10px;
    }
    .password-container {
  position: relative;
}

.password-container input[type="password"],
.password-container input[type="text"] {
  width: 100%;
  padding-right: 15px; /* space for eye */
}

.toggle-password {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
  font-size: 18px;
  color: #888;
}


  </style>
</head>
<body>

<div class="login-container">
  <h2>Admin Login</h2>

  <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>

  <form method="POST" action="">
    <input type="text" name="username" placeholder="admin" required />
    <div class="password-container">
  <input type="password" id="password" name="password" placeholder="admin123" required />
  <span class="toggle-password" onclick="togglePassword()">👁️</span>
</div>

    <button type="submit">Login</button>
  </form>
      <div class="register-link">
        New here? <a href="register.php">Create an account</a>
      </div>
  

</div>
<script>
function togglePassword() {
  const passwordInput = document.getElementById("password");
  if (passwordInput.type === "password") {
    passwordInput.type = "text";
  } else {
    passwordInput.type = "password";
  }
}
</script>







</body>
</html>
