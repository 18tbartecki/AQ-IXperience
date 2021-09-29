<?php

	require 'config.php';

	if ( !isset($_GET['location_id']) || empty($_GET['location_id']) 
		|| !isset($_SESSION['user_id']) || empty($_SESSION['user_id']) 
		|| !isset($_GET['city']) || empty($_GET['city'])
		|| !isset($_GET['state']) || empty($_GET['state'])
		|| !isset($_GET['country']) || empty($_GET['country'])
		|| !isset($_GET['aqi']) || empty($_GET['aqi'])
		|| !isset($_GET['quality']) || empty($_GET['quality'])
		|| !isset($_GET['pollutant']) || empty($_GET['pollutant'])) {

		$error = "All data not submitted.";
		//header("Location: search.php");

	} 
	else {
		// All required fields provided.

		// DB Connection
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if ( $mysqli->errno ) {
			echo $mysqli->error;
			exit();
		}

		$location_id = $_GET["location_id"];
		var_dump($location_id);
		$statement = $mysqli->prepare("INSERT INTO favorites(city, state, country, aqi, quality, pollutant, location_id, user_id, image_url) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
		var_dump($statement);
		$statement->bind_param("sssddsiis", $_GET["city"], $_GET["state"], $_GET["country"], $_GET["aqi"], $_GET["quality"], $_GET["pollutant"], $location_id, $_SESSION["user_id"], $_GET["image_url"]);

		$executed = $statement->execute();

		if(!$executed) {
		 	echo $mysqli->error;
		 	exit();
		}

		$statement->close();
		$mysqli->close();

		header("Location: city-details.php?location_id=$location_id");

	}

?>