<?php

require_once "src/session.php";
require_once "src/function.php";

authenticateAdmin();

$role_id = (isset($_GET["id"]) && !empty($_GET["id"])) ? $_GET["id"] : 0;

if (deleteRoleById($role_id)) {
	$_SESSION["successes"][] = "Role with ID: $role_id has been deleted.";
} else {
	$_SESSION["errors"][] = "Role with ID: $role_id was not deleted.";
}

redirectTo("roleList.php");

