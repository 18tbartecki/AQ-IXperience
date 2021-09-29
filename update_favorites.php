<?php

	require 'config.php';

	// DB Connection
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if ( $mysqli->errno ) {
		echo $mysqli->error;
		exit();
	}

	$location_id = $_GET["location_id"];

	// Get location search terms to call the API
	$statement = $mysqli->prepare("SELECT * FROM locations WHERE location_id = ?");

	$statement->bind_param("i", $location_id);

	$statement->execute();
	$results = $statement->get_result();

	if(!$results) {
		echo $mysqli->error;
		exit();
	}

	if(mysqli_num_rows($results) == 1) {
		$row = $results->fetch_assoc();
		$slug = $row["slug"];
		$aqi_search = $row["aqi_search"];
		if($slug == "Data Not Available") {
			$quality = "Data Not Available";
		}
		else {

		    $full_url = "https://api.teleport.org/api/urban_areas/slug:" . $slug . "/scores/";


		    $curl = curl_init();
		    curl_setopt_array($curl, array(
		        CURLOPT_URL => $full_url,
		        CURLOPT_RETURNTRANSFER => true,
		        CURLOPT_HTTPHEADER => array(
		            'Accept: application/vnd.teleport.v1+json'
		        )
		    ));


		    $response = curl_exec($curl);
		    $response = json_decode($response, true);


		    foreach($response["categories"] as $category) {

		        $metricData = array(
		            "color" => $category["color"],
		            "name" => $category["name"],
		            "score_out_of_10" => $category["score_out_of_10"]
		        );

		        if($category["name"] == "Environmental Quality") {
		        	$quality = $category["score_out_of_10"];
		        }

		    }
		    
		}

		if($aqi_search == "Data Not Available") {
			$aqi = "Data Not Available";
		}
		else {

			$data = array(
			        "city" => $aqi_search,
			        "state" => $row["state"],
			        "country" => $row["country"],
			        "key" => $api_key
			    );


		    $full_url = "https://thawing-shore-61339.herokuapp.com/http://api.airvisual.com/v2/city?" . http_build_query($data);

		
		    $curl = curl_init();
		    curl_setopt_array($curl, array(
		        CURLOPT_URL => $full_url,
		        CURLOPT_RETURNTRANSFER => true,
		        CURLOPT_HTTPHEADER => array(
		        	"X-Requested-With: XMLHttpRequest"
		        )
		        
		    ));


		    $response = curl_exec($curl);

		    $response = json_decode($response, true);

		    $filteredResponse = array();

		    $timeframes = $response["data"];
		    $current = $timeframes["current"];
		    $pollution = $current["pollution"];

		    $aqi = $pollution["aqius"];
		    $pollutant = $pollution["mainus"];
		    


		    if ($aqi == "Data Not Available") {
		    	$pollutant = "Data Not Available";
		    }
			else if ($pollutant == "p2") {
				$pollutant = "PM2.5";
			}
			else if($pollutant == "p1") {
				$pollutant = "PM1.0";
			}	
			else if($pollutant == "o3") {
				$pollutant = "O3";
			}
			else if($pollutant == "n2") {
				$pollutant = "N2";
			}
			else if($pollutant == "s2") {
				$pollutant = "S2";
			}
			else if($pollutant == "co") {
				$pollutant = "CO";
			}
		    
		}
	}
	else {
		$error = "Invalid update. Please try again.";
	}


	// Now that we have new data, update the favorites table with it
	$update_statement = $mysqli->prepare("UPDATE favorites SET aqi = ?, quality = ?, pollutant = ? WHERE user_id = ? AND location_id = ?");
	
	$update_statement->bind_param("ddsii", $aqi, $quality, $pollutant, $_SESSION["user_id"], $location_id);


	$update_executed = $update_statement->execute();

	if(!$update_executed) {
	 	echo $mysqli->error;
	 	exit();
	}

	$update_statement->close();
	$mysqli->close();

	header("Location: favorites.php");


?>