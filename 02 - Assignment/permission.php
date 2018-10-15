<?php

require_once "src/session.php";
require_once "src/function.php";

authenticateAdmin();

$id = $_SESSION["id"];

$permission_id = 0;
$name = "";
$description = "";
if (isset($_GET["id"])) {
	$permission_id = $_GET["id"];
	$result = getPermissionById($permission_id);
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			$name = $row["name"];
			$description = $row["description"];
		}
	} else {
		$permission_id = 0;
		$_SESSION["errors"][] = "No record was found.";
	}
}

if (isset($_POST["submit"])) {
	$dt = MySqlFormattedTime(time());
	$permission_id = (int) $_POST["permission_id"];
	$name = $_POST["name"];
	$description = $_POST["description"];
	$nameValidationResult = validateName($name);
	$descriptionValidationResult = validateDescription($description);
	if ($nameValidationResult && $descriptionValidationResult) {
		if ($permission_id == 0) {
			$permissionNameDuplicationResult = duplicatePermissionName($name);
			if ($permissionNameDuplicationResult) {
				if (addNewPermission($name, $description, $dt, $id)) {
					$permission_id = lastInsertId();
					$_SESSION["successes"][] = "Permission with ID: $permission_id has been added.";
					$permission_id = 0;
					$name = "";
					$description = "";
				} else {
					$_SESSION["errors"][] = "Permission was not added.";
				}
			}
		} else {
			$permissionNameDuplicationResult = duplicatePermissionNameWithId($name, $permission_id);
			if ($permissionNameDuplicationResult) {
				if (updatePermission($permission_id, $name, $description, $id)) {
					$_SESSION["successes"][] = "Permission with ID: $permission_id has been updated.";
					$permission_id = 0;
					$name = "";
					$description = "";
				} else {
					$_SESSION["errors"][] = "Permission was not updated.";
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
					<div class="card-header bg-dark text-white"><?php echo $permission_id ? "Edit Permission" : "Add New Permission"; ?></div>
					<div class="card-body">
						<input type="hidden" id="permission_id" name="permission_id" value="<?php echo $permission_id; ?>">
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
						<button type="submit" name="submit" class="btn btn-sm btn-light"><?php echo $permission_id ? "Update" : "Save"; ?></button>
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