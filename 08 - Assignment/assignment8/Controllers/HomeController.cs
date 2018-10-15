using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;

namespace assignment8.Controllers
{
	public class HomeController : Controller
	{
		public ActionResult Index()
		{
			if (Session["UserID"] == null)
			{
				return Redirect("~/Users/Login");
			}
			ViewBag.UserId = Session["UserId"];
			ViewBag.UserName = Session["UserName"];
			return View();
		}
	}
}
