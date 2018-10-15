$("document").ready(function () {
    if ($("#countries").length != 0) {
        $("#countries").change(function () {
            var countryId = $("#countries").val();
            var cityId = $("#cities").val();
            populateCities(countryId, cityId);
        });
        populateCountries(0);
    }
    if ($("#cities").length != 0) {
        populateCities(0, 0);
    }
    if ($("#usersTable").length != 0) {
        populateUsersTable();
    }
    $("#saveUser").click(function () {
        var id = $("#id").val();
        var login = $("#login").val();
        var password = $("#password").val();
        var name = $("#name").val();
        var email = $("#email").val();
        var countryId = $("#countries").val();
        var cityId = $("#cities").val();
        var isAdmin = 0;
        if ($("#isAdminYes").is(":checked")) {
            isAdmin = 1;
        }
        var flag = "";
        if (id == 0) {
            flag = "saveUser";
        } else {
            flag = "editUser";
        }
        var settings = {
            type: "POST",
            dataType: "json",
            url: "src/api.php",
            data: {
                flag: flag,
                id: id,
                login: login,
                password: password,
                name: name,
                email: email,
                countryId: countryId,
                cityId: cityId,
                isAdmin: isAdmin
            },
            success: function (response) {
                if (response.status == "success") {
                    alert(response.msg);
                    populateUsersTable();
                    clearUser();
                } else {
                    alert(response.msg);
                }
            },
            error: function (response) {
                console.log(response);
            }
        };
        $.ajax(settings);
        return false;
    });
    $("#clearUser").click(clearUser);
    if ($("#rolesTable").length != 0) {
        populateRolesTable();
    }
    $("#saveRole").click(function () {
        var id = $("#id").val();
        var name = $("#name").val();
        var description = $("#description").val();
        var flag = "";
        if (id == 0) {
            flag = "saveRole";
        } else {
            flag = "editRole";
        }
        var settings = {
            type: "POST",
            dataType: "json",
            url: "src/api.php",
            data: {
                flag: flag,
                id: id,
                name: name,
                description: description
            },
            success: function (response) {
                if (response.status == "success") {
                    alert(response.msg);
                    populateRolesTable();
                    clearRole();
                } else {
                    alert(response.msg);
                }
            },
            error: function (response) {
                console.log(response);
            }
        };
        $.ajax(settings);
        return false;
    });
    $("#clearRole").click(clearRole);
    if ($("#permissionsTable").length != 0) {
        populatePermissionsTable();
    }
    $("#savePermission").click(function () {
        var id = $("#id").val();
        var name = $("#name").val();
        var description = $("#description").val();
        var flag = "";
        if (id == 0) {
            flag = "savePermission";
        } else {
            flag = "editPermission";
        }
        var settings = {
            type: "POST",
            dataType: "json",
            url: "src/api.php",
            data: {
                flag: flag,
                id: id,
                name: name,
                description: description
            },
            success: function (response) {
                if (response.status == "success") {
                    alert(response.msg);
                    populatePermissionsTable();
                    clearPermission();
                } else {
                    alert(response.msg);
                }
            },
            error: function (response) {
                console.log(response);
            }
        };
        $.ajax(settings);
        return false;
    });
    $("#clearPermission").click(clearPermission);
    if ($("#roles").length != 0) {
        populateRoles(0);
    }
    if ($("#permissions").length != 0) {
        populatePermissions(0);
    }
    if ($("#rolePermissionsTable").length != 0) {
        populateRolePermissionsTable();
    }
    $("#saveRolePermission").click(function () {
        var id = $("#id").val();
        var roleId = $("#roles").val();
        var permissionId = $("#permissions").val();
        var flag = "";
        if (id == 0) {
            flag = "saveRolePermission";
        } else {
            flag = "editRolePermission";
        }
        var settings = {
            type: "POST",
            dataType: "json",
            url: "src/api.php",
            data: {
                flag: flag,
                id: id,
                roleId: roleId,
                permissionId: permissionId
            },
            success: function (response) {
                if (response.status == "success") {
                    alert(response.msg);
                    populateRolePermissionsTable();
                    clearRolePermission();
                } else {
                    alert(response.msg);
                }
            },
            error: function (response) {
                console.log(response);
            }
        };
        $.ajax(settings);
        return false;
    });
    $("#clearRolePermission").click(clearRolePermission);
    if ($("#users").length != 0) {
        populateUsers(0);
    }
    if ($("#userRolesTable").length != 0) {
        populateUserRolesTable();
    }
    $("#saveUserRole").click(function () {
        var id = $("#id").val();
        var userId = $("#users").val();
        var roleId = $("#roles").val();
        var flag = "";
        if (id == 0) {
            flag = "saveUserRole";
        } else {
            flag = "editUserRole";
        }
        var settings = {
            type: "POST",
            dataType: "json",
            url: "src/api.php",
            data: {
                flag: flag,
                id: id,
                userId: userId,
                roleId: roleId
            },
            success: function (response) {
                if (response.status == "success") {
                    alert(response.msg);
                    populateUserRolesTable();
                    clearUserRole();
                } else {
                    alert(response.msg);
                }
            },
            error: function (response) {
                console.log(response);
            }
        };
        $.ajax(settings);
        return false;
    });
    $("#clearUserRole").click(clearUserRole);
});

function populateCountries(countryId) {
    var settings = {
        type: "POST",
        dataType: "json",
        url: "src/api.php",
        data: {
            flag: "getAllCountries"
        },
        success: function (response) {
            var countries = $("#countries").empty();
            var option = $("<option>");
            option.attr("value", 0);
            option.text("--Select--");
            countries.append(option);
            $(response).each(function () {
                option = $("<option>");
                option.attr("value", this.id);
                option.text(this.name);
                if (this.id == countryId) {
                    option.attr("selected", true);
                }
                countries.append(option);
            });
        },
        error: function (response) {
            console.log(response);
        }
    };
    $.ajax(settings);
}

function populateCities(countryId, cityId) {
    var settings = {
        type: "POST",
        dataType: "json",
        url: "src/api.php",
        data: {
            flag: "getAllCitiesByCountryId",
            countryId: countryId
        },
        success: function (response) {
            var cities = $("#cities").empty();
            var option = $("<option>");
            option.attr("value", 0);
            option.text("--Select--");
            cities.append(option);
            $(response).each(function () {
                option = $("<option>");
                option.attr("value", this.id);
                option.text(this.name);
                if (this.id == cityId) {
                    option.attr("selected", true);
                }
                cities.append(option);
            });
        },
        error: function (response) {
            console.log(response);
        }
    };
    $.ajax(settings);
}

function populateUsersTable() {
    var settings = {
        type: "POST",
        dataType: "json",
        url: "src/api.php",
        data: {
            flag: "getAllUsers"
        },
        success: function (response) {
            var usersTable = $("#usersTable").empty();
            var tr = $("<tr>" +
                "<th>ID</th>" +
                "<th>Name</th>" +
                "<th>Email</th>" +
                "<th>Edit</th>" +
                "<th>Delete</th>" +
                "</tr>");
            usersTable.append(tr);
            $(response).each(function () {
                tr = $("<tr>" +
                    "<td class='align-middle'>" + this.id + "</td>" +
                    "<td class='align-middle'>" + this.name + "</td>" +
                    "<td class='align-middle'>" + this.email + "</td>" +
                    "<td class='align-middle'><button class='btn btn-primary' id='" + this.id + "' onclick='editUser(id);'>Edit</button></td>" +
                    "<td class='align-middle'><button class='btn btn-danger' id='" + this.id + "' onclick='deleteUser(id);'>Delete</button></td>" +
                    "</tr>");
                usersTable.append(tr);
            });
        },
        error: function (response) {
            console.log(response);
        }
    };
    $.ajax(settings);
}

function editUser(id) {
    var settings = {
        type: "POST",
        dataType: "json",
        url: "src/api.php",
        data: {
            flag: "getUserById",
            id: id
        },
        success: function (response) {
            $(response).each(function () {
                $("#id").val(this.id);
                $("#login").val(this.login);
                $("#password").val(this.password);
                $("#name").val(this.name);
                $("#email").val(this.email);
                populateCountries(this.countryId);
                populateCities(this.countryId, this.cityId);
                if (this.isAdmin == 1) {
                    $("#isAdminYes").prop("checked", true);
                } else {
                    $("#isAdminNo").prop("checked", true);
                }
            });
        },
        error: function (response) {
            console.log(response);
        }
    };
    $.ajax(settings);
}

function deleteUser(id) {
    var settings = {
        type: "POST",
        dataType: "json",
        url: "src/api.php",
        data: {
            flag: "deleteUserById",
            id: id
        },
        success: function (response) {
            alert(response.msg);
            populateUsersTable();
        },
        error: function (response) {
            console.log(response);
        }
    };
    $.ajax(settings);
}

function clearUser() {
    $("#id").val("0");
    $("#login").val("");
    $("#password").val("");
    $("#name").val("");
    $("#email").val("");
    populateCountries(0);
    populateCities(0, 0);
    $("#isAdminYes").prop("checked", false);
    $("#isAdminNo").prop("checked", true);
}

function populateRolesTable() {
    var settings = {
        type: "POST",
        dataType: "json",
        url: "src/api.php",
        data: {
            flag: "getAllRoles"
        },
        success: function (response) {
            var rolesTable = $("#rolesTable").empty();
            var tr = $("<tr>" +
                "<th>ID</th>" +
                "<th>Name</th>" +
                "<th>Description</th>" +
                "<th>Edit</th>" +
                "<th>Delete</th>" +
                "</tr>");
            rolesTable.append(tr);
            $(response).each(function () {
                tr = $("<tr>" +
                    "<td class='align-middle'>" + this.id + "</td>" +
                    "<td class='align-middle'>" + this.name + "</td>" +
                    "<td class='align-middle'>" + this.description + "</td>" +
                    "<td class='align-middle'><button class='btn btn-primary' id='" + this.id + "' onclick='editRole(id);'>Edit</button></td>" +
                    "<td class='align-middle'><button class='btn btn-danger' id='" + this.id + "' onclick='deleteRole(id);'>Delete</button></td>" +
                    "</tr>");
                rolesTable.append(tr);
            });
        },
        error: function (response) {
            console.log(response);
        }
    };
    $.ajax(settings);
}

function editRole(id) {
    var settings = {
        type: "POST",
        dataType: "json",
        url: "src/api.php",
        data: {
            flag: "getRoleById",
            id: id
        },
        success: function (response) {
            $(response).each(function () {
                $("#id").val(this.id);
                $("#name").val(this.name);
                $("#description").val(this.description);
            });
        },
        error: function (response) {
            console.log(response);
        }
    };
    $.ajax(settings);
}

function deleteRole(id) {
    var settings = {
        type: "POST",
        dataType: "json",
        url: "src/api.php",
        data: {
            flag: "deleteRoleById",
            id: id
        },
        success: function (response) {
            alert(response.msg);
            populateRolesTable();
        },
        error: function (response) {
            console.log(response);
        }
    };
    $.ajax(settings);
}

function clearRole() {
    $("#id").val("0");
    $("#name").val("");
    $("#description").val("");
}

function populatePermissionsTable() {
    var settings = {
        type: "POST",
        dataType: "json",
        url: "src/api.php",
        data: {
            flag: "getAllPermissions"
        },
        success: function (response) {
            var permissionsTable = $("#permissionsTable").empty();
            var tr = $("<tr>" +
                "<th>ID</th>" +
                "<th>Name</th>" +
                "<th>Description</th>" +
                "<th>Edit</th>" +
                "<th>Delete</th>" +
                "</tr>");
            permissionsTable.append(tr);
            $(response).each(function () {
                tr = $("<tr>" +
                    "<td class='align-middle'>" + this.id + "</td>" +
                    "<td class='align-middle'>" + this.name + "</td>" +
                    "<td class='align-middle'>" + this.description + "</td>" +
                    "<td class='align-middle'><button class='btn btn-primary' id='" + this.id + "' onclick='editPermission(id);'>Edit</button></td>" +
                    "<td class='align-middle'><button class='btn btn-danger' id='" + this.id + "' onclick='deletePermission(id);'>Delete</button></td>" +
                    "</tr>");
                permissionsTable.append(tr);
            });
        },
        error: function (response) {
            console.log(response);
        }
    };
    $.ajax(settings);
}

function editPermission(id) {
    var settings = {
        type: "POST",
        dataType: "json",
        url: "src/api.php",
        data: {
            flag: "getPermissionById",
            id: id
        },
        success: function (response) {
            $(response).each(function () {
                $("#id").val(this.id);
                $("#name").val(this.name);
                $("#description").val(this.description);
            });
        },
        error: function (response) {
            console.log(response);
        }
    };
    $.ajax(settings);
}

function deletePermission(id) {
    var settings = {
        type: "POST",
        dataType: "json",
        url: "src/api.php",
        data: {
            flag: "deletePermissionById",
            id: id
        },
        success: function (response) {
            alert(response.msg);
            populatePermissionsTable();
        },
        error: function (response) {
            console.log(response);
        }
    };
    $.ajax(settings);
}

function clearPermission() {
    $("#id").val("0");
    $("#name").val("");
    $("#description").val("");
}

function populateRoles(roleId) {
    var settings = {
        type: "POST",
        dataType: "json",
        url: "src/api.php",
        data: {
            flag: "getAllRoles"
        },
        success: function (response) {
            var roles = $("#roles").empty();
            var option = $("<option>");
            option.attr("value", 0);
            option.text("--Select--");
            roles.append(option);
            $(response).each(function () {
                option = $("<option>");
                option.attr("value", this.id);
                option.text(this.name);
                if (this.id == roleId) {
                    option.attr("selected", true);
                }
                roles.append(option);
            });
        },
        error: function (response) {
            console.log(response);
        }
    };
    $.ajax(settings);
}

function populatePermissions(permissionId) {
    var settings = {
        type: "POST",
        dataType: "json",
        url: "src/api.php",
        data: {
            flag: "getAllPermissions"
        },
        success: function (response) {
            var permissions = $("#permissions").empty();
            var option = $("<option>");
            option.attr("value", 0);
            option.text("--Select--");
            permissions.append(option);
            $(response).each(function () {
                option = $("<option>");
                option.attr("value", this.id);
                option.text(this.name);
                if (this.id == permissionId) {
                    option.attr("selected", true);
                }
                permissions.append(option);
            });
        },
        error: function (response) {
            console.log(response);
        }
    };
    $.ajax(settings);
}

function populateRolePermissionsTable() {
    var settings = {
        type: "POST",
        dataType: "json",
        url: "src/api.php",
        data: {
            flag: "getAllRolePermissions"
        },
        success: function (response) {
            var rolePermissionsTable = $("#rolePermissionsTable").empty();
            var tr = $("<tr>" +
                "<th>ID</th>" +
                "<th>Role</th>" +
                "<th>Permission</th>" +
                "<th>Edit</th>" +
                "<th>Delete</th>" +
                "</tr>");
            rolePermissionsTable.append(tr);
            $(response).each(function () {
                tr = $("<tr>" +
                    "<td class='align-middle'>" + this.id + "</td>" +
                    "<td class='align-middle'>" + this.role + "</td>" +
                    "<td class='align-middle'>" + this.permission + "</td>" +
                    "<td class='align-middle'><button class='btn btn-primary' id='" + this.id + "' onclick='editRolePermission(id);'>Edit</button></td>" +
                    "<td class='align-middle'><button class='btn btn-danger' id='" + this.id + "' onclick='deleteRolePermission(id);'>Delete</button></td>" +
                    "</tr>");
                rolePermissionsTable.append(tr);
            });
        },
        error: function (response) {
            console.log(response);
        }
    };
    $.ajax(settings);
}

function editRolePermission(id) {
    var settings = {
        type: "POST",
        dataType: "json",
        url: "src/api.php",
        data: {
            flag: "getRolePermissionById",
            id: id
        },
        success: function (response) {
            $(response).each(function () {
                $("#id").val(this.id);
                populateRoles(this.roleId);
                populatePermissions(this.permissionId);
            });
        },
        error: function (response) {
            console.log(response);
        }
    };
    $.ajax(settings);
}

function deleteRolePermission(id) {
    var settings = {
        type: "POST",
        dataType: "json",
        url: "src/api.php",
        data: {
            flag: "deleteRolePermissionById",
            id: id
        },
        success: function (response) {
            alert(response.msg);
            populateRolePermissionsTable();
        },
        error: function (response) {
            console.log(response);
        }
    };
    $.ajax(settings);
}

function clearRolePermission() {
    $("#id").val("0");
    populateRoles(0);
    populatePermissions(0);
}

function populateUsers(userId) {
    var settings = {
        type: "POST",
        dataType: "json",
        url: "src/api.php",
        data: {
            flag: "getAllUsers"
        },
        success: function (response) {
            var users = $("#users").empty();
            var option = $("<option>");
            option.attr("value", 0);
            option.text("--Select--");
            users.append(option);
            $(response).each(function () {
                option = $("<option>");
                option.attr("value", this.id);
                option.text(this.login);
                if (this.id == userId) {
                    option.attr("selected", true);
                }
                users.append(option);
            });
        },
        error: function (response) {
            console.log(response);
        }
    };
    $.ajax(settings);
}

function populateUserRolesTable() {
    var settings = {
        type: "POST",
        dataType: "json",
        url: "src/api.php",
        data: {
            flag: "getAllUserRoles"
        },
        success: function (response) {
            var userRolesTable = $("#userRolesTable").empty();
            var tr = $("<tr>" +
                "<th>ID</th>" +
                "<th>User</th>" +
                "<th>Role</th>" +
                "<th>Edit</th>" +
                "<th>Delete</th>" +
                "</tr>");
            userRolesTable.append(tr);
            $(response).each(function () {
                tr = $("<tr>" +
                    "<td class='align-middle'>" + this.id + "</td>" +
                    "<td class='align-middle'>" + this.user + "</td>" +
                    "<td class='align-middle'>" + this.role + "</td>" +
                    "<td class='align-middle'><button class='btn btn-primary' id='" + this.id + "' onclick='editUserRole(id);'>Edit</button></td>" +
                    "<td class='align-middle'><button class='btn btn-danger' id='" + this.id + "' onclick='deleteUserRole(id);'>Delete</button></td>" +
                    "</tr>");
                userRolesTable.append(tr);
            });
        },
        error: function (response) {
            console.log(response);
        }
    };
    $.ajax(settings);
}

function editUserRole(id) {
    var settings = {
        type: "POST",
        dataType: "json",
        url: "src/api.php",
        data: {
            flag: "getUserRoleById",
            id: id
        },
        success: function (response) {
            $(response).each(function () {
                $("#id").val(this.id);
                populateUsers(this.userId);
                populateRoles(this.roleId);
            });
        },
        error: function (response) {
            console.log(response);
        }
    };
    $.ajax(settings);
}

function deleteUserRole(id) {
    var settings = {
        type: "POST",
        dataType: "json",
        url: "src/api.php",
        data: {
            flag: "deleteUserRoleById",
            id: id
        },
        success: function (response) {
            alert(response.msg);
            populateUserRolesTable();
        },
        error: function (response) {
            console.log(response);
        }
    };
    $.ajax(settings);
}

function clearUserRole() {
    $("#id").val("0");
    populateUsers(0);
    populateRoles(0);
}