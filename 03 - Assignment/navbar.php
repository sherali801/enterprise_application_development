<?php

require_once "src/session.php";
require_once "src/functions.php";

$userRole = $_SESSION["userRole"];

?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
    <?php if ($userRole == 1) { ?>
        <a class="navbar-brand" href="home.php">Security Manager</a>
    <?php } ?>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>

<?php if ($userRole == 1) { ?>

            <li class="nav-item"><a class="nav-link" href="users.php">User Management</a></li>
            <li class="nav-item"><a class="nav-link" href="roles.php">Role Management</a></li>
            <li class="nav-item"><a class="nav-link" href="permissions.php">Permission Management</a></li>
            <li class="nav-item"><a class="nav-link" href="rolePermissions.php">Role-Permission Management</a></li>
            <li class="nav-item"><a class="nav-link" href="userRoles.php">User-Role Management</a></li>
            <li class="nav-item"><a class="nav-link" href="loginHistory.php">Login History</a></li>

<?php } ?>

            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        </ul>
    </div>
</nav>