<?php

require_once "src/session.php";
require_once "src/function.php";

authenticateAdmin();

$id = $_SESSION["id"];

$user_id = 0;
$login = "";
$password = "";
$name = "";
$email = "";
$is_admin = 0;
$country_id = 0;
if (isset($_GET["id"])) {
	$user_id = $_GET["id"];
	$result = getUserById($user_id);
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			$login = $row["login"];
			$name = $row["name"];
			$email = $row["email"];
			$is_admin = $row["is_admin"];
			$country_id = $row["country_id"];
		}
	} else {
		$user_id = 0;
		$_SESSION["errors"][] = "No record was found.";
    }
}

if (isset($_POST["submit"])) {
    $dt = MySqlFormattedTime(time());
	$user_id = (int) $_POST["user_id"];
    $login = $_POST["login"];
    $password = $_POST["password"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $country_id = $_POST["country_id"];
    $is_admin = $_POST["is_admin"];
    $usernameValidationResult = validateUsername($login);
    $passwordValidationResult = validatePassword($password);
    $nameValidationResult = validateName($name);
    $emailValidationResult = validateEmail($email);
    if ($usernameValidationResult && $passwordValidationResult && $nameValidationResult && $emailValidationResult) {
	    if ($user_id == 0) {
		    $usernameDuplictionResult = duplicateUsername($login);
		    if ($usernameDuplictionResult) {
			    if (addNewUser($login, $password, $name, $email, $country_id, $is_admin, $dt, $id)) {
				    $user_id = lastInsertId();
				    $_SESSION["successes"][] = "User with ID: $user_id has been added.";
				    $user_id = 0;
				    $login = "";
				    $password = "";
				    $name = "";
				    $email = "";
				    $is_admin = "";
				    $country_id = "";
			    } else {
				    $_SESSION["errors"][] = "User was not added.";
			    }
		    }
	    } else {
	        $usernameDuplictionResult = duplicateUsernameWithId($login, $user_id);
		    if ($usernameDuplictionResult) {
			    if (updateUser($user_id, $login, $password, $name, $email, $country_id, $is_admin, $id)) {
				    $_SESSION["successes"][] = "User with ID: $user_id has been updated.";
				    $user_id = 0;
				    $login = "";
				    $password = "";
				    $name = "";
				    $email = "";
				    $is_admin = "";
				    $country_id = "";
			    } else {
				    $_SESSION["errors"][] = "User was not updated.";
			    }
		    }
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
<?php require_once "navbar.php"; ?>
<div class="container">
	<div class="row mb-3">
		<div class="col-6 mx-auto">
			<?php require_once "success_messages.php"; ?>
            <?php require_once "error_messages.php"; ?>
			<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
				<div class="card">
					<div class="card-header bg-dark text-white"><?php echo $user_id ? "Edit User" : "Add New User"; ?></div>
					<div class="card-body">
						<input type="hidden" id="user_id" name="user_id" value="<?php echo $user_id; ?>">
						<div class="form-group">
							<label for="login">Login: </label>
							<input type="text" class="form-control" id="login" name="login" value="<?php echo $login; ?>" aria-describedby="loginHelp" placeholder="Login" pattern="^[\w]{1,45}$" required autofocus>
							<small id="loginHelp" class="form-text text-muted">At max 45 characters.</small>
						</div>
						<div class="form-group">
							<label for="password">Password: </label>
							<input type="password" class="form-control" id="password" name="password" aria-describedby="passwordHelp" placeholder="Password" pattern="^(?=.*\d)(?=.*[~!@#$%^&*()_\-+=|\\{}[\]:;<>?/])(?=.*[A-Z])(?=.*[a-z])\S{8,15}$" required>
							<small id="passwordHelp" class="form-text text-muted">8-15 characters. one lowercase, one uppercase, one digit, one special character</small>
						</div>
						<div class="form-group">
							<label for="name">Name: </label>
							<input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>" aria-describedby="nameHelp" placeholder="Name" pattern="^.{1,45}$" required>
							<small id="nameHelp" class="form-text text-muted">At max 45 characters.</small>
						</div>
						<div class="form-group">
							<label for="email">Email: </label>
							<input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" aria-describedby="emailHelp" placeholder="Email" pattern="^[\w.%+\-]+@[\w.\-]+\.[A-Za-z]{2,6}$" required>
						</div>
						<div class="form-group">
							<label for="countries">Country: </label>
							<select class="form-control" name="country_id">
								<option value="0">--Select--</option>
								<?php $result = getAllCountries(); ?>
								<?php if ($result) { ?>
									<?php while ($row = mysqli_fetch_assoc($result)) { ?>
										<option value="<?php echo $row["id"]; ?>" <?php echo ($row["id"] == $country_id) ? "selected" : "" ?>><?php echo $row["name"]; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</div>
                        <div class="form-group">
                            <label for="is_admin">Is Admin: </label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_admin" id="is_admin_yes" value="1" <?php echo $is_admin ? "checked" : ""; ?>>
                                <label class="form-check-label" for="is_admin_yes">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_admin" id="is_admin_no" value="0" <?php echo $is_admin ? "" : "checked"; ?>>
                                <label class="form-check-label" for="is_admin_no">No</label>
                            </div>
                        </div>
					</div><!-- card-body -->
					<div class="card-footer bg-dark text-right">
						<button type="reset" name="clearBtn" class="btn btn-sm btn-light" id="clearBtn">Clear</button>
						<button type="submit" name="submit" class="btn btn-sm btn-light"><?php echo $user_id ? "Update" : "Save"; ?></button>
					</div>
				</div><!-- card -->
			</form>
		</div><!-- col-sm -->
	</div><!-- row -->
</div><!-- container -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>