<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="SecurityManager.js"></script>
	<script>

        function Main() {
            populatePermissions();
            document.getElementById("clearBtn").onclick = clear;
            document.getElementById("saveBtn").onclick = savePermission;
        }//Main

        function populatePermissions() {
            var allPermissions = SecurityManager.GetAllPermissions();
            var table = document.getElementById("table");
            table.innerHTML = "";
            var tr = document.createElement("tr");
            tr.innerHTML = "<th>ID</th><th>Name</th><th>Description</th><th>Edit</th><th>Delete</th>";
            table.appendChild(tr);
            for (var i = 0; i < allPermissions.length; i++) {
                tr = document.createElement("tr");
                tr.innerHTML = "<td class='align-middle'>" + allPermissions[i].ID + "</td>" +
                               "<td class='align-middle'>" + allPermissions[i].Name + "</td>" +
                               "<td class='align-middle'>" + allPermissions[i].Description + "</td>" +
                               "<td class='align-middle'><button class='btn btn-secondary' id='" + allPermissions[i].ID + "' onclick='editPermission(id);'>Edit</button></td>" +
                               "<td class='align-middle'><button class='btn btn-danger' id='" + allPermissions[i].ID + "' onclick='deletePermission(id);'>Delete</button></td>";
                table.appendChild(tr);
            }
        }

        function clear() {
            document.getElementById("id").value = "0";
            document.getElementById("name").value = "";
            document.getElementById("description").value = "";
        }

        function savePermission() {
            var id = parseInt(document.getElementById("id").value);
            var name = document.getElementById("name").value;
            var description = document.getElementById("description").value;
            var msg = "";
            var nameValidationResult = validateName(name);
            if (!nameValidationResult[0]) {
                msg += nameValidationResult[1] + "\n";
            }
            var descriptionValidationResult = validateDescription(description);
            if (!descriptionValidationResult[0]) {
                msg += descriptionValidationResult[1] + "\n";
            }
            if (msg == "") {
                SecurityManager.SavePermission({
                    ID: id,
                    Name: name,
                    Description: description
                }, savePermissionSuccess, savePermissionFailure);
            } else {
                alert(msg);
            }
        }

        function validateName(name) {
            var id = parseInt(document.getElementById("id").value);
            var allPermissions = SecurityManager.GetAllPermissions();
            if (id == 0) {
                for (var i = 0; i < allPermissions.length; i++) {
                    if (name == allPermissions[i].Name) {
                        return [false, "Permission already exists."];
                    }
                }
            } else {
                for (var i = 0; i < allPermissions.length; i++) {
                    if (name == allPermissions[i].Name && id != allPermissions[i].ID) {
                        return [false, "Permission already exists."];
                    }
                }
            }
            if (name === "") {
                return [false, "Permission field is empty."];
            }
            if (name.match(/^.{1,30}$/)) {
                return [true];
            } else {
                return [false, "Permission is invalid."];
            }
        }

        function validateDescription(description) {
            if (description === "") {
                return [false, "Description field is empty."];
            }
            if (description.match(/^.{1,30}$/)) {
                return [true];
            } else {
                return [false, "Description is invalid."];
            }
        }

        function savePermissionSuccess(permission) {
            var id = parseInt(document.getElementById("id").value);
            if (id == 0) {
                alert("Permission with ID: " + permission.ID + " has been added.");
            } else {
                alert("Permission with ID: " + permission.ID + " has been updated.");
            }
            clear();
            populatePermissions();
        }

        function savePermissionFailure(msg) {
            var id = parseInt(document.getElementById("id").value);
            if (id) {
                alert("Permission with ID: " + id + " wasn't updated.\n" + msg);
            } else {
                alert("Permission wasn't added.\n" + msg);
            }
        }

        function editPermission(id) {
            id = parseInt(id);
            var user = SecurityManager.GetPermissionById(id);
            document.getElementById("id").value = user.ID;
            document.getElementById("name").value = user.Name;
            document.getElementById("description").value = user.Description;
        }

        function deletePermission(id) {
            id = parseInt(id);
            if (confirm("Do you want to delete permission with ID: " + id + "?")) {
                SecurityManager.DeletePermission(id, deletePermissionSuccess, deletePermissionFailure);
            }
        }

        function deletePermissionSuccess(id) {
            id = parseInt(id);
            alert("Permission with ID: " + id + " has been deleted.");
            populatePermissions();
        }

        function deletePermissionFailure(msg) {
            alert("Permission wasn't deleted.\n" + msg);
        }

	</script>
</head>
<body onload="Main();">
<?php require_once 'adminNavbar.php'; ?>
<div class="container">
    <div class="row mb-3">
        <div class="col-sm">
            <div class="card">
                <div class="card-header bg-dark text-white">Permission Management</div>
                <div class="card-body">
                    <input type="hidden" id="id" value="0">
                    <div class="form-group">
                        <label for="name">Permission: </label>
                        <input type="text" class="form-control" id="name" aria-describedby="nameHelp" placeholder="Permission" pattern="^.{1,30}$" required autofocus>
                        <small id="nameHelp" class="form-text text-muted">At max 30 characters.</small>
                    </div>
                    <div class="form-group">
                        <label for="description">Description: </label>
                        <input type="text" class="form-control" id="description" aria-describedby="descriptionHelp" placeholder="Description" pattern="^.{1,30}$" required>
                        <small id="descriptionHelp" class="form-text text-muted">At max 30 characters.</small>
                    </div>
                </div><!-- card-body -->
                <div class="card-footer bg-dark text-right">
                    <button class="btn btn-sm btn-light" id="clearBtn">Clear</button>
                    <button class="btn btn-sm btn-light" id="saveBtn">Save</button>
                </div>
            </div><!-- card -->
        </div><!-- col-sm -->
        <div class="col-sm">
            <table id="table" class="table table-striped table-bordered text-center"></table>
        </div><!-- col-sm -->
    </div><!-- row -->
</div><!-- container -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>