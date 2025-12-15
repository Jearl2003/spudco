<?php
session_start();
require 'db_connect.php'; // make sure $conn = new mysqli(...);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if info already exists
$stmt = $conn->prepare("SELECT * FROM patient_info WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'] ?? '';
    $last_name = $_POST['last_name'];
    $suffix = $_POST['suffix'] ?? '';
    $birthday = $_POST['birthday'];
    $age = (int)$_POST['age'];
    $sex = $_POST['sex'];
    $house_no = $_POST['house_no'];
    $district = $_POST['district'];
    $city = $_POST['city'];
    $zip_code = $_POST['zip_code'];
    $province = $_POST['province'];
    $status = $_POST['status'];
    $phone = $_POST['phone'];
    $requesting_company = $_POST['requesting_company'] ?? '';

    if ($patient) {
        // Update
        $stmt = $conn->prepare("UPDATE patient_info 
            SET first_name=?, middle_name=?, last_name=?, suffix=?, birthday=?, age=?, sex=?, 
                house_no=?, district=?, city=?, zip_code=?, province=?, status=?, phone=?, requesting_company=? 
            WHERE user_id=?");
        $stmt->bind_param("sssssisississssi",
            $first_name, $middle_name, $last_name, $suffix, $birthday, $age, $sex,
            $house_no, $district, $city, $zip_code, $province, $status, $phone, $requesting_company,
            $user_id
        );
        $stmt->execute();
        $stmt->close();
    } else {
        // Insert
        $stmt = $conn->prepare("INSERT INTO patient_info 
            (user_id, first_name, middle_name, last_name, suffix, birthday, age, sex, 
             house_no, district, city, zip_code, province, status, phone, requesting_company) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssissississss",
            $user_id, $first_name, $middle_name, $last_name, $suffix, $birthday, $age, $sex,
            $house_no, $district, $city, $zip_code, $province, $status, $phone, $requesting_company
        );
        $stmt->execute();
        $stmt->close();
    }

    header("Location: patient_dashboard.php?tab=profile&msg=Patient information saved successfully.");
    exit;
}
?>

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Information | San Francisco Diagnostic & Medical Clinic</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 20px;
        }
        .clinic-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 10px;
        }
        .clinic-name {
            font-size: 1.4rem;
            font-weight: 600;
            color: #0d6efd;
            margin: 5px 0;
        }
        .clinic-address {
            font-size: 0.9rem;
            color: #555;
            margin: 5px 0;
        }
        .clinic-contact {
            font-size: 0.9rem;
            color: #333;
            margin: 5px 0;
        }
        .form-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 1.3rem;
        }
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1rem;
        }
        .btn-save {
            background: #0d6efd;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            width: 100%;
            margin-top: 10px;
        }
        .alert {
            padding: 12px;
            margin-bottom: 20px;
            background: #d4edda;
            color: #155724;
            border-radius: 6px;
            text-align: center;
        }
        .form-row {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        .form-row .form-group {
            flex: 1;
            min-width: 200px;
        }
    </style>
    <script>
        function calculateAge() {
            const dob = new Date(document.querySelector('input[name="birthday"]').value);
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const monthDiff = today.getMonth() - dob.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                age--;
            }

            document.getElementById('age').value = age;
        }
    </script>
</head>
<body>
    <div class="clinic-header">
        <div class="clinic-name">San Francisco Diagnostic & Medical Clinic</div>
        <div class="clinic-address">
            SVDP Arcade, John Bosco District, Mgy, Bislig City, Surigao del Sur
        </div>
        <div class="clinic-contact">
            Tel No: (086) 645-0741 | Email: bisligdiagnostic@yahoo.com
        </div>
    </div>

    <div class="form-container">
        <h2>PATIENT INFORMATION FORM</h2>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert"><?php echo htmlspecialchars($_GET['msg']); ?></div>
        <?php endif; ?>

     <form method="POST" onsubmit="calculateAge()">
    <!-- Full Name -->
    <div class="form-row">
        <div class="form-group">
            <input type="text" name="first_name" value="<?php echo htmlspecialchars($patient['first_name'] ?? ''); ?>" placeholder="First Name" required>
        </div>
        <div class="form-group">
            <input type="text" name="middle_name" value="<?php echo htmlspecialchars($patient['middle_name'] ?? ''); ?>" placeholder="Middle Name">
        </div>
        <div class="form-group">
            <input type="text" name="last_name" value="<?php echo htmlspecialchars($patient['last_name'] ?? ''); ?>" placeholder="Last Name" required>
        </div>
    </div>

    <!-- Suffix (Optional) -->
    <div class="form-group">
        <input type="text" name="suffix" value="<?php echo htmlspecialchars($patient['suffix'] ?? ''); ?>" placeholder="Suffix (e.g. Jr., Sr.)" style="width:100%; padding:12px; border:1px solid #ccc; border-radius:6px;">
    </div>

    <!-- Birthday & Age -->
    <div class="form-row">
        <div class="form-group">
            <input type="date" name="birthday" value="<?php echo htmlspecialchars($patient['birthday'] ?? ''); ?>" required onchange="calculateAge()">
        </div>
        <div class="form-group">
            <input type="number" name="age" id="age" value="<?php echo htmlspecialchars($patient['age'] ?? ''); ?>" readonly placeholder="Age" style="width:100%; padding:12px; border:1px solid #ccc; border-radius:6px;">
        </div>
    </div>

    <!-- Sex & Status -->
    <div class="form-row">
        <div class="form-group">
            <select name="sex" required style="width:100%; padding:12px; border:1px solid #ccc; border-radius:6px;">
                <option value="">Select</option>
                <option value="Male" <?php echo (isset($patient['sex']) && $patient['sex'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo (isset($patient['sex']) && $patient['sex'] == 'Female') ? 'selected' : ''; ?>>Female</option>
            </select>
        </div>
        <div class="form-group">
            <input type="text" name="status" value="<?php echo htmlspecialchars($patient['status'] ?? ''); ?>" placeholder="Status (e.g. Single, Married)" required style="width:100%; padding:12px; border:1px solid #ccc; border-radius:6px;">
        </div>
    </div>

    <!-- Phone -->
    <div class="form-group">
        <input type="text" name="phone" value="<?php echo htmlspecialchars($patient['phone'] ?? ''); ?>" placeholder="Phone Number" required style="width:100%; padding:12px; border:1px solid #ccc; border-radius:6px;">
    </div>

    <!-- Address -->
    <h4 style="margin:20px 0 10px; color:#333;">Address</h4>
    <div class="form-row">
        <div class="form-group">
            <input type="text" name="house_no" value="<?php echo htmlspecialchars($patient['house_no'] ?? ''); ?>" placeholder="House No & Street" required style="width:100%; padding:12px; border:1px solid #ccc; border-radius:6px;">
        </div>
        <div class="form-group">
            <input type="text" name="district" value="<?php echo htmlspecialchars($patient['district'] ?? ''); ?>" placeholder="Barangay" required style="width:100%; padding:12px; border:1px solid #ccc; border-radius:6px;">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <input type="text" name="city" value="<?php echo htmlspecialchars($patient['city'] ?? ''); ?>" placeholder="City" required style="width:100%; padding:12px; border:1px solid #ccc; border-radius:6px;">
        </div>
        <div class="form-group">
            <input type="text" name="zip_code" value="<?php echo htmlspecialchars($patient['zip_code'] ?? ''); ?>" placeholder="Zip Code" required style="width:100%; padding:12px; border:1px solid #ccc; border-radius:6px;">
        </div>
        <div class="form-group">
            <input type="text" name="province" value="<?php echo htmlspecialchars($patient['province'] ?? ''); ?>" placeholder="Province" required style="width:100%; padding:12px; border:1px solid #ccc; border-radius:6px;">
        </div>
    </div>

    <!-- Requesting Company -->
    <div class="form-group">
        <input type="text" name="requesting_company" value="<?php echo htmlspecialchars($patient['requesting_company'] ?? ''); ?>" placeholder="Requesting Company (Optional)" style="width:100%; padding:12px; border:1px solid #ccc; border-radius:6px;">
    </div>

    <button type="submit" class="btn-save">Save Information</button>
</form>
    </div>
</body>
</html>