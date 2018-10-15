using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;

namespace UserManagementSystem.Controllers
{
    public class MainScreenController : Controller
    {
        // GET: MainScreen
        public ActionResult Index()
        {
            return View("MainScreen");
        }
    }
}