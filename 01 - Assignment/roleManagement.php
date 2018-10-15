<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="SecurityManager.js"></script>
	<script>

        function Main() {
            populateRoles();
            document.getElementById("clearBtn").onclick = clear;
            document.getElementById("saveBtn").onclick = saveRole;
        }//Main

        function populateRoles() {
            var allRoles = SecurityManager.GetAllRoles();
            var table = document.getElementById("table");
            table.innerHTML = "";
            var tr = document.createElement("tr");
            tr.innerHTML = "<th>ID</th><th>Name</th><th>Description</th><th>Edit</th><th>Delete</th>";
            table.appendChild(tr);
            for (var i = 0; i < allRoles.length; i++) {
                tr = document.createElement("tr");
                tr.innerHTML = "<td class='align-middle'>" + allRoles[i].ID + "</td>" +
                               "<td class='align-middle'>" + allRoles[i].Name + "</td>" +
                               "<td class='align-middle'>" + allRoles[i].Description + "</td>" +
                               "<td class='align-middle'><button class='btn btn-secondary' id='" + allRoles[i].ID + "' onclick='editRole(id);'>Edit</button></td>" +
                               "<td class='align-middle'><button class='btn btn-danger' id='" + allRoles[i].ID + "' onclick='deleteRole(id);'>Delete</button></td>";
                table.appendChild(tr);
            }
        }

        function clear() {
            document.getElementById("id").value = "0";
            document.getElementById("name").value = "";
            document.getElementById("description").value = "";
        }

        function saveRole() {
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
                SecurityManager.SaveRole({
                    ID: id,
                    Name: name,
                    Description: description
                }, saveRoleSuccess, saveRoleFailure);
            } else {
                alert(msg);
            }
        }

        function validateName(name) {
            var id = parseInt(document.getElementById("id").value);
            var allRoles = SecurityManager.GetAllRoles();
            if (id == 0) {
                for (var i = 0; i < allRoles.length; i++) {
                    if (name == allRoles[i].Name) {
                        return [false, "Role already exists."];
                    }
                }
            } else {
                for (var i = 0; i < allRoles.length; i++) {
                    if (name == allRoles[i].Name && id != allRoles[i].ID) {
                        return [false, "Role already exists."];
                    }
                }
            }
            if (name === "") {
                return [false, "Role field is empty."];
            }
            if (name.match(/^.{1,30}$/)) {
                return [true];
            } else {
                return [false, "Role is invalid."];
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

        function saveRoleSuccess(role) {
            var id = parseInt(document.getElementById("id").value);
            if (id == 0) {
                alert("Role with ID: " + role.ID + " has been added.");
            } else {
                alert("Role with ID: " + role.ID + " has been updated.");
            }
            clear();
            populateRoles();
        }

        function saveRoleFailure(msg) {
            var id = parseInt(document.getElementById("id").value);
            if (id) {
                alert("Role with ID: " + id + " wasn't updated.\n" + msg);
            } else {
                alert("Role wasn't added.\n" + msg);
            }
        }

        function editRole(id) {
            id = parseInt(id);
            var user = SecurityManager.GetRoleById(id);
            document.getElementById("id").value = user.ID;
            document.getElementById("name").value = user.Name;
            document.getElementById("description").value = user.Description;
        }

        function deleteRole(id) {
            id = parseInt(id);
            if (confirm("Do you want to delete role with ID: " + id + "?")) {
                SecurityManager.DeleteRole(id, deleteRoleSuccess, deleteRoleFailure);
            }
        }

        function deleteRoleSuccess(id) {
            id = parseInt(id);
            alert("Role with ID: " + id + " has been deleted.");
            populateRoles();
        }

        function deleteRoleFailure(msg) {
            alert("Role with wasn't deleted.\n" + msg);
        }

	</script>
</head>
<body onload="Main();">
<?php require_once 'adminNavbar.php'; ?>
<div class="container">
    <div class="row mb-3">
        <div class="col-sm">
            <div class="card">
                <div class="card-header bg-dark text-white">Role Management</div>
                <div class="card-body">
                    <input type="hidden" id="id" value="0">
                    <div class="form-group">
                        <label for="name">Role: </label>
                        <input type="text" class="form-control" id="name" aria-describedby="nameHelp" placeholder="Role" pattern="^.{1,30}$" required autofocus>
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