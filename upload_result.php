<?php
require 'db_connect.php';
session_start();

$patient_id = $_POST['patient_id'];
$file = $_FILES['result_file'];
$target = "uploads/" . time() . "_" . basename($file['name']);
move_uploaded_file($file['tmp_name'], $target);

$expires_at = date('Y-m-d H:i:s', strtotime('+1 day'));

$stmt = $conn->prepare("INSERT INTO results (patient_id, file_path, expires_at) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $patient_id, $target, $expires_at);
$stmt->execute();

// Trigger one-time email
$_POST['patient_id'] = $patient_id;
$_POST['result_id'] = $conn->insert_id;
include 'send_email.php'; // Send immediately
?>