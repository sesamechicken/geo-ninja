<?php

include '../includes/config.php';
include '../includes/opendb.php';


if(isset($_POST['user'])){
	$user = $_POST['user'];
	$lat = $_POST['lat'];
	$lng = $_POST['lng'];
	$recipient = $_POST['recip'];

	// Clean up vars 
	$encoded_user = urlencode($user);

	// Add new record to the db
	$session_key = md5($user.$lat.$lng);

	$sql = "INSERT INTO positions (ninja_username, ninja_lat, ninja_lng, ninja_session_key) VALUES ('$user', '$lat', '$lng', '$session_key')";
	$result = mysql_query($sql) or die(mysql_error());

	$link = "http://www.project107.net/geo-ninja/?lat=". $lat ."&lng=". $lng ."&user=". $encoded_user ."&id=". $session_key;
	$msg = "<h2>geo-ninja tracking</h2> \n".
			"<p>". $user . " wants you to track them with the geo-ninja app. To see where they're at, click the tracking link below: </p> \n".
			$link;
	
    $headers = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: tracker@project107.net' . "\r\n" .
    'Reply-To: chris@project107.net' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

	mail( $recipient, $user . ' wants you to track them!', $msg, $headers ) or die("Failure on mail");

	// Everything is squared away. Link sent, first set of coords in db.
	echo '{"session_key": "'. $session_key .'"}';
}


?>
