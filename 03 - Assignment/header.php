<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/functions.js"></script>
	<title>Security Manager</title>
</head>
<body>

<?php
if (isset($_SESSION["id"])) {
	require_once "navbar.php";
}
?>

<div class="container">