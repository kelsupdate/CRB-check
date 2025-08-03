<?php
session_start();
require_once 'db.php';

// Check if user is logged in and has an active report
if (!isset($_SESSION['user_id']) || !isset($_SESSION['report_id'])) {
    header("Location: index2.php");
    exit();
}

$userId = $_SESSION['user_id'];
$reportId = $_SESSION['report_id'];

// Get report details
$stmt = executeQuery("SELECT * FROM reports WHERE report_id = ? AND user_id = ?", [$reportId, $userId]);
$report = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$report) {
    $_SESSION['error'] = "Invalid report request";
    header("Location: reportpurpose.php");
    exit();
}

// Process M-PESA verification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmationText'])) {
    $confirmationText = trim($_POST['confirmationText']);
    
    // Simple validation (in a real app, you'd parse the M-PESA confirmation)
    if (strpos($confirmationText, 'PREMIUM INVESTMENTS') !== false) {
        // Mark payment as completed
        $amount = 100.00; // KES 100
        $mpesaCode = 'SIM' . substr(md5(uniqid()), 0, 8); // Simulated M-PESA code
        
        $sql = "INSERT INTO payments (report_id, amount, mpesa_code, status) 
                VALUES (?, ?, ?, 'completed')";
        $stmt = executeQuery($sql, [$reportId, $amount, $mpesaCode]);
        
        if ($stmt) {
            // Update report status
            executeQuery("UPDATE reports SET status = 'completed' WHERE report_id = ?", [$reportId]);
            
            // Generate credit report (simulated data)
            $creditScore = rand(300, 850); // Random score for demo
            $rating = '';
            
            if ($creditScore >= 750) $rating = 'excellent';
            elseif ($creditScore >= 650) $rating = 'very-good';
            elseif ($creditScore >= 550) $rating = 'good';
            elseif ($creditScore >= 400) $rating = 'fair';
            else $rating = 'poor';
            
            $negativeListings = rand(0, 5);
            $activeLoans = rand(0, 3);
            $loanDefaults = rand(0, $negativeListings);
            
            $sql = "INSERT INTO credit_reports (report_id, user_id, credit_score, rating, 
                    negative_listings, active_loans, loan_defaults) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            executeQuery($sql, [$reportId, $userId, $creditScore, $rating, 
                              $negativeListings, $activeLoans, $loanDefaults]);
            
            // Log the payment
            $action = "Payment Completed";
            $description = "User completed payment for report $reportId with M-PESA code $mpesaCode";
            executeQuery("INSERT INTO audit_logs (user_id, action, description) VALUES (?, ?, ?)", 
                        [$userId, $action, $description]);
            
            // Store report data in session for display
            $_SESSION['credit_score'] = $creditScore;
            $_SESSION['rating'] = $rating;
            $_SESSION['negative_listings'] = $negativeListings;
            $_SESSION['active_loans'] = $activeLoans;
            $_SESSION['loan_defaults'] = $loanDefaults;
            
            header("Location: report.php");
            exit();
        }
    }
    
    $_SESSION['error'] = "Invalid M-PESA confirmation message";
    header("Location: payment.php");
    exit();
}

// Display payment page (HTML from index4.php)
?>