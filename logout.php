<?php
	session_start();
	session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Logout</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="starter.css" type="text/css">
	<link rel="stylesheet" href="footer.css" type="text/css">
	<script src="https://kit.fontawesome.com/b4b2d9549e.js" crossorigin="anonymous"></script>
</head>
<body>
	<nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow">
		<a class="navbar-brand ml-5" href="search.php">
			<h4>AQ IXperience <i class="ml-2 fas fa-wind"></i></h4> 
		</a>
	</nav>
	<div class="container-fluid">
		<div class="row">
			<h1 class="col-12 mt-4 mb-4 text-center">Logged Out</h1>
			<div id="logout-message" class="col-12 mt-3 text-center">You can <a href="search.php">search</a> for a new city or <a href="login.php">login</a> again.</div>
			<div id="logout-message" class="col-12 mt-3 text-center">Thanks for stopping by!</div>

		</div> <!-- .row -->
	</div> <!-- .container -->

	<?php include 'footer.php' ?>

</body>
</html>