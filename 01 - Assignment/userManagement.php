<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="SecurityManager.js"></script>
	<script>

        function Main() {
            populateCountries(0);
            populateCities(0, 0);
            populateUsers();
            document.getElementById("countries").onchange = changeCities;
            document.getElementById("clearBtn").onclick = clear;
            document.getElementById("saveBtn").onclick = saveUser;
        }//Main

		function populateCountries(id) {
            var allCountries = SecurityManager.GetCountries();
            var countries = document.getElementById("countries");
            countries.innerHTML = "";
            var option = document.createElement("option");
            option.setAttribute("value", "0");
            option.innerText = "--Select--";
            countries.appendChild(option);
            for (var i = 0; i < allCountries.length; i++) {
                option = document.createElement("option");
                option.setAttribute("value", allCountries[i].CountryID + "");
                if (id == allCountries[i].CountryID) {
                    option.setAttribute("selected", true);
                }
                option.innerText = allCountries[i].Name;
                countries.appendChild(option);
            }
        }//populateCountries

        function changeCities() {
            populateCities(this.value, 0);
        }//changeCities

        function populateCities(countryId, cityId) {
            var allCitiesOfACountry = SecurityManager.GetCitiesByCountryId(countryId);
            var cities = document.getElementById("cities");
            cities.innerHTML = "";
            var option = document.createElement("option");
            option.setAttribute("value", "0");
            option.innerText = "--Select--";
            cities.appendChild(option);
            for (var i = 0; i < allCitiesOfACountry.length; i++) {
                option = document.createElement("option");
                option.setAttribute("value", allCitiesOfACountry[i].CityID + "");
                if (cityId == allCitiesOfACountry[i].CityID) {
                    option.setAttribute("selected", true);
                }
                option.innerText = allCitiesOfACountry[i].Name;
                cities.appendChild(option);
            }
        }//populateCities
        
        function populateUsers() {
            var allUsers = SecurityManager.GetAllUsers();
            var table = document.getElementById("table");
            table.innerHTML = "";
            var tr = document.createElement("tr");
            tr.innerHTML = "<th>ID</th><th>Name</th><th>Email</th><th>Edit</th><th>Delete</th>";
            table.appendChild(tr);
            for (var i = 0; i < allUsers.length; i++) {
                tr = document.createElement("tr");
                tr.innerHTML = "<td class='align-middle'>" + allUsers[i].ID + "</td>" +
                               "<td class='align-middle'>" + allUsers[i].Name + "</td>" +
                               "<td class='align-middle'>" + allUsers[i].Email + "</td>" +
                               "<td class='align-middle'><button class='btn btn-secondary' id='" + allUsers[i].ID + "' onclick='editUser(id);'>Edit</button></td>" +
                               "<td class='align-middle'><button class='btn btn-danger' id='" + allUsers[i].ID + "' onclick='deleteUser(id)'>Delete</button></td>";
                table.appendChild(tr);
            }
        }//populateUsers

        function clear() {
            document.getElementById("id").value = "0";
	        document.getElementById("login").value = "";
	        document.getElementById("password").value = "";
	        document.getElementById("name").value = "";
	        document.getElementById("email").value = "";
	        populateCountries(0);
	        populateCities(0, 0);
        }//clear

        function saveUser() {
            var id = parseInt(document.getElementById("id").value);
            var login = document.getElementById("login").value;
            var password = document.getElementById("password").value;
            var name = document.getElementById("name").value;
            var email = document.getElementById("email").value;
	        var countryId = parseInt(document.getElementById("countries").value);
	        var cityId = parseInt(document.getElementById("cities").value);
	        var msg = "";
	        var loginValidationResult = validateLogin(login);
	        if (!loginValidationResult[0]) {
	            msg += loginValidationResult[1] + "\n";
            }
            var passwordValidationResult = validatePassword(password);
            if (!passwordValidationResult[0]) {
	            msg += passwordValidationResult[1] + "\n";
            }
            var nameValidationResult = validateName(name);
            if (!nameValidationResult[0]) {
                msg += nameValidationResult[1] + "\n";
            }
            var emailValidationResult = validateEmail(email);
            if (!emailValidationResult[0]) {
                msg += emailValidationResult[1] + "\n";
            }
            var countryIdValidationResult = validateCountryId(countryId);
            if (!countryIdValidationResult[0]) {
                msg += countryIdValidationResult[1] + "\n";
            }
            var cityIdValidationResult = validateCityId(countryId, cityId);
            if (!cityIdValidationResult[0]) {
                msg += cityIdValidationResult[1] + "\n";
            }
	        if (msg == "") {
                SecurityManager.SaveUser({
                    ID: id,
                    Login: login,
                    Password: password,
                    Name: name,
                    Email: email,
                    Country: countryId,
                    City: cityId
                }, saveUserSuccess, saveUserFailure);
            } else {
	            alert(msg);
            }
        }//saveUser

        function validateLogin(login) {
            var id = parseInt(document.getElementById("id").value);
            var allUsers = SecurityManager.GetAllUsers();
            if (id == 0) {
                for (var i = 0; i < allUsers.length; i++) {
                    if (login == allUsers[i].Login) {
                        return [false, "Username already exists."];
                    }
                }
            } else {
                for (var i = 0; i < allUsers.length; i++) {
                    if (login == allUsers[i].Login && id != allUsers[i].ID) {
                        return [false, "Username already exists."];
                    }
                }
            }
            if (login === "") {
                return [false, "Username field is empty."];
            }
            if (login.match(/^[\w]{1,15}$/)) {
                return [true];
            } else {
                return [false, "Username is invalid."];
            }
        }//validateLogin

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

        function validateName(name) {
            if (name === "") {
                return [false, "Name field is empty."];
            }
            if (name.match(/^.{1,30}$/)) {
                return [true];
            } else {
                return [false, "Name is invalid."];
            }
        }//validateName

        function validateEmail(email) {
            var id = parseInt(document.getElementById("id").value);
            var allUsers = SecurityManager.GetAllUsers();
            if (id == 0) {
                for (var i = 0; i < allUsers.length; i++) {
                    if (email == allUsers[i].Email) {
                        return [false, "Email already exists."];
                    }
                }
            } else {
                for (var i = 0; i < allUsers.length; i++) {
                    if (email == allUsers[i].Email && id != allUsers[i].ID) {
                        return [false, "Email already exists."];
                    }
                }
            }
            if (email === "") {
                return [false, "Email field is empty."];
            }
            if (email.match(/^[\w.%+\-]+@[\w.\-]+\.[A-Za-z]{2,6}$/)) {
                return [true];
            } else {
                return [false, "Email is invalid."];
            }
        }//validateEmail
        
        function validateCountryId(countryId) {
            var allCountries = SecurityManager.GetCountries();
            for (var i = 0; i < allCountries.length; i++) {
                if (countryId == allCountries[i].CountryID) {
                    return [true];
                }
            }
            return [false, "Country is invalid."];
        }//validateCountryId

        function validateCityId(countryId, cityId) {
            var allCitiesOfACountry = SecurityManager.GetCitiesByCountryId(countryId);
            for (var i = 0; i < allCitiesOfACountry.length; i++) {
                if (cityId == allCitiesOfACountry[i].CityID) {
                    return [true];
                }
            }
            return [false, "City is invalid."];
        }//validateCityId

        function saveUserSuccess(user) {
            var id = parseInt(document.getElementById("id").value);
	        if (id == 0) {
                alert("User with ID: " + user.ID + " has been added.");
            } else {
    	        alert("User with ID: " + user.ID + " has been updated.");
            }
            clear();
	        populateUsers();
        }//saveUserSuccess

        function saveUserFailure(msg) {
            var id = parseInt(document.getElementById("id").value);
            if (id) {
                alert("User with ID:" + id + " wasn't updated.\n" + msg);
            } else {
                alert("User wasn't added.\n" + msg);
            }
        }//saveUserFailure

        function editUser(id) {
            id = parseInt(id);
            var user = SecurityManager.GetUserById(id);
            document.getElementById("id").value = user.ID;
            document.getElementById("login").value = user.Login;
            document.getElementById("password").value = user.Password;
            document.getElementById("name").value = user.Name;
            document.getElementById("email").value = user.Email;
            populateCountries(user.Country);
            populateCities(user.Country, user.City);
        }//editUser

        function deleteUser(id) {
            id = parseInt(id);
            if (confirm("Do you want to delete user with ID: " + id + "?")) {
                SecurityManager.DeleteUser(id, deleteUserSuccess, deleteUserFailure);
            }
        }//deleteUser

        function deleteUserSuccess(id) {
	        alert("User with ID: " + id + " has been deleted.");
	        populateUsers();
        }//deleteUserSuccess

        function deleteUserFailure(msg) {
	        alert("User wasn't deleted.\n" + msg)
        }//deleteUserFailure

	</script>
</head>
<body onload="Main();">
<?php require_once 'adminNavbar.php'; ?>
<div class="container">
    <div class="row mb-3">
        <div class="col-sm">
            <div class="card">
                <div class="card-header bg-dark text-white">User Management</div>
                <div class="card-body">
                    <input type="hidden" id="id" value="0">
                    <div class="form-group">
                        <label for="login">Login: </label>
                        <input type="text" class="form-control" id="login" aria-describedby="loginHelp" placeholder="Login" pattern="^[\w]{1,25}$" required autofocus>
                        <small id="loginHelp" class="form-text text-muted">At max 25 characters.</small>
                    </div>
                    <div class="form-group">
                        <label for="password">Password: </label>
                        <input type="password" class="form-control" id="password" aria-describedby="passwordHelp" placeholder="Password" pattern="^(?=.*\d)(?=.*[~!@#$%^&*()_\-+=|\\{}[\]:;<>?/])(?=.*[A-Z])(?=.*[a-z])\S{8,15}$" required>
                        <small id="passwordHelp" class="form-text text-muted">8-15 characters. one lowercase, one uppercase, one digit, one special character</small>
                    </div>
                    <div class="form-group">
                        <label for="name">Name: </label>
                        <input type="text" class="form-control" id="name" aria-describedby="nameHelp" placeholder="Name" pattern="^.{1,30}$" required>
                        <small id="nameHelp" class="form-text text-muted">At max 30 characters.</small>
                    </div>
                    <div class="form-group">
                        <label for="email">Email: </label>
                        <input type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Email" pattern="^[\w.%+\-]+@[\w.\-]+\.[A-Za-z]{2,6}$" required>
                    </div>
                    <div class="form-group">
                        <label for="countries">Country: </label>
                        <select class="form-control" id="countries"></select>
                    </div>
                    <div class="form-group">
                        <label for="cities">City: </label>
                        <select class="form-control" id="cities"></select>
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