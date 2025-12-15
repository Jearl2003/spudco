<?php
// Correct path to PHPMailer autoload
require '../PHPMailer/PHPMailerAutoload.php';
require 'db_connect.php'; // Adjust path if needed

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_id = $_POST['patient_id'];
    $result_id = $_POST['result_id'];

    // Fetch patient email and result file path
    $sql = "SELECT u.email, r.file_path FROM results r JOIN users u ON r.patient_id = u.id WHERE r.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $result_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result) {
        // Initialize PHPMailer
        $mail = new PHPMailer\PHPMailer\PHPMailer(true); // Use FQCN with true for exceptions

        try {
            // SMTP Configuration
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'your_email@gmail.com';         // CHANGE ME
            $mail->Password   = 'your_app_password_here';       // App Password (not Gmail password)
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            // Recipients
            $mail->setFrom('clinic@health.com', 'Health Clinic');
            $mail->addAddress($result['email']);

            // Attachment
            $mail->addAttachment($result['file_path']);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Your Medical Result is Ready';
            $mail->Body    = "
                <h3>Hello,</h3>
                <p>Your medical result has been uploaded and is available for 24 hours.</p>
                <p><strong>Important:</strong> This file will expire in 24 hours. Please download it now.</p>
                <p>Thank you.</p>
            ";

            // Send email
            if ($mail->send()) {
                echo "Email sent successfully.";
            }
        } catch (Exception $e) {
            echo "Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "No result found for the given ID.";
    }
}
?>