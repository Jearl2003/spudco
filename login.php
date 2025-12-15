<?php
session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if user exists
    $stmt = $conn->prepare("SELECT id, full_name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            // Login successful
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['full_name'] = $row['full_name'];

            // ✅ Check if patient info exists
            $stmt = $conn->prepare("SELECT id FROM patient_info WHERE user_id = ?");
            $stmt->bind_param("i", $row['id']);
            $stmt->execute();
            $info_result = $stmt->get_result();

            if ($info_result->num_rows > 0) {
                header("Location:patient_dashboard.php");
            } else {
                header("Location:patient_info.php");
            }
            exit;
        }
    }

    // ❌ Invalid credentials → redirect with error
    header("Location:login.php?error=invalid");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Health Clinic</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .form-container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        h2 {
            color: #0d6efd;
        }
        .alert {
            padding: 12px;
            margin-bottom: 20px;
            background: #d4edda;
            color: #155724;
            border-radius: 6px;
            font-size: 0.95rem;
        }
        .alert.error {
            background: #f8d7da;
            color: #721c24;
        }
        .form-group {
            margin-bottom: 18px;
            text-align: left;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
        }
        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        .btn-login {
            background: #0d6efd;
            color: white;
            padding: 12px;
            border: none;
            width: 100%;
            font-size: 1rem;
            border-radius: 6px;
            cursor: pointer;
        }
        .register-link {
            margin-top: 15px;
            font-size: 0.95rem;
        }
        .register-link a {
            color: #0d6efd;
            text-decoration: none;
        }
        .back-link {
            display: block;
            margin-top: 20px;
            color: #0d6efd;
            text-decoration: none;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Login to Your Account</h2>

        <!-- ✅ Show error message at the top -->
        <?php if (isset($_GET['error'])): ?>
            <div class="alert error">
                ❌ Incorrect email or password. Please try again.
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn-login">Login</button>
        </form>

        <div class="register-link">
            Don't have an account? <a href="register.php">Register as Patient</a>
        </div>

        <a href="index.php" class="back-link">← Back to Home</a>
    </div>
</body>
</html>