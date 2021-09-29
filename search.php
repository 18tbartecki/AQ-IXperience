<?php
	session_start();
	
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Search Page</title>
		<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link rel="stylesheet" href="city-details.css" type="text/css">
		<link rel="stylesheet" href="footer.css" type="text/css">
		<script src="https://kit.fontawesome.com/b4b2d9549e.js" crossorigin="anonymous"></script>

	</head>
	<body>
		<?php include 'nav.php' ?>

		<div class="container">
			<div class="row">
				<h1 class="col-12 mt-4 mb-4 text-center">Search for a City</h1>
			</div> <!-- .row -->
		</div> <!-- .container -->

	<div class="container" id="city-row">
		<form id="search-form" method="GET" action="search_results.php?">
			<div class="form-group row">
				<div class="col-12">
					<input type="text" class="form-control" id="city" name="city">
				</div>
			</div> <!-- .form-group -->
			<div class="form-group row text-center">
				<div class="col-12 mt-2">
					<button type="submit" class="btn" id="search">Search</button>
				</div>
			</div> <!-- .form-group -->
		</form>
	</div>


		<?php include 'footer.php' ?>
		<script>
			
		/*document.querySelector("#search-form").onsubmit = function() {
			var form = new FormData();
			$.ajax({
			  url: "https://thawing-shore-61339.herokuapp.com/http://api.airvisual.com/v2/city?city=Mexico City&state=Mexico City&country=Mexico&key=91269b01-77f3-4a6b-aea8-4e475c430aa5",
			  method: "GET",
			  timeout: 0,
			  processData: false,
			  mimeType: "multipart/form-data",
			  contentType: false,
			  data: form

			}).done(function (response) {
			  console.log(response);
			}).fail(function() {
				console.log("Error!");
			});
			return false;
		}*/

		</script>

	</body>


</html>