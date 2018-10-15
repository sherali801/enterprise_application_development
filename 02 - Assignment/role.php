<?php

require_once "src/session.php";
require_once "src/function.php";

authenticateAdmin();

$id = $_SESSION["id"];

$role_id = 0;
$name = "";
$description = "";
if (isset($_GET["id"])) {
	$role_id = $_GET["id"];
	$result = getRoleById($role_id);
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			$name = $row["name"];
			$description = $row["description"];
		}
	} else {
		$role_id = 0;
		$_SESSION["errors"][] = "No record was found.";
    }
}

if (isset($_POST["submit"])) {
	$dt = MySqlFormattedTime(time());
	$role_id = (int) $_POST["role_id"];
	$name = $_POST["name"];
	$description = $_POST["description"];
	$nameValidationResult = validateName($name);
	$descriptionValidationResult = validateDescription($description);
	if ($nameValidationResult && $descriptionValidationResult) {
		if ($role_id == 0) {
			$roleNameDuplicationResult = duplicateRoleName($name);
			if ($roleNameDuplicationResult) {
				if (addNewRole($name, $description, $dt, $id)) {
					$role_id = lastInsertId();
					$_SESSION["successes"][] = "Role with ID: $role_id has been added.";
					$role_id = 0;
					$name = "";
					$description = "";
				} else {
					$_SESSION["errors"][] = "Role was not added.";
				}
			}
		} else {
			$roleNameDuplicationResult = duplicateRoleNameWithId($name, $role_id);
			if ($roleNameDuplicationResult) {
				if (updateRole($role_id, $name, $description, $id)) {
					$_SESSION["successes"][] = "Role with ID: $role_id has been updated.";
					$role_id = 0;
					$name = "";
					$description = "";
				} else {
					$_SESSION["errors"][] = "Role was not updated.";
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
					<div class="card-header bg-dark text-white"><?php echo $role_id ? "Edit Role" : "Add New Role"; ?></div>
					<div class="card-body">
						<input type="hidden" id="role_id" name="role_id" value="<?php echo $role_id; ?>">
						<div class="form-group">
							<label for="name">Name: </label>
							<input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>" aria-describedby="nameHelp" placeholder="Name" pattern="^.{1,45}$" required>
							<small id="nameHelp" class="form-text text-muted">At max 45 characters.</small>
						</div>
						<div class="form-group">
							<label for="description">Description: </label>
							<textarea class="form-control" id="description" name="description" rows="3" aria-describedby="descriptionHelp" placeholder="Description" required><?php echo $description; ?></textarea>
							<small id="descriptionHelp" class="form-text text-muted">At max 45 characters.</small>
						</div>
					</div><!-- card-body -->
					<div class="card-footer bg-dark text-right">
						<button type="reset" name="clearBtn" class="btn btn-sm btn-light" id="clearBtn">Clear</button>
						<button type="submit" name="submit" class="btn btn-sm btn-light"><?php echo $role_id ? "Update" : "Save"; ?></button>
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