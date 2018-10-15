using BusinessAccessLayer;
using DataTransferObjects;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Net.Http;
using System.Web.Http;

namespace assignment8.Controllers
{
	public class FolderController : ApiController
	{
		[HttpPost]
		public List<FolderDTO> GetFoldersByParentFolderIdAndUserId(FolderDTO folderDTO)
		{
			return FolderBAL.GetFoldersByParentFolderIdAndUserId(folderDTO.ParentFolderId, folderDTO.CreatedBy);
		}

		[HttpPost]
		public int CreateFolder(FolderDTO folderDTO)
		{
			folderDTO.CreatedOn = DateTime.Now;
			folderDTO.IsActive = true;
			return FolderBAL.CreateNewFolder(folderDTO);
		}

		[HttpPost]
		public List<FolderDTO> GetNavigationByFolderIdAndUserId(FolderDTO folderDTO)
		{
			return FolderBAL.GetNavigationByFolderIdAndUserId(folderDTO.Id, folderDTO.CreatedBy);
		}

		[HttpPost]
		public bool DeleteFolderByFolderIdAndUserId(FolderDTO folderDTO)
		{
			return FolderBAL.DeleteFolderByFolderIdAndUserId(folderDTO.Id, folderDTO.CreatedBy);
		}
	}
}
