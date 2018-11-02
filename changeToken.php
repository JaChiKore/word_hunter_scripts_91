<?php
	include 'Token.php';
	$params = parse_ini_file("../config.ini");
	$conn = mysqli_connect($params['hostname'],$params['username'],$params['password'],$params['db_name']);

	if (mysqli_connect_errno($conn)) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	$username = $_POST['username'];
	$password = $_POST['password'];
	$token = $_POST['token'];
	
	if (strlen($token) == 45) {
		$res = mysqli_query($conn, "SELECT token FROM user WHERE username = '$username';");
		$res = mysqli_fetch_object($res);
		$db_token = $res->token;
		
		if ($db_token == $token) {
			$result = mysqli_query($conn, "SELECT password FROM user WHERE username = '$username';");
			$result = mysqli_fetch_object($result);
			if (sha1($password) == $result->password) {
				$token = generateToken();
				mysqli_query($conn, "UPDATE user SET token = '$token' WHERE username = '$username';");
				$send_data->token = $token;
				$send_data->res = "true";
				$json = json_encode($send_data);
				print($json);
			} else {
				$send_data->token = $token;
				$send_data->res = "false";
				$json = json_encode($send_data);
				print($json);
			}
		} else {
			print("404 Not Found");
		}
	} else {
		print("404 Not Found");
	}
	mysqli_close();

?>
