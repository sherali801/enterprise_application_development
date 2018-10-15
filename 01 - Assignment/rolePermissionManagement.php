<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="SecurityManager.js"></script>
	<script>

        function Main() {
            populateRoles("");
            populatePermissions("");
            populateRolesPermissions();
            document.getElementById("clearBtn").onclick = clear;
            document.getElementById("saveBtn").onclick = saveRolePermission;
        }//Main

        function populateRoles(role) {
            var allRoles = SecurityManager.GetAllRoles();
            var roles = document.getElementById("roles");
            roles.innerHTML = "";
            var option = document.createElement("option");
            option.setAttribute("value", "0");
            option.innerText = "--Select--";
            roles.appendChild(option);
            for (var i = 0; i < allRoles.length; i++) {
                option = document.createElement("option");
                option.setAttribute("value", allRoles[i].ID + "");
                if (role == allRoles[i].Name) {
                    option.setAttribute("selected", true);
                }
                option.innerText = allRoles[i].Name;
                roles.appendChild(option);
            }
        }

        function populatePermissions(permission) {
            var allPermissions = SecurityManager.GetAllPermissions();
            var permissions = document.getElementById("permissions");
            permissions.innerHTML = "";
            var option = document.createElement("option");
            option.setAttribute("value", "0");
            option.innerText = "--Select--";
            permissions.appendChild(option);
            for (var i = 0; i < allPermissions.length; i++) {
                option = document.createElement("option");
                option.setAttribute("value", allPermissions[i].ID + "");
                if (permission == allPermissions[i].Name) {
                    option.setAttribute("selected", true);
                }
                option.innerText = allPermissions[i].Name;
                permissions.appendChild(option);
            }
        }

        function populateRolesPermissions() {
            var allRolesPermissions = SecurityManager.GetAllRolePermissions();
            var table = document.getElementById("table");
            table.innerHTML = "";
            var tr = document.createElement("tr");
            tr.innerHTML = "<th>ID</th><th>Role</th><th>Permission</th><th>Edit</th><th>Delete</th>";
            table.appendChild(tr);
            for (var i = 0; i < allRolesPermissions.length; i++) {
                tr = document.createElement("tr");
                tr.innerHTML = "<td class='align-middle'>" + allRolesPermissions[i].ID + "</td>" +
                               "<td class='align-middle'>" + allRolesPermissions[i].Role + "</td>" +
                               "<td class='align-middle'>" + allRolesPermissions[i].Permission + "</td>" +
                               "<td class='align-middle'><button class='btn btn-secondary' id='" + allRolesPermissions[i].ID + "' onclick='editRolePermission(id);'>Edit</button></td>" +
                               "<td class='align-middle'><button class='btn btn-danger' id='" + allRolesPermissions[i].ID + "' onclick='deleteRolePermission(id);'>Delete</button></td>";
                table.appendChild(tr);
            }
        }

        function clear() {
            document.getElementById("id").value = "0";
            populateRoles("");
            populatePermissions("");
        }

        function saveRolePermission() {
            var id = parseInt(document.getElementById("id").value);
            var roleId = parseInt(document.getElementById("roles").value);
            var permissionId = parseInt(document.getElementById("permissions").value);
            var msg = "";
            var roleIdValidationResult = validateRoleId(roleId);
            if (!roleIdValidationResult[0]) {
                msg += roleIdValidationResult[1] + "\n";
            }
            var permissionIdValidationResult = validatePermissionId(permissionId);
            if (!permissionIdValidationResult[0]) {
                msg += permissionIdValidationResult[1] + "\n";
            }
            if (msg == "") {
                var role = SecurityManager.GetRoleById(roleId);
                var permission = SecurityManager.GetPermissionById(permissionId);
                var rolePermissionValidationResult = validateRolePermission(role.Name, permission.Name);
                if (rolePermissionValidationResult[0]) {
                    SecurityManager.SaveRolePermission({
                        ID: id,
                        Role: role.Name,
                        Permission: permission.Name
                    }, saveRolePermissionSuccess, saveRolePermissionFailure);
                } else {
                    alert(rolePermissionValidationResult[1]);
                }
            } else {
                alert(msg);
            }
        }

        function validateRoleId(roleId) {
            roleId = parseInt(roleId);
            var role = SecurityManager.GetRoleById(roleId);
            if (role != null) {
                return [true];
            } else {
                return [false, "Invalid role."];
            }
        }

        function validatePermissionId(permissionId) {
            permissionId = parseInt(permissionId);
            var permission = SecurityManager.GetPermissionById(permissionId);
            if (permission != null) {
                return [true];
            } else {
                return [false, "Invalid Permission."];
            }
        }

        function validateRolePermission(role, permission) {
            var id = parseInt(document.getElementById("id").value);
            var allRolesPermissions = SecurityManager.GetAllRolePermissions();
	        for (var i = 0; i < allRolesPermissions.length; i++) {
	            if (allRolesPermissions[i].Role == role && allRolesPermissions[i].Permission == permission && id != allRolesPermissions[i].ID) {
	                return [false, "Role-Permission combination already exists."];
	            }
	        }
	        return [true];
        }

        function saveRolePermissionSuccess(rolePermission) {
            var id = parseInt(document.getElementById("id").value);
            if (id == 0) {
                alert("Role-Permission with ID: " + rolePermission.ID + " has been added.");
            } else {
                alert("Role-Permission with ID: " + rolePermission.ID + " has been updated.");
            }
            clear();
            populateRolesPermissions();
        }

        function saveRolePermissionFailure(msg) {
            var id = parseInt(document.getElementById("id").value);
            if (id) {
                alert("Role-Permission with ID: " + id + " wasn't updated.\n" + msg);
            } else {
                alert("Role-Permission wasn't added.\n" + msg);
            }
        }

        function editRolePermission(id) {
            id = parseInt(id);
            var rolePermission = SecurityManager.GetRolePermissionById(id);
            document.getElementById("id").value = rolePermission.ID;
            populateRoles(rolePermission.Role);
            populatePermissions(rolePermission.Permission);
        }

        function deleteRolePermission(id) {
            id = parseInt(id);
            if (confirm("Do you want to delete role-permission with ID: " + id + "?")) {
                SecurityManager.DeleteRolePermission(id, deleteRolePermissionSuccess, deleteRolePermissionFailure);
            }
        }

        function deleteRolePermissionSuccess(id) {
            id = parseInt(id);
            alert("Role-Permission with ID: " + id + " has been deleted.");
            populateRolesPermissions();
        }

        function deleteRolePermissionFailure(msg) {
            alert("Role-Permission wasn't deleted.\n" + msg)
        }

	</script>
</head>
<body onload="Main();">
<?php require_once 'adminNavbar.php'; ?>
<div class="container">
    <div class="row mb-3">
        <div class="col-sm">
            <div class="card">
                <div class="card-header bg-dark text-white">Role-Permission Management</div>
                <div class="card-body">
                    <input type="hidden" id="id" value="0">
                    <div class="form-group">
                        <label for="roles">Role: </label>
                        <select class="form-control" id="roles"></select>
                    </div>
                    <div class="form-group">
                        <label for="permissions">Permission: </label>
                        <select class="form-control" id="permissions"></select>
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