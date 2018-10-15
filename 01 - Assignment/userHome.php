<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="SecurityManager.js"></script>
	<script>

        function Main() {
            document.getElementById("logout").onclick = logout;
            var id = parseInt(window.localStorage.getItem("id"));
            var user = SecurityManager.GetUserById(id);
            document.getElementById("welcome").innerText = "Welcome " + user.Login;
            var userRoles = allUserRoles(user.Login);
            populateRoles(userRoles);
            var rolesPermissions = allRolesPermissions(userRoles);
            populatePermissions(rolesPermissions);
        }//Main

        function logout() {
            window.localStorage.removeItem("login");
            window.location.href = "login.php";
        }

        function allUserRoles(login) {
            var allUsersRoles = SecurityManager.GetAllUserRoles();
            var userRoles = [];
            for (var i = 0; i < allUsersRoles.length; i++) {
                if (login == allUsersRoles[i].User) {
                    userRoles[allUsersRoles[i].Role] = allUsersRoles[i].Role;
                }
            }
            return userRoles;
        }

		function populateRoles(userRoles) {
            var roles = document.getElementById("roles");
            for (var k in userRoles) {
                if (userRoles.hasOwnProperty(k)) {
                    var li = document.createElement("li");
                    li.classList.add("list-group-item");
                    li.innerText = userRoles[k];
                    roles.appendChild(li);
                }
            }
        }

        function allRolesPermissions(userRoles) {
            var allRolesPermissions = SecurityManager.GetAllRolePermissions();
            var rolesPermissions = [];
            for (var i = 0; i < allRolesPermissions.length; i++) {
                for (var k in userRoles) {
                    if (userRoles.hasOwnProperty(k)) {
                        if (userRoles[k] == allRolesPermissions[i].Role) {
                            rolesPermissions[allRolesPermissions[i].Permission] = allRolesPermissions[i].Permission;
                        }
                    }
                }
            }
            return rolesPermissions;
        }

        function populatePermissions(rolesPermissions) {
            var permissions = document.getElementById("permissions");
            for (var k in rolesPermissions) {
                if (rolesPermissions.hasOwnProperty(k)) {
                    var li = document.createElement("li");
                    li.classList.add("list-group-item");
                    li.innerText = rolesPermissions[k];
                    permissions.appendChild(li);
                }
            }
        }

	</script>
</head>
<body onload="Main();">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="userHome.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="#" id="logout">Logout</a></li>
        </ul>
    </div>
</nav>
<div class="container">
    <h2 id="welcome" class="mb-3"></h2>
    <h3>Roles</h3>
    <ul id="roles" class="list-group mb-3"></ul>
    <h3>Permissions</h3>
    <ul id="permissions" class="list-group mb-3"></ul>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>