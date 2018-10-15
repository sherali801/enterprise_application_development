<?php

require_once "config.php";

$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PWD, DB_NAME);
if (!$conn) {
	die("Connect Error (" . mysqli_connect_errno() . ") " . mysqli_connect_error());
}
