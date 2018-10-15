<?php

require_once "src/session.php";
require_once "src/function.php";

authenticateAdmin();

$role_permission_id = (isset($_GET["id"]) && !empty($_GET["id"])) ? $_GET["id"] : 0;

if (deleteRolePermissionById($role_permission_id)) {
	$_SESSION["successes"][] = "Role-Permission with ID: $role_permission_id has been deleted.";
} else {
	$_SESSION["errors"][] = "Role-Permission with ID: $role_permission_id was not deleted.";
}

redirectTo("rolePermissionList.php");

