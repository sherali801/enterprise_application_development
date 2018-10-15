using Helpers;
using PMS.Entities;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Net.Mail;
using System.Web;
using System.Web.Mvc;
using WebPrac.Security;

namespace WebPrac.Controllers
{
	public class UserController : Controller
	{
		[HttpGet]
		public ActionResult Login()
		{
			return View();
		}

		[HttpPost]
		public ActionResult Login(String login, String password)
		{
			bool status = false;
			List<string> messages = new List<string>();
			if (InputHelper.Empty(login))
			{
				messages.Add("Login field is empty.");
			}
			if (!InputHelper.Length(login, 1, 50))
			{
				messages.Add("Login at max 50 characters.");
			}
			if (InputHelper.Empty(password))
			{
				messages.Add("Password field is empty.");
			}
			if (!InputHelper.Length(password, 1, 50))
			{
				messages.Add("Password at max 50 characters.");
			}
			if (messages.Count == 0)
			{
				var obj = PMS.BAL.UserBO.ValidateUser(login, password);
				if (obj != null)
				{
					Session["user"] = obj;
					if (obj.IsAdmin)
						return Redirect("~/Home/Admin");
					else
						return Redirect("~/Home/NormalUser");
				}
				messages.Add("Login/Password combination doesn't match.");
				ViewBag.Login = login;
			}
			ViewBag.Messages = messages;
			ViewBag.Status = status;
			return View();
		}

		[HttpGet]
		public ActionResult Register()
		{
			UserDTO userDTO = new UserDTO();
			return View(userDTO);
		}

		[HttpPost]
		public ActionResult Save(UserDTO userDTO)
		{
			List<string> messages = new List<string>();
			bool status = false;
			if (InputHelper.Empty(userDTO.Name))
			{
				messages.Add("Name field is empty.");
			}
			if (!InputHelper.Length(userDTO.Name, 1, 50))
			{
				messages.Add("Name at max 50 characters.");
			}
			if (InputHelper.Empty(userDTO.Login))
			{
				messages.Add("Login field is empty.");
			}
			if (!InputHelper.Length(userDTO.Login, 1, 50))
			{
				messages.Add("Login at max 50 characters.");
			}
			if (!InputHelper.Length(userDTO.Password, 1, 50))
			{
				messages.Add("Password at max 50 characters.");
			}
			userDTO.IsActive = true;
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
						string rootPath = Server.MapPath("~/userpics");
						string fileSavePath = System.IO.Path.Combine(rootPath, uniqueName);
						file.SaveAs(fileSavePath);
						userDTO.PictureName = uniqueName;
						int id = PMS.BAL.UserBO.Save(userDTO);
						if (id > 0)
						{
							status = true;
							messages.Add("Registration completed.");
							userDTO = new UserDTO();
						}
						else
						{
							messages.Add("Registeration failed.");
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

			return View("Register", userDTO);
		}

		[HttpGet]
		public ActionResult Logout()
		{
			SessionManager.ClearSession();
			return RedirectToAction("Login");
		}

		public ActionResult Edit()
		{
			if (SessionManager.IsValidUser)
			{
				UserDTO userDTO = PMS.BAL.UserBO.GetUserById(SessionManager.User.UserID);
				return View(userDTO);
			}
			else
			{
				return Redirect("~/User/Login");
			}
		}

		[HttpPost]
		[ActionName("Edit")]
		public ActionResult Edit2(UserDTO userDTO)
		{
			if (SessionManager.IsValidUser)
			{
				bool status = false;
				List<string> messages = new List<string>();
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

				userDTO.IsActive = true;

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
							string rootPath = Server.MapPath("~/userpics");

							if (userDTO.PictureName != null)
							{
								System.IO.File.Delete(System.IO.Path.Combine(rootPath, userDTO.PictureName));
							}

							string fileSavePath = System.IO.Path.Combine(rootPath, uniqueName);
							file.SaveAs(fileSavePath);

							userDTO.PictureName = uniqueName;

						}
					}
				}

				int id = PMS.BAL.UserBO.Save(userDTO);
				if (id > 0)
				{
					status = true;
					messages.Add("Profile has been updated.");
				}
				else
				{
					messages.Add("Profile was not updated.");
				}

				ViewBag.Status = status;
				ViewBag.Messages = messages;
				return View("Edit", userDTO);
			}
			else
			{
				return Redirect("~/User/Login");
			}
		}

		public ActionResult ChangePassword()
		{
			if (SessionManager.IsValidUser)
			{
				return View();
			}
			else
			{
				return Redirect("~/User/Login");
			}
		}

		[HttpPost]
		public ActionResult ChangePassword(string oldPassword, string newPassword, string confirmPassword)
		{
			if (SessionManager.IsValidUser)
			{
				bool status = false;
				List<string> messages = new List<string>();
				UserDTO userDTO = PMS.BAL.UserBO.GetUserById(SessionManager.User.UserID);
				if (userDTO.Password == oldPassword && newPassword == confirmPassword)
				{
					userDTO.Password = newPassword;
					int id = PMS.BAL.UserBO.UpdatePassword(userDTO);
					if (id > 0)
					{
						status = true;
						messages.Add("Password has been updated.");
					}
					else
					{
						messages.Add("Password was not updated.");
					}
				}
				else
				{
					messages.Add("Password doesn't match.");
				}
				ViewBag.Status = status;
				ViewBag.Messages = messages;
				return View();
			}
			else
			{
				return Redirect("~/User/Login");
			}
		}

		public ActionResult ForgotPassword()
		{
			return View();
		}

		[HttpPost]
		public ActionResult ForgotPassword(string login, string email)
		{
			bool status = false;
			List<string> messages = new List<string>();
			if (InputHelper.Empty(login))
			{
				messages.Add("Login field is empty.");
			}
			if (!InputHelper.Length(login, 1, 50))
			{
				messages.Add("Login at max 50 characters.");
			}
			if (InputHelper.Empty(email))
			{
				messages.Add("Email field is empty.");
			}
			if (!InputHelper.MatchPattern(email, @"^[\w.%+\-]+@[\w.\-]+\.[A-Za-z]{2,6}$"))
			{
				messages.Add("Invalid email format.");
			}
			if (messages.Count == 0)
			{
				if (PMS.BAL.UserBO.ValidateLogin(login) != null)
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
						Session["Login"] = login;
						Session["ResetCode"] = resetCode;
						mail.Body = "Reset Code: " + resetCode;
						var sc = new SmtpClient("smtp.gmail.com", 587)
						{
							Credentials = new System.Net.NetworkCredential("ead.csf15", "EAD_csf15m"),
							EnableSsl = true
						};
						sc.Send(mail);
						return RedirectToAction("ResetCode");
					}
					catch (Exception ex)
					{
						messages.Add("Unable to send reset code.");
					}
				}
				else
				{
					messages.Add("Invalid login.");
				}
			}
			ViewBag.Login = login;
			ViewBag.Email = email;
			ViewBag.Status = status;
			ViewBag.Messages = messages;
			return View();
		}

		public ActionResult ResetCode()
		{
			return View();
		}

		[HttpPost]
		public ActionResult ResetCode(string resetCode)
		{
			if (Session["ResetCode"].ToString() == resetCode)
			{
				return RedirectToAction("NewPassword");
			}
			bool status = false;
			List<string> messages = new List<string>();
			messages.Add("Invalid reset code.");
			ViewBag.Status = status;
			ViewBag.Messages = messages;
			return View();
		}

		public ActionResult NewPassword()
		{
			return View();
		}

		[HttpPost]
		public ActionResult NewPassword(string newPassword, string confirmPassword)
		{
			bool status = false;
			List<string> messages = new List<string>();
			if (newPassword == confirmPassword)
			{
				UserDTO userDTO = new UserDTO();
				userDTO.Login = Session["Login"].ToString();
				userDTO.Password = newPassword;
				int id = PMS.BAL.UserBO.UpdatePasswordWithLogin(userDTO);
				if (id > 0)
				{
					status = true;
					messages.Add("Password has been updated.");
				}
				else
				{
					messages.Add("Password was not updated.");
				}
			}
			ViewBag.Status = status;
			ViewBag.Messages = messages;
			return View();
		}

		public new ActionResult Profile(int id)
		{
			UserDTO userDTO = PMS.BAL.UserBO.GetUserById(id);
			return View(userDTO);
		}

		[HttpGet]
		public ActionResult Login2()
		{
			return View();
		}

		[HttpPost]
		public JsonResult ValidateUser(String login, String password)
		{

			Object data = null;

			try
			{
				var url = "";
				var flag = false;

				var obj = PMS.BAL.UserBO.ValidateUser(login, password);
				if (obj != null)
				{
					flag = true;
					SessionManager.User = obj;

					if (obj.IsAdmin == true)
						url = Url.Content("~/Home/Admin");
					else
						url = Url.Content("~/Home/NormalUser");
				}

				data = new
				{
					valid = flag,
					urlToRedirect = url
				};
			}
			catch (Exception)
			{
				data = new
				{
					valid = false,
					urlToRedirect = ""
				};
			}

			return Json(data, JsonRequestBehavior.AllowGet);
		}
	}
}