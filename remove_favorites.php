<?php

	require 'config.php';
	
	if ( !isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
		header('Location: search.php');
	}

	if ( !isset($_GET['location_id']) || empty($_GET['location_id']) || 
		 !isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
		header('Location: search.php');
	} 
	else {

		// DB Connection
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if ( $mysqli->connect_errno ) {
			echo $mysqli->connect_error;
			exit();
		}

		$mysqli->set_charset('utf8');

		$location_id = $_GET['location_id'];

		// Remove from any users favorite list first
		$statement = $mysqli->prepare("DELETE FROM favorites WHERE location_id = ? AND user_id = ?");

		$statement->bind_param("ii", $location_id, $_SESSION["user_id"]);

		$executed = $statement->execute();

		if(!$executed) {
			echo $mysqli->error;
			exit();
		}

		$mysqli->close();
		if($_GET["page"] == "details") {
			header("Location: city-details.php?location_id=$location_id");
		}
		else {
			header("Location: favorites.php");
		}

	}

?>