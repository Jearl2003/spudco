<?php
session_start();
require 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Patient name
$stmt = $conn->prepare("SELECT full_name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();
$stmt->close();

// Appointments
$stmt = $conn->prepare("SELECT * FROM appointments WHERE patient_id = ? ORDER BY appointment_date DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$appointments = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Patient Info
$patient_info = [];
$stmt = $conn->prepare("SELECT * FROM patient_info WHERE user_id = ?");
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $patient_info = $result->fetch_assoc();
    }
    $stmt->close();
}

// Lab results
$stmt = $conn->prepare("SELECT * FROM lab_tests WHERE patient_id = ? ORDER BY uploaded_at DESC");
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $results = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    $results = [];
}

// Billing
$stmt = $conn->prepare("SELECT * FROM billing WHERE patient_id = ? ORDER BY created_at DESC");
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $bills = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    $bills = [];
}

$active_tab = $_GET['tab'] ?? 'home';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Patient Dashboard | Capstone</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Styles -->
  <style>
    * {margin:0; padding:0; box-sizing:border-box;}
    body {
      font-family: 'Segoe UI', sans-serif;
      background:#f4f7fb;
      color:#333;
      display:flex;
    }
    /* Sidebar */
    .sidebar {
      width:260px;
      background:#0d6efd;
      color:#fff;
      min-height:100vh;
      padding:20px;
      position:fixed;
      transition:all 0.3s;
    }
    .sidebar-header {
      text-align:center;
      margin-bottom:20px;
    }
    .sidebar-header img {width:60px; border-radius:50%;}
    .sidebar a {
      display:flex; align-items:center;
      padding:12px 15px;
      border-radius:8px;
      color:white;
      margin-bottom:10px;
      transition:0.3s;
    }
    .sidebar a:hover, .sidebar a.active {
      background:rgba(255,255,255,0.2);
    }
    .sidebar i {margin-right:12px;}
    /* Main */
    .main-content {
      flex:1;
      margin-left:260px;
      padding:25px;
      transition:all 0.3s;
    }
    .welcome-banner {
      background:linear-gradient(135deg,#0d6efd,#007bff);
      color:white;
      padding:35px;
      border-radius:12px;
      margin-bottom:30px;
      text-align:center;
      box-shadow:0 6px 18px rgba(0,0,0,0.15);
    }
    .section {
      background:white;
      border-radius:12px;
      padding:25px;
      margin-bottom:25px;
      box-shadow:0 4px 12px rgba(0,0,0,0.05);
    }
    .section-title {
      font-size:1.3rem;
      font-weight:600;
      color:#0d6efd;
      margin-bottom:15px;
      display:flex;
      align-items:center;
      gap:8px;
    }
    table {
      width:100%;
      border-collapse:collapse;
      margin-top:15px;
    }
    th,td {
      padding:12px;
      border-bottom:1px solid #eee;
      text-align:left;
    }
    th {background:#0d6efd; color:white; font-weight:500;}
    tr:hover {background:#f9fafc;}
    .badge {
      padding:6px 12px;
      border-radius:8px;
      color:#fff;
      font-size:0.85rem;
    }
    .pending{background:#ffc107;}
    .confirmed{background:#0d6efd;}
    .completed,.paid{background:#198754;}
    .unpaid{background:#dc3545;}
    .tab-content{display:none;}
    .tab-content.active{display:block;}
    /* Buttons */
    .btn {
      padding:12px 20px;
      border:none;
      border-radius:8px;
      font-size:1rem;
      cursor:pointer;
      transition:0.2s;
    }
    .btn-primary {background:#0d6efd; color:white;}
    .btn-primary:hover {background:#0b5ed7;}
    /* Modals */
    .modal {
      display:none; position:fixed;
      top:0; left:0; width:100%; height:100%;
      background:rgba(0,0,0,0.5);
      justify-content:center; align-items:center;
      z-index:1000;
    }
    .modal-content {
      background:white;
      padding:25px;
      border-radius:12px;
      text-align:center;
      max-width:400px;
      width:90%;
      box-shadow:0 6px 18px rgba(0,0,0,0.2);
    }
    @media(max-width:768px){
      .sidebar{width:100%; height:auto; position:relative;}
      .main-content{margin-left:0;}
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="sidebar-header">
      <img src="logo.jpg" alt="Logo">
    </div>
    <a href="?tab=home" class="<?=($active_tab=='home')?'active':''?>"><i class="fas fa-home"></i> Home</a>
    <a href="?tab=appointments" class="<?=($active_tab=='appointments')?'active':''?>"><i class="fas fa-calendar"></i> Appointments</a>
    <a href="?tab=profile" class="<?=($active_tab=='profile')?'active':''?>"><i class="fas fa-user"></i> Profile</a>
    <a href="?tab=results" class="<?=($active_tab=='results')?'active':''?>"><i class="fas fa-file-medical"></i> Results</a>
    <a href="?tab=billing" class="<?=($active_tab=='billing')?'active':''?>"><i class="fas fa-receipt"></i> Billing</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>

  <!-- Main -->
  <div class="main-content">
    <!-- Home -->
    <div id="home" class="tab-content <?=$active_tab=='home'?'active':''?>">
      <div class="welcome-banner">
        <h2>Welcome, <?=htmlspecialchars($patient['full_name'] ?? 'Patient')?>!</h2>
        <p>San Francisco Diagnostic & Medical Clinic</p>
      </div>
      <div class="section">
        <div class="section-title"><i class="fas fa-calendar-check"></i> Upcoming Appointments</div>
        <?php if(!empty($appointments)): ?>
          <table>
            <tr><th>Date</th><th>Time</th><th>Test</th><th>Status</th></tr>
            <?php foreach($appointments as $app): ?>
              <tr>
                <td><?=htmlspecialchars($app['appointment_date'])?></td>
                <td><?=htmlspecialchars($app['appointment_time'])?></td>
                <td><?=htmlspecialchars($app['test_type'])?></td>
                <td><span class="badge <?=$app['status']?>"><?=ucfirst($app['status'])?></span></td>
              </tr>
            <?php endforeach; ?>
          </table>
        <?php else: ?><p>No upcoming appointments.</p><?php endif; ?>
      </div>
    </div>

    <!-- Appointments -->
    <div id="appointments" class="tab-content <?=$active_tab=='appointments'?'active':''?>">
      <div class="section">
        <div class="section-title"><i class="fas fa-calendar-plus"></i> Book New Appointment</div>
        <form method="POST" action="book_appointment.php" id="apptForm">
          <!-- Full Name -->
          <input type="text" readonly
            value="<?=htmlspecialchars(($patient_info['first_name']??'').' '.($patient_info['middle_name']??'').' '.($patient_info['last_name']??''))?>"
            name="full_name" style="width:100%;padding:12px;margin-bottom:15px;background:#f8f9fa;border:1px solid #ddd;border-radius:6px;">
          <!-- Tests -->
          <label style="font-weight:500">Test Type:</label>
          <div style="display:flex;flex-wrap:wrap;gap:12px;margin:10px 0;">
            <?php $tests=['CBC','X-RAY','DRUG TEST','HEPA B','VDRL','BLOOD TYPE','URINALYSIS','STOOL','RAPID TEST','LIPID PROFILE','LIVER PROFILE','NA, K, CL','OGTT','FBS','URIC ACID','TUBEX TEST','THYROID PANEL','ULTRASOUND']; 
              foreach($tests as $test): ?>
              <label><input type="checkbox" name="tests[]" value="<?=$test?>"> <?=$test?></label>
            <?php endforeach; ?>
          </div>
          <!-- Requested by -->
          <input type="text" name="requested_by" readonly
            value="<?=htmlspecialchars($patient_info['requesting_company']??'')?>"
            style="width:100%;padding:12px;margin:15px 0;background:#f8f9fa;border:1px solid #ddd;border-radius:6px;">
          <!-- Date / Time -->
          <div style="display:flex;gap:15px;">
            <input type="date" name="appointment_date" required style="flex:1;padding:12px;border:1px solid #ddd;border-radius:6px;">
            <select name="appointment_time" required style="flex:1;padding:12px;border:1px solid #ddd;border-radius:6px;">
              <option value="">Select Time</option>
              <?php foreach(['08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','13:00','13:30','14:00','14:30','15:00','15:30'] as $time): ?>
                <option value="<?=$time?>"><?=$time?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <br>
          <button type="button" class="btn btn-primary" onclick="showConfirm()">📅 Book Appointment</button>
        </form>
      </div>
    </div>

    <!-- Profile -->
    <div id="profile" class="tab-content <?=$active_tab=='profile'?'active':''?>">
      <div class="section">
        <div class="section-title"><i class="fas fa-id-card"></i> Profile</div>
        <?php if($patient_info): ?>
          <p><b>Name:</b> <?=$patient_info['first_name'].' '.$patient_info['last_name']?></p>
          <p><b>Birthday:</b> <?=$patient_info['birthday']?></p>
          <p><b>Age:</b> <?=$patient_info['age']?></p>
          <p><b>Sex:</b> <?=$patient_info['sex']?></p>
          <p><b>Address:</b> <?=$patient_info['house_no'].' '.$patient_info['district'].', '.$patient_info['city'].' '.$patient_info['province']?></p>
          <p><b>Phone:</b> <?=$patient_info['phone']?></p>
          <p><b>Status:</b> <?=$patient_info['status']?></p>
          <p><b>Requesting Company:</b> <?=$patient_info['requesting_company']?></p>
        <?php else: ?><p>No profile info.</p><?php endif; ?>
      </div>
    </div>

    <!-- Results -->
    <div id="results" class="tab-content <?=$active_tab=='results'?'active':''?>">
      <div class="section">
        <div class="section-title"><i class="fas fa-vials"></i> Test Results</div>
        <?php if(empty($results)): ?><p>No test results yet.</p>
        <?php else: ?>
          <table>
            <tr><th>Test</th><th>Date</th><th>Status</th><th>Action</th></tr>
            <?php foreach($results as $r): ?>
              <tr>
                <td><?=$r['test_type']?></td>
                <td><?=$r['uploaded_at']?></td>
                <td><span class="badge <?=$r['result_status']?>"><?=ucfirst($r['result_status'])?></span></td>
                <td><a href="<?=$r['file_path']?>" target="_blank" style="color:#0d6efd;">View</a></td>
              </tr>
            <?php endforeach; ?>
          </table>
        <?php endif; ?>
      </div>
    </div>

    <!-- Billing -->
    <div id="billing" class="tab-content <?=$active_tab=='billing'?'active':''?>">
      <div class="section">
        <div class="section-title"><i class="fas fa-money-bill"></i> Billing</div>
        <?php if(empty($bills)): ?><p>No billing yet.</p>
        <?php else: ?>
          <table>
            <tr><th>Service</th><th>Amount</th><th>Status</th><th>Date</th></tr>
            <?php foreach($bills as $b): ?>
              <tr>
                <td><?=$b['service_type']?></td>
                <td>₱<?=number_format($b['amount'],2)?></td>
                <td><span class="badge <?=$b['status']?>"><?=ucfirst($b['status'])?></span></td>
                <td><?=$b['created_at']?></td>
              </tr>
            <?php endforeach; ?>
          </table>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Modals -->
  <div id="confirmModal" class="modal"><div class="modal-content">
    <h3>Confirm Booking</h3>
    <p>Are you sure you want to book this appointment?</p>
    <button class="btn btn-primary" id="yesBtn">Yes</button>
    <button class="btn" style="background:#6c757d;color:white" id="noBtn">No</button>
  </div></div>
  <div id="successModal" class="modal"><div class="modal-content">
    <h3 style="color:#198754">✅ Success</h3>
    <p>Your appointment has been booked.</p>
    <button class="btn btn-primary" id="okBtn">OK</button>
  </div></div>

<script>
function showConfirm(){
  document.getElementById('confirmModal').style.display='flex';
}
document.getElementById('noBtn').onclick=()=>document.getElementById('confirmModal').style.display='none';
document.getElementById('yesBtn').onclick=()=>{
  document.getElementById('confirmModal').style.display='none';
  document.getElementById('successModal').style.display='flex';
}
document.getElementById('okBtn').onclick=()=>{
  document.getElementById('apptForm').submit();
}
</script>
</body>
</html>
