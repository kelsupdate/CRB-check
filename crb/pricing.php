<?php
session_start();
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index2.php');
    exit();
}

// Get user details from database
$stmt = $conn->prepare("SELECT first_name, last_name, id_number FROM users WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// If user not found, redirect to login
if (!$user) {
    header('Location: index2.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>CRB Report Options</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      padding: 20px;
    }
    .card {
      background-color: white;
      border-radius: 20px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      padding: 20px;
      margin-bottom: 20px;
      max-width: 400px;
      margin-left: auto;
      margin-right: auto;
      position: relative;
    }
    .card.recommended {
      border: 2px solid #ffd700;
      background: #fff9e6;
    }
    .header {
      font-size: 20px;
      font-weight: bold;
      margin-top: 10px;
    }
    .price {
      font-size: 22px;
      font-weight: bold;
      float: right;
    }
    .one-time {
      clear: both;
      text-align: right;
      font-size: 14px;
      color: #666;
      margin-top: 5px;
    }
    .feature {
      color: green;
      margin: 10px 0;
    }
    .select-button {
      display: block;
      background-color: #f0ad4e;
      color: white;
      padding: 12px 20px;
      border-radius: 10px;
      text-decoration: none;
      margin-top: 20px;
      text-align: center;
      font-weight: bold;
    }
    .recommended-badge {
      background-color: #ffd700;
      color: #000;
      padding: 5px 15px;
      border-radius: 15px;
      font-weight: bold;
      font-size: 14px;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      position: absolute;
      top: -12px;
      left: 20px;
    }
    .terms {
      font-size: 12px;
      color: #666;
      margin-top: 15px;
      text-align: center;
      line-height: 1.4;
    }
    .user-info {
      text-align: center;
      margin-bottom: 20px;
    }
    .user-info h2 {
      margin: 5px 0;
    }
    .user-info span {
      color: #666;
    }
	 /* Toast notification styles */
    .toast {
      position: fixed;
      top: 20px;
      right: 20px;
      background-color: #4CAF50;
      color: white;
      padding: 15px 25px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      z-index: 1000;
      display: flex;
      align-items: center;
      transform: translateX(150%);
      transition: transform 0.3s ease-in-out;
    }
    .toast.show {
      transform: translateX(0);
    }
    .toast-icon {
      margin-right: 10px;
      font-size: 20px;
    }
    .close-toast {
      margin-left: 15px;
      cursor: pointer;
      font-weight: bold;
    }
  </style>
</head>
<body>
<!-- Toast Notification -->
  <div id="toast" class="toast">
    <span class="toast-icon">✓</span>
    <span>Report is ready for purchase!</span>
    <span class="close-toast" onclick="hideToast()">×</span>
  </div>
  <div class="user-info">
    <div style="font-size: 40px;">🔰</div>
    <h2>Hello: <?php echo htmlspecialchars($user['first_name']); ?> <?php echo htmlspecialchars($user['last_name']); ?></h2>
    <span>ID Number: <?php echo htmlspecialchars($user['id_number']); ?></span>
  </div>

  <div class="card">
    <div class="header">
      Standard Report
      <span class="price">KES 150</span>
    </div>
    <div class="one-time">One-time payment</div>
    <p>Basic credit information</p>
    <div class="feature">✔ Credit Score Analysis</div>
    <div class="feature">✔ Payment History Overview</div>
    <div class="feature">✔ Credit Utilization Report</div>
    <div class="feature">✔ Basic Recommendations</div>
    <a href="standard.html" class="select-button">Select Standard →</a>
  </div>

  <div class="card recommended">
    <div class="recommended-badge">
      <span>👑</span>
      <span>RECOMMENDED</span>
    </div>
    
    <div class="header">
      Gold Premium
      <span class="price">KES 250</span>
    </div>
    <div class="one-time">One-time payment</div>
    <p>Enhanced credit insights</p>
    
    <div class="feature">✔ All Standard Features</div>
    <div class="feature">✔ Loan Application Matches</div>
    <div class="feature">✔ Credit Score Simulator</div>
    <div class="feature">✔ Personalized Improvement Plan</div>
    <div class="feature">✔ Monthly Score Updates</div>
    <div class="feature">✔ Direct Lender Connections</div>
    
    <a href="premium.html" class="select-button">Select Gold Premium</a>
    
    <div class="terms">
      By proceeding with the payment, you agree to our terms and conditions. Your credit report will be generated instantly after payment confirmation.
    </div>
  </div>
 <script>
    // Show toast notification when page loads
    window.onload = function() {
      const toast = document.getElementById('toast');
      setTimeout(() => {
        toast.classList.add('show');
      }, 500);
      
      // Auto-hide after 5 seconds
      setTimeout(() => {
        hideToast();
      }, 5000);
    };
    
    // Hide toast function
    function hideToast() {
      const toast = document.getElementById('toast');
      toast.classList.remove('show');
    }
  </script>
</body>
</html>