<?php
session_start();
require_once 'db.php';

// Check for errors and form data from previous submission
$errors = $_SESSION['errors'] ?? [];
$formData = $_SESSION['form_data'] ?? [];
unset($_SESSION['errors']);
unset($_SESSION['form_data']);

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: purpose.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>CRB Checker Kenya - Register Now</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 10px;
            line-height: 1.4;
        }

        .signup-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 100%;
            width: 100%;
            margin: 0 auto;
            animation: slideInUp 0.6s ease forwards;
        }

        /* Mobile-first header section */
        .signup-header-mobile {
            background: linear-gradient(135deg, #667eea, #764ba2);
            padding: 25px 20px;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .signup-header-mobile::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: rotate(45deg);
        }

        .signup-header-mobile * {
            position: relative;
            z-index: 2;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 8px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .tagline {
            font-size: 0.95rem;
            opacity: 0.95;
            line-height: 1.4;
        }

        /* Form section */
        .signup-form-section {
            padding: 25px 20px 30px;
        }

        .form-title {
            text-align: center;
            margin-bottom: 25px;
        }

        .form-title h2 {
            font-size: 1.4rem;
            color: #333;
            margin-bottom: 5px;
        }

        .form-title p {
            color: #666;
            font-size: 0.85rem;
        }

        .form-group {
            margin-bottom: 18px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #333;
            font-size: 0.85rem;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
            background: #f8f9fa;
            -webkit-appearance: none;
            appearance: none;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 15px rgba(102, 126, 234, 0.15);
        }

        /* Mobile-optimized two-column layout */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 18px;
        }

        /* Single column for complex fields */
        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .submit-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 16px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 15px;
            width: 100%;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
            -webkit-tap-highlight-color: transparent;
        }

        .submit-btn:active {
            transform: translateY(1px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 0.85rem;
            padding-bottom: 10px;
        }

        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .terms-checkbox {
            display: flex;
            align-items: flex-start;
            margin: 20px 0;
            font-size: 0.8rem;
            line-height: 1.4;
        }

        .terms-checkbox input {
            margin-right: 10px;
            margin-top: 2px;
            width: 16px;
            height: 16px;
            min-width: 16px;
        }

        .terms-checkbox label {
            color: #666;
            margin-bottom: 0;
            flex: 1;
        }

        /* Messages */
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            border: 1px solid #c3e6cb;
            display: none;
            font-size: 0.85rem;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            border: 1px solid #f5c6cb;
            font-size: 0.85rem;
        }

        /* Animation */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive adjustments */
        @media (max-width: 360px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <!-- Mobile Header -->
        <div class="signup-header-mobile">
            <div class="logo">CRB Checker KE</div>
            <div class="tagline">Get instant access to your Credit Reference Bureau status and reports</div>
        </div>

        <!-- Form Section -->
        <div class="signup-form-section">
            <div class="form-title">
                <h2>Create Your Account</h2>
                <p>Join thousands of Kenyans who trust us with their credit information</p>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="error-message">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form id="signupForm" action="register_process.php" method="POST">
                <!-- Personal Information -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstName">First Name *</label>
                        <input type="text" id="firstName" name="firstName" required 
                               value="<?php echo htmlspecialchars($formData['firstName'] ?? ''); ?>" 
                               placeholder="John" autocomplete="given-name">
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name *</label>
                        <input type="text" id="lastName" name="lastName" required 
                               value="<?php echo htmlspecialchars($formData['lastName'] ?? ''); ?>" 
                               placeholder="Doe" autocomplete="family-name">
                    </div>
                </div>

                <div class="form-group">
                    <label for="idNumber">National ID Number *</label>
                    <input type="text" id="idNumber" name="idNumber" required 
                           value="<?php echo htmlspecialchars($formData['idNumber'] ?? ''); ?>" 
                           placeholder="12345678" inputmode="numeric">
                </div>

                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" required 
                           value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>" 
                           placeholder="john@example.com" autocomplete="email">
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number *</label>
                    <input type="tel" id="phone" name="phone" required 
                           value="<?php echo htmlspecialchars($formData['phone'] ?? ''); ?>" 
                           placeholder="0700000000" autocomplete="tel">
                </div>

                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" required 
                           placeholder="Create a strong password" autocomplete="new-password">
                </div>

                <div class="form-group">
                    <label for="confirmPassword">Confirm Password *</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required 
                           placeholder="Repeat your password" autocomplete="new-password">
                </div>

                <!-- Terms and Conditions -->
                <div class="terms-checkbox">
                    <input type="checkbox" id="terms" name="terms" required <?php echo isset($formData['terms']) ? 'checked' : ''; ?>>
                    <label for="terms">
                        I agree to the <a href="terms.php">Terms & Conditions</a> and 
                        <a href="privacy.php">Privacy Policy</a>. I consent to the processing 
                        of my personal data for CRB checking services.
                    </label>
                </div>

                <button type="submit" class="submit-btn">
                    Create Account & Proceed
                </button>
            </form>

            <div class="login-link">
                Already have an account? <a href="login.php">Login here</a>
            </div>
        </div>
    </div>

    <script>
        // Client-side validation
        document.getElementById('signupForm').addEventListener('submit', function(e) {
            // Clear previous errors
            const errorElements = document.querySelectorAll('.error-text');
            errorElements.forEach(el => el.remove());
            
            let isValid = true;
            
            // Validate password match
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            if (password !== confirmPassword) {
                showError('confirmPassword', 'Passwords do not match');
                isValid = false;
            }
            
            // Validate password strength
            if (password.length < 8) {
                showError('password', 'Password must be at least 8 characters');
                isValid = false;
            }
            
            // Validate Kenyan phone number
            const phone = document.getElementById('phone').value;
            const phoneRegex = /^(\+254|0)[17]\d{8}$/;
            if (!phoneRegex.test(phone)) {
                showError('phone', 'Please enter a valid Kenyan phone number');
                isValid = false;
            }
            
            // Validate ID number
            const idNumber = document.getElementById('idNumber').value;
            if (idNumber.length < 6 || idNumber.length > 8) {
                showError('idNumber', 'Please enter a valid ID number');
                isValid = false;
            }
            
            // Validate terms checkbox
            if (!document.getElementById('terms').checked) {
                showError('terms', 'You must accept the terms and conditions');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });

        function showError(fieldId, message) {
            const field = document.getElementById(fieldId);
            const errorElement = document.createElement('div');
            errorElement.className = 'error-text';
            errorElement.style.color = '#e53e3e';
            errorElement.style.fontSize = '0.75rem';
            errorElement.style.marginTop = '0.25rem';
            errorElement.textContent = message;
            
            field.parentNode.appendChild(errorElement);
            field.style.borderColor = '#e53e3e';
        }

        // Auto-format phone number
        document.getElementById('phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.startsWith('254')) {
                value = '+' + value;
            } else if (value.startsWith('7') || value.startsWith('1')) {
                value = '0' + value;
            }
            e.target.value = value;
        });

        // Auto-format ID number
        document.getElementById('idNumber').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });
    </script>
</body>
</html>