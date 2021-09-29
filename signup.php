<?php

require 'config.php';

	if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['confirmPassword']) ){
		if(empty($_POST['username']) || empty($_POST['password']) || empty($_POST['confirmPassword']) ) {
			$error = "Please fill out all required fields.";
		}
		else if($_POST['password'] != $_POST['confirmPassword']) {
			$error = "Passwords do not match.";
		}
		else {
			//Store user in database
			$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			if($mysqli->connect_errno) {
				echo $mysqli->connect_error;
				exit();
			}

			$mysqli->set_charset('utf8');

			// Check if user already exists
			$registered_statemtent = $mysqli->prepare("SELECT * FROM users WHERE username = ?");

			$registered_statemtent->bind_param("s", $_POST["username"]);

			$registered_statemtent->execute();
			$results_registered = $registered_statemtent->get_result();

			if(!$results_registered) {
				echo $mysqli->error;
				exit();
			}

			if($results_registered->num_rows > 0) {
				$error = "Username has already been taken.";
			}
			else {

				$password = hash('sha256', $_POST["password"]);

				$statement = $mysqli->prepare("INSERT INTO users(username, password) VALUES(?, ?)");

				$statement->bind_param("ss", $_POST["username"], $password);

				$executed = $statement->execute();

				if(!$executed) {
				 	echo $mysqli->error;
				 	exit();
				}

				$statement->close();
				header("Location: login.php");
			}
			
			$mysqli->close();
		}
	}

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Sign Up</title>
		<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link rel="stylesheet" href="starter.css" type="text/css">
		<link rel="stylesheet" href="signup.css" type="text/css">
		<link rel="stylesheet" href="footer.css" type="text/css">
		<script src="https://kit.fontawesome.com/b4b2d9549e.js" crossorigin="anonymous"></script>
		<script src="signup.js"></script>
	</head>
	<body>
		<!-- Header -->
		<nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow">
			<a class="navbar-brand ml-5" href="search.php">
				<h4>AQ IXperience <i class="ml-2 fas fa-wind"></i></h4>
			</a>
		</nav>
		
		<div class="container-fluid" id="signup-body">
			<div class="row">
				<div class="col-12">
					<h1 class="text-center">Sign Up</h1>
				</div>
			</div>
			<div class="row">
				<div class="col-1 col-md-3"> </div>
				<div class="col-10 col-md-6">
					<form action="signup.php" id="signUpForm" method="POST" onsubmit="return checkSignUp(event)">
						<div class="form-group">
							<label for="username">Username</label>
							<input type="text" class="form-control" id="username" placeholder="Username" name="username">
						</div>
						<div class="form-group">
							<label for="password">Password </label>
							<input type="password" class="form-control" id="password" placeholder="Password" name="password">
						</div>
						<div class="form-group">
							<label for="confirmPassword">Confirm Password </label>
							<input type="password" class="form-control" id="confirmPassword" placeholder="Confirm Password" name="confirmPassword">
						</div>
						<div class="form-group">
							<label for="submit"></label>
							<button type="submit" class="btn btn-block" id="signup">Create User</button>
						</div>
						<div class="form-group">
							<label for="submit"></label>
							<button onclick="location.href = 'login.php'; return false;" type="submit" class="btn btn-block" id="signin">Already a user? Sign In</button>
						</div>
						<div class="form-group text-danger text-center" id="errorMessage"></div>
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