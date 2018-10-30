<?php
	include 'changeToken.php';
	$params = parse_ini_file("../config.ini");
	$conn = mysqli_connect($params['hostname'],$params['username'],$params['password'],$params['db_name']);

	if (mysqli_connect_errno($conn)) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	$username = $_REQUEST['username'];
	$password = $_REQUEST['password'];
	$token = $_REQUEST['token'];

	$result = mysqli_query($conn, "SELECT id_user FROM user WHERE username = '$username';");

	while ($row = mysqli_fetch_assoc($result)) {
		$output[] = $row[id_user];
	}

	$count = count($output);

	if ($count == 1) {
		$result = mysqli_query($conn, "SELECT u.password FROM user u WHERE u.username = '$username';");
		$result = mysqli_fetch_assoc($result);
		if (sha1($password) == $result[password]) {
			$token = getNewToken($token);
			mysqli_query($conn, "UPDATE user SET token = '$token' WHERE username = '$username';");
			$send_data->token = $token;
			$send_data->res = "true";
			$json = json_encode($send_data);
			print($json);
		} else {
			$send_data->token = -1;
			$send_data->res = "false";
			$json = json_encode($send_data);
			print($json);
		}
	} else {
		$send_data->token = -1;
		$send_data->res = "false";
		$json = json_encode($send_data);
		print($json);
	}

	mysqli_close();

?>
