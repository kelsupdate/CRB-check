<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    $formData = [
        'email' => trim($_POST['email'] ?? ''),
        'password' => $_POST['password'] ?? '',
        'remember' => isset($_POST['remember'])
    ];

    // Validate input
    if (empty($formData['email'])) {
        $errors[] = 'Email or phone number is required';
    }

    if (empty($formData['password'])) {
        $errors[] = 'Password is required';
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $formData;
        header('Location: login.php');
        exit;
    }

    try {
        // Check if login is by email or phone
        $isEmail = filter_var($formData['email'], FILTER_VALIDATE_EMAIL);
        
        if ($isEmail) {
            $stmt = $conn->prepare("SELECT user_id, first_name, last_name, password FROM users WHERE email = ?");
        } else {
            // Clean phone number for comparison
            $phone = preg_replace('/[^0-9]/', '', $formData['email']);
            if (strpos($phone, '254') === 0) {
                $phone = '0' . substr($phone, 3);
            }
            $stmt = $db->prepare("SELECT user_id, first_name, last_name, password FROM users WHERE phone = ?");
        }
        
        $stmt->execute([$isEmail ? $formData['email'] : $phone]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($formData['password'], $user['password'])) {
            // Login successful
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];

            // Remember me functionality
            if ($formData['remember']) {
                $token = bin2hex(random_bytes(32));
                $expiry = time() + 60 * 60 * 24 * 30; // 30 days
                
                setcookie('remember_token', $token, $expiry, '/');
                
                // Store token in database
                $stmt = $db->prepare("UPDATE users SET remember_token = ?, token_expiry = ? WHERE user_id = ?");
                $stmt->execute([$token, date('Y-m-d H:i:s', $expiry), $user['user_id']]);
            }
            
            header('Location: index.php');
            exit;
        } else {
            $errors[] = 'Invalid email/phone or password';
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $formData;
            header('Location: login.php');
            exit;
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        $_SESSION['errors'] = ['A system error occurred. Please try again later.'];
        header('Location: login.php');
        exit;
    }
} else {
    header('Location: login.php');
    exit;
}