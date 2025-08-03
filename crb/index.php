<?php
session_start();
require_once 'db.php';

// Check if user is logged in
$loggedIn = isset($_SESSION['user_id']);
$userName = $loggedIn ? $_SESSION['first_name'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRB Checker Kenya - Check Your Credit Status Online</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header */
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: #667eea;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
        }

        .nav-links a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #667eea;
        }

        /* Hero Section */
        .hero {
            padding: 120px 0 80px;
            text-align: center;
            color: white;
        }

        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .hero p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 15px 40px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s;
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.3);
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(255, 107, 107, 0.4);
        }

        /* Services Section */
        .services {
            background: white;
            padding: 80px 0;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 3rem;
            color: #333;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .service-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: all 0.3s;
            border: 1px solid #f0f0f0;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .service-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .service-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #333;
        }

        .service-card p {
            color: #666;
            margin-bottom: 1.5rem;
        }

        .price {
            font-size: 1.3rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 1rem;
        }

        .btn {
            background: #667eea;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn:hover {
            background: #5a67d8;
            transform: translateY(-2px);
        }

        /* Check Form */
        .check-form {
            background: linear-gradient(135deg, #667eea, #764ba2);
            padding: 80px 0;
            color: white;
        }

        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            background: white;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
        }

        /* Features */
        .features {
            background: #f8f9fa;
            padding: 80px 0;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .feature {
            text-align: center;
            padding: 1.5rem;
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #667eea;
        }

        /* Footer */
        .footer {
            background: #2d3748;
            color: white;
            padding: 50px 0 30px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h3 {
            margin-bottom: 1rem;
            color: #667eea;
        }

        .footer-section p,
        .footer-section a {
            color: #a0aec0;
            text-decoration: none;
        }

        .footer-section a:hover {
            color: white;
        }

        .footer-bottom {
            border-top: 1px solid #4a5568;
            padding-top: 2rem;
            text-align: center;
            color: #a0aec0;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .nav-links {
                display: none;
            }

            .services-grid {
                grid-template-columns: 1fr;
            }

            .form-container {
                margin: 0 20px;
                padding: 2rem;
            }
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.8s ease forwards;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="nav">
                <div class="logo">CRB Checker KE</div>
                <ul class="nav-links">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#services">Services</a></li>
                    <?php if ($loggedIn): ?>
                        <li><a href="pricing.php">My Report</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="container">
            <h1 class="fade-in-up">Check Your CRB Status Instantly</h1>
            <p class="fade-in-up">Get your Credit Reference Bureau report and clearance certificate online in minutes</p>
            <?php if ($loggedIn): ?>
                <p class="fade-in-up" style="margin-bottom: 20px;">Welcome back, <?php echo htmlspecialchars($userName); ?>!</p>
                <a href="pricing.php" class="cta-button fade-in-up">View My Report</a>
            <?php else: ?>
                <a href="register.php" class="cta-button fade-in-up">Get Started Now!</a>
            <?php endif; ?>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="services">
        <div class="container">
            <h2 class="section-title">Our Services</h2>
            <div class="services-grid">
                <div class="service-card fade-in-up">
                    <div class="service-icon">📊</div>
                    <h3>CRB Status Check</h3>
                    <p>Check your credit reference bureau status and get detailed credit report</p>
                </div>
                <div class="service-card fade-in-up">
                    <div class="service-icon">📋</div>
                    <h3>CRB Clearance Certificate</h3>
                    <p>Get official clearance certificate to prove your credit worthiness</p>
                </div>
                <div class="service-card fade-in-up">
                    <div class="service-icon">📈</div>
                    <h3>Credit Score Report</h3>
                    <p>Comprehensive credit score analysis with improvement recommendations</p>
                </div>
                <div class="service-card fade-in-up">
                    <div class="service-icon">🔄</div>
                    <h3>Credit Repair Assistance</h3>
                    <p>Professional guidance to improve your credit score and clear negative listings</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <h2 class="section-title">Why Choose CRB Checker KE?</h2>
            <div class="features-grid">
                <div class="feature fade-in-up">
                    <div class="feature-icon">⚡</div>
                    <h3>Instant Results</h3>
                    <p>Get your CRB status and reports delivered within minutes</p>
                </div>
                <div class="feature fade-in-up">
                    <div class="feature-icon">🔒</div>
                    <h3>Secure & Private</h3>
                    <p>Your personal information is encrypted and protected</p>
                </div>
                <div class="feature fade-in-up">
                    <div class="feature-icon">💰</div>
                    <h3>Affordable Rates</h3>
                    <p>Competitive pricing for all our credit services</p>
                </div>
                <div class="feature fade-in-up">
                    <div class="feature-icon">📱</div>
                    <h3>Mobile Friendly</h3>
                    <p>Access our services from any device, anywhere</p>
                </div>
                <div class="feature fade-in-up">
                    <div class="feature-icon">🎯</div>
                    <h3>Accurate Data</h3>
                    <p>Connected to all major credit bureaus in Kenya</p>
                </div>
                <div class="feature fade-in-up">
                    <div class="feature-icon">💬</div>
                    <h3>24/7 Support</h3>
                    <p>Our customer support team is always ready to help</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>CRB Checker KE</h3>
                    <p>Your trusted partner for credit reference bureau services in Kenya. We help you understand and improve your creditworthiness.</p>
                </div>
                <div class="footer-section">
                    <h3>Services</h3>
                    <p><a href="#services">CRB Status Check</a></p>
                    <p><a href="#services">Clearance Certificate</a></p>
                    <p><a href="#services">Credit Score Report</a></p>
                    <p><a href="#services">Credit Repair</a></p>
                </div>
                <div class="footer-section">
                    <h3>Contact Info</h3>
                    <p>📧 info@crbchecker.co.ke</p>
                    <p>📱 +254 700 000 000</p>
                    <p>📍 Nairobi, Kenya</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <p><a href="#home">Home</a></p>
                    <p><a href="#services">Services</a></p>
                    <p><a href="#check">Check Status</a></p>
                    <p><a href="#about">About Us</a></p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> CRB Checker KE. All rights reserved. | <a href="privacy.php">Privacy Policy</a> | <a href="terms.php">Terms of Service</a></p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling
        function scrollToCheck() {
            document.getElementById('check').scrollIntoView({
                behavior: 'smooth'
            });
        }

        // Animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all fade-in-up elements
        document.querySelectorAll('.fade-in-up').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
            observer.observe(el);
        });

        // Smooth scroll for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>