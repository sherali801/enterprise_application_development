<?php

require_once "session.php";
require_once "db_connection.php";

$_SERVER["errors"] = [];


function validateUsername($username) {
	if (empty($username)) {
		$_SESSION["errors"][] = "Username is empty.";
		return false;
	}
	if (!preg_match("/^[\w]{1,45}$/", $username)) {
		$_SESSION["errors"][] = "Username must be less than 45 characters.";
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

function duplicateUsername($username) {
	global $conn;
	$sql = "SELECT COUNT(*)
			FROM users 
			WHERE login = '$username'";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$result = mysqli_fetch_row($result);
		$result = array_shift($result);
		if ($result) {
			$_SESSION["errors"][] = "Login already exists.";
			return false;
		}
	}
	return true;
}

function duplicateUsernameWithId($username, $id) {
	global $conn;
	$sql = "SELECT COUNT(*)
			FROM users 
			WHERE login = '$username' 
			AND id != $id";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$result = mysqli_fetch_row($result);
		$result = array_shift($result);
		if ($result) {
			$_SESSION["errors"][] = "Login already exists.";
			return false;
		}
	}
	return true;
}

function duplicateRoleName($name) {
	global $conn;
	$sql = "SELECT COUNT(*)
			FROM roles 
			WHERE name = '$name'";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$result = mysqli_fetch_row($result);
		$result = array_shift($result);
		if ($result) {
			$_SESSION["errors"][] = "Role already exists.";
			return false;
		}
	}
	return true;
}

function duplicateRoleNameWithId($name, $id) {
	global $conn;
	$sql = "SELECT COUNT(*)
			FROM roles 
			WHERE name = '$name' 
			AND id != $id";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$result = mysqli_fetch_row($result);
		$result = array_shift($result);
		if ($result) {
			$_SESSION["errors"][] = "Role already exists.";
			return false;
		}
	}
	return true;
}

function duplicatePermissionName($name) {
	global $conn;
	$sql = "SELECT COUNT(*)
			FROM permissions 
			WHERE name = '$name'";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$result = mysqli_fetch_row($result);
		$result = array_shift($result);
		if ($result) {
			$_SESSION["errors"][] = "Permission already exists.";
			return false;
		}
	}
	return true;
}

function duplicatePermissionNameWithId($name, $id) {
	global $conn;
	$sql = "SELECT COUNT(*)
			FROM permissions 
			WHERE name = '$name' 
			AND id != $id";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$result = mysqli_fetch_row($result);
		$result = array_shift($result);
		if ($result) {
			$_SESSION["errors"][] = "Permission already exists.";
			return false;
		}
	}
	return true;
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

function validateUser($username, $password) {
	global $conn;
	$sql = "SELECT * 
			FROM users 
			WHERE login = '$username' 
			AND password = '$password'";
	$result = mysqli_query($conn, $sql);
	if ($result) {
		while ($row = mysqli_fetch_assoc($result)) {
			if ($row["login"] == $username && $row["password"] == $password) {
				$_SESSION["id"] = $row["id"];
				$_SESSION["username"] = $row["login"];
				$_SESSION["user_role"] = $row["is_admin"];
				return true;
			}
		}
	}
	$_SESSION["errors"][] = "Username/Password combination doesn't match.";
	return false;
}

function validateRoleId($id) {
	global $conn;
	$sql = "SELECT * 
			FROM roles 
			WHERE id = $id";
	$result = mysqli_query($conn, $sql);
	if (!(mysqli_num_rows($result) > 0)) {
		$_SESSION["errors"][] = "Invalid role.";
		return false;
	}
	return true;
}

function validatePermissionId($id) {
	global $conn;
	$sql = "SELECT * 
			FROM permissions 
			WHERE id = $id";
	$result = mysqli_query($conn, $sql);
	if (!(mysqli_num_rows($result) > 0)) {
		$_SESSION["errors"][] = "Invalid permission.";
		return false;
	}
	return true;
}

function duplicateRolePermission($role_id, $permission_id) {
	global $conn;
	$sql = "SELECT COUNT(*)
			FROM role_permission 
			WHERE role_id = $role_id 
			AND permission_id = $permission_id";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$result = mysqli_fetch_row($result);
		$result = array_shift($result);
		if ($result) {
			$_SESSION["errors"][] = "Role-Permission already exists.";
			return false;
		}
	}
	return true;
}

function duplicateRolePermissionWithId($role_id, $permission_id, $role_permission_id) {
	global $conn;
	$sql = "SELECT COUNT(*)
			FROM role_permission 
			WHERE role_id = $role_id 
			AND permission_id = $permission_id 
			AND id != $role_permission_id";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$result = mysqli_fetch_row($result);
		$result = array_shift($result);
		if ($result) {
			$_SESSION["errors"][] = "Role-Permission already exists.";
			return false;
		}
	}
	return true;
}

function validateUserId($id) {
	global $conn;
	$sql = "SELECT * 
			FROM users 
			WHERE id = $id";
	$result = mysqli_query($conn, $sql);
	if (!(mysqli_num_rows($result) > 0)) {
		$_SESSION["errors"][] = "Invalid user.";
		return false;
	}
	return true;
}

function duplicateUserRole($user_id, $role_id) {
	global $conn;
	$sql = "SELECT COUNT(*)
			FROM user_role 
			WHERE user_id = $user_id 
			AND role_id = $role_id";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$result = mysqli_fetch_row($result);
		$result = array_shift($result);
		if ($result) {
			$_SESSION["errors"][] = "Role-Permission already exists.";
			return false;
		}
	}
	return true;
}

function duplicateUserRoleWithId($user_role_id, $user_id, $role_id) {
	global $conn;
	$sql = "SELECT COUNT(*)
			FROM user_role 
			WHERE user_id = $user_id
			AND role_id = $role_id 
			AND id != $user_role_id";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$result = mysqli_fetch_row($result);
		$result = array_shift($result);
		if ($result) {
			$_SESSION["errors"][] = "User-Role already exists.";
			return false;
		}
	}
	return true;
}

function authenticateUser() {
	if (!isset($_SESSION["id"])) {
		redirectTo("logout.php");
	}
}

function authenticateAdmin() {
	authenticateUser();
	if ($_SESSION["user_role"] != 1) {
		redirectTo("logout.php");
	}
}

function redirectTo($to) {
	header("Location: " . $to);
	exit;
}

function MySqlFormattedTime($dt) {
	return strftime("%Y-%m-%d %H:%M:%S", $dt);
}

function lastInsertId() {
	global $conn;
	return mysqli_insert_id($conn);
}

function getAllUsers() {
	global $conn;
	$sql = "SELECT * 
			FROM users";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function getUserById($id) {
	global $conn;
	$sql = "SELECT * 
			FROM users 
			WHERE id = $id";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function getUserByLogin($login) {
	global $conn;
	$sql = "SELECT * 
			FROM users 
			WHERE login = '$login'";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function addNewUser($login, $password, $name, $email, $country_id, $is_admin, $dt, $id) {
	global $conn;
	$sql = "INSERT INTO users (
			login, password, name, email, is_admin, country_id, created_at, created_by
			) VALUES (
			'$login', '$password', '$name', '$email', $is_admin, $country_id, '$dt', $id
			)";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function updateUser($user_id, $login, $password, $name, $email, $country_id, $is_admin, $id) {
	global $conn;
	$sql = "UPDATE users SET 
			login = '$login',
			password = '$password',
			name = '$name',
			email = '$email',
			country_id = $country_id,
			is_admin = $is_admin, 
			created_by = $id
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

function getAllCountries() {
	global $conn;
	$sql = "SELECT * 
			FROM countries";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function getAllRoles() {
	global $conn;
	$sql = "SELECT * 
			FROM roles";
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

function addNewRole($name, $description, $dt, $id) {
	global $conn;
	$sql = "INSERT INTO roles (
			name, description, created_at, created_by
			) VALUES (
			'$name', '$description', '$dt', $id
			)";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function updateRole($user_id, $name, $description, $id) {
	global $conn;
	$sql = "UPDATE roles SET 
			name = '$name',
			description = '$description',
			created_by = $id
			WHERE id = $user_id";
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

function addNewPermission($name, $description, $dt, $id) {
	global $conn;
	$sql = "INSERT INTO permissions (
			name, description, created_at, created_by
			) VALUES (
			'$name', '$description', '$dt', $id
			)";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function updatePermission($user_id, $name, $description, $id) {
	global $conn;
	$sql = "UPDATE permissions SET 
			name = '$name',
			description = '$description',
			created_by = $id
			WHERE id = $user_id";
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

function getRolePermissionById($id) {
	global $conn;
	$sql = "SELECT * 
			FROM role_permission 
			WHERE id = $id";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function addNewRolePermission($role_id, $permission_id) {
	global $conn;
	$sql = "INSERT INTO role_permission (
			role_id, permission_id
			) VALUES (
			$role_id, $permission_id
			)";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function updateRolePermission($role_permission_id, $role_id, $permission_id) {
	global $conn;
	$sql = "UPDATE role_permission SET 
			role_id = $role_id,
			permission_id = $permission_id
			WHERE id = $role_permission_id";
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
	$sql = "SELECT ur.id AS ur_id, u.login AS u_login, r.name AS r_name
			FROM users u, roles r, user_role ur 
			WHERE u.id = ur.user_id 
			AND r.id = ur.role_id";
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

function addNewUserRole($user_id, $role_id) {
	global $conn;
	$sql = "INSERT INTO user_role (
			user_id, role_id
			) VALUES (
			$user_id, $role_id
			)";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function updateUserRole($user_role_id, $user_id, $role_id) {
	global $conn;
	$sql = "UPDATE user_role SET 
			user_id = $user_id,
			role_id = $role_id 
			WHERE id = $user_role_id";
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

function getLoginHistory() {
	global $conn;
	$sql = "SELECT * 
			FROM login_history";
	$result = mysqli_query($conn, $sql);
	return $result;
}

function addNewLoginHistory($user_id, $login, $login_time, $machine_ip) {
	global $conn;
	$sql = "INSERT INTO login_history (
			user_id, login, login_time, machine_ip
			) VALUES (
			$user_id, '$login', '$login_time', '$machine_ip'
			)";
	$result = mysqli_query($conn, $sql);
	return $result;
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
