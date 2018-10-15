<?php

require_once "session.php";
require_once "functions.php";

authenticateAdmin();

if (isset($_POST["flag"])) {
	if ($_POST["flag"] == "getAllCountries") {
		$result = getAllCountries();
		$countries = [];
		while ($row = mysqli_fetch_assoc($result)) {
			$countries[] = array(
				"id" => $row["id"],
				"name" => $row["name"]
			);
		}
		echo json_encode($countries);
	} else if ($_POST["flag"] == "getAllCitiesByCountryId") {
		$countryId = $_POST["countryId"];
		$result = getAllCitiesByCountryId($countryId);
		$cities = [];
		while ($row = mysqli_fetch_assoc($result)) {
			$cities[] = array(
				"id" => $row["id"],
				"name" => $row["name"]
			);
		}
		echo json_encode($cities);
	} else if ($_POST["flag"] == "getAllUsers") {
		$result = getAllUsers();
		$users = [];
		while ($row = mysqli_fetch_assoc($result)) {
			$users[] = array(
				"id" => $row["id"],
				"login" => $row["login"],
				"name" => $row["name"],
				"email" => $row["email"]
			);
		}
		echo json_encode($users);
	} else if ($_POST["flag"] == "saveUser" || $_POST["flag"] == "editUser") {
		$id = $_POST["id"];
		$login = $_POST["login"];
		$password = $_POST["password"];
		$name = $_POST["name"];
		$email = $_POST["email"];
		$countryId = $_POST["countryId"];
		$cityId = $_POST["cityId"];
		$isAdmin = $_POST["isAdmin"];
		$createdAt = MySqlFormattedTime(time());
		$usernameValidationResult = validateLogin($login);
		$passwordValidationResult = validatePassword($password);
		$nameValidationResult = validateName($name);
		$emailValidationResult = validateEmail($email);
		$countryIdValidationResult = validateCountryId($countryId);
		$cityIdValidationResult = validateCityId($countryId, $cityId);
		$response = [];
		if ($usernameValidationResult && $passwordValidationResult && $nameValidationResult && $emailValidationResult && $countryIdValidationResult && $cityIdValidationResult) {
			if ($id == 0 && $_POST["flag"] == "saveUser") {
				$loginDuplicationResult = duplicateLogin($login);
				if ($loginDuplicationResult) {
					if (saveUser($login, $password, $name, $email, $countryId, $cityId, $isAdmin, $createdAt, $_SESSION["id"])) {
						$response = array(
							"status" => "success",
							"msg" => "User with ID: " . lastInsertId() . " has been added."
						);
					} else {
						$_SESSION["errors"][] = "User was not added.";
						$msg = getErrorMessages();
						$response = array(
							"status" => "failure",
							"msg" => $msg
						);
					}
				} else {
					$msg = getErrorMessages();
					$response = array(
						"status" => "failure",
						"msg" => $msg
					);
				}
			} else {
				$usernameDuplicationResult = duplicateLoginWithId($login, $id);
				if ($usernameDuplicationResult) {
					if (updateUser($id, $login, $password, $name, $email, $countryId, $cityId, $isAdmin)) {
						$response = array(
							"status" => "success",
							"msg" => "User with ID: $id has been updated."
						);
					} else {
						$_SESSION["errors"][] = "User with ID: $id was not updated.";
						$msg = getErrorMessages();
						$response = array(
							"status" => "failure",
							"msg" => $msg
						);
					}
				} else {
					$msg = getErrorMessages();
					$response = array(
						"status" => "failure",
						"msg" => $msg
					);
				}
			}
		} else {
			$msg = getErrorMessages();
			$response = array(
				"status" => "failure",
				"msg" => $msg
			);
		}
		echo json_encode($response);
	} else if ($_POST["flag"] == "getUserById") {
		$id = $_POST["id"];
		$result = getUserById($id);
		$users = [];
		while ($row = mysqli_fetch_assoc($result)) {
			$users[] = array(
				"id" => $row["id"],
				"login" => $row["login"],
				"password" => $row["password"],
				"name" => $row["name"],
				"email" => $row["email"],
				"isAdmin" => $row["is_admin"],
				"countryId" => $row["country_id"],
				"cityId" => $row["city_id"]
			);
		}
		echo json_encode($users);
	} else if ($_POST["flag"] == "deleteUserById") {
		$id = $_POST["id"];
		$result = deleteUserById($id);
		$response = [];
		if ($result) {
			$response = array(
				"msg" => "User with ID: $id has been deleted."
			);
		} else {
			$response = array(
				"msg" => "User with ID: $id was not deleted."
			);
		}
		echo json_encode($response);
	} else if ($_POST["flag"] == "getAllRoles") {
		$result = getAllRoles();
		$roles = [];
		while ($row = mysqli_fetch_assoc($result)) {
			$roles[] = array(
				"id" => $row["id"],
				"name" => $row["name"],
				"description" => $row["description"]
			);
		}
		echo json_encode($roles);
	} else if ($_POST["flag"] == "saveRole" || $_POST["flag"] == "editRole") {
		$id = $_POST["id"];
		$name = $_POST["name"];
		$description = $_POST["description"];
		$createdAt = MySqlFormattedTime(time());
		$nameValidationResult = validateName($name);
		$descriptionValidationResult = validateDescription($description);
		if ($nameValidationResult && $descriptionValidationResult) {
			if ($id == 0 && $_POST["flag"] == "saveRole") {
				$roleNameDuplicationResult = duplicateRoleName($name);
				if ($roleNameDuplicationResult) {
					if (saveRole($name, $description, $createdAt, $_SESSION["id"])) {
						$response = array(
							"status" => "success",
							"msg" => "Role with ID: " . lastInsertId() . " has been added."
						);
					} else {
						$_SESSION["errors"][] = "Role was not added.";
						$msg = getErrorMessages();
						$response = array(
							"status" => "failure",
							"msg" => $msg
						);
					}
				} else {
					$msg = getErrorMessages();
					$response = array(
						"status" => "failure",
						"msg" => $msg
					);
				}
			} else {
				$roleNameDuplicationResult = duplicateRoleNameWithId($name, $id);
				if ($roleNameDuplicationResult) {
					if (updateRole($id, $name, $description)) {
						$response = array(
							"status" => "success",
							"msg" => "Role with ID: $id has been updated."
						);
					} else {
						$_SESSION["errors"][] = "Role with ID: $id was not updated.";
						$msg = getErrorMessages();
						$response = array(
							"status" => "failure",
							"msg" => $msg
						);
					}
				} else {
					$msg = getErrorMessages();
					$response = array(
						"status" => "failure",
						"msg" => $msg
					);
				}
			}
		} else {
			$msg = getErrorMessages();
			$response = array(
				"status" => "failure",
				"msg" => $msg
			);
		}
		echo json_encode($response);
	} else if ($_POST["flag"] == "getRoleById") {
		$id = $_POST["id"];
		$result = getRoleById($id);
		$roles = [];
		while ($row = mysqli_fetch_assoc($result)) {
			$roles[] = array(
				"id" => $row["id"],
				"name" => $row["name"],
				"description" => $row["description"]
			);
		}
		echo json_encode($roles);
	} else if ($_POST["flag"] == "deleteRoleById") {
		$id = $_POST["id"];
		$result = deleteRoleById($id);
		if ($result) {
			$response = array(
				"msg" => "Role with ID: $id has been deleted."
			);
		} else {
			$response = array(
				"msg" => "Role with ID: $id was not deleted."
			);
		}
		echo json_encode($response);
	} else if ($_POST["flag"] == "getAllPermissions") {
		$result = getAllPermissions();
		$permissions = [];
		while ($row = mysqli_fetch_assoc($result)) {
			$permissions[] = array(
				"id" => $row["id"],
				"name" => $row["name"],
				"description" => $row["description"]
			);
		}
		echo json_encode($permissions);
	} else if ($_POST["flag"] == "savePermission" || $_POST["flag"] == "editPermission") {
		$id = $_POST["id"];
		$name = $_POST["name"];
		$description = $_POST["description"];
		$createdAt = MySqlFormattedTime(time());
		$nameValidationResult = validateName($name);
		$descriptionValidationResult = validateDescription($description);
		if ($nameValidationResult && $descriptionValidationResult) {
			if ($id == 0 && $_POST["flag"] == "savePermission") {
				$permissionNameDuplicationResult = duplicatePermissionName($name);
				if ($permissionNameDuplicationResult) {
					if (savePermission($name, $description, $createdAt, $_SESSION["id"])) {
						$response = array(
							"status" => "success",
							"msg" => "Permission with ID: " . lastInsertId() . " has been added."
						);
					} else {
						$_SESSION["errors"][] = "Permission was not added.";
						$msg = getErrorMessages();
						$response = array(
							"status" => "failure",
							"msg" => $msg
						);
					}
				} else {
					$msg = getErrorMessages();
					$response = array(
						"status" => "failure",
						"msg" => $msg
					);
				}
			} else {
				$permissionNameDuplicationResult = duplicatePermissionNameWithId($name, $id);
				if ($permissionNameDuplicationResult) {
					if (updatePermission($id, $name, $description)) {
						$response = array(
							"status" => "success",
							"msg" => "Permission with ID: $id has been updated."
						);
					} else {
						$_SESSION["errors"][] = "Permission with ID: $id was not updated.";
						$msg = getErrorMessages();
						$response = array(
							"status" => "failure",
							"msg" => $msg
						);
					}
				} else {
					$msg = getErrorMessages();
					$response = array(
						"status" => "failure",
						"msg" => $msg
					);
				}
			}
		} else {
			$msg = getErrorMessages();
			$response = array(
				"status" => "failure",
				"msg" => $msg
			);
		}
		echo json_encode($response);
	} else if ($_POST["flag"] == "getPermissionById") {
		$id = $_POST["id"];
		$result = getPermissionById($id);
		$permissions = [];
		while ($permission = mysqli_fetch_assoc($result)) {
			$permissions[] = array(
				"id" => $permission["id"],
				"name" => $permission["name"],
				"description" => $permission["description"]
			);
		}
		echo json_encode($permissions);
	} else if ($_POST["flag"] == "deletePermissionById") {
		$id = $_POST["id"];
		$result = deletePermissionById($id);
		if ($result) {
			$response = array(
				"msg" => "Permission with ID: $id has been deleted."
			);
		} else {
			$response = array(
				"msg" => "Permission with ID: $id was not deleted."
			);
		}
		echo json_encode($response);
	} else if ($_POST["flag"] == "getAllRolePermissions") {
		$result = getAllRolePermissions();
		$rolePermissions = [];
		while ($row = mysqli_fetch_assoc($result)) {
			$rolePermissions[] = array(
				"id" => $row["rp_id"],
				"role" => $row["r_name"],
				"permission" => $row["p_name"]
			);
		}
		echo json_encode($rolePermissions);
	} else if ($_POST["flag"] == "saveRolePermission" || $_POST["flag"] == "editRolePermission") {
		$id = $_POST["id"];
		$roleId = $_POST["roleId"];
		$permissionId = $_POST["permissionId"];
		$roleIdValidationResult = validateRoleId($roleId);
		$permissionIdValidationResult = validatePermissionId($permissionId);
		if ($roleIdValidationResult && $permissionIdValidationResult) {
			if ($id == 0 && $_POST["flag"] == "saveRolePermission") {
				$rolePermissionDuplicationResult = duplicateRolePermission($roleId, $permissionId);
				if ($rolePermissionDuplicationResult) {
					if (saveRolePermission($roleId, $permissionId)) {
						$response = array(
							"status" => "success",
							"msg" => "Role-Permission with ID: " . lastInsertId() . " has been added."
						);
					} else {
						$_SESSION["errors"][] = "Role-Permission was not added.";
						$msg = getErrorMessages();
						$response = array(
							"status" => "failure",
							"msg" => $msg
						);
					}
				} else {
					$msg = getErrorMessages();
					$response = array(
						"status" => "failure",
						"msg" => $msg
					);
				}
			} else {
				$rolePermissionDuplicationResult = duplicateRolePermissionWithId($id, $roleId, $permissionId);
				if ($rolePermissionDuplicationResult) {
					if (updateRolePermission($id, $roleId, $permissionId)) {
						$response = array(
							"status" => "success",
							"msg" => "Role-Permission with ID: $id has been updated."
						);
					} else {
						$_SESSION["errors"][] = "Role-Permission with ID: $id was not updated.";
						$msg = getErrorMessages();
						$response = array(
							"status" => "failure",
							"msg" => $msg
						);
					}
				} else {
					$msg = getErrorMessages();
					$response = array(
						"status" => "failure",
						"msg" => $msg
					);
				}
			}
		} else {
			$msg = getErrorMessages();
			$response = array(
				"status" => "failure",
				"msg" => $msg
			);
		}
		echo json_encode($response);
	} else if ($_POST["flag"] == "getRolePermissionById") {
		$id = $_POST["id"];
		$result = getRolePermissionById($id);
		$rolePermissions = [];
		while ($rolePermission = mysqli_fetch_assoc($result)) {
			$rolePermissions[] = array(
				"id" => $rolePermission["id"],
				"roleId" => $rolePermission["role_id"],
				"permissionId" => $rolePermission["permission_id"]
			);
		}
		echo json_encode($rolePermissions);
	} else if ($_POST["flag"] == "deleteRolePermissionById") {
		$id = $_POST["id"];
		$result = deleteRolePermissionById($id);
		if ($result) {
			$response = array(
				"msg" => "Role-Permission with ID: $id has been deleted."
			);
		} else {
			$response = array(
				"msg" => "Role-Permission with ID: $id was not deleted."
			);
		}
		echo json_encode($response);
	} else if ($_POST["flag"] == "getAllUserRoles") {
		$result = getAllUserRoles();
		$userRoles = [];
		while ($userRole = mysqli_fetch_assoc($result)) {
			$userRoles[] = array(
				"id" => $userRole["ur_id"],
				"user" => $userRole["u_name"],
				"role" => $userRole["r_name"]
			);
		}
		echo json_encode($userRoles);
	} else if ($_POST["flag"] == "saveUserRole" || $_POST["flag"] == "editUserRole") {
		$id = $_POST["id"];
		$userId = $_POST["userId"];
		$roleId = $_POST["roleId"];
		$userIdValidationResult = validateUserId($userId);
		$roleIdValidationResult = validateRoleId($roleId);
		if ($userIdValidationResult && $roleIdValidationResult) {
			if ($id == 0 && $_POST["flag"] == "saveUserRole") {
				$userRoleDuplicationResult = duplicateUserRole($userId, $roleId);
				if ($userRoleDuplicationResult) {
					if (saveUserRole($userId, $roleId)) {
						$response = array(
							"status" => "success",
							"msg" => "User-Role with ID: " . lastInsertId() . " has been added."
						);
					} else {
						$_SESSION["errors"][] = "User-Role was not added.";
						$msg = getErrorMessages();
						$response = array(
							"status" => "failure",
							"msg" => $msg
						);
					}
				} else {
					$msg = getErrorMessages();
					$response = array(
						"status" => "failure",
						"msg" => $msg
					);
				}
			} else {
				$userRoleDuplicationResult = duplicateUserRoleWithId($id, $userId, $roleId);
				if ($userRoleDuplicationResult) {
					if (updateUserRole($id, $userId, $roleId)) {
						$response = array(
							"status" => "success",
							"msg" => "User-Role with ID: $id has been updated."
						);
					} else {
						$_SESSION["errors"][] = "User-Role with ID: $id was not updated.";
						$msg = getErrorMessages();
						$response = array(
							"status" => "failure",
							"msg" => $msg
						);
					}
				} else {
					$msg = getErrorMessages();
					$response = array(
						"status" => "failure",
						"msg" => $msg
					);
				}
			}
		} else {
			$msg = getErrorMessages();
			$response = array(
				"status" => "failure",
				"msg" => $msg
			);
		}
		echo json_encode($response);
	} else if ($_POST["flag"] == "getUserRoleById") {
		$id = $_POST["id"];
		$result = getUserRoleById($id);
		$userRoles = [];
		while ($row = mysqli_fetch_assoc($result)) {
			$userRoles[] = array(
				"id" => $row["id"],
				"userId" => $row["user_id"],
				"roleId" => $row["role_id"]
			);
		}
		echo json_encode($userRoles);
	} else if ($_POST["flag"] == "deleteUserRoleById") {
		$id = $_POST["id"];
		$result = deleteUserRoleById($id);
		if ($result) {
			$response = array(
				"msg" => "User-Role with ID: $id has been deleted."
			);
		} else {
			$response = array(
				"msg" => "User-Role with ID: $id was not deleted."
			);
		}
		echo json_encode($response);
	}
}