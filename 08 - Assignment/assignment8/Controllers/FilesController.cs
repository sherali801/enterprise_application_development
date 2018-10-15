using BusinessAccessLayer;
using DataTransferObjects;
using Microsoft.WindowsAPICodePack.Shell;
using System;
using System.Collections.Generic;
using System.Drawing;
using System.IO;
using System.Linq;
using System.Net;
using System.Net.Http;
using System.Net.Http.Headers;
using System.Web;
using System.Web.Http;

namespace assignment8.Controllers
{
	public class FilesController : ApiController
	{
		[HttpPost]
		public List<FilesDTO> GetFilesByParentFolderIdAndUserId(FilesDTO fileDTO)
		{
			return FilesBAL.GetFilesByParentFolderIdAndUserId(fileDTO.ParentFolderId, fileDTO.CreatedBy);
		}

		[HttpPost]
		public int UploadFileByParentFolderIdAndUserId()
		{
			int id = 0;
			if (HttpContext.Current.Request.Files.Count > 0)
			{
				try
				{
					string name = "";
					string fileExt = "";
					int fileSizeInKB = 0;
					foreach (var fileName in HttpContext.Current.Request.Files.AllKeys)
					{
						HttpPostedFile file = HttpContext.Current.Request.Files[fileName];
						if (file != null)
						{
							fileSizeInKB = file.ContentLength / 1024;
							if ((fileSizeInKB / 1024) > 8)
							{
								return 0;
							}
							fileExt = Path.GetExtension(file.FileName);
							name = file.FileName;
							string rootPath = HttpContext.Current.Server.MapPath("~/Uploads");
							string fileSavePath = Path.Combine(rootPath, name);
							file.SaveAs(fileSavePath);
						}
					}//end of foreach
					FilesDTO filesDTO = new FilesDTO();
					filesDTO.Name = name;
					filesDTO.ParentFolderId = Convert.ToInt32(HttpContext.Current.Request["ParentFolderId"]);
					filesDTO.FileExt = fileExt;
					filesDTO.FileSizeInKB = fileSizeInKB;
					filesDTO.CreatedBy = Convert.ToInt32(HttpContext.Current.Request["CreatedBy"]);
					filesDTO.UploadedOn = DateTime.Now;
					filesDTO.IsActive = true;
					id = FilesBAL.CreateFile(filesDTO);
				}
				catch (Exception ex)
				{
					return id;
				}
			}//end of if count > 0

			return id;
		}

		[HttpPost]
		public bool DeleteFileByFileIdAndUserId(FilesDTO fileDTO)
		{
			return FilesBAL.DeleteFileByFileIdAndUserId(fileDTO.Id, fileDTO.CreatedBy);
		}

		[HttpGet]
		public HttpResponseMessage DownloadFile([FromUri] FilesDTO fileDTO)
		{
			HttpResponseMessage result = null;
			FilesDTO filesDTO = FilesBAL.GetFileByFileIdAndUserId(fileDTO.Id, fileDTO.CreatedBy);
			if (filesDTO == null)
			{
				result = Request.CreateResponse(HttpStatusCode.BadRequest);
			}
			else
			{
				var localFilePath = HttpContext.Current.Server.MapPath("~/Uploads/" + filesDTO.Name);
				if (!File.Exists(localFilePath))
				{
					result = Request.CreateResponse(HttpStatusCode.Gone);
				}
				else
				{
					result = Request.CreateResponse(HttpStatusCode.OK);
					result.Content = new StreamContent(new FileStream(localFilePath, FileMode.Open, FileAccess.Read));
					result.Content.Headers.ContentDisposition = new System.Net.Http.Headers.ContentDispositionHeaderValue("attachment");
					result.Content.Headers.ContentDisposition.FileName = filesDTO.Name;
				}
			}
			return result;
		}

		[HttpGet]
		public HttpResponseMessage GetThumbnail([FromUri] FilesDTO fileDTO)
		{
			HttpResponseMessage response = null;
			fileDTO = FilesBAL.GetFileByFileIdAndUserId(fileDTO.Id, fileDTO.CreatedBy);
			if (fileDTO == null)
			{
				response = Request.CreateResponse(HttpStatusCode.NotFound);
			}
			else
			{
				string path = HttpContext.Current.Server.MapPath("~/Uploads/" + fileDTO.Name);
				if (!File.Exists(path))
				{
					response = Request.CreateResponse(HttpStatusCode.NotFound);
				}
				ShellFile shellFile = ShellFile.FromFilePath(path);
				Bitmap shellThumb = shellFile.Thumbnail.MediumBitmap;
				byte[] file = ImageToBytes(shellThumb);
				MemoryStream ms = new MemoryStream(file);

				response = Request.CreateResponse(HttpStatusCode.OK);
				response.Content = new ByteArrayContent(file);
				response.Content.Headers.ContentDisposition = new System.Net.Http.Headers.ContentDispositionHeaderValue("attachment");
				response.Content.Headers.ContentDisposition.FileName = fileDTO.Name;
			}
			return response;
		}

		private byte[] ImageToBytes(Image img)
		{
			using (var stream = new MemoryStream())
			{
				img.Save(stream, System.Drawing.Imaging.ImageFormat.Png);
				return stream.ToArray();
			}
		}
	}
}
