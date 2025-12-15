<?php
// Show errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        die("<p style='color:red; text-align:center;'>📧 Email already registered. <a href='javascript:history.back()'>Go back</a>.</p>");
    }

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $full_name, $email, $password);

    if ($stmt->execute()) {
        // ✅ Redirect to login.php with success message
        header("Location: login.php?msg=Registered successfully! Please log in.");
        exit;
    } else {
        echo "<p style='color:red; text-align:center;'>❌ Error: " . $stmt->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Patient Registration | Health Clinic System</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- External CSS -->
    <link rel="stylesheet" href="css/style.css" />

    <!-- Inline Style for Form -->
    <style>

        body {
            font-family: 'Segoe UI', sans-serif;;
        }
        .form-container {
            max-width: 500px;
            margin: 20px auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 6px 16px rgba(0,0,0,0.1);
        }

        .form-container h2 {
            color: #0d6efd;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1rem;
            transition: border 0.3s;
        }

        .form-group input:focus {
            border-color: #0d6efd;
            outline: none;
        }

        .btn-register {
            background: #0d6efd;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
            font-weight: 600;
            transition: background 0.3s;
        }

        .btn-register:hover {
            background: #0b5ed7;
            transform: translateY(-2px);
        }

        .back-link {
            display: block;
            text-align: center;
            margin: 15px 0;
            color: #0d6efd;
            text-decoration: none;
            font-weight: 500;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .login-link {
            text-align: center;
            margin-top: 15px;
            font-size: 0.95rem;
            color: #555;
        }

        .login-link a {
            color: #0d6efd;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- Back to Home -->
    <a href="index.php" class="back-link">← Back to Home</a>

    <!-- Registration Form -->
    <div class="form-container">
        <h2>Patient Registration</h2>

        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" placeholder="Enter your full name" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Create a password" required>
            </div>

            <button type="submit" class="btn-register"> Register Now</button>
        </form>

        <div class="login-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>

    <!-- Footer -->
    <footer style="text-align: center; margin-top: 40px; color: #777; font-size: 0.9rem;">
        &copy; <?php echo date("Y"); ?> Health Clinic Appointment and Result Management System
    </footer>

    <!-- External JS -->
    <script src="js/script.js"></script>

</body>
</html>