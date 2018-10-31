<?php
	include 'Token.php';
	$params = parse_ini_file("../config.ini");
	$conn = mysqli_connect($params['hostname'],$params['username'],$params['password'],$params['db_name']);

	if (mysqli_connect_errno($conn)) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	$username = $_POST['username'];
	$password = $_POST['password'];
	$score1 = $_POST['match_game'];
	$score2 = $_POST['difference_game'];
	$level1 = $_POST['match_level'];
	$level2 = $_POST['difference_level'];
	$token = $_POST['token'];
	
	if (strlen($token) == 45) {
		$res = mysqli_query($conn, "SELECT token FROM user WHERE username = '$username';");
		$res = mysqli_fetch_object($res);
		$db_token = $res->token;
		
		if ($db_token == $token) {
			$result = mysqli_query($conn, "SELECT password FROM user WHERE username = '$username';");
			$result = mysqli_fetch_object($result);
			if (sha1($password) == $result->password) {
				$result = mysqli_query($conn, "SELECT id_user FROM user WHERE username = '$username';");
				$result = mysqli_fetch_object($result);
				$id_user = $result->id_user;

				if (!$result) {
					$send_data->res = "false";
					$json = json_encode($send_data);
					print($json);
				} else {
					$result = mysqli_query($conn, "SELECT max_score,level FROM user_task WHERE id_task = '1' AND id_user = '$username';");
					$result = mysqli_fetch_object($result);
					if (($score1 > ($result->max_score + 20)) || ($score1 < $result->max_score)) {
						$score1 = $result->max_score;
						echo nl2br("…………………./´¯/) \n");
						echo nl2br("………………..,/¯../ \n");
						echo nl2br("………………./…./ \n");
					}
					if (($level1 > ($result->level + 2)) || ($score1 < $result->level - 2) || ($score1 < 1)) {
						$level1 = $result->level;
						echo nl2br("…………./´¯/’…’/´¯¯`·¸ \n");
						echo nl2br("………./’/…/…./……./¨¯\ \n");
					}
					$result = mysqli_query($conn, "UPDATE user_task SET max_score = '$score1', level = '$level1' WHERE id_task = '1' AND id_user = '$id_user';");

					$result = mysqli_query($conn, "SELECT max_score,level FROM user_task WHERE id_task = '2' AND id_user = '$username';");
					$result = mysqli_fetch_object($result);
					if (($score2 > ($result->max_score + 20)) || ($score2 < $result->max_score)) {
						$score2 = $result->max_score;
						echo nl2br("……..(‘(…´…´…. ¯~/’…’) \n");
						echo nl2br("………\……………..’…../ \n");
						echo nl2br("……….”…\………. _.·´ \n");
					}
					if (($level2 > ($result->level + 2)) || ($score2 < $result->level - 2) || ($score2 < 1)) {
						$level2 = $result->level;
						echo nl2br("…………\…………..( \n");
						echo nl2br("…………..\………….\ \n");
					}
					$result = mysqli_query($conn, "UPDATE user_task SET max_score = '$score2', level = '$level2' WHERE id_task = '2' AND id_user = '$id_user';");

					$send_data->res = "true";
					$json = json_encode($send_data);
					print($json);
				}
			} else {
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
