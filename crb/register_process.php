<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    $formData = [
        'firstName' => trim($_POST['firstName'] ?? ''),
        'lastName' => trim($_POST['lastName'] ?? ''),
        'idNumber' => trim($_POST['idNumber'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'phone' => trim($_POST['phone'] ?? ''),
        'password' => $_POST['password'] ?? '',
        'terms' => isset($_POST['terms'])
    ];

    // Validation
    if (empty($formData['firstName'])) {
        $errors[] = 'First name is required';
    }

    if (empty($formData['lastName'])) {
        $errors[] = 'Last name is required';
    }

    if (empty($formData['idNumber']) || !preg_match('/^\d{6,8}$/', $formData['idNumber'])) {
        $errors[] = 'Valid ID number is required (6-8 digits)';
    }

    if (empty($formData['email']) || !filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required';
    }

    if (empty($formData['phone']) || !preg_match('/^(\+254|0)[17]\d{8}$/', $formData['phone'])) {
        $errors[] = 'Valid Kenyan phone number is required';
    }

    if (empty($formData['password']) || strlen($formData['password']) < 8) {
        $errors[] = 'Password must be at least 8 characters';
    }

    if (!$formData['terms']) {
        $errors[] = 'You must accept the terms and conditions';
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $formData;
        header('Location: index2.php');
        exit;
    }

    try {
        // Check if email or ID number already exists
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? OR id_number = ?");
        $stmt->execute([$formData['email'], $formData['idNumber']]);
        
        if ($stmt->fetch()) {
            $_SESSION['errors'] = ['Email or ID number already registered'];
            $_SESSION['form_data'] = $formData;
            header('Location: index2.php');
            exit;
        }

        // Hash password
        $hashedPassword = password_hash($formData['password'], PASSWORD_DEFAULT);

        // Insert new user
        $stmt =$conn->prepare("INSERT INTO users (first_name, last_name, id_number, email, phone, password) 
                             VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $formData['firstName'],
            $formData['lastName'],
            $formData['idNumber'],
            $formData['email'],
            $formData['phone'],
            $hashedPassword
        ]);

        // Get new user ID
        $userId = $conn->lastInsertId();

        // Log in the user
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $formData['firstName'] . ' ' . $formData['lastName'];

        // Redirect to dashboard
        header('Location: purpose.html');
        exit;

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        $_SESSION['errors'] = ['Registration failed. Please try again.'];
        $_SESSION['form_data'] = $formData;
        header('Location: index2.php');
        exit;
    }
} else {
    header('Location: index2.php');
    exit;
}