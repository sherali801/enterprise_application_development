<?php

require_once "src/functions.php";

if (isset($_SESSION["id"])) {
    redirectTo("home.php");
}

$username = "";
$password = "";

if (isset($_POST["submit"])) {
	$username = $_POST["username"];
	$password = $_POST["password"];
	$usernameValidationResult = validateLogin($username);
	$passwordValidationResult = validatePassword($password);
	if ($usernameValidationResult && $passwordValidationResult) {
		if (validateUser($username, $password)) {
			$result = getUserById($_SESSION["id"]);
			while ($row = mysqli_fetch_assoc($result)) {
				$userId = $row["id"];
				$login = $row["login"];
			}
			$loginTime = MySqlFormattedTime(time());
			$machineIp = getHostByName(getHostName());
			saveLoginHistory($userId, $login, $loginTime, $machineIp);
			redirectTo("home.php");
		}
	}
}

?>

<?php require_once "header.php"; ?>

	<div class="col-6 mx-auto">
		<h1 class="mb-3">Security Manager</h1>

		<?php require_once "errorMessages.php"; ?>

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
					<input type="submit" name="submit" value="Login" class="btn btn-sm btn-light" id="btnLogin">
				</div>
			</div><!-- card -->
		</form>
	</div>

<?php require_once "footer.php"; ?>