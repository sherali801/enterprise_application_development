using BusinessAccessLayer;
using DataTransferObjects;
using Helpers;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;

namespace UserManagementSystem.Controllers
{
	public class AdminController : Controller
	{
		public ActionResult Login()
		{
			return View();
		}

		[HttpPost]
		public ActionResult Login(string login, string password)
		{
			if (!AdminBAL.ValidateAdmin(login, password))
			{
				List<string> messages = new List<string>();
				messages.Add("Login/Password combination doesn't match.");
				ViewBag.Login = login;
				ViewBag.Password = password;
				ViewBag.Status = false;
				ViewBag.Messages = messages;
				return View();
			}
			Session["Id"] = AdminBAL.GetIdByLogin(login);
			Session["Login"] = login;
			return RedirectToAction("Home");
		}

		public ActionResult Home()
		{
			if (Session["Id"] == null)
			{
				return RedirectToAction("Login");
			}
			List<UserDTO> userDTOs = AdminBAL.GetAllUsers();
			return View(userDTOs);
		}

		public ActionResult Logout()
		{
			if (Session["Id"] != null)
			{
				Session["Id"] = null;
			}
			if (Session["Login"] != null)
			{
				Session["Login"] = null;
			}
			return RedirectToAction("Login");
		}

		public ActionResult EditUser(int id)
		{
			if (Session["Id"] == null)
			{
				return RedirectToAction("Login");
			}
			UserDTO userDTO = UserBAL.GetUserById(id);
			return View(userDTO);
		}

		[HttpPost]
		public ActionResult EditUser(UserDTO userDTO)
		{
			if (Session["Id"] == null)
			{
				return RedirectToAction("Login");
			}

			userDTO.IsCricket = Request["IsCricket"] == "on";
			userDTO.Hockey = Request["Hockey"] == "on";
			userDTO.Chess = Request["Chess"] == "on";

			bool status = false;
			List<string> messages = new List<string>();
			if (UserBAL.DuplicateLoginWithId(userDTO))
			{
				messages.Add("Login already exist.");
			}
			if (InputHelper.Empty(userDTO.Name))
			{
				messages.Add("Name field is empty.");
			}
			if (!InputHelper.Length(userDTO.Name, 1, 50))
			{
				messages.Add("Name at max 50 characters long.");
			}
			if (InputHelper.Empty(userDTO.Login))
			{
				messages.Add("Login field is empty.");
			}
			if (!InputHelper.Length(userDTO.Login, 1, 50))
			{
				messages.Add("Login at max 50 characters long.");
			}
			if (!InputHelper.MatchPattern(userDTO.Password, @"^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!*@#$%^&+=]).*$"))
			{
				messages.Add("Password must be 8-15 characters including letters, numbers, special characters.");
			}
			if (!InputHelper.MatchPattern(userDTO.Email, @"^[\w.%+\-]+@[\w.\-]+\.[A-Za-z]{2,6}$"))
			{
				messages.Add("Invalid email format.");
			}
			if (!InputHelper.Gender(userDTO.Gender))
			{
				messages.Add("Invalid gender.");
			}
			if (!InputHelper.Length(userDTO.Address, 1, 45))
			{
				messages.Add("Address at max 50 characters long.");
			}
			if (!InputHelper.Age(userDTO.Age))
			{
				messages.Add("Age is invalid.");
			}
			if (!InputHelper.MatchPattern(userDTO.NIC, @"^\d{5}-\d{7}-\d{1}$"))
			{
				messages.Add("NIC is invalid.");
			}
			if (messages.Count == 0)
			{
				string uniqueName = "";

				if (Request.Files["Image"] != null)
				{
					var file = Request.Files["Image"];
					if (file.FileName != "")
					{
						string ext = System.IO.Path.GetExtension(file.FileName);
						uniqueName = Guid.NewGuid().ToString() + ext;
						string rootPath = Server.MapPath("~/Images");

						System.IO.File.Delete(System.IO.Path.Combine(rootPath, userDTO.ImageName));

						string fileSavePath = System.IO.Path.Combine(rootPath, uniqueName);
						file.SaveAs(fileSavePath);

						userDTO.ImageName = uniqueName;
					}
				}

				if (UserBAL.UpdateUser(userDTO))
				{
					status = true;
					messages.Add("User with ID: " + userDTO.UserID + " has been updated.");
				}
				else
				{
					messages.Add("User was not updated.");
				}

			}
			
			ViewBag.Status = status;
			ViewBag.Messages = messages;

			return View(userDTO);
		}

	}
}