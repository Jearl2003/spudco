<?php
session_start();
require 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get POST data
$patient_id = $_SESSION['user_id'] ?? null;
$full_name = $_POST['full_name'] ?? '';
$tests = $_POST['tests'] ?? [];
$requested_by = $_POST['requested_by'] ?? '';
$appointment_date = $_POST['appointment_date'] ?? '';
$appointment_time = $_POST['appointment_time'] ?? '';

// Validate required fields
if (!$patient_id || empty($full_name) || empty($tests) || empty($requested_by) || empty($appointment_date) || empty($appointment_time)) {
    header("Location: patient_dashboard.php?tab=appointments&error=Please fill all required fields.");
    exit;
}

// Combine selected tests into one string
$test_type = implode(', ', $tests);
$status = 'pending';

try {
    // Insert into database
    $stmt = $conn->prepare("INSERT INTO appointments (patient_id, full_name, test_type, requested_by, appointment_date, appointment_time, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss", $patient_id, $full_name, $test_type, $requested_by, $appointment_date, $appointment_time, $status);

    if ($stmt->execute()) {
        header("Location: patient_dashboard.php?tab=home&msg=Appointment booked successfully!");
    } else {
        header("Location: patient_dashboard.php?tab=appointments&error=Database error. Failed to book.");
    }
} catch (Exception $e) {
    header("Location: patient_dashboard.php?tab=appointments&error=An error occurred.");
}


exit;