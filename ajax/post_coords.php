<?php
include '../includes/config.php';
include '../includes/opendb.php';

if(isset($_POST['session_key'])){
	$user = $_POST['user'];
	$lat = $_POST['lat'];
	$lng = $_POST['lng'];
	$session_key = $_POST['session_key'];
	$accuracy = $_POST['accuracy'];
	$sql = "INSERT INTO positions (ninja_username, ninja_lat, ninja_lng, ninja_accuracy, ninja_session_key) VALUES ('$user', '$lat', '$lng', '$accuracy', '$session_key')";
	$result = mysql_query($sql) or die(mysql_error());
	echo '{"status": "success"}';
}

?>