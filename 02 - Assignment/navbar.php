<?php

require_once "src/session.php";
require_once "src/function.php";

$user_role = $_SESSION["user_role"];

?>

<?php if ($user_role == 1) { ?>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
		<a class="navbar-brand" href="home.php">Security Manager</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav">
				<li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
				<li class="nav-item"><a class="nav-link" href="userList.php">User Management</a></li>
				<li class="nav-item"><a class="nav-link" href="roleList.php">Role Management</a></li>
				<li class="nav-item"><a class="nav-link" href="permissionList.php">Permission Management</a></li>
				<li class="nav-item"><a class="nav-link" href="rolePermissionList.php">Role-Permission Management</a></li>
				<li class="nav-item"><a class="nav-link" href="userRoleList.php">User-Role Management</a></li>
                <li class="nav-item"><a class="nav-link" href="loginHistory.php">Login History</a></li>
				<li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
			</ul>
		</div>
	</nav>
<?php } else if ($user_role == 0) { ?>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav">
				<li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
				<li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
			</ul>
		</div>
	</nav>
<?php } ?>
