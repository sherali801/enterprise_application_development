<?php

require_once "src/session.php";
require_once "src/function.php";

authenticateAdmin();

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
	<?php require_once "success_messages.php"; ?>
    <?php require_once "error_messages.php"; ?>
	<?php $result = getAllUserRoles(); ?>
	<?php if (mysqli_num_rows($result) > 0) { ?>
		<h2 class="text-center">User-Roles</h2>
		<table class="table table-striped table-bordered text-center">
			<thead>
			<tr>
				<th>ID</th>
				<th>User</th>
				<th>Role</th>
				<th>Edit</th>
				<th>Delete</th>
			</tr>
			</thead>
			<tbody>
			<?php while ($row = mysqli_fetch_assoc($result)) { ?>
				<tr>
					<th class="align-middle"><?php echo $row["ur_id"]; ?></th>
					<td class="align-middle"><?php echo $row["u_login"]; ?></td>
					<td class="align-middle"><?php echo $row["r_name"]; ?></td>
					<td class="align-middle"><a href="userRole.php?id=<?php echo $row["ur_id"]; ?>" class="btn btn-primary">Edit</a></td>
					<td class="align-middle"><a href="deleteUserRole.php?id=<?php echo $row["ur_id"]; ?>" class="btn btn-danger" onclick="return confirm('Do you want to delete user-role with ID: <?php echo $row["ur_id"]; ?>?')">Delete</a></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	<?php } else { ?>
		<h2 class="text-center">No record was found.</h2>
	<?php } ?>
	<a href="userRole.php">Add New User-Role</a>
</div><!-- container -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>