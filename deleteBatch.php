<?php
//$servername = "localhost";
//$username = "jfweb";
//$password = "cff7a2726123"; //"jfweb0622";
$servername = "172.31.91.60";
$username = "remoteacc";
$password = "b3FFc99bFE34885"; //"jfweb0622";
$dbname = "jfweb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// 3366
// 3403, 3404, 3405, 3406, 3408, 3410, 3411 and 3412
// 3407, 3409, 3419
// 3421,3422,3427
// 3443
// 3449
// 3439
// 3486. 3487, 3488, 3489
// 3504
// 3524
$sql = "SELECT * FROM  `plan` WHERE  `batch_number`=3524";
echo $sql."\n";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $plan_id = $row["plan_id"];

	$sql = "DELETE FROM customer where plan_id = '" . (int)$plan_id . "'";
	echo $sql."\n";
	$conn->query($sql);
	$sql = "DELETE FROM payment where plan_id = '" . (int)$plan_id . "'";
	echo $sql."\n";
	$conn->query($sql);
	$sql = "DELETE FROM plan where plan_id = '" . (int)$plan_id . "'";
	echo $sql."\n";
	$conn->query($sql);
    }
} else {
    echo "0 results";
}
$conn->close();
