<?php

include '../includes/config.php';
include '../includes/opendb.php';


if(isset($_GET['session_key'])){
	
	$session_key = $_GET['session_key'];

<<<<<<< HEAD
	$sql = "SELECT * FROM positions WHERE ninja_session_key = '$session_key' ORDER BY ninja_update_time DESC LIMIT 1";
=======

	$sql = "SELECT * FROM positions WHERE ninja_session_key = '$session_key' ORDER BY ninja_update_time DESC LIMIT 1";

>>>>>>> e483bd935f649a7ea8f067752993261577039cea
	$result = mysql_query($sql) or die(mysql_error());

	while($row = mysql_fetch_assoc($result)){
		$lat = $row['ninja_lat'];
		$lng = $row['ninja_lng'];
		$timestamp = $row['ninja_update_time'];
		$key = $row['ninja_session_key'];
	}
	// mail($recipient, $user . ' wants you to track them!', $msg, $headers);

	// Everything is squared away. Link sent, first set of coords in db.
<<<<<<< HEAD
	echo '{"lat": "'. $lat .'", "lng": "'. $lng .'", "session_key": "'. $session_key .'"}';
=======

	echo '{"lat": "'. $lat .'", "lng": "'. $lng .'", "session_key": "'. $session_key .'"}';

>>>>>>> e483bd935f649a7ea8f067752993261577039cea
}


?>
