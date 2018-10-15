<?php

require_once "src/session.php";
require_once "src/function.php";

authenticateAdmin();

$user_id = (isset($_GET["id"]) && !empty($_GET["id"])) ? $_GET["id"] : 0;

if (deleteUserById($user_id)) {
	$_SESSION["successes"][] = "User with ID: $user_id has been deleted.";
} else {
	$_SESSION["errors"][] = "User with ID: $user_id was not deleted.";
}

redirectTo("userList.php");

