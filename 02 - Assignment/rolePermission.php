<?php

require_once "src/session.php";
require_once "src/function.php";

authenticateAdmin();

$id = $_SESSION["id"];

$role_permission_id = 0;
$role_id = 0;
$permission_id = 0;
if (isset($_GET["id"])) {
	$role_permission_id = $_GET["id"];
	$result = getRolePermissionById($role_permission_id);
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			$role_id = $row["role_id"];
			$permission_id = $row["permission_id"];
		}
	} else {
		$role_permission_id = 0;
		$_SESSION["errors"][] = "No record was found.";
	}
}

if (isset($_POST["submit"])) {
	$dt = MySqlFormattedTime(time());
	$role_permission_id = (int) $_POST["role_permission_id"];
	$role_id = $_POST["role_id"];
	$permission_id = $_POST["permission_id"];
	$roleIdValidationResult = validateRoleId($role_id);
	$permissionIdValidationResult = validatePermissionId($permission_id);
	if ($roleIdValidationResult && $permissionIdValidationResult) {
		if ($role_permission_id == 0) {
			$rolePermissionDuplicationResult = duplicateRolePermission($role_id, $permission_id);
			if ($rolePermissionDuplicationResult) {
				if (addNewRolePermission($role_id, $permission_id)) {
					$role_permission_id = lastInsertId();
					$_SESSION["successes"][] = "Role-Permission with ID: $role_permission_id has been added.";
					$role_permission_id = 0;
					$role_id = 0;
					$permission_id = 0;
				} else {
					$_SESSION["errors"][] = "Role-Permission was not added.";
				}
			}
		} else {
			$rolePermissionDuplicationResult = duplicateRolePermissionWithId($role_permission_id, $role_id, $permission_id);
			if ($rolePermissionDuplicationResult) {
				if (updateRolePermission($role_permission_id, $role_id, $permission_id)) {
					$_SESSION["successes"][] = "Role-Permission with ID: $role_permission_id has been updated.";
					$role_permission_id = 0;
					$role_id = 0;
					$permission_id = 0;
				} else {
					$_SESSION["errors"][] = "Role-Permission was not updated.";
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
					<div class="card-header bg-dark text-white"><?php echo $role_permission_id ? "Edit Role-Permission" : "Add New Role-Permission"; ?></div>
					<div class="card-body">
						<input type="hidden" id="role_permission_id" name="role_permission_id" value="<?php echo $role_permission_id; ?>">
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
						<div class="form-group">
							<label for="permissions">Permission: </label>
							<select class="form-control" id="permissions" name="permission_id">
								<option value="0">--Select--</option>
								<?php $result = getAllPermissions(); ?>
								<?php while ($row = mysqli_fetch_assoc($result)) { ?>
									<option value="<?php echo $row["id"]; ?>" <?php echo ($row["id"] == $permission_id) ? "selected" : ""; ?>><?php echo $row["name"]; ?></option>
								<?php } ?>
							</select>
						</div>
					</div><!-- card-body -->
					<div class="card-footer bg-dark text-right">
						<button type="reset" name="clearBtn" class="btn btn-sm btn-light" id="clearBtn">Clear</button>
						<button type="submit" name="submit" class="btn btn-sm btn-light"><?php echo $role_permission_id ? "Update" : "Save"; ?></button>
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