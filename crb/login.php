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
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>CRB Checker Kenya - Login</title>
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

        .login-container {
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
        .login-header-mobile {
            background: linear-gradient(135deg, #667eea, #764ba2);
            padding: 25px 20px;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .login-header-mobile::before {
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

        .login-header-mobile * {
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
        .login-form-section {
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

        .form-group input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
            background: #f8f9fa;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 15px rgba(102, 126, 234, 0.15);
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

        .register-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 0.85rem;
            padding-bottom: 10px;
        }

        .register-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .forgot-password {
            text-align: right;
            margin-top: -10px;
            margin-bottom: 15px;
        }

        .forgot-password a {
            color: #667eea;
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 500;
        }

        /* Messages */
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
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Mobile Header -->
        <div class="login-header-mobile">
            <div class="logo">CRB Checker KE</div>
            <div class="tagline">Access your Credit Reference Bureau status and reports</div>
        </div>

        <!-- Form Section -->
        <div class="login-form-section">
            <div class="form-title">
                <h2>Welcome Back</h2>
                <p>Sign in to access your account</p>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="error-message">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form id="loginForm" action="login_process.php" method="POST">
                <div class="form-group">
                    <label for="email">Email or Phone Number *</label>
                    <input type="text" id="email" name="email" required 
                           value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>" 
                           placeholder="john@example.com or 0700000000" autocomplete="username">
                </div>

                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" required 
                           placeholder="Enter your password" autocomplete="current-password">
                </div>

                <div class="forgot-password">
                    <a href="forgot_password.php">Forgot Password?</a>
                </div>

                <button type="submit" class="submit-btn">
                    Sign In
                </button>
            </form>

            <div class="register-link">
                Don't have an account? <a href="register.php">Register here</a>
            </div>
        </div>
    </div>

    <script>
        // Client-side validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            // Clear previous errors
            const errorElements = document.querySelectorAll('.error-text');
            errorElements.forEach(el => el.remove());
            
            let isValid = true;
            
            // Validate email/phone
            const email = document.getElementById('email').value;
            const phoneRegex = /^(\+254|0)[17]\d{8}$/;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (!emailRegex.test(email) {
                // Not an email, check if it's a phone number
                const phoneValue = email.replace(/\D/g, '');
                const formattedPhone = phoneValue.startsWith('254') ? phoneValue : 
                                      (phoneValue.startsWith('7') || phoneValue.startsWith('1')) ? '0' + phoneValue : phoneValue;
                
                if (!phoneRegex.test(formattedPhone)) {
                    showError('email', 'Please enter a valid email or Kenyan phone number');
                    isValid = false;
                }
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

        // Auto-format phone number if entered as username
        document.getElementById('email').addEventListener('input', function(e) {
            const value = e.target.value;
            if (/^[0-9+]+$/.test(value)) {
                let phoneValue = value.replace(/\D/g, '');
                if (phoneValue.startsWith('254')) {
                    phoneValue = '+' + phoneValue;
                } else if (phoneValue.startsWith('7') || phoneValue.startsWith('1')) {
                    phoneValue = '0' + phoneValue;
                }
                e.target.value = phoneValue;
            }
        });
    </script>
</body>
</html>