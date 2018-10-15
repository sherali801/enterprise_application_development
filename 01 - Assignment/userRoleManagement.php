<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="SecurityManager.js"></script>
	<script>

        function Main() {
            populateUsers("");
            populateRoles("");
            populateUsersRoles();
            document.getElementById("clearBtn").onclick = clear;
            document.getElementById("saveBtn").onclick = saveUserRole;
        }//Main

        function populateUsers(user) {
            var allUsers = SecurityManager.GetAllUsers();
            var users = document.getElementById("users");
            users.innerHTML = "";
            var option = document.createElement("option");
            option.setAttribute("value", "0");
            option.innerText = "--Select--";
            users.appendChild(option);
            for (var i = 0; i < allUsers.length; i++) {
                option = document.createElement("option");
                option.setAttribute("value", allUsers[i].ID + "");
                if (user == allUsers[i].Login) {
                    option.setAttribute("selected", true);
                }
                option.innerText = allUsers[i].Login;
                users.appendChild(option);
            }
        }

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

        function populateUsersRoles() {
            var allUsersRoles = SecurityManager.GetAllUserRoles();
            var table = document.getElementById("table");
            table.innerHTML = "";
            var tr = document.createElement("tr");
            tr.innerHTML = "<th>ID</th><th>User</th><th>Role</th><th>Edit</th><th>Delete</th>";
            table.appendChild(tr);
            for (var i = 0; i < allUsersRoles.length; i++) {
                tr = document.createElement("tr");
                tr.innerHTML = "<td class='align-middle'>" + allUsersRoles[i].ID + "</td>" +
                               "<td class='align-middle'>" + allUsersRoles[i].User + "</td>" +
                               "<td class='align-middle'>" + allUsersRoles[i].Role + "</td>" +
                               "<td class='align-middle'><button class='btn btn-secondary' id='" + allUsersRoles[i].ID + "' onclick='editUserRole(id);'>Edit</button></td>" +
                               "<td class='align-middle'><button class='btn btn-danger' id='" + allUsersRoles[i].ID + "' onclick='deleteUserRole(id);'>Delete</button></td>";
                table.appendChild(tr);
            }
        }

        function clear() {
            document.getElementById("id").value = "0";
            populateRoles("");
            populateUsers("");
        }

        function saveUserRole() {
            var id = parseInt(document.getElementById("id").value);
            var userId = parseInt(document.getElementById("users").value);
            var roleId = parseInt(document.getElementById("roles").value);
            var msg = "";
            var userIdValidationResult = validateUserId(userId);
            if (!userIdValidationResult[0]) {
                msg += userIdValidationResult[1] + "\n";
            }
            var roleIdValidationResult = validateRoleId(roleId);
            if (!roleIdValidationResult[0]) {
                msg += roleIdValidationResult[1] + "\n";
            }
            if (msg == "") {
                var user = SecurityManager.GetUserById(userId);
                var role = SecurityManager.GetRoleById(roleId);
                var userRoleValidationResult = validateUserRole(user.Login, role.Name);
                if (userRoleValidationResult[0]) {
                    SecurityManager.SaveUserRole({
                        ID: id,
                        User: user.Login,
                        Role: role.Name
                    }, saveUserRoleSuccess, saveUserRoleFailure);
                } else {
                    alert(userRoleValidationResult[1]);
                }
            } else {
                alert(msg);
            }
        }

        function validateUserId(userId) {
            userId = parseInt(userId);
            var user = SecurityManager.GetUserById(userId);
            if (user != null) {
                return [true];
            } else {
                return [false, "Invalid user."];
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

        function validateUserRole(user, role) {
            var id = parseInt(document.getElementById("id").value);
            var allUsersRoles = SecurityManager.GetAllUserRoles();
            for (var i = 0; i < allUsersRoles.length; i++) {
                if (allUsersRoles[i].User == user && allUsersRoles[i].Role == role && id != allUsersRoles[i].ID) {
                    return [false, "User-Role combination already exists."];
                }
            }
            return [true];
        }

        function saveUserRoleSuccess(userRole) {
            var id = parseInt(document.getElementById("id").value);
            if (id == 0) {
                alert("User-Role with ID: " + userRole.ID + " has been added.");
            } else {
                alert("User-Role with ID: " + userRole.ID + " has been updated.");
            }
            clear();
            populateUsersRoles();
        }

        function saveUserRoleFailure(msg) {
            var id = parseInt(document.getElementById("id").value);
            if (id) {
                alert("User-Role with ID: " + id + " wasn't updated.\n" + msg);
            } else {
                alert("User-Role wasn't added.\n" + msg);
            }
        }

        function editUserRole(id) {
            id = parseInt(id);
            var userRole = SecurityManager.GetUserRoleById(id);
            document.getElementById("id").value = userRole.ID;
            populateUsers(userRole.User);
            populateRoles(userRole.Role);
        }

        function deleteUserRole(id) {
            id = parseInt(id);
            if (confirm("Do you want to delete user-role with ID: " + id + "?")) {
                SecurityManager.DeleteUserRole(id, deleteUserRoleSuccess, deleteUserRoleFailure);
            }
        }

        function deleteUserRoleSuccess(id) {
            id = parseInt(id);
            alert("User-Role with ID: " + id + " has been deleted.");
            populateUsersRoles();
        }

        function deleteUserRoleFailure(msg) {
            alert("User-Role wasn't deleted.\n" + msg)
        }

	</script>
</head>
<body onload="Main();">
<?php require_once 'adminNavbar.php'; ?>
<div class="container">
    <div class="row mb-3">
        <div class="col-sm">
            <div class="card">
                <div class="card-header bg-dark text-white">User-Role Management</div>
                <div class="card-body">
                    <input type="hidden" id="id" value="0">
                    <div class="form-group">
                        <label for="users">User: </label>
                        <select class="form-control" id="users"></select>
                    </div>
                    <div class="form-group">
                        <label for="roles">Role: </label>
                        <select class="form-control" id="roles"></select>
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