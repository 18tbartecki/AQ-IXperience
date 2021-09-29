<?php
	require 'config.php';

	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	if($mysqli->connect_errno) {
		echo $mysqli->connect_error;
		exit();
	}
	$search_term = $_GET["city"];
	$city = "%{$_GET['city']}%";

	$statement = $mysqli->prepare("SELECT * FROM locations WHERE city LIKE ?");
	$statement->bind_param("s", $city);

	$statement->execute();
	$results = $statement->get_result();
	if(!$results) {
		echo $mysqli->error;
		exit();
	}

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
			<h1 class="col-12 my-4 text-center">Matching Cities</h1>
		</div> <!-- .row -->
	</div> <!-- .container-fluid -->
	<div class="container-fluid" id="results">
		<div class="row" id="full-search">
			<div class="col-12">

				Showing <?php if(mysqli_num_rows($results) == 100): 
							echo "all results"; 
						else: 
							echo mysqli_num_rows($results);
							echo " result(s)";
						endif; ?> 

			</div> <!-- .col -->
				<?php while($row = $results->fetch_assoc()): ?>
				
					<div class="col-md-7 mb-2 mt-4">
					  <a href="city-details.php?location_id=<?php echo $row['location_id'] ?>&search_term=<?php echo $search_term; ?>"> <img class="my-3" src="<?php echo $row["image_url"]; ?>" alt="City image"></a>
					</div>
					<div class="col-md-5 mb-2 text-center" id="results-data-adjust">
					    <h6 class="mt-0">Location: <span class="search-info">
					    <a href="city-details.php?location_id=<?php echo $row['location_id'] ?>&search_term=<?php echo $search_term; ?>"> <?php echo $row["city"]; ?>, <?php echo $row["state"]; ?></a> </span> </h6>


					    <h6 class="mt-0">Country: <span class="search-info">
					    <?php echo $row["country"]; ?></span></h6>

					 	<?php if(isset($_SESSION["logged_in"]) && $_SESSION["username"] == "admin"): ?>
					 		<div class="text-center mt-3">
							<a onclick="return confirm('Are you sure you want to delete this city?');" href="delete.php?location_id=<?php echo $row['location_id']; ?>&city=<?php echo $_GET['city']; ?>" class="btn btn-block btn-outline-danger delete-btn">
								Delete
							</a> </div>
						<?php else: ?>
							Only Admin may delete
						<?php endif; ?>

					  </div>
				
				<?php endwhile; ?>
					
		</div> <!-- .row -->
		<div class="row mt-4 mb-4">
			<div class="col-12 text-center">
				<a href="search.php" role="button" class="btn" id="search-return">Back to Search</a>
			</div> <!-- .col -->
		</div> <!-- .row -->
	</div> <!-- .container-fluid -->

	<?php include 'footer.php' ?>
</body>
</html>