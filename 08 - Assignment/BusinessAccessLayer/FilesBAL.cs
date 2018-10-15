using DataAccessLayer;
using DataTransferObjects;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace BusinessAccessLayer
{
	public static class FilesBAL
	{
		public static List<FilesDTO> GetFilesByParentFolderIdAndUserId(int ParentFolderId, int UserId)
		{
			return FilesDAL.GetFilesByParentFolderIdAndUserId(ParentFolderId, UserId);
		}

		public static int CreateFile(FilesDTO filesDTO)
		{
			return FilesDAL.CreateFile(filesDTO);
		}

		public static bool DeleteFileByFileIdAndUserId(int FileId, int UserId)
		{
			return FilesDAL.DeleteFileByFileIdAndUserId(FileId, UserId);
		}

		public static FilesDTO GetFileByFileIdAndUserId(int FileId, int UserId)
		{
			return FilesDAL.GetFileByFileIdAndUserId(FileId, UserId);
		}
	}
}
