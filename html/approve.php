<?php
// Set the filename for logging
$logFile = 'approve.txt';

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
// {"trnApproved":"1","trnId":"10000233","messageId":"1","messageText":"Approved","authCode":"TEST","responseType":"T","trnAmount":"306.72","trnDate":"1\/8\/2026 7:27:02 AM","trnOrderNumber":"6332-297","trnLanguage":"eng","trnCustomerName":"test","trnEmailAddress":"zeyu@otcww.com","trnPhoneNumber":"3433335672","avsProcessed":"1","avsId":"N","avsResult":"0","avsAddrMatch":"0","avsPostalMatch":"0","avsMessage":"Street address and Postal\/ZIP do not match.","cvdId":"1","cardType":"VI","trnType":"P","paymentMethod":"CC","ref1":"monthly","ref2":"6332","ref3":"297","ref4":"","ref5":"","hashValue":"4a25a898337360c27cc14dddab545da6"}
// Call the logging function
logAccess($logFile);

// Respond to the web call
// header('Content-Type: application/json');
// echo json_encode(['status' => 'success', 'message' => 'Access logged successfully.']);
if (isset($_GET["ref2"])) {
	$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
	$host   = $_SERVER['HTTP_HOST'];
	
	if (isset($_GET["ref4"]) && ($_GET["ref4"] == "web")) {
		header("Location: {$scheme}://{$host}/plan/detail/".$_GET["ref2"]);
	}
} else {
	header("Location: {$scheme}://{$host}/user/login");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Success</title>
<style>
    body {
        margin: 0;
        height: 100vh;
        position: relative;
        font-family: Arial, sans-serif;
				background: #EDEDED;
    }
    .success-text {
        position: absolute;
        left: 50%;
        top: 30%; /* slightly above 1/3 (33%) */
        transform: translate(-50%, -50%);
        font-size: 48px;
        font-weight: bold;
    }
</style>
</head>
<body>
<div class="success-text">Success</div>
</body>
</html>
