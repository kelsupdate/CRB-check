<?php
session_start();
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index2.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $purpose = $_POST['purpose'] ?? '';
    $additionalDetails = $_POST['details'] ?? '';
    $userId = $_SESSION['user_id'];
    
    // Validate purpose
    $validPurposes = ['loan-application', 'employment', 'business-registration', 
                     'property-rental', 'personal-verification', 'visa-application', 'other'];
    
    if (!in_array($purpose, $validPurposes)) {
        $_SESSION['error'] = "Please select a valid purpose for your CRB report";
        header("Location: reportpurpose.php");
        exit();
    }
    
    // Additional details required for 'other' purpose
    if ($purpose === 'other' && empty($additionalDetails)) {
        $_SESSION['error'] = "Please provide additional details for your request";
        header("Location: reportpurpose.php");
        exit();
    }
    
    // Create report record
    $sql = "INSERT INTO reports (user_id, purpose, additional_details) VALUES (?, ?, ?)";
    $stmt = executeQuery($sql, [$userId, $purpose, $additionalDetails]);
    
    if ($stmt) {
        $reportId = $conn->lastInsertId();
        $_SESSION['report_id'] = $reportId;
        
        // Log the report request
        $action = "Report Request";
        $description = "User requested CRB report for purpose: $purpose";
        executeQuery("INSERT INTO audit_logs (user_id, action, description) VALUES (?, ?, ?)", 
                    [$userId, $action, $description]);
        
        header("Location: payment.php");
        exit();
    } else {
        $_SESSION['error'] = "Failed to process your request. Please try again.";
        header("Location: reportpurpose.php");
        exit();
    }
}

// Get user data
$userId = $_SESSION['user_id'];
$stmt = executeQuery("SELECT first_name, last_name, id_number FROM users WHERE user_id = ?", [$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Display the form (HTML from report.html)
?>