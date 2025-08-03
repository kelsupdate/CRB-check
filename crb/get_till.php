<?php
header('Content-Type: application/json');

// Database connection
$conn = new mysqli('172.18.180.9', 'grandpac_nash1', 'Kenya@50', 'grandpac_crb_checker_ke');

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed']));
}

// Get current time and calculate rotation window
$currentHour = date('H');
$rotationWindow = floor($currentHour / 6);

// Check if we need to rotate
$result = $conn->query("SELECT * FROM till_numbers WHERE is_active = TRUE");
if ($result->num_rows > 0) {
    $activeTill = $result->fetch_assoc();
    $lastRotation = strtotime($activeTill['last_activated']);
    $hoursSinceRotation = (time() - $lastRotation) / 3600;
    
    if ($hoursSinceRotation >= 6) {
        // Time to rotate
        $conn->query("UPDATE till_numbers SET is_active = FALSE");
        
        // Get the inactive till number
        $newActive = $conn->query("SELECT * FROM till_numbers WHERE is_active = FALSE LIMIT 1")->fetch_assoc();
        $conn->query("UPDATE till_numbers SET is_active = TRUE, last_activated = NOW() WHERE id = {$newActive['id']}");
    }
}

// Get the currently active till number
$activeTill = $conn->query("SELECT number FROM till_numbers WHERE is_active = TRUE LIMIT 1")->fetch_assoc();

echo json_encode(['till_number' => $activeTill['number']]);
$conn->close();
?>