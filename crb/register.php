<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    $errors = [];
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $idNumber = trim($_POST['idNumber']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $terms = isset($_POST['terms']) ? true : false;

    // Validation checks
    if (empty($firstName)) $errors[] = "First name is required";
    if (empty($lastName)) $errors[] = "Last name is required";
    if (empty($idNumber) || !preg_match('/^\d{6,8}$/', $idNumber)) $errors[] = "Valid ID number is required";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required";
    if (empty($phone) || !preg_match('/^(\+254|0)[17]\d{8}$/', $phone)) $errors[] = "Valid Kenyan phone number is required";
    if (empty($password) || strlen($password) < 8) $errors[] = "Password must be at least 8 characters";
    if ($password !== $confirmPassword) $errors[] = "Passwords do not match";
    if (!$terms) $errors[] = "You must accept the terms and conditions";

    if (empty($errors)) {
        // Check if email or ID already exists
        $stmt = executeQuery("SELECT user_id FROM users WHERE email = ? OR id_number = ?", [$email, $idNumber]);
        if ($stmt->rowCount() > 0) {
            $errors[] = "Email or ID number already registered";
        } else {
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Format phone number to standard format (254...)
            $phone = preg_replace('/^0/', '254', $phone);
            
            // Insert user
            $sql = "INSERT INTO users (first_name, last_name, id_number, email, phone, password) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = executeQuery($sql, [$firstName, $lastName, $idNumber, $email, $phone, $hashedPassword]);
            
            if ($stmt) {
                // Log the registration
                $userId = $conn->lastInsertId();
                $action = "User Registration";
                $description = "New user registered with ID: $idNumber";
                executeQuery("INSERT INTO audit_logs (user_id, action, description) VALUES (?, ?, ?)", 
                            [$userId, $action, $description]);
                
                // Start session and redirect
                session_start();
                $_SESSION['user_id'] = $userId;
                $_SESSION['email'] = $email;
                $_SESSION['first_name'] = $firstName;
                
                header("Location: reportpurpose.php");
                exit();
            } else {
                $errors[] = "Registration failed. Please try again.";
            }
        }
    }
    
    // If there are errors, return to form with messages
    if (!empty($errors)) {
        session_start();
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header("Location: index2.php");
        exit();
    }
} else {
    header("Location: index2.php");
    exit();
}
?>