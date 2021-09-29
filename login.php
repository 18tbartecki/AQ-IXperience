<?php
	require 'config.php';

	if(!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {

		if ( isset($_POST['username']) && isset($_POST['password']) ) {
			if ( empty($_POST['username']) || empty($_POST['password']) ) {

				$error = "Please enter username and password.";

			}
			else {
				$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

				if($mysqli->connect_errno) {
					echo $mysqli->connect_error;
					exit();
				}

				$passwordInput = hash('sha256', $_POST["password"]);

				$statement = $mysqli->prepare("SELECT * FROM users WHERE username = ? AND password = ?");

				$statement->bind_param("ss", $_POST['username'], $passwordInput);

				$statement->execute();
				$results = $statement->get_result();


				if(!$results) {
					echo $mysqli->error;
					exit();
				}


				// Info is correct
				if(mysqli_num_rows($results) > 0) {
					$row = $results->fetch_assoc();

					$_SESSION["username"] = $_POST["username"];
					$_SESSION["user_id"] = $row["user_id"];
					$_SESSION["logged_in"] = true;
					// Redirect to homepage
					header("Location: search.php");
				
				}
				else {
					$error = "Invalid username or password.";
				}
			} 
		}
	}
	else {
		header("Location: search.php");
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Sign In</title>
		<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link rel="stylesheet" href="starter.css" type="text/css">
		<link rel="stylesheet" href="footer.css" type="text/css">
		<script src="https://kit.fontawesome.com/b4b2d9549e.js" crossorigin="anonymous"></script>
		<script src="login.js"></script>
	</head>
	<body>
		<!-- Header -->
		<nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow">
			<a class="navbar-brand ml-5" href="search.php">
				<h4>AQ IXperience <i class="ml-2 fas fa-wind"></i></h4> 
			</a>
		</nav>
		
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<h1 class="text-center">Sign In</h1>
				</div>
			</div>
			<div class="row">
				<div class="col-1 col-md-3"> </div>
				<div class="col-10 col-md-6" id="login">
					<form action="login.php" id="login" method="POST" onsubmit="return checkLogin(event);">
						<div class="form-group">
							<label for="username">Username</label>
							<input type="text" class="form-control" id="username" placeholder="Username" name="username">
						</div>
						<div class="form-group">
							<label for="password">Password </label>
							<input type="password" class="form-control" id="password" placeholder="Password" name="password">
						</div>
						<div class="form-group">
							<label for="submit"></label>
							<button type="submit" class="btn btn-block" id="submit">Sign In</button>
						</div>
						<div class="form-group">
							<label for="signup"></label>
							<button onclick="location.href = 'signup.php'; return false;" class="btn btn-block" id="signup">Sign Up for an Account</button>
						</div>
						<div class="form-group text-danger text-center" id="errorMessage">
						</div>
						<div class="form-group text-danger text-center">
							<!-- Show errors here. -->
							<?php
								if ( isset($error) && !empty($error) ) {
									echo $error;
								}
							?>
						</div>
					</form>
				</div>
			</div>
		</div>
	<?php include 'footer.php' ?>
	
	</body>
</html>