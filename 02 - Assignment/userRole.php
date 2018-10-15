<?php

require_once "src/session.php";
require_once "src/function.php";

authenticateAdmin();

$id = $_SESSION["id"];

$user_role_id = 0;
$user_id = 0;
$role_id = 0;
if (isset($_GET["id"])) {
	$user_role_id = $_GET["id"];
	$result = getUserRoleById($user_role_id);
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			$user_id = $row["user_id"];
			$role_id = $row["role_id"];
		}
	} else {
		$user_role_id = 0;
		$_SESSION["errors"][] = "No record was found.";
	}
}

if (isset($_POST["submit"])) {
	$dt = MySqlFormattedTime(time());
	$user_role_id = (int) $_POST["user_role_id"];
	$user_id = $_POST["user_id"];
	$role_id = $_POST["role_id"];
	$userIdValidationResult = validateUserId($user_id);
	$roleIdValidationResult = validateRoleId($role_id);
	if ($userIdValidationResult && $roleIdValidationResult) {
		if ($user_role_id == 0) {
			$userRoleDuplicationResult = duplicateUserRole($user_id, $role_id);
			if ($userRoleDuplicationResult) {
				if (addNewUserRole($user_id, $role_id)) {
					$user_role_id = lastInsertId();
					$_SESSION["successes"][] = "User-Role with ID: $user_role_id has been added.";
					$user_role_id = 0;
					$user_id = 0;
					$role_id = 0;
				} else {
					$_SESSION["errors"][] = "User-Role was not added.";
				}
			}
		} else {
			$userRoleDuplicationResult = duplicateUserRoleWithId($user_role_id, $user_id, $role_id);
			if ($userRoleDuplicationResult) {
				if (updateUserRole($user_role_id, $user_id, $role_id)) {
					$_SESSION["successes"][] = "User-Role with ID: $user_role_id has been updated.";
					$user_role_id = 0;
					$user_id = 0;
					$role_id = 0;
				} else {
					$_SESSION["errors"][] = "User-Role was not updated.";
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
					<div class="card-header bg-dark text-white"><?php echo $user_role_id ? "Edit User-Role" : "Add New User-Role"; ?></div>
					<div class="card-body">
						<input type="hidden" id="user_role_id" name="user_role_id" value="<?php echo $user_role_id; ?>">
						<div class="form-group">
							<label for="users">User: </label>
							<select class="form-control" id="users" name="user_id">
								<option value="0">--Select--</option>
								<?php $result = getAllUsers(); ?>
								<?php while ($row = mysqli_fetch_assoc($result)) { ?>
									<option value="<?php echo $row["id"]; ?>" <?php echo ($row["id"] == $user_id) ? "selected" : ""; ?>><?php echo $row["login"]; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="form-group">
							<label for="roles">Role: </label>
							<select class="form-control" id="roles" name="role_id">
								<option value="0">--Select--</option>
								<?php $result = getAllRoles(); ?>
								<?php while ($row = mysqli_fetch_assoc($result)) { ?>
									<option value="<?php echo $row["id"]; ?>" <?php echo ($row["id"] == $role_id) ? "selected" : ""; ?>><?php echo $row["name"]; ?></option>
								<?php } ?>
							</select>
						</div>
					</div><!-- card-body -->
					<div class="card-footer bg-dark text-right">
						<button type="reset" name="clearBtn" class="btn btn-sm btn-light" id="clearBtn">Clear</button>
						<button type="submit" name="submit" class="btn btn-sm btn-light"><?php echo $user_role_id ? "Update" : "Save"; ?></button>
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