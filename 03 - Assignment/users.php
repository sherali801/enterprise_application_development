<?php

require_once "src/session.php";
require_once "src/functions.php";

authenticateAdmin();

?>

<?php require_once "header.php"; ?>

	<div class="row mb-3">
		<div class="col-sm">
			<form action="" method="post">
				<div class="card">
					<div class="card-header bg-dark text-white">User Management</div>
					<div class="card-body">
						<input type="hidden" id="id" name="id" value="0">
						<div class="form-group">
							<label for="login">Login: </label>
							<input type="text" class="form-control" id="login" name="login" value="" aria-describedby="loginHelp" placeholder="Login" pattern="^[\w]{1,45}$" required autofocus>
							<small id="loginHelp" class="form-text text-muted">At max 45 characters.</small>
						</div>
						<div class="form-group">
							<label for="password">Password: </label>
							<input type="password" class="form-control" id="password" name="password" aria-describedby="passwordHelp" placeholder="Password" pattern="^(?=.*\d)(?=.*[~!@#$%^&*()_\-+=|\\{}[\]:;<>?/])(?=.*[A-Z])(?=.*[a-z])\S{8,15}$" required>
							<small id="passwordHelp" class="form-text text-muted">8-15 characters. one lowercase, one uppercase, one digit, one special character</small>
						</div>
						<div class="form-group">
							<label for="name">Name: </label>
							<input type="text" class="form-control" id="name" name="name" value="" aria-describedby="nameHelp" placeholder="Name" pattern="^.{1,45}$" required>
							<small id="nameHelp" class="form-text text-muted">At max 45 characters.</small>
						</div>
						<div class="form-group">
							<label for="email">Email: </label>
							<input type="email" class="form-control" id="email" name="email" value="" aria-describedby="emailHelp" placeholder="Email" pattern="^[\w.%+\-]+@[\w.\-]+\.[A-Za-z]{2,6}$" required>
						</div>
						<div class="form-group">
							<label for="countries">Country: </label>
							<select class="form-control" name="countryId" id="countries">
							</select>
						</div>
						<div class="form-group">
							<label for="cities">City: </label>
							<select class="form-control" name="cityId" id="cities">
							</select>
						</div>
						<div class="form-group">
							<label for="isAdmin">Is Admin: </label><br>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="isAdmin" id="isAdminYes" value="1">
								<label class="form-check-label" for="isAdminYes">Yes</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="isAdmin" id="isAdminNo" value="0" checked>
								<label class="form-check-label" for="isAdminNo">No</label>
							</div>
						</div>
					</div><!-- card-body -->
					<div class="card-footer bg-dark text-right">
						<button type="reset" name="clear" class="btn btn-sm btn-light" id="clearUser">Clear</button>
						<button type="submit" name="submit" class="btn btn-sm btn-light" id="saveUser">Save</button>
					</div>
				</div><!-- card -->
			</form>
		</div><!-- col-sm -->
		<div class="col-sm">
			<h2 class="text-center">Users</h2>
			<table class="table table-striped table-bordered text-center" id="usersTable"></table>
		</div><!-- col-sm -->
	</div><!-- row -->

<?php require_once "footer.php"; ?>