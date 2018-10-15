<?php

require_once "src/session.php";
require_once "src/function.php";

authenticateAdmin();

$permission_id = (isset($_GET["id"]) && !empty($_GET["id"])) ? $_GET["id"] : 0;

if (deletePermissionById($permission_id)) {
	$_SESSION["successes"][] = "Role-Permission with ID: $permission_id has been deleted.";
} else {
	$_SESSION["errors"][] = "Role-Permission with ID: $permission_id was not deleted.";
}

redirectTo("permissionList.php");

