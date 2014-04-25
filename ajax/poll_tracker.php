<?php
// Called repeatedly to get the users' position

include '../includes/config.php';
include '../includes/opendb.php';

if(isset($_GET['session_key'])){
	
	$session_key = $_GET['session_key'];

	$sql = "SELECT * FROM positions WHERE ninja_session_key = '$session_key' ORDER BY ninja_update_time DESC LIMIT 1";
	$result = mysql_query($sql) or die(mysql_error());

	while($row = mysql_fetch_assoc($result)){
		$lat = $row['ninja_lat'];
		$lng = $row['ninja_lng'];
		$timestamp = $row['ninja_update_time'];
		$key = $row['ninja_session_key'];
		$accuracy = $row['ninja_accuracy'];
		$username = $row['ninja_username'];
	}
	// Send back the JSON data
	echo '{"lat": "'. $lat .'", "lng": "'. $lng .'", "session_key": "'. $session_key .'", "accuracy": "'. $accuracy . '", "timestamp": "'. $timestamp .'", "username": "'. $username .'"}';
}

?>