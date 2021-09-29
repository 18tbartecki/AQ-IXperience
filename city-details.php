<?php
	if (!isset($_GET['location_id']) || empty($_GET['location_id']) ) {
		$error = "Invalid location. Please select another.";
	} 
	else {
		require 'config.php';

		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if ( $mysqli->connect_errno ) {
			echo $mysqli->connect_error;
			exit();
		}

		$mysqli->set_charset('utf8');

		$location_id = $_GET['location_id'];

		// Check if location is already in user's favorites
		$fav_statement = $mysqli->prepare("SELECT * FROM favorites WHERE location_id = ? AND user_id = ?");

		$fav_statement->bind_param("ii", $location_id, $_SESSION["user_id"]);

		$fav_statement->execute();
		$fav_results = $fav_statement->get_result();

		if(!$fav_results) {
			echo $mysqli->error;
			exit();
		}

		if(mysqli_num_rows($fav_results) == 1) {
			$favorite = true;
		}
		else {
			$favorite = false;
		}

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

			    $filteredResponse = array();
			    $summary = $response["summary"];

			    foreach($response["categories"] as $category) {

			        $metricData = array(
			            "color" => $category["color"],
			            "name" => $category["name"],
			            "score_out_of_10" => $category["score_out_of_10"]
			        );

			        if($category["name"] == "Environmental Quality") {
			        	$quality = $category["score_out_of_10"];
			        }
			        else if($category["name"] == "Housing") {
			        	$housing = $category["score_out_of_10"];
			        }
			        else if($category["name"] == "Safety") {
			        	$safety = $category["score_out_of_10"];
			        }
			        else if($category["name"] == "Education") {
			        	$education = $category["score_out_of_10"];
			        }
			        else if($category["name"] == "Economy") {
			        	$economy = $category["score_out_of_10"];
			        }
			        else if($category["name"] == "Healthcare") {
			        	$healthcare = $category["score_out_of_10"];
			        }
			        else if($category["name"] == "Outdoors") {
			        	$outdoors = $category["score_out_of_10"];
			        }

			        $filteredResponse[] = $metricData;
			    
			    }
			    
			}


			if($aqi_search == "Data Not Available") {
				$aqi = "Data Not Available";
				$pollutant = "Data Not Available";
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


    			if($aqi == 0) {
    				$aqi = "0";
    				$pollutant = "Data Not Available";
    			}

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
			$error = "Invalid result. Please try again.";
		}

		$mysqli->close();
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>City Information</title>
		<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link rel="stylesheet" href="city-details.css" type="text/css">
		<link rel="stylesheet" href="footer.css" type="text/css">
		<script src="https://kit.fontawesome.com/b4b2d9549e.js" crossorigin="anonymous"></script>
		<script src='https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js'></script>
	
		<script src="chart_data.js"></script>

	</head>
	<body>
		<?php include 'nav.php' ?>

		<div class="container">
			<div class="row" id="location">
				<p class="col-12 mt-2"><a href="search_results.php?city=<?php echo $_GET['search_term']; ?>"><i class="fas fa-chevron-left mr-4 ml-3"></i></a> <?php echo $row["city"] ?>, <?php echo $row["state"] ?></p>
			</div> 
		</div> 

		<div class="container" id="city-data">
			<?php if ( isset($error) && !empty($error) ) : ?>

				<div class="text-danger">
					<?php echo $error; ?>
				</div>

			<?php else : ?>
				<div class="row mt-3">
					<div class="col-12 col-lg-4">
						<div class="metric-heading my-2">Air Quality Index</div>
						<div class="index text-center mb-4 mt-2" id="AQI">
						<?php if ($aqi == "Data Not Available" || $aqi == "0"): ?>
							<small><?php echo $aqi?></small>
						<?php elseif ($aqi > 301): ?>
							<p style="color: #7e0023;"><?php echo $aqi?></p>
						<?php elseif($aqi > 201): ?>
							<p style="color: #99004c;"><?php echo $aqi?></p>
						<?php elseif($aqi > 151): ?>
							<p style="color: #ff0000;"><?php echo $aqi?></p>
						<?php elseif($aqi > 101): ?>
							<p style="color: #ff7e00;"><?php echo $aqi?></p>
						<?php elseif($aqi > 51): ?>
							<p style="color: #ffff00;"><?php echo $aqi?></p>
						<?php else : ?>
							<p style="color: #00e400;"><?php echo $aqi?></p>
						<?php endif; ?>
						</div>
					</div> 
					<div class="col-12 col-lg-4"  id="hover-quality">
						<div class="metric-heading my-2">Environmental Quality</div>
						<div class="index text-center mb-4 mt-2" id="environment">
						<?php if ($quality == "Data Not Available"): ?>
							<small><?php echo $quality?></small>
						<?php elseif ($quality > 7.5): ?>
							<p style="color: #00e400;"><?php echo $quality?></p>
						<?php elseif($quality > 4): ?>
							<p class="text-warning"><?php echo $quality?></p>
						<?php else : ?>
							<p style="color: #ff0000;"><?php echo $quality?></p>
						<?php endif; ?>
						</div>
					</div>
					<div class="col-12 col-lg-4">
						<div class="metric-heading my-2">Main Pollutant</div>
						<div class="index text-center mb-4 mt-2" id="pollutant">
						<?php if ($aqi == "0" || $pollutant == "Data Not Available"): ?>
							<small><?php echo $aqi?></small>
						<?php elseif ($pollutant == "PM2.5"): ?>
							<p style="color: #188FA7;">PM<sub>2.5</sub></p>
						<?php elseif($pollutant == "PM1.0"): ?>
							<p style="color: #188FA7;">PM<sub>1.0</sub></p>
						<?php elseif($pollutant == "O3"): ?>
							<p style="color: #188FA7;">O<sub>3</sub></p>
						<?php elseif($pollutant == "N2"): ?>
							<p style="color: #188FA7;">N<sub>2</sub></p>
						<?php elseif($pollutant == "S2"): ?>
							<p style="color: #188FA7;">S<sub>2</sub></p>
						<?php elseif($pollutant == "CO"): ?>
							<p style="color: #188FA7;">CO</p>
						<?php else: ?>
							<small><?php echo $pollutant ?></small>
						<?php endif; ?>
						</div>
					</div>
				</div> 
				<div id="summary" class="col-12 text-center" style="display: none">
					<div ><?php echo $summary?></div>
				</div>
				<div class="row mt-1 mb-4">
					<div class="col-7 offset-2">
						<canvas id="chart" width="300" height="300"></canvas>

						<div id="hidden-aqi" style="display: none"><?php echo $aqi?></div>
						<div id="hidden-quality" style="display: none"><?php echo $quality?></div>
						<div id="hidden-housing" style="display: none"><?php echo $housing?></div>
						<div id="hidden-safety" style="display: none"><?php echo $safety?></div>
						<div id="hidden-education" style="display: none"><?php echo $education?></div>
						<div id="hidden-healthcare" style="display: none"><?php echo $healthcare?></div>
						<div id="hidden-outdoors" style="display: none"><?php echo $outdoors?></div>
						<div id="hidden-economy" style="display: none"><?php echo $economy?></div>

					</div>
					
				</div>
				<div class="row mt-3 mb-4">
					<div class="col-12 text-center">
						<?php if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) : ?>
							Sign in to add to favorites
						<?php elseif (isset($favorite) && !$favorite ) : ?>
							<a href="add_favorites.php?location_id=<?php echo $row['location_id']; ?>&city=<?php echo $row['city']; ?>&state=<?php echo $row['state']; ?>&country=<?php echo $row['country']; ?>&aqi=<?php echo $aqi; ?>&quality=<?php echo $quality; ?>&pollutant=<?php echo $pollutant; ?>&image_url=<?php echo $row["image_url"]; ?>" class="btn btn-outline-success btn-block"> Add to Favorites </a>
						<?php else : ?>
							<a href="remove_favorites.php?location_id=<?php echo $row['location_id']; ?>&page=details" class="btn btn-outline-danger delete-btn btn-block"> Remove from Favorites </a>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
		</div> <!-- .container -->


		<?php include 'footer.php' ?>

	</body>
</html>