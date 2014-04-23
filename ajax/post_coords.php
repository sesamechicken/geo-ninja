<?php

include '../includes/config.php';
include '../includes/opendb.php';


if(isset($_POST['session_key'])){
	$user = $_POST['user'];
	$lat = $_POST['lat'];
	$lng = $_POST['lng'];
	$session_key = $_POST['session_key'];

	$sql = "INSERT INTO positions (ninja_username, ninja_lat, ninja_lng, ninja_session_key) VALUES ('$user', '$lat', '$lng', '$session_key')";
	$result = mysql_query($sql) or die(mysql_error());

	// mail($recipient, $user . ' wants you to track them!', $msg, $headers);

	// Everything is squared away. Link sent, first set of coords in db.
	echo '{"status": "success"}';
}


?>
