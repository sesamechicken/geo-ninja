<?php




if(isset($_POST['user'])){
	$user = $_POST['user'];
	$lat = $_POST['lat'];
	$lng = $_POST['lng'];
	$recipient = $_POST['recip'];

	// Add new record to the db
	$session_key = md5($user.$lat.$lng);

	$sql = "INSERT INTO geo_ninja (user, lat, lng, session_key) VALUES ($user, $lat, $lng, $session_key)";
	$result = mysql_query($sql) or die(mysql_error());

	$link = "track.php?k=" . $session_key;
	$msg = "geo-ninja tracking \n".
			$user . " wants you to track them with the geo-ninja app. To see where they're at, click the tracking link below: \n".
			$link;
	$headers = 'From: tracker@project-107.net' . "\r\n" .
    'Reply-To: chris@project-107.net' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

	mail($recipient, $user . ' wants you to track them!', $msg, $headers);

	// Everything is squared away. Link sent, first set of coords in db.
	echo "[session_key: '$session_key']";
}


?>
