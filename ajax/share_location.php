<?php
// Called only once when user initiates a share

include '../includes/config.php';
include '../includes/opendb.php';

if(isset($_POST['user'])){
	$user = $_POST['user'];
	$lat = $_POST['lat'];
	$lng = $_POST['lng'];
	$accuracy = $_POST['acc'];
	$recipient = $_POST['recip'];

	// Clean up vars 
	$user = trim($user);
	$encoded_user = urlencode($user);

	$recipient = trim($recipient);

	// Add new record to the db
	$session_key = md5($user.$lat.$lng.$accuracy);

	$sql = "INSERT INTO positions (ninja_username, ninja_lat, ninja_lng, ninja_accuracy, ninja_session_key) VALUES ('$user', '$lat', '$lng', '$accuracy', '$session_key')";
	$result = mysql_query($sql) or die(mysql_error());

	//$link = "http://www.project107.net/geo-ninja/?lat=". $lat ."&lng=". $lng ."&user=". $encoded_user ."&id=". $session_key;

	$link = "http://www.project107.net/geo-ninja/?id=". $session_key;

	$msg = "<img src='http://project107.net/geo-ninja/img/marker.png' alt='geo-ninja' /> <h1>geo-ninja tracking</h1> \n".
			"<p>". $user . " wants you to track them with the geo-ninja app. To see where they're at, click the tracking link below: </p> \n".
			$link;
	
    $headers = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: geo-ninja@project107.net' . "\r\n" .
    'Reply-To: chris@project107.net' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

	mail( $recipient, $user . ' wants you to track them!', $msg, $headers ) or die("Failure on mail");

	// Everything is squared away. Link sent, first set of coords in db.
	echo '{"session_key": "'. $session_key .'"}';

}


?>
