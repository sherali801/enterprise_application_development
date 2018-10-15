using BusinessAccessLayer;
using DataTransferObjects;
using iText.Kernel.Pdf;
using iText.Layout;
using iText.Layout.Element;
using PdfSharp.Drawing;
using PdfSharp.Drawing.Layout;
using PdfSharp.Pdf;
using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Net;
using System.Net.Http;
using System.Web;
using System.Web.Http;

namespace assignment8.Controllers
{
	public class MetaDataController : ApiController
	{
		[HttpGet]
		public HttpResponseMessage DownloadMetaDataDocument(int ParentFolderId, int UserId)
		{
			HttpResponseMessage result = null;
			var dest = HttpContext.Current.Server.MapPath("~/Uploads/MetaDataDocument.pdf");
			if (File.Exists(dest))
			{
				File.Delete(dest);
			}
			var writer = new PdfWriter(dest);
			var pdf = new iText.Kernel.Pdf.PdfDocument(writer);
			var document = new Document(pdf);

			Queue<FolderDTO> folderDTOsQueue = new Queue<FolderDTO>();
			FolderDTO folderDTO = FolderBAL.GetFolderByFolderIdAndUserId(ParentFolderId, UserId);
			folderDTOsQueue.Enqueue(folderDTO);
			while (folderDTOsQueue.Count > 0)
			{
				folderDTO = folderDTOsQueue.Dequeue();
				document.Add(new Paragraph("Name: " + folderDTO.Name + "\nType: Folder\nSize: None\nParent: " + folderDTO.ParentFolderName));
				List<FilesDTO> fileDTOsList = FilesBAL.GetFilesByParentFolderIdAndUserId(folderDTO.Id, folderDTO.CreatedBy);
				foreach (FilesDTO fileDTO in fileDTOsList)
				{
					document.Add(new Paragraph("Name: " + fileDTO.Name + "\nType: File\nSize: " + fileDTO.FileSizeInKB + " KB\nParent: " + folderDTO.ParentFolderName));
				}
				List<FolderDTO> folderDTOsList = FolderBAL.GetFoldersWithParentFolderNameByParentFolderIdAndUserId(folderDTO.Id, folderDTO.CreatedBy);
				foreach (FolderDTO tempDTO in folderDTOsList)
				{
					folderDTOsQueue.Enqueue(tempDTO);
				}
			}
			document.Close();
			result = Request.CreateResponse(HttpStatusCode.OK);
			result.Content = new StreamContent(new FileStream(dest, FileMode.Open, FileAccess.Read));
			result.Content.Headers.ContentDisposition = new System.Net.Http.Headers.ContentDispositionHeaderValue("attachment");
			result.Content.Headers.ContentDisposition.FileName = "MetaDataDocument.pdf";
			return result;
		}
	}
}
