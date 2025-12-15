<?php
session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check in admins table
    $stmt = $conn->prepare("SELECT id, full_name, password FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Compare plain text password (for testing)
        if ($password === $row['password']) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['full_name'] = $row['full_name'];

            // ✅ Redirect with success message (no pop-up)
            header("Location: admin_login.php?success=logged_in");
            exit;
        } else {
            // ❌ Wrong password → redirect with error
            header("Location: admin_login.php?error=wrong_password");
            exit;
        }
    } else {
        // ❌ Admin not found → redirect with error
        header("Location:admin_login.php?error=not_found");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Staff Login | Health Clinic</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #0d6efd;
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
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        /* Messages */
        .success-msg {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 0.95rem;
            text-align: center;
            border: 1px solid #c3e6cb;
        }

        .error-msg {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 0.95rem;
            text-align: center;
            border: 1px solid #f5c6cb;
        }

        h2 {
            color: #0d6efd;
            margin-bottom: 20px;
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
        button {
            background: #0d6efd;
            color: white;
            padding: 12px;
            border: none;
            width: 100%;
            font-size: 1rem;
            border-radius: 6px;
            cursor: pointer;
        }
        .back-link {
            display: block;
            margin-top: 20px;
            color: #0d6efd;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Staff Login</h2>

        <!-- Show messages -->
        <?php if (isset($_GET['success'])): ?>
            <div class="success-msg">🎉 Admin login successful! Please Wait..</div>
            <script>setTimeout(function() { window.location.href = 'admin_dashboard.php'; }, 1000);</script>
        <?php elseif (isset($_GET['error'])): ?>
            <?php if ($_GET['error'] == 'not_found'): ?>
                <div class="error-msg">❌ Admin not found. Please check your email.</div>
            <?php elseif ($_GET['error'] == 'wrong_password'): ?>
                <div class="error-msg">❌ Incorrect password. Please try again.</div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Login Form -->
        <form action="admin_login.php" method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="admin@clinic.com" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter password" required>
            </div>
            <button type="submit">Login as Staff</button>
        </form>

        <a href="index.php" class="back-link">← Back to Home</a>
    </div>
</body>
</html>