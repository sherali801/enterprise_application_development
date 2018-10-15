<?php

require_once "src/function.php";

$username = "";
$password = "";

if (isset($_POST["submit"])) {
    $dt = MySqlFormattedTime(time());
    $machine_ip = getHostByName(getHostName());
	$username = $_POST["username"];
	$password = $_POST["password"];
	$usernameValidationResult = validateUsername($username);
	$passwordValidationResult = validatePassword($password);
	if ($usernameValidationResult && $passwordValidationResult) {
		if (validateUser($username, $password)) {
		    $result = getUserByLogin($username);
		    while ($row = mysqli_fetch_assoc($result)) {
		        $user_id = $row["id"];
		        $login = $row["login"];
            }
            addNewLoginHistory($user_id, $login, $dt, $machine_ip);
			redirectTo("home.php");
		}
	}
}

?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<title>Security Manager</title>
</head>
<body>
<div class="container mt-3">
	<div class="col-6 mx-auto">
		<h1 class="mb-3">Security Manager</h1>

        <?php require_once "error_messages.php"; ?>

		<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
			<div class="card">
				<div class="card-header bg-dark text-white">Login</div>
				<div class="card-body">
					<div class="form-group">
						<label for="username">Username: </label>
						<input type="text" name="username" value="<?php echo $username; ?>" class="form-control" id="username" aria-describedby="usernameHelp" placeholder="Username" pattern="^[\w]{1,45}$" required autofocus>
						<small id="usernameHelp" class="form-text text-muted">At max 45 characters.</small>
					</div>
					<div class="form-group">
						<label for="password">Password: </label>
						<input type="password" name="password" class="form-control" id="password" aria-describedby="passwordHelp" placeholder="Password" pattern="^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$" required>
						<small id="passwordHelp" class="form-text text-muted">8-15 characters. one lowercase, one uppercase, one digit, one special character.</small>
					</div>
				</div>
				<div class="card-footer bg-dark text-right">
					<input type="submit" name="submit" value="Login" class="btn btn-sm btn-light" id="loginBtn">
				</div>
			</div><!-- card -->
		</form>
	</div>
</div><!-- container -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>