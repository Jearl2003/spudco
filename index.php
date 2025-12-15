<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>San Francisco HealthCare Clinic | Secure Appointment & Result System</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" />
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #e3f2fd, #bbdefb);
    min-height: 100vh;
    color: #333;
}

.container {
    max-width: 100%;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    animation: fadeIn 1s ease-in;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Hero Section */
.hero {
    position: relative;
    text-align: center;
    padding: 100px 20px 60px;
    background: linear-gradient(135deg, #0d6efd, #0b5ed7);
    color: white;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(13, 110, 253, 0.3);
    z-index: 1;
}

.hero h1 {
    font-size: 2.8rem;
    margin-bottom: 16px;
    font-weight: 600;
    text-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.hero p {
    font-size: 1.2rem;
    max-width: 700px;
    margin: 0 auto 30px;
    opacity: 0.9;
    line-height: 1.6;
}

/* CTA Buttons */
.cta-buttons {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
    margin-top: 20px;
}

.btn-large {
    font-size: 1.1rem;
    padding: 14px 32px;
    background: white;
    color: #0d6efd;
    border: none;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-block;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.btn-large:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.btn-outline {
    background: transparent;
    border: 2px solid white;
    color: white;
}

.btn-outline:hover {
    background: rgba(255, 255, 255, 0.1);
}

/* Decorative Floating Dots */
.dots-decor {
    display: flex;
    justify-content: center;
    gap: 12px;
    margin-top: 30px;
    padding: 10px 0;
}

.dot {
    width: 8px;
    height: 8px;
    background-color: #0d6efd;
    border-radius: 50%;
    opacity: 0.7;
    animation: pulse 2s infinite alternate;
}

.dot:nth-child(1) { animation-delay: 0s; }
.dot:nth-child(2) { animation-delay: 0.2s; }
.dot:nth-child(3) { animation-delay: 0.4s; }
.dot:nth-child(4) { animation-delay: 0.6s; }
.dot:nth-child(5) { animation-delay: 0.8s; }
.dot:nth-child(6) { animation-delay: 1.0s; }
.dot:nth-child(7) { animation-delay: 1.2s; }
.dot:nth-child(8) { animation-delay: 1.4s; }
.dot:nth-child(9) { animation-delay: 1.6s; }
.dot:nth-child(10) { animation-delay: 1.8s; }

@keyframes pulse {
    0% {
        transform: scale(0.8);
        opacity: 0.7;
    }
    100% {
        transform: scale(1.2);
        opacity: 1;
    }
}

/* Features Section */
.features {
    margin: 60px 0;
}

.features h2 {
    text-align: center;
    color: #0d6efd;
    margin-bottom: 30px;
    font-size: 1.8rem;
}

.feature-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.feature-card {
    background: #e3f2fd;
    padding: 24px;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    transition: transform 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-5px);
}

.feature-card h3 {
    color: #0d6efd;
    margin-bottom: 12px;
    font-size: 1.3rem;
}

.feature-card p {
    font-size: 0.95rem;
    color: #555;
    line-height: 1.5;
}

/* Admin Note */
.admin-note {
    text-align: center;
    margin: 40px 0 20px;
    font-size: 0.9rem;
    color: #555;
}

.admin-note a {
    color: #0d6efd;
    font-weight: 600;
    text-decoration: underline;
}

/* Footer */
footer {
    text-align: center;
    margin-top: 60px;
    color: #777;
    font-size: 0.9rem;
    padding-bottom: 20px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero h1 {
        font-size: 2.2rem;
    }
    .hero p {
        font-size: 1.1rem;
    }
    .container {
        margin: 20px;
        padding: 15px;
    }
    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }
    .btn-large {
        width: 80%;
        text-align: center;
    }
}
</style>
</head>

<body>


    <div class="container">

        <!-- Hero Section -->
        <section class="hero">
             <img src="logo.jpg" alt="Logo" style="width:100px; height:auto;">
            <h1>Welcome to San Francisco Diagnostic Medical Clinic and Drug Testing Center Bislig</h1>
            <p>
                A secure, modern, and paperless system for booking appointments and receiving your medical results — 
                protected with 24-hour access for privacy and peace of mind.
            </p>
            <div class="cta-buttons">
                <a href="login.php" class="btn-large">Login to Your Account</a>
                <a href="register.php" class="btn-large btn-outline">Register as Patient</a>
            </div>

            <!-- Decorative Floating Dots -->
            <div class="dots-decor">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features">
            <h2>Why Choose Our System?</h2>
            <div class="feature-grid">
                <div class="feature-card">
                    <h3>📅 Easy Appointment Booking</h3>
                    <p>Book, reschedule, or cancel your clinic visit anytime — no phone calls needed.</p>
                </div>
                <div class="feature-card">
                    <h3>📄 Secure Result Delivery</h3>
                    <p>Results are uploaded by the clinic and accessible only to you — with 24-hour download window.</p>
                </div>
                <div class="feature-card">
                    <h3>📧 One-Time Email Alert</h3>
                    <p>You’ll receive your result file once via email. No spam — just security.</p>
                </div>
                <div class="feature-card">
                    <h3>🔐 Privacy-First Design</h3>
                    <p>Role-based access, secure login, and automatic result expiry protect your data.</p>
                </div>
            </div>
        </section>
<!-- Admin & Staff Note with Footer Text -->
<div style="text-align: center; padding: 20px; color: #666; font-size: 0.9rem;">
    <p>
        <strong>Admin & Staff?</strong> 
        <a href="admin_login.php" style="color: #0d6efd; text-decoration: underline;">Log in here</a> 
        to manage appointments, upload results, and view patient records.
    </p>
    <p style="margin-top: 10px;">
        &copy; <?php echo date("Y"); ?> San Francisco Diagnostic Medical Clinic and Drug Testing Center Bislig.<br>
        All rights reserved.
    </p>
</div>
<script>
// Fade-in animations
document.addEventListener("DOMContentLoaded", function () {
    const elements = document.querySelectorAll("h1, h2, p, form, .btn");
    elements.forEach((el, i) => {
        el.style.opacity = "0";
        el.style.transform = "translateY(20px)";
        el.style.transition = "all 0.6s ease";
        setTimeout(() => {
            el.style.opacity = "1";
            el.style.transform = "translateY(0)";
        }, 100 * i);
    });
});

// Auto-hide alerts
setTimeout(() => {
    const alert = document.querySelector('.alert');
    if (alert) alert.style.display = 'none';
}, 5000);
</script>

</body>
</html>