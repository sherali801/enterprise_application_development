using DataAccessLayer;
using DataTransferObjects;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace BusinessAccessLayer
{
	public static class FolderBAL
	{
		public static List<FolderDTO> GetFoldersByParentFolderIdAndUserId(int ParentFolderId, int UserId)
		{
			return FolderDAL.GetFoldersByParentFolderIdAndUserId(ParentFolderId, UserId);
		}

		public static List<FolderDTO> GetFoldersWithParentFolderNameByParentFolderIdAndUserId(int ParentFolderId, int UserId)
		{
			return FolderDAL.GetFoldersWithParentFolderNameByParentFolderIdAndUserId(ParentFolderId, UserId);
		}

		public static int CreateNewFolder(FolderDTO folderDTO)
		{
			return FolderDAL.CreateNewFolder(folderDTO);
		}

		public static List<FolderDTO> GetNavigationByFolderIdAndUserId(int FolderId, int UserId)
		{
			List<FolderDTO> path = new List<FolderDTO>();
			FolderDTO folderDTO = null;
			while ((folderDTO = FolderDAL.GetFolderByFolderIdAndUserId(FolderId, UserId)) != null)
			{
				FolderId = folderDTO.ParentFolderId;
				path.Add(folderDTO);
			}
			return path;
		}

		public static bool DeleteFolderByFolderIdAndUserId(int FolderId, int UserId)
		{
			return FolderDAL.DeleteFolderByFolderIdAndUserId(FolderId, UserId);
		}

		public static FolderDTO GetFolderByFolderIdAndUserId(int FolderId, int UserId)
		{
			return FolderDAL.GetFolderByFolderIdAndUserId(FolderId, UserId);
		}
	}
}
