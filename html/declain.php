<?php
// Set the filename for logging
$logFile = 'declain.txt';

// Function to log access information
function logAccess($logFile) {
    // Get the request method
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    $getData = $_GET ? json_encode($_GET) : 'No GET data';
    $postData = $_POST ? json_encode($_POST) : 'No POST data';
		$rawInput = file_get_contents('php://input') ?: 'No raw input';
		$headers = json_encode(getallheaders());
		$logEntry = date('Y-m-d H:i:s') . PHP_EOL .
				" - Method: $requestMethod" . PHP_EOL .
				" - Headers: $headers" . PHP_EOL .
				" - GET: $getData" . PHP_EOL .
				" - POST: $postData" . PHP_EOL .
				" - RAW: $rawInput" . PHP_EOL . PHP_EOL;
		file_put_contents($logFile, $logEntry, FILE_APPEND);
}

// Call the logging function
logAccess($logFile);

// Respond to the web call
header('Content-Type: application/json');
echo json_encode(['status' => 'success', 'message' => 'Access logged successfully.']);
