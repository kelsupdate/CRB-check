<?php
session_start();
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check for errors from previous submission
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);

// Get any previously selected purpose from session
$selectedPurpose = $_SESSION['purpose'] ?? '';
$additionalDetails = $_SESSION['additional_details'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRB Checker Kenya - Report Purpose</title>
    <style>
        /* All the existing CSS styles from the original file */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 0;
            font-size: 16px;
            line-height: 1.5;
            overflow-x: hidden;
        }

        /* ... (include all other CSS styles from the original file) ... */
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Report Purpose</h1>
            <p>Please select the primary reason you need your CRB report. This helps us provide you with the most appropriate documentation for your specific needs.</p>
        </div>

        <!-- Progress Bar -->
        <div class="progress-bar">
            <div class="progress-steps">
                <div class="step completed">
                    <div class="step-number">1</div>
                    <span>Registration</span>
                </div>
                <div class="step-separator"></div>
                <div class="step active">
                    <div class="step-number">2</div>
                    <span>Purpose</span>
                </div>
                <div class="step-separator"></div>
                <div class="step">
                    <div class="step-number">3</div>
                    <span>Report</span>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            <?php if ($error): ?>
                <div class="error-message" style="display: block;">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form id="purposeForm" action="process_purpose.php" method="POST">
                <div class="purpose-section">
                    <h2 class="section-title">Why do you need your CRB report?</h2>
                    <p class="section-description">
                        Select the option that best describes your reason for requesting a CRB report.
                    </p>

                    <div class="purpose-grid">
                        <div class="purpose-card <?php echo $selectedPurpose === 'loan-application' ? 'selected' : ''; ?>" onclick="selectPurpose('loan-application')">
                            <input type="radio" name="purpose" value="loan-application" id="loan-application" <?php echo $selectedPurpose === 'loan-application' ? 'checked' : ''; ?>>
                            <div class="selected-indicator">✓</div>
                            <div class="purpose-icon">🏦</div>
                            <div class="purpose-title">Loan Application</div>
                            <div class="purpose-examples">Examples: Bank loans, mortgage, business loans</div>
                        </div>

                        <div class="purpose-card <?php echo $selectedPurpose === 'employment' ? 'selected' : ''; ?>" onclick="selectPurpose('employment')">
                            <input type="radio" name="purpose" value="employment" id="employment" <?php echo $selectedPurpose === 'employment' ? 'checked' : ''; ?>>
                            <div class="selected-indicator">✓</div>
                            <div class="purpose-icon">💼</div>
                            <div class="purpose-title">Employment</div>
                            <div class="purpose-examples">Examples: Banking jobs, government positions, security roles</div>
                        </div>

                        <div class="purpose-card <?php echo $selectedPurpose === 'business-registration' ? 'selected' : ''; ?>" onclick="selectPurpose('business-registration')">
                            <input type="radio" name="purpose" value="business-registration" id="business-registration" <?php echo $selectedPurpose === 'business-registration' ? 'checked' : ''; ?>>
                            <div class="selected-indicator">✓</div>
                            <div class="purpose-icon">🏢</div>
                            <div class="purpose-title">Business Registration</div>
                            <div class="purpose-examples">Examples: Business licenses, tenders, supplier registration</div>
                        </div>

                        <div class="purpose-card <?php echo $selectedPurpose === 'property-rental' ? 'selected' : ''; ?>" onclick="selectPurpose('property-rental')">
                            <input type="radio" name="purpose" value="property-rental" id="property-rental" <?php echo $selectedPurpose === 'property-rental' ? 'checked' : ''; ?>>
                            <div class="selected-indicator">✓</div>
                            <div class="purpose-icon">🏠</div>
                            <div class="purpose-title">Property Rental</div>
                            <div class="purpose-examples">Examples: House rental, office space, commercial property</div>
                        </div>

                        <div class="purpose-card <?php echo $selectedPurpose === 'personal-verification' ? 'selected' : ''; ?>" onclick="selectPurpose('personal-verification')">
                            <input type="radio" name="purpose" value="personal-verification" id="personal-verification" <?php echo $selectedPurpose === 'personal-verification' ? 'checked' : ''; ?>>
                            <div class="selected-indicator">✓</div>
                            <div class="purpose-icon">👤</div>
                            <div class="purpose-title">Personal Verification</div>
                            <div class="purpose-examples">Examples: Credit monitoring, identity verification</div>
                        </div>

                        <div class="purpose-card <?php echo $selectedPurpose === 'visa-application' ? 'selected' : ''; ?>" onclick="selectPurpose('visa-application')">
                            <input type="radio" name="purpose" value="visa-application" id="visa-application" <?php echo $selectedPurpose === 'visa-application' ? 'checked' : ''; ?>>
                            <div class="selected-indicator">✓</div>
                            <div class="purpose-icon">✈️</div>
                            <div class="purpose-title">Visa/Travel</div>
                            <div class="purpose-examples">Examples: Schengen visa, work permits, immigration</div>
                        </div>

                        <div class="purpose-card <?php echo $selectedPurpose === 'other' ? 'selected' : ''; ?>" onclick="selectPurpose('other')">
                            <input type="radio" name="purpose" value="other" id="other" <?php echo $selectedPurpose === 'other' ? 'checked' : ''; ?>>
                            <div class="selected-indicator">✓</div>
                            <div class="purpose-icon">📋</div>
                            <div class="purpose-title">Other Purpose</div>
                            <div class="purpose-description">Any other legitimate reason not listed above</div>
                        </div>
                    </div>
                </div>

                <div class="additional-info">
                    <h3>Important Information</h3>
                    <p>
                        Your CRB report contains sensitive financial information from all three licensed CRBs in Kenya: CreditInfo CRB, Metropol CRB, and TransUnion Kenya CRB.
                    </p>
                </div>

                <div class="form-group" id="additionalDetails" style="<?php echo $selectedPurpose === 'other' ? 'display: block;' : 'display: none;'; ?>">
                    <label for="details">Additional Details (Required for "Other Purpose")</label>
                    <textarea 
                        id="details" 
                        name="additional_details" 
                        placeholder="Please provide specific details about why you need the CRB report..."
                    ><?php echo htmlspecialchars($additionalDetails); ?></textarea>
                </div>

                <div class="action-buttons">
                    <a href="dashboard.php" class="btn btn-secondary">
                        ← Back to Dashboard
                    </a>
                    <button type="submit" class="btn btn-primary" id="continueBtn" <?php echo empty($selectedPurpose) ? 'disabled' : ''; ?>>
                        Continue →
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Processing Popup -->
    <div class="popup-overlay" id="processingPopup">
        <div class="processing-popup">
            <div class="popup-icon">
                ⚡
            </div>
            <h3 class="popup-title">Processing Your Request</h3>
            <p class="popup-subtitle">Please wait while we prepare your CRB report application</p>
            
            <div class="progress-container">
                <div class="progress-track">
                    <div class="progress-fill" id="progressFill"></div>
                </div>
                <div class="progress-percentage" id="progressPercentage">0%</div>
            </div>

            <div class="processing-status" id="processingStatus">
                <span>Initializing</span>
                <div class="processing-dots">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>

            <div class="success-animation" id="successAnimation">
                <div class="checkmark">✓</div>
                <span>Processing Complete!</span>
            </div>
        </div>
    </div>

    <script>
        let selectedPurpose = '<?php echo $selectedPurpose; ?>';

        function selectPurpose(purpose) {
            // Remove selected class from all cards
            document.querySelectorAll('.purpose-card').forEach(card => {
                card.classList.remove('selected');
            });

            // Add selected class to clicked card
            event.currentTarget.classList.add('selected');

            // Check the radio button
            document.getElementById(purpose).checked = true;
            selectedPurpose = purpose;

            // Show/hide additional details section
            const additionalDetails = document.getElementById('additionalDetails');
            if (purpose === 'other') {
                additionalDetails.style.display = 'block';
                document.getElementById('details').required = true;
            } else {
                additionalDetails.style.display = 'none';
                document.getElementById('details').required = false;
            }

            // Enable continue button
            document.getElementById('continueBtn').disabled = false;

            // Hide error message
            document.getElementById('errorMessage').style.display = 'none';
        }

        // Processing popup functionality
        function showProcessingPopup() {
            const popup = document.getElementById('processingPopup');
            const progressFill = document.getElementById('progressFill');
            const progressPercentage = document.getElementById('progressPercentage');
            const processingStatus = document.getElementById('processingStatus');
            const successAnimation = document.getElementById('successAnimation');

            // Show popup
            popup.classList.add('show');
            document.body.style.overflow = 'hidden';

            // Processing steps with different durations
            const steps = [
                { text: 'Initializing request', duration: 1000 },
                { text: 'Validating your information', duration: 1500 },
                { text: 'Connecting to CRB databases', duration: 2000 },
                { text: 'Fetching credit information', duration: 2500 },
                { text: 'Compiling your report', duration: 2000 },
                { text: 'Finalizing processing', duration: 1000 }
            ];

            let currentProgress = 0;
            let stepIndex = 0;
            let totalDuration = 10000;
            let elapsed = 0;

            const interval = setInterval(() => {
                elapsed += 50;
                
                // Calculate progress percentage
                const newProgress = Math.min((elapsed / totalDuration) * 100, 100);
                currentProgress = newProgress;

                // Update progress bar and percentage
                progressFill.style.width = currentProgress + '%';
                progressPercentage.textContent = Math.floor(currentProgress) + '%';

                // Update status text based on progress
                const stepProgress = elapsed / (totalDuration / steps.length);
                const newStepIndex = Math.floor(stepProgress);
                
                if (newStepIndex !== stepIndex && newStepIndex < steps.length) {
                    stepIndex = newStepIndex;
                    processingStatus.innerHTML = `
                        <span>${steps[stepIndex].text}</span>
                        <div class="processing-dots">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    `;
                }

                // Complete processing
                if (elapsed >= totalDuration) {
                    clearInterval(interval);
                    
                    // Show completion
                    progressFill.style.width = '100%';
                    progressPercentage.textContent = '100%';
                    processingStatus.style.display = 'none';
                    successAnimation.style.display = 'flex';

                    // Submit the form after 2 seconds
                    setTimeout(() => {
                        document.getElementById('purposeForm').submit();
                    }, 2000);
                }
            }, 50);
        }

        // Form submission
        document.getElementById('purposeForm').addEventListener('submit', function(e) {
            if (!selectedPurpose) {
                e.preventDefault();
                document.getElementById('errorMessage').style.display = 'block';
                return;
            }

            // If "Other" is selected but no details provided
            if (selectedPurpose === 'other' && !document.getElementById('details').value.trim()) {
                e.preventDefault();
                document.getElementById('errorMessage').textContent = 'Please provide additional details for "Other Purpose"';
                document.getElementById('errorMessage').style.display = 'block';
                return;
            }

            // Show processing popup
            e.preventDefault();
            showProcessingPopup();
        });

        // Initialize the form based on PHP values
        document.addEventListener('DOMContentLoaded', function() {
            if (selectedPurpose) {
                document.getElementById(selectedPurpose).checked = true;
                document.getElementById('continueBtn').disabled = false;
            }
        });
    </script>
</body>
</html>