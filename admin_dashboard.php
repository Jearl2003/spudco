<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: admin_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Admin Dashboard | Health Clinic</title>
    <link rel="stylesheet" href="css/style.css" />
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 60px auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            text-align: center;
        }
        h2 {
            color: #0d6efd;
        }
        .welcome {
            font-size: 1.4rem;
            margin: 20px 0;
        }
        ul {
            text-align: left;
            margin-top: 20px;
        }
        ul li {
            margin: 10px 0;
        }
        ul li a {
            color: #0d6efd;
            text-decoration: none;
            font-weight: 500;
        }
        ul li a:hover {
            text-decoration: underline;
        }
        .logout-btn {
            display: inline-block;
            margin-top: 20px;
            color: red;
            font-weight: 500;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Dashboard</h2>
        <p class="welcome">Welcome, <strong>Admin <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</strong></p>
        <p><em>What's new? You now have full access to manage appointments and upload results.</em></p>

        <ul>
            <li><a href="#">View Appointments</a></li>
            <li><a href="#">Upload Medical Results</a></li>
            <li><a href="#">Manage Doctors</a></li>
        </ul>

        <br>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</body>
</html>