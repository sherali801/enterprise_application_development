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
					<div class="card-header bg-dark text-white">Permission Management</div>
					<div class="card-body">
						<input type="hidden" id="id" name="id" value="">
						<div class="form-group">
							<label for="name">Name: </label>
							<input type="text" class="form-control" id="name" name="name" value="" aria-describedby="nameHelp" placeholder="Name" pattern="^.{1,45}$" required>
							<small id="nameHelp" class="form-text text-muted">At max 45 characters.</small>
						</div>
						<div class="form-group">
							<label for="description">Description: </label>
							<textarea class="form-control" id="description" name="description" rows="3" aria-describedby="descriptionHelp" placeholder="Description" required></textarea>
							<small id="descriptionHelp" class="form-text text-muted">At max 45 characters.</small>
						</div>
					</div><!-- card-body -->
					<div class="card-footer bg-dark text-right">
						<button type="reset" name="clear" class="btn btn-sm btn-light" id="clearPermission">Clear</button>
						<button type="submit" name="submit" class="btn btn-sm btn-light" id="savePermission">Save</button>
					</div>
				</div><!-- card -->
			</form>
		</div><!-- col-sm -->
		<div class="col-sm">
			<h2 class="text-center">Permissions</h2>
			<table class="table table-striped table-bordered text-center" id="permissionsTable"></table>
		</div><!-- col-sm -->
	</div><!-- row -->

<?php require_once "footer.php"; ?>