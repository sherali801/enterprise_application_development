using PMS.Entities;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using WebPrac.Security;

namespace WebPrac.Controllers
{
	public class ProductController : Controller
	{
		private ActionResult GetUrlToRedirect()
		{
			if (SessionManager.IsValidUser)
			{
				if (SessionManager.User.IsAdmin == false)
				{
					TempData["Message"] = "Unauthorized Access";
					return Redirect("~/Home/NormalUser");
				}
			}
			else
			{
				TempData["Message"] = "Unauthorized Access";
				return Redirect("~/User/Login");
			}
			return null;
		}

		public ActionResult ShowAll()
		{
			if (!SessionManager.IsValidUser)
			{
				return Redirect("~/User/Login");
			}
			var products = PMS.BAL.ProductBO.GetAllProducts(true);
			return View(products);
		}

		public ActionResult New()
		{
			if (!SessionManager.IsValidUser)
			{
				return Redirect("~/User/Login");
			}
			ProductDTO productDTO = new ProductDTO();
			return View(productDTO);
		}

		public ActionResult Edit(int id)
		{
			if (!SessionManager.IsValidUser)
			{
				return Redirect("~/User/Login");
			}
			var prod = PMS.BAL.ProductBO.GetProductById(id);
			if (!SessionManager.User.IsAdmin)
			{
				if (prod.CreatedBy != SessionManager.User.UserID)
				{
					TempData["Message"] = "Unauthorized Access";
					return Redirect("~/Home/NormalUser");
				}
			}
			return View("New", prod);
			
		}

		public ActionResult Edit2(int ProductID)
		{
			if (!SessionManager.IsValidUser)
			{
				return Redirect("~/User/Login");
			}
			var prod = PMS.BAL.ProductBO.GetProductById(ProductID);
			return View("New", prod);
		}

		public ActionResult Delete(int id)
		{
			if (!SessionManager.IsValidUser)
			{
				return Redirect("~/User/Login");
			}
			var prod = PMS.BAL.ProductBO.GetProductById(id);
			if (!SessionManager.User.IsAdmin)
			{
				if (prod.CreatedBy != SessionManager.User.UserID)
				{
					TempData["Message"] = "Unauthorized Access";
					return Redirect("~/Home/NormalUser");
				}
			}
			PMS.BAL.ProductBO.DeleteProduct(id);
			TempData["Message"] = "Product has been deleted!";
			return RedirectToAction("ShowAll");
		}

		[HttpPost]
		public ActionResult Save(ProductDTO dto)
		{
			if (!SessionManager.IsValidUser)
			{
				return Redirect("~/User/Login");
			}
			if (!SessionManager.User.IsAdmin && dto.ProductID > 0)
			{
				var prod = PMS.BAL.ProductBO.GetProductById(dto.ProductID);
				if (prod.CreatedBy != SessionManager.User.UserID)
				{
					TempData["Message"] = "Unauthorized Access";
					return Redirect("~/Home/NormalUser");
				}
			}
			bool status = false;
			List<string> messages = new List<string>();
			var uniqueName = "";
			if (Request.Files["Image"] != null)
			{
				var file = Request.Files["Image"];
				if (file.FileName != "")
				{
					var ext = System.IO.Path.GetExtension(file.FileName);
					
					if (ext.ToLower().Equals(".jpg"))
					{
						//Generate a unique name using Guid
						uniqueName = Guid.NewGuid().ToString() + ext;

						//Get physical path of our folder where we want to save images
						var rootPath = Server.MapPath("~/UploadedFiles");

						var fileSavePath = System.IO.Path.Combine(rootPath, uniqueName);

						// Save the uploaded file to "UploadedFiles" folder
						file.SaveAs(fileSavePath);

						dto.PictureName = uniqueName;
					}
					else
					{
						messages.Add("Only JPG format is allowed.");
					}
				}
			}

			if (messages.Count > 0)
			{
				ViewBag.Status = status;
				ViewBag.Messages = messages;
				return ViewBag("New");
			}

			if (dto.ProductID > 0)
			{
				dto.ModifiedOn = DateTime.Now;
				dto.ModifiedBy = SessionManager.User.UserID;
			}
			else
			{
				dto.CreatedOn = DateTime.Now;
				dto.CreatedBy = SessionManager.User.UserID;
			}
			PMS.BAL.ProductBO.Save(dto);
			TempData["Message"] = "New Product added.";
			return RedirectToAction("ShowAll");
		}
	}
}