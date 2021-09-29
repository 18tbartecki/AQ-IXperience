<?php
	if ( !isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || $_SESSION["username"] != "admin") {
		header('Location: search.php');
	}

	if ( !isset($_GET['location_id']) || empty($_GET['location_id'])) {
		header('Location: search.php');
	} 
	else {
		
		require 'config.php';

		// DB Connection
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if ( $mysqli->connect_errno ) {
			echo $mysqli->connect_error;
			exit();
		}

		$mysqli->set_charset('utf8');

		// Remove from any users favorite list first
		$fav_statement = $mysqli->prepare("DELETE FROM favorites WHERE location_id = ?");

		$fav_statement->bind_param("i", $_GET['location_id']);

		$fav_executed = $fav_statement->execute();

		if(!$fav_executed) {
			echo $mysqli->error;
			exit();
		}

		// Remove from master list next
		$statement = $mysqli->prepare("DELETE FROM locations WHERE location_id = ?");

		$statement->bind_param("i", $_GET['location_id']);

		$executed = $statement->execute();
		
		if(!$executed) {
			echo $mysqli->error;
			exit();
		}

		$city = $_GET['city'];

		$mysqli->close();
		header("Location: search_results.php?city=$city");

	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Delete</title>
</head>
<body>

</body>
</html>