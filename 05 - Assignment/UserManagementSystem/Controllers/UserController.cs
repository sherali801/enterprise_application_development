using BusinessAccessLayer;
using DataTransferObjects;
using Helpers;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Net.Mail;
using System.Web;
using System.Web.Mvc;

namespace UserManagementSystem.Controllers
{
	public class UserController : Controller
	{
		// GET: User
		public ActionResult NewUser()
		{
			return View();
		}

		[HttpPost]
		public ActionResult CreateUser()
		{
			ViewBag.Name = Request["name"];
			ViewBag.Login = Request["login"];
			ViewBag.Password = Request["password"];
			ViewBag.Email = Request["email"];
			ViewBag.Gender = Request["gender"];
			ViewBag.Address = Request["address"];
			ViewBag.Age = Request["age"];
			ViewBag.NIC = Request["nic"];
			ViewBag.DOB = Request["dob"];
			ViewBag.Cricket = Request["cricket"];
			ViewBag.Hockey = Request["hockey"];
			ViewBag.Chess = Request["chess"];

			UserDTO userDTO = new UserDTO();
			userDTO.Name = Request["name"];
			userDTO.Login = Request["login"];
			userDTO.Password = Request["password"];
			userDTO.Email = Request["email"];
			userDTO.Gender = Convert.ToChar(Request["gender"]);
			userDTO.Address = Request["address"];
			userDTO.Age = Convert.ToInt32(Request["age"]);
			userDTO.NIC = Request["nic"];
			userDTO.DOB = Convert.ToDateTime(Request["dob"]);
			userDTO.IsCricket = (Request["cricket"] == "on") ? true : false;
			userDTO.Hockey = (Request["hockey"] == "on") ? true : false;
			userDTO.Chess = (Request["chess"] == "on") ? true : false;
			userDTO.CreatedOn = DateTime.Now;

			bool status = false;
			List<string> messages = new List<string>();
			if (UserBAL.DuplicateLogin(userDTO))
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

				if (Request.Files["image"] != null)
				{
					var file = Request.Files["image"];
					if (file.FileName != "")
					{
						string ext = System.IO.Path.GetExtension(file.FileName);
						uniqueName = Guid.NewGuid().ToString() + ext;
						string rootPath = Server.MapPath("~/Images");
						string fileSavePath = System.IO.Path.Combine(rootPath, uniqueName);
						file.SaveAs(fileSavePath);

						userDTO.ImageName = uniqueName;

						int id = UserBAL.CreateUser(userDTO);
						if (id > 0)
						{
							Session["Login"] = userDTO.Login;
							return RedirectToAction("Home");
						}
						else
						{
							messages.Add("User was not added.");
						}
					}
				}
				else
				{
					messages.Add("Choose an image.");
				}
			}

			ViewBag.Status = status;
			ViewBag.Messages = messages;

			return View("NewUser");
		}

		public ActionResult EditProfile()
		{
			if (Session["Login"] == null)
			{
				return RedirectToAction("Login");
			}
			string login = Session["Login"].ToString();
			UserDTO userDTO = UserBAL.GetUserByLogin(login);
			ViewBag.Name = userDTO.Name;
			ViewBag.Login = userDTO.Login;
			ViewBag.Password = userDTO.Password;
			ViewBag.Email = userDTO.Email;
			ViewBag.Gender = userDTO.Gender.ToString();
			ViewBag.Address = userDTO.Address;
			ViewBag.Age = userDTO.Age;
			ViewBag.NIC = userDTO.NIC;
			ViewBag.DOB = userDTO.DOB;
			ViewBag.Cricket = userDTO.IsCricket ? "on" : "";
			ViewBag.Hockey = userDTO.Hockey ? "on" : "";
			ViewBag.Chess = userDTO.Chess ? "on" : "";
			ViewBag.ImageName = "~/Images/" + userDTO.ImageName;
			return View();
		}

		[HttpPost]
		[ActionName("EditProfile")]
		public ActionResult EditProfile2()
		{
			ViewBag.Name = Request["name"];
			ViewBag.Login = Request["login"];
			ViewBag.Password = Request["password"];
			ViewBag.Email = Request["email"];
			ViewBag.Gender = Request["gender"];
			ViewBag.Address = Request["address"];
			ViewBag.Age = Request["age"];
			ViewBag.NIC = Request["nic"];
			ViewBag.DOB = Request["dob"];
			ViewBag.Cricket = Request["cricket"];
			ViewBag.Hockey = Request["hockey"];
			ViewBag.Chess = Request["chess"];

			UserDTO userDTO = UserBAL.GetUserByLogin(Session["Login"].ToString());
			userDTO.UserID = Convert.ToInt32(Session["Id"]);
			userDTO.Name = Request["name"];
			userDTO.Login = Request["login"];
			userDTO.Password = Request["password"];
			userDTO.Email = Request["email"];
			userDTO.Gender = Convert.ToChar(Request["gender"]);
			userDTO.Address = Request["address"];
			userDTO.Age = Convert.ToInt32(Request["age"]);
			userDTO.NIC = Request["nic"];
			userDTO.DOB = Convert.ToDateTime(Request["dob"]);
			userDTO.IsCricket = (Request["cricket"] == "on") ? true : false;
			userDTO.Hockey = (Request["hockey"] == "on") ? true : false;
			userDTO.Chess = (Request["chess"] == "on") ? true : false;
			userDTO.CreatedOn = DateTime.Now;

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

				if (Request.Files["image"] != null)
				{
					var file = Request.Files["image"];
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

			ViewBag.ImageName = "~/Images/" + userDTO.ImageName;
			ViewBag.Status = status;
			ViewBag.Messages = messages;

			return View();
		}

		public ActionResult Home()
		{
			if (Session["Login"] == null)
			{
				return RedirectToAction("Login");
			}
			UserDTO userDTO = UserBAL.GetUserByLogin(Session["Login"].ToString());
			ViewBag.Name = userDTO.Name;
			ViewBag.ImageName = "~/Images/" + userDTO.ImageName;
			return View();
		}

		public ActionResult Login()
		{
			return View();
		}

		[HttpPost]
		public ActionResult Login(string login, string password)
		{
			if (!UserBAL.ValidateUser(login, password))
			{
				List<string> messages = new List<string>();
				messages.Add("Login/Password combination doesn't match.");
				ViewBag.Login = login;
				ViewBag.Password = password;
				ViewBag.Status = false;
				ViewBag.Messages = messages;
				return View("Login");
			}
			Session["Id"] = UserBAL.GetIdByLogin(login);
			Session["Login"] = login;
			return RedirectToAction("Home");
		}

		public ActionResult Logout()
		{
			Session["Id"] = null;
			Session["Login"] = null;
			return RedirectToAction("Index", "MainScreen");
		}

		public ActionResult ForgotPassword(string email)
		{
			if (UserBAL.ValidateEmail(email))
			{
				try
				{

					MailMessage mail = new MailMessage();

					MailAddress to = new MailAddress(email);
					mail.To.Add(to);

					MailAddress from = new MailAddress("ead.csf15@gmail.com", "Sher Ali");
					mail.From = from;

					mail.Subject = "Reset Code";
					string resetCode = Guid.NewGuid().ToString();
					Session["ResetCode"] = resetCode;
					Session["Email"] = email;
					mail.Body = "Reset Code: " + resetCode;

					var sc = new SmtpClient("smtp.gmail.com", 587)
					{
						Credentials = new System.Net.NetworkCredential("ead.csf15", "EAD_csf15m"),
						EnableSsl = true
					};

					sc.Send(mail);
				}
				catch (Exception ex)
				{
				}
				return View("ResetCode");
			}
			else
			{
				List<string> messages = new List<string>();
				messages.Add("Invalid email.");
				ViewBag.Status = false;
				ViewBag.Messages = messages;
				return View();
			}
		}

		public ActionResult ResetCode(string resetCode)
		{
			if (resetCode == Session["ResetCode"].ToString())
			{
				Session["ResetCode"] = null;
				Session["Reset"] = true;
				return View("NewPassword");
			}

			ViewBag.ResetCode = resetCode;
			List<string> messages = new List<string>();
			messages.Add("Invalid code.");
			ViewBag.Status = false;
			ViewBag.Messages = messages;
			return View();
		}

		public ActionResult NewPassword(string password)
		{
			if (Convert.ToBoolean(Session["Reset"]) == true)
			{
				UserDTO userDTO = UserBAL.GetUserByEmail(Session["Email"].ToString());

				userDTO.Password = password;

				if (UserBAL.UpdateUser(userDTO))
				{
					Session["Id"] = userDTO.UserID;
					Session["Login"] = userDTO.Login;
					return RedirectToAction("Home");
				}
			}
			Session["Email"] = null;
			Session["ResetCode"] = null;
			Session["Reset"] = null;

			return RedirectToAction("Login");
		}
	}
}