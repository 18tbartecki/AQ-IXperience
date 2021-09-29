<?php
	require 'config.php';

	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	if($mysqli->connect_errno) {
		echo $mysqli->connect_error;
		exit();
	}

	$user_id = $_SESSION["user_id"];

	$statement = $mysqli->prepare("SELECT * FROM favorites WHERE user_id = ?");

	$statement->bind_param("i", $user_id);

	$statement->execute();
	$results = $statement->get_result();

	if(!$results) {
		echo $mysqli->error;
		exit();
	}

	$mysqli->close();

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>City Search Results</title>
	<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="city-details.css" type="text/css">
	<link rel="stylesheet" href="footer.css" type="text/css">
	<script src="https://kit.fontawesome.com/b4b2d9549e.js" crossorigin="anonymous"></script>
</head>
<body>
	<?php include 'nav.php' ?>

	<div class="container-fluid">
		<div class="row">
			<h1 class="col-12 my-4 text-center">Favorites</h1>
		</div> <!-- .row -->
	</div> <!-- .container-fluid -->
	<div class="container-fluid" id="results">
		<div class="row" id="full-favs">
			<div class="col-12 text-center">
				<?php if(mysqli_num_rows($results) == 0): ?>
					No Favorites yet! 
				<?php else: ?>
			</div>

				<?php while($row = $results->fetch_assoc()): ?>
				
					<div class="col-md-6 mb-2 mt-4">
					    <img class="my-3" src="<?php echo $row["image_url"]; ?>" alt="City image">
					</div>
					<div class="col-md-6 mb-2" id="results-data-adjust-fav">
					    <h6 class="mt-0">Location: <span class="search-info">
					    <a href="city-details.php?location_id=<?php echo $row['location_id'] ?>"> <?php echo $row["city"]; ?>, <?php echo $row["state"]; ?> <?php echo $row["country"]; ?> </a> </span> </h6>

					    <h6 class="mt-0">Last AQI: 
					    <?php if ($row["aqi"] == "Data Not Available"): ?>
							<small><?php echo $row["aqi"]?></small>
						<?php elseif ($row["aqi"] > 301): ?>
							<span class="search-info" style="color: #7e0023;"><?php echo $row["aqi"]?></span>
						<?php elseif($row["aqi"] > 201): ?>
							<span class="search-info" style="color: #99004c;"><?php echo $row["aqi"]?></span>
						<?php elseif($row["aqi"] > 151): ?>
							<span class="search-info" style="color: #ff0000;"><?php echo $row["aqi"]?></span>
						<?php elseif($row["aqi"] > 101): ?>
							<span class="search-info" style="color: #ff7e00;"><?php echo $row["aqi"]?></span>
						<?php elseif($row["aqi"] > 51): ?>
							<span class="search-info" style="color: #ffff00;"><?php echo $row["aqi"]?></span>
						<?php else : ?>
							<span class="search-info" style="color: #00e400;"><?php echo $row["aqi"]?></span>
						<?php endif; ?></h6>

					    <h6 class="mt-0">Last Environmental Quality: 
					    <?php if ($row["quality"] == "Data Not Available"): ?>
							<small><?php echo $quality?></small>
						<?php elseif ($row["quality"] > 7.5): ?>
							<span class="search-info" style="color: #00e400;"><?php echo $row["quality"]?></span>
						<?php elseif($row["quality"] > 4): ?>
							<span class="search-info" class="text-warning"><?php echo $row["quality"]?></span>
						<?php else : ?>
							<span class="search-info" style="color: #ff7e00;"><?php echo $row["quality"]?></span>
						<?php endif; ?></h6>

					    <h6 class="mt-0">Last Main Pollutant: <span style="color: #188FA7;" class="search-info">
					    <?php echo $row["pollutant"]; ?></span></h6>

					    <h6 class="mt-0" ><span id="update-remove">Update or Remove </span> <span class="search-info ml-1">
					    <a href="update_favorites.php?location_id=<?php echo $row['location_id']; ?>" class="btn btn-outline-success text-success mt-2 mt-md-0"> Update</a></span> <span class="search-info ml-1">
				    	<a onclick="return confirm('Are you sure you want to remove this city?');" href="remove_favorites.php?location_id=<?php echo $row['location_id']; ?>&page=favorites" class="btn btn-outline-danger delete-btn text-danger mt-2 mt-md-0"> Remove </a> </span> </h6>

					  </div>
				
				<?php endwhile; ?>

			<?php endif; ?>
			 <!-- .col -->
		</div> <!-- .row -->
		<div class="row mt-4 mb-4">
			<div class="col-12 text-center mx-auto">
				<a href="search.php" role="button" class="btn btn-block " id="search-return-fav">Back to Search</a>
			</div> <!-- .col -->
		</div> <!-- .row -->
	</div> <!-- .container-fluid -->

	<?php include 'footer.php' ?>
</body>
</html>