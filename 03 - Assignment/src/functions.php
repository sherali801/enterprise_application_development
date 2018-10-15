<?php

require_once "session.php";
require_once "dbConnection.php";

$_SESSION["errors"] = [];

function redirectTo($to) {
	header("Location: " . $to);
	exit;
}

function MySqlFormattedTime($dt) {
	return strftime("%Y-%m-%d %H:%M:%S", $dt);
}

function validateLogin($login) {
	if (empty($login)) {
		$_SESSION["errors"][] = "Login is empty.";
		return false;
	}
	if (!preg_match("/^[\w]{1,45}$/", $login)) {
		$_SESSION["errors"][] = "Login must be less than 45 characters.";
		return false;
	}
	return true;
}

function validatePassword($password) {
	if (empty($password)) {
		$_SESSION["errors"][] = "Password is empty.";
		return false;
	}
	if (!preg_match("/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/", $password)) {
		$_SESSION["errors"][] = "Password must be 8-15 characters. one lowercase, one uppercase, one digit, one special character.";
		return false;
	}
	return true;
}

function validateName($name) {
	if (empty($name)) {
		$_SESSION["errors"][] = "Name is empty.";
		return false;
	}
	if (!preg_match("/^.{1,45}$/", $name)) {
		$_SESSION["errors"][] = "Name must be less than 45 characters.";
		return false;
	}
	return true;
}

function validateEmail($email) {
	if (empty($email)) {
		$_SESSION["errors"][] = "Email is empty.";
		return false;
	}
	if (!preg_match("/^[\w.%+\-]+@[\w.\-]+\.[A-Za-z]{2,6}$/", $email)) {
		$_SESSION["errors"][] = "Invalid email.";
		return false;
	}
	return true;
}

function validateCountryId($id) {
	global $conn;
	$sql = "SELECT *
			FROM countries 
			WHERE id = $id";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		return true;
	}
	$_SESSION["errors"][] = "Invalid country.";
	return false;
}

function validateCityId($countryId, $cityId) {
	global $conn;
	$sql = "SELECT *
			FROM countries, cities 
			WHERE countries.id = cities.country_id
			AND countries.id = $countryId
			AND cities.id = $cityId";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		return true;
	}
	$_SESSION["errors"][] = "Invalid city.";
	return false;
}

function validateDescription($description) {
	if (empty($description)) {
		$_SESSION["errors"][] = "Description is empty.";
		return false;
	}
	if (!preg_match("/^.{1,45}$/", $description)) {
		$_SESSION["errors"][] = "Description must be less than 45 characters.";
		return false;
	}
	return true;
}

function validateRoleId($id) {
	global $conn;
	$sql = "SELECT * 
			FROM roles 
			WHERE id = $id";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		return true;
	}
	$_SESSION["errors"][] = "Invalid role.";
	return false;
}

function validatePermissionId($id) {
	global $conn;
	$sql = "SELECT * 
			FROM permissions 
			WHERE id = $id";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		return true;
	}
	$_SESSION["errors"][] = "Invalid permission.";
	return false;
}

function validateUserId($id) {
	global $conn;
	$sql = "SELECT * 
			FROM users 
			WHERE id = $id";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		return true;
	}
	$_SESSION["errors"][] = "Invalid user.";
	return false;
}

function duplicateLogin($login) {
	global $conn;
	$sql = "SELECT *
			FROM users 
			WHERE login = '$login'";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$_SESSION["errors"][] = "Login already exists.";
		return false;
	}
	return true;
}

function duplicateLoginWithId($login, $id) {
	global $conn;
	$sql = "SELECT *
			FROM users 
			WHERE login = '$login' 
			AND id != $id";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$_SESSION["errors"][] = "Login already exists.";
		return false;
	}
	return true;
}

function duplicateRoleName($name) {
	global $conn;
	$sql = "SELECT *
			FROM roles 
			WHERE name = '$name'";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$_SESSION["errors"][] = "Role already exists.";
		return false;
	}
	return true;
}

function duplicateRoleNameWithId($name, $id) {
	global $conn;
	$sql = "SELECT *
			FROM roles 
			WHERE name = '$name' 
			AND id != $id";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$_SESSION["errors"][] = "Role already exists.";
		return false;
	}
	return true;
}

function duplicatePermissionName($name) {
	global $conn;
	$sql = "SELECT *
			FROM permissions 
			WHERE name = '$name'";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$_SESSION["errors"][] = "Permission already exists.";
		return false;
	}
	return true;
}

function duplicatePermissionNameWithId($name, $id) {
	global $conn;
	$sql = "SELECT *
			FROM permissions 
			WHERE name = '$name' 
			AND id != $id";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$_SESSION["errors"][] = "Permission already exists.";
		return false;
	}
	return true;
}

function duplicateRolePermission($roleId, $permissionId) {
	global $conn;
	$sql = "SELECT *
			FROM role_permission 
			WHERE role_id = $roleId 
			AND permission_id = $permissionId";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$_SESSION["errors"][] = "Role-Permission already exists.";
		return false;
	}
	return true;
}

function duplicateRolePermissionWithId($id, $roleId, $permissionId) {
	global $conn;
	$sql = "SELECT *
			FROM role_permission 
			WHERE role_id = $roleId 
			AND permission_id = $permissionId 
			AND id != $id";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$_SESSION["errors"][] = "Role-Permission already exists.";
		return false;
	}
	return true;
}

function duplicateUserRole($userId, $roleId) {
	global $conn;
	$sql = "SELECT *
			FROM user_role 
			WHERE user_id = $userId 
			AND role_id = $roleId";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$_SESSION["errors"][] = "User-Role already exists.";
		return false;
	}
	return true;
}

function duplicateUserRoleWithId($id, $userId, $roleId) {
	global $conn;
	$sql = "SELECT *
			FROM user_role 
			WHERE user_id = $userId
			AND role_id = $roleId 
			AND id != $id";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$_SESSION["errors"][] = "User-Role already exists.";
		return false;
	}
	return true;
}

function validateUser($login, $password) {
	global $conn;
	$sql = "SELECT * 
			FROM users 
			WHERE login = '$login' 
			AND password = '$password'";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			if ($row["login"] == $login && $row["password"] == $password) {
				$_SESSION["id"] = $row["id"];
				$_SESSION["login"] = $row["login"];
				$_SESSION["userRole"] = $row["is_admin"];
				return true;
			}
		}
	}
	$_SESSION["errors"][] = "Username/Password combination doesn't match.";
	return false;
}

function getErrorMessages() {
	$errors = $_SESSION["errors"];
	$msg = "";
	foreach ($errors as $error) {
		$msg .= $error . "\n";
	}
	$_SESSION["errors"] = [];
	return $msg;
}

function getUserById($id) {
	global $conn;
	$sql = "SELECT * 
			FROM users 
			WHERE id = $id";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function saveLoginHistory($userId, $login, $loginTime, $machineIp) {
	global $conn;
	$sql = "INSERT INTO login_history (
			user_id, login, login_time, machine_ip
			) VALUES (
			$userId, '$login', '$loginTime', '$machineIp'
			)";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function authenticateUser() {
	if (!isset($_SESSION["id"])) {
		redirectTo("logout.php");
	}
}

function authenticateAdmin() {
	authenticateUser();
	if ($_SESSION["userRole"] != 1) {
		redirectTo("logout.php");
	}
}

function getRolesOfAUserById($id) {
	global $conn;
	$sql = "SELECT r.name AS r_name 
			FROM user_role ur, users u, roles r 
			WHERE ur.user_id = u.id 
			AND ur.role_id = r.id 
			AND u.id = $id";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function getPermissionsOfAUserById($id) {
	global $conn;
	$sql = "SELECT DISTINCT p.name AS p_name
			FROM users u, user_role ur, roles r, role_permission rp, permissions p
			WHERE u.id = ur.user_id
			AND ur.role_id = r.id
			AND r.id = rp.role_id
			AND rp.permission_id = p.id 
			AND u.id = $id";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function getLoginHistory() {
	global $conn;
	$sql = "SELECT * 
			FROM login_history";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function getAllCountries() {
	global $conn;
	$sql = "SELECT * 
			FROM countries
			ORDER BY id ASC";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function getAllCitiesByCountryId($countryId) {
	global $conn;
	$sql = "SELECT * 
			FROM cities
			WHERE country_id = $countryId
			ORDER BY id ASC";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function getAllUsers() {
	global $conn;
	$sql = "SELECT * 
			FROM users";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function saveUser($login, $password, $name, $email, $countryId, $cityId, $isAdmin, $createdAt, $id) {
	global $conn;
	$sql = "INSERT INTO users (
			login, password, name, email, is_admin, country_id, city_id, created_at, created_by
			) VALUES (
			'$login', '$password', '$name', '$email', $isAdmin, $countryId, $cityId, '$createdAt', $id
			)";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function updateUser($user_id, $login, $password, $name, $email, $countryId, $cityId, $isAdmin) {
	global $conn;
	$sql = "UPDATE users SET 
			login = '$login',
			password = '$password',
			name = '$name',
			email = '$email',
			country_id = $countryId,
			city_id = $cityId,
			is_admin = $isAdmin
			WHERE id = $user_id";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function deleteUserById($id) {
	global $conn;
	$sql = "DELETE FROM users 
			WHERE id = $id";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function lastInsertId() {
	global $conn;
	return mysqli_insert_id($conn);
}

function getAllRoles() {
	global $conn;
	$sql = "SELECT * 
			FROM roles";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function saveRole($name, $description, $createdAt, $id) {
	global $conn;
	$sql = "INSERT INTO roles (
			name, description, created_at, created_by
			) VALUES (
			'$name', '$description', '$createdAt', $id
			)";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function updateRole($id, $name, $description) {
	global $conn;
	$sql = "UPDATE roles SET 
			name = '$name',
			description = '$description'
			WHERE id = $id";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function getRoleById($id) {
	global $conn;
	$sql = "SELECT * 
			FROM roles 
			WHERE id = $id";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function deleteRoleById($id) {
	global $conn;
	$sql = "DELETE FROM roles 
			WHERE id = $id";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function getAllPermissions() {
	global $conn;
	$sql = "SELECT * 
			FROM permissions";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function getPermissionById($id) {
	global $conn;
	$sql = "SELECT * 
			FROM permissions 
			WHERE id = $id";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function savePermission($name, $description, $createdAt, $id) {
	global $conn;
	$sql = "INSERT INTO permissions (
			name, description, created_at, created_by
			) VALUES (
			'$name', '$description', '$createdAt', $id
			)";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function updatePermission($id, $name, $description) {
	global $conn;
	$sql = "UPDATE permissions SET 
			name = '$name',
			description = '$description'
			WHERE id = $id";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function deletePermissionById($id) {
	global $conn;
	$sql = "DELETE FROM permissions 
			WHERE id = $id";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function getAllRolePermissions() {
	global $conn;
	$sql = "SELECT rp.id AS rp_id, r.name AS r_name, p.name AS p_name
			FROM roles r, permissions p, role_permission rp 
			WHERE r.id = rp.role_id 
			AND p.id = rp.permission_id";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function saveRolePermission($roleId, $permissionId) {
	global $conn;
	$sql = "INSERT INTO role_permission (
			role_id, permission_id
			) VALUES (
			$roleId, $permissionId
			)";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function updateRolePermission($id, $roleId, $permissionId) {
	global $conn;
	$sql = "UPDATE role_permission SET 
			role_id = $roleId,
			permission_id = $permissionId
			WHERE id = $id";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function getRolePermissionById($id) {
	global $conn;
	$sql = "SELECT * 
			FROM role_permission 
			WHERE id = $id";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function deleteRolePermissionById($id) {
	global $conn;
	$sql = "DELETE FROM role_permission 
			WHERE id = $id";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function getAllUserRoles() {
	global $conn;
	$sql = "SELECT ur.id AS ur_id, u.name AS u_name, r.name AS r_name
			FROM users u, roles r, user_role ur 
			WHERE u.id = ur.user_id 
			AND r.id = ur.role_id";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function saveUserRole($userId, $roleId) {
	global $conn;
	$sql = "INSERT INTO user_role (
			user_id, role_id
			) VALUES (
			$userId, $roleId
			)";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function updateUserRole($id, $userId, $roleId) {
	global $conn;
	$sql = "UPDATE user_role SET 
			user_id = $userId,
			role_id = $roleId 
			WHERE id = $id";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function getUserRoleById($id) {
	global $conn;
	$sql = "SELECT * 
			FROM user_role 
			WHERE id = $id";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function deleteUserRoleById($id) {
	global $conn;
	$sql = "DELETE FROM user_role 
			WHERE id = $id";
	$result = mysqli_query($conn, $sql);
	return $result;
}