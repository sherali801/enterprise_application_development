<?php

require_once "src/session.php";
require_once "src/functions.php";

authenticateAdmin();

?>

<?php require_once "header.php"; ?>

	<?php $result = getLoginHistory(); ?>
	<?php if (mysqli_num_rows($result) > 0) { ?>
		<h2 class="text-center">Login History</h2>
		<table class="table table-striped table-bordered text-center">
			<thead>
			<tr>
				<th>ID</th>
				<th>User ID</th>
				<th>Login</th>
				<th>Login Time</th>
				<th>Machine IP</th>
			</tr>
			</thead>
			<tbody>
			<?php while ($row = mysqli_fetch_assoc($result)) { ?>
				<tr>
					<th class="align-middle"><?php echo $row["id"]; ?></th>
					<td class="align-middle"><?php echo $row["user_id"]; ?></td>
					<td class="align-middle"><?php echo $row["login"]; ?></td>
					<td class="align-middle"><?php echo $row["login_time"]; ?></td>
					<td class="align-middle"><?php echo $row["machine_ip"]; ?></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	<?php } else { ?>
		<h2 class="text-center">No Login History.</h2>
	<?php } ?>

<?php require_once "footer.php"; ?>