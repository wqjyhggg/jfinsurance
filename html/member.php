<?php
// Set the filename for logging
$logFile = 'member.txt';

// Function to log access information
function logAccess($logFile) {
    // Get the request method
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    
    // Get GET data
    $getData = $_GET ? json_encode($_GET) : 'No GET data';
    
    // Get POST data
    $postData = $_POST ? json_encode($_POST) : 'No POST data';
    
    // Create a log entry
    $logEntry = date('Y-m-d H:i:s') . " - Method: $requestMethod - GET: $getData - POST: $postData" . PHP_EOL;
    
    // Write the log entry to the file
    file_put_contents($logFile, $logEntry, FILE_APPEND);
}

// Call the logging function
logAccess($logFile);

// Respond to the web call
header('Content-Type: application/json');
echo json_encode(['status' => 'success', 'message' => 'Access logged successfully.']);
