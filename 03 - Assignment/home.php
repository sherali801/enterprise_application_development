<?php

require_once "src/session.php";
require_once "src/functions.php";

authenticateUser();

$id = $_SESSION["id"];
$login = $_SESSION["login"];
$userRole = $_SESSION["userRole"];

?>

<?php require_once "header.php"; ?>

	<h2 class="mb-3">Welcome <?php echo $login; ?></h2>
<?php if ($userRole == 0) { ?>
	<h3>Roles</h3>
	<ul class="list-group mb-3">
		<?php $result = getRolesOfAUserById($id); ?>
		<?php if (mysqli_num_rows($result) > 0) { ?>
			<?php while($row = mysqli_fetch_assoc($result)) { ?>
				<li class="list-group-item"><?php echo $row["r_name"]; ?></li>
			<?php } ?>
		<?php } ?>
	</ul>
	<h3>Permissions</h3>
	<ul class="list-group mb-3">
		<?php $result = getPermissionsOfAUserById($id); ?>
		<?php if (mysqli_num_rows($result) > 0) { ?>
			<?php while($row = mysqli_fetch_assoc($result)) { ?>
				<li class="list-group-item"><?php echo $row["p_name"]; ?></li>
			<?php } ?>
		<?php } ?>
	</ul>
<?php } ?>

<?php require_once "footer.php"; ?>