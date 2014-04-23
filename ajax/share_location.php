<?php

include '../includes/config.php';
include '../includes/opendb.php';


if(isset($_POST['user'])){
	$user = $_POST['user'];
	$lat = $_POST['lat'];
	$lng = $_POST['lng'];
	$recipient = $_POST['recip'];

	// Add new record to the db
	$session_key = md5($user.$lat.$lng);

	$sql = "INSERT INTO positions (ninja_username, ninja_lat, ninja_lng, ninja_session_key) VALUES ('$user', '$lat', '$lng', '$session_key')";
	$result = mysql_query($sql) or die(mysql_error());

	$link = "http://www.project107.net/geo-ninja/?lat=". $lat ."&lng=". $lng ."&user=". $user ."&id=". $session_key;
	$msg = "geo-ninja tracking \n".
			$user . " wants you to track them with the geo-ninja app. To see where they're at, click the tracking link below: \n".
			$link;
	$headers = 'From: tracker@project107.net' . "\r\n" .
    'Reply-To: chris@project107.net' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

	mail($recipient, $user . ' wants you to track them!', $msg, $headers) or die("Failure on mail");

	// Everything is squared away. Link sent, first set of coords in db.
	echo '{"session_key": "48deeda8a3f2984670bd316057a8fd5f"}';
}


?>
