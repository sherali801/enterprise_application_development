using BusinessAccessLayer;
using DataTransferObjects;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;

namespace assignment8.Controllers
{
	public class UsersController : Controller
	{
		public ActionResult Login()
		{
			UsersDTO usersDTO = new UsersDTO();
			return View(usersDTO);
		}

		[HttpPost]
		[ActionName("Login")]
		public ActionResult Login2(UsersDTO usersDTO)
		{
			bool status = false;
			List<string> messages = new List<string>();
			if (UsersBAL.ValidateUser(usersDTO))
			{
				usersDTO = UsersBAL.GetUserByLogin(usersDTO.Login);
				Session["UserId"] = usersDTO.Id;
				Session["UserName"] = usersDTO.Name;
				return Redirect("~/Home/Index");
			}
			messages.Add("Invalid Login/Password combination.");
			ViewBag.Status = status;
			ViewBag.Messages = messages;
			return View(usersDTO);
		}

		public ActionResult Logout()
		{
			Session["UserId"] = null;
			UsersDTO usersDTO = new UsersDTO();
			return View("Login", usersDTO);
		}
	}
}