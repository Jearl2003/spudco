<?php
session_start();
require 'php/db_connect.php';

$result_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM results WHERE id = ? AND patient_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $result_id, $user_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if (!$result) {
    die("Not found or unauthorized.");
}

if (new DateTime() > new DateTime($result['expires_at'])) {
    die("<h3>❌ This result has expired. Contact clinic for copy.</h3>");
}
?>

<h2>Medical Result (Expires: <?= $result['expires_at'] ?>)</h2>
<a href="<?= $result['file_path'] ?>" class="btn" download>Download Result</a>