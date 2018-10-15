<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="SecurityManager.js"></script>
	<script>

        function Main() {
            document.getElementById("adminLoginBtn").onclick = adminLogin;
            document.getElementById("userLoginBtn").onclick = userLogin;
        }//Main

        function adminLogin() {
            var username = document.getElementById("adminUsername").value;
            var password = document.getElementById("adminPassword").value;
            if (SecurityManager.ValidateAdmin(username, password)) {
                window.location.href = "adminHome.php";
            } else {
                alert("Username/Password combination doesn't match.");
            }
        }//adminLogin

        function userLogin() {
            var username = document.getElementById("userUsername").value;
            var password = document.getElementById("userPassword").value;
            var msg = "";
            var usernameValidationResult = validateUsername(username);
            if (!usernameValidationResult[0]) {
                msg += usernameValidationResult[1] + "\n";
            }
            var passwordValidationResult = validatePassword(password);
            if (!passwordValidationResult[0]) {
                msg += passwordValidationResult[1] + "\n";
            }
            if (msg == "") {
                var id = validateUser(username, password);
                if (id) {
                    window.localStorage.setItem("id", id);
                    window.location.href = "userHome.php";
                } else {
                    alert("Username/Password combination doesn't match.");
                }
            } else {
                alert(msg);
            }
        }//userLogin

        function validateUsername(username) {
            if (username === "") {
                return [false, "Username field is empty."];
            }
            if (username.match(/^[\w]{1,25}$/)) {
                return [true];
            } else {
                return [false, "Username is invalid."];
            }
        }//validateUsername

        function validatePassword(password) {
            if (password === "") {
                return [false, "Password field is empty."];
            }
            if (password.match(/^(?=.*\d)(?=.*[~!@#$%^&*()_\-+=|\\{}[\]:;<>?/])(?=.*[A-Z])(?=.*[a-z])\S{8,15}$/)) {
                return [true];
            } else {
                return [false, "Password is invalid."];
            }
        }//validatePassword

        function validateUser(username, password) {
            var allUsers = SecurityManager.GetAllUsers();
            for (var i = 0; i < allUsers.length; i++) {
                if (username == allUsers[i].Login && password == allUsers[i].Password) {
                    return allUsers[i].ID;
                }
            }
            return 0;
        }//validateUser

	</script>
</head>
<body onload="Main();">
<div class="container">
    <h1>Security Manager</h1>
    <div class="card-group">
        <div class="card">
            <div class="card-header bg-dark text-white">Login Admin</div>
            <div class="card-body">
                <div class="form-group">
                    <label for="adminUsername">Username: </label>
                    <input type="text" class="form-control" id="adminUsername" aria-describedby="adminUsernameHelp" placeholder="Username" pattern="^[\w]{1,25}$" required autofocus>
                    <small id="adminUsernameHelp" class="form-text text-muted">At max 25 characters.</small>
                </div>
                <div class="form-group">
                    <label for="adminPassword">Password: </label>
                    <input type="password" class="form-control" id="adminPassword" aria-describedby="adminPasswordHelp" placeholder="Password" pattern="^(?=.*\d)(?=.*[~!@#$%^&*()_\-+=|\\{}[\]:;<>?/])(?=.*[A-Z])(?=.*[a-z])\S{8,15}$" required>
                    <small id="adminPasswordHelp" class="form-text text-muted">8-15 characters. one lowercase, one uppercase, one digit, one special character</small>
                </div>
            </div>
            <div class="card-footer bg-dark text-right">
                <button class="btn btn-sm btn-light" id="adminLoginBtn">Login</button>
            </div>
        </div><!-- card -->
        <div class="card">
            <div class="card-header text-white bg-dark">Login User</div>
            <div class="card-body">
                <div class="form-group">
                    <label for="userUsername">Username: </label>
                    <input type="text" class="form-control" id="userUsername" aria-describedby="userUsernameHelp" placeholder="Username" pattern="^[\w]{1,25}$" required>
                    <small id="userUsernameHelp" class="form-text text-muted">At max 25 characters.</small>
                </div>
                <div class="form-group">
                    <label for="userPassword">Password: </label>
                    <input type="password" class="form-control" id="userPassword" aria-describedby="userPasswordHelp" placeholder="Password" pattern="^(?=.*\d)(?=.*[~!@#$%^&*()_\-+=|\\{}[\]:;<>?/])(?=.*[A-Z])(?=.*[a-z])\S{8,15}$" required>
                    <small id="userPasswordHelp" class="form-text text-muted">8-15 characters. one lowercase, one uppercase, one digit, one special character</small>
                </div>
            </div>
            <div class="card-footer bg-dark text-right">
                <button class="btn btn-sm btn-light" id="userLoginBtn">Login</button>
            </div>
        </div><!-- card -->
    </div><!-- card-group -->
</div><!-- container -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>