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
					<div class="card-header bg-dark text-white">User-Role</div>
					<div class="card-body">
						<input type="hidden" id="id" name="id" value="0">
						<div class="form-group">
							<label for="users">User: </label>
							<select class="form-control" id="users" name="userId"></select>
						</div>
						<div class="form-group">
							<label for="roles">Role: </label>
							<select class="form-control" id="roles" name="roleId"></select>
						</div>
					</div><!-- card-body -->
					<div class="card-footer bg-dark text-right">
						<button type="reset" name="clear" class="btn btn-sm btn-light" id="clearUserRole">Clear</button>
						<button type="submit" name="submit" class="btn btn-sm btn-light" id="saveUserRole">Save</button>
					</div>
				</div><!-- card -->
			</form>
		</div><!-- col-sm -->
		<div class="col-sm">
			<h2 class="text-center">User-Roles</h2>
			<table class="table table-striped table-bordered text-center" id="userRolesTable"></table>
		</div><!-- col-sm -->
	</div><!-- row -->

<?php require_once "footer.php"; ?>