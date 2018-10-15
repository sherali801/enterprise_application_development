<?php

require_once "src/session.php";
require_once "src/function.php";

authenticateAdmin();

$user_role_id = (isset($_GET["id"]) && !empty($_GET["id"])) ? $_GET["id"] : 0;

if (deleteUserRoleById($user_role_id)) {
	$_SESSION["successes"][] = "User-Role with ID: $user_role_id has been deleted.";
} else {
	$_SESSION["errors"][] = "User-Role with ID: $user_role_id was not deleted.";
}

redirectTo("userRoleList.php");

