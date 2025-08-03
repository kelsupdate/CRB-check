<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRB Report Purpose</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #333;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        h2 {
            color: #444;
            margin-top: 30px;
        }
        h3 {
            color: #555;
        }
        ul {
            list-style-type: none;
            padding-left: 0;
        }
        li {
            margin-bottom: 8px;
            padding-left: 20px;
            position: relative;
        }
        li:before {
            content: "•";
            position: absolute;
            left: 0;
            color: #666;
        }
        .section {
            margin-bottom: 30px;
        }
        .option {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .examples {
            margin-left: 20px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <h1>Report Purpose</h1>
    
    <div class="section">
        <p>Please select the primary reason you need your CRB report. This helps us provide you with the most appropriate documentation for your specific needs.</p>
        
        <ul>
            <li class="option">1. Registration</li>
            <li class="option">2. Purpose</li>
            <li class="option">3. Report</li>
        </ul>
    </div>

    <hr>

    <div class="section">
        <h2>Why do you need your CRB report?</h2>
        <p>Select the option that best describes your reason for requesting a CRB report.</p>
    </div>

    <div class="section">
        <h3>Loan Application</h3>
        <div class="examples">
            <p>Examples: Bank loans, mortgage, business loans</p>
            <p>Examples: Banking jobs, government positions, security roles</p>
        </div>
    </div>

    <div class="section">
        <h3>Employment</h3>
        <ul>
            <li class="option">Business Registration</li>
            <div class="examples">
                <p>Examples: Business licenses, tenders, supplier registration</p>
                <p>Examples: House rental, office space, commercial property</p>
            </div>
        </ul>
    </div>

    <div class="section">
        <h3><?php echo "[Property Rental]"; ?></h3>
    </div>
</body>
</html>