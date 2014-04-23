<?php

include '../includes/config.php';
include '../includes/opendb.php';


if(isset($_GET['session_key'])){
	
	$session_key = $_GET['session_key'];

	$sql = "SELECT * FROM positions WHERE ninja_session_key = '$session_key' LIMIT 1";
	$result = mysql_query($sql) or die(mysql_error());

	while($row = mysql_fetch_assoc($result)){
		$lat = $row['ninja_lat'];
		$lng = $row['ninja_lng'];
		$timestamp = $row['ninja_update_time'];
		$key = $row['ninja_session_key'];
	}
	// mail($recipient, $user . ' wants you to track them!', $msg, $headers);

	// Everything is squared away. Link sent, first set of coords in db.
	echo '{"lat": "'. $lat .'", "lng": "'. $lng .'", "timestamp": "'. $timestamp .'", "session_key": "'. $session_key .'"}';
}


?>
