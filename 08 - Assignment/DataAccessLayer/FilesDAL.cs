using DataTransferObjects;
using System;
using System.Collections.Generic;
using System.Data.SqlClient;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace DataAccessLayer
{
	public class FilesDAL
	{
		public static List<FilesDTO> GetFilesByParentFolderIdAndUserId(int ParentFolderId, int UserId)
		{
			string connectionString = null;
			SqlConnection connection = null;
			SqlParameter sqlParameter = null;
			SqlCommand sqlCommand = null;
			SqlDataReader sqlDataReader = null;
			List<FilesDTO> filesDTOs = new List<FilesDTO>();
			FilesDTO filesDTO = null;
			try
			{
				connectionString = System.Configuration.ConfigurationManager.ConnectionStrings["ConnectionString"].ConnectionString;
				connection = new SqlConnection(connectionString);
				connection.Open();
				string sql = @"SELECT * 
							   FROM dbo.Files
							   WHERE IsActive = 'true'
							   AND ParentFolderId = @ParentFolderId
							   AND CreatedBy = @UserId";
				sqlCommand = new SqlCommand(sql, connection);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "ParentFolderId";
				sqlParameter.SqlDbType = System.Data.SqlDbType.Int;
				sqlParameter.Value = ParentFolderId;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "UserId";
				sqlParameter.SqlDbType = System.Data.SqlDbType.Int;
				sqlParameter.Value = UserId;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlDataReader = sqlCommand.ExecuteReader();

				while (sqlDataReader.Read())
				{
					filesDTO = new FilesDTO();
					filesDTO.Id = sqlDataReader.GetInt32(sqlDataReader.GetOrdinal("Id"));
					filesDTO.Name = sqlDataReader.GetString(sqlDataReader.GetOrdinal("Name"));
					filesDTO.ParentFolderId = sqlDataReader.GetInt32(sqlDataReader.GetOrdinal("ParentFolderId"));
					filesDTO.FileExt = sqlDataReader.GetString(sqlDataReader.GetOrdinal("FileExt"));
					filesDTO.FileSizeInKB = sqlDataReader.GetInt32(sqlDataReader.GetOrdinal("FileSizeInKB"));
					filesDTO.CreatedBy = sqlDataReader.GetInt32(sqlDataReader.GetOrdinal("CreatedBy"));
					filesDTO.UploadedOn = sqlDataReader.GetDateTime(sqlDataReader.GetOrdinal("UploadedOn"));
					filesDTO.IsActive = sqlDataReader.GetBoolean(sqlDataReader.GetOrdinal("IsActive"));

					filesDTOs.Add(filesDTO);
				}
			}
			catch (Exception ex)
			{
				return null;
			}
			finally
			{
				if (sqlDataReader != null)
				{
					sqlDataReader.Close();
				}
				if (sqlCommand != null)
				{
					sqlCommand.Dispose();
				}
				if (connection != null)
				{
					connection.Close();
				}
			}

			return filesDTOs;
		}

		public static int CreateFile(FilesDTO filesDTO)
		{
			string connectionString = null;
			SqlConnection connection = null;
			SqlCommand sqlCommand = null;
			SqlParameter sqlParameter = null;
			int id = 0;
			try
			{
				connectionString = System.Configuration.ConfigurationManager.ConnectionStrings["ConnectionString"].ConnectionString;
				connection = new SqlConnection(connectionString);
				connection.Open();
				string sql = @"INSERT INTO dbo.Files (
							   Name, ParentFolderId, FileExt, FileSizeInKB, CreatedBy, UploadedOn, IsActive
							   ) VALUES (
							   @Name, @ParentFolderId, @FileExt, @FileSizeInKB, @CreatedBy, @UploadedOn, @IsActive
							   ); SELECT SCOPE_IDENTITY()";
				sqlCommand = new SqlCommand(sql, connection);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "Name";
				sqlParameter.SqlDbType = System.Data.SqlDbType.VarChar;
				sqlParameter.Value = filesDTO.Name;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "ParentFolderId";
				sqlParameter.SqlDbType = System.Data.SqlDbType.Int;
				sqlParameter.Value = filesDTO.ParentFolderId;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "FileExt";
				sqlParameter.SqlDbType = System.Data.SqlDbType.VarChar;
				sqlParameter.Value = filesDTO.FileExt;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "FileSizeInKB";
				sqlParameter.SqlDbType = System.Data.SqlDbType.Int;
				sqlParameter.Value = filesDTO.FileSizeInKB;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "CreatedBy";
				sqlParameter.SqlDbType = System.Data.SqlDbType.Int;
				sqlParameter.Value = filesDTO.CreatedBy;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "UploadedOn";
				sqlParameter.SqlDbType = System.Data.SqlDbType.DateTime;
				sqlParameter.Value = filesDTO.UploadedOn;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "IsActive";
				sqlParameter.SqlDbType = System.Data.SqlDbType.Bit;
				sqlParameter.Value = filesDTO.IsActive;
				sqlCommand.Parameters.Add(sqlParameter);

				id = Convert.ToInt32(sqlCommand.ExecuteScalar());
			}
			catch (Exception ex)
			{
				return 0;
			}
			finally
			{
				if (sqlCommand != null)
				{
					sqlCommand.Dispose();
				}
				if (connection != null)
				{
					connection.Close();
				}
			}

			return id;
		}

		public static bool DeleteFileByFileIdAndUserId(int FileId, int UserId)
		{
			bool status = false;
			string connectionString = null;
			SqlConnection connection = null;
			SqlCommand sqlCommand = null;
			SqlParameter sqlParameter = null;
			try
			{
				connectionString = System.Configuration.ConfigurationManager.ConnectionStrings["ConnectionString"].ConnectionString;
				connection = new SqlConnection(connectionString);
				connection.Open();
				string sql = @"UPDATE dbo.Files SET
							   IsActive = 'false'
							   WHERE Id = @FileId
							   AND CreatedBy = @UserId";
				sqlCommand = new SqlCommand(sql, connection);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "FileId";
				sqlParameter.SqlDbType = System.Data.SqlDbType.Int;
				sqlParameter.Value = FileId;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "UserId";
				sqlParameter.SqlDbType = System.Data.SqlDbType.Int;
				sqlParameter.Value = UserId;
				sqlCommand.Parameters.Add(sqlParameter);

				int affectedRows = Convert.ToInt32(sqlCommand.ExecuteNonQuery());

				if (affectedRows == 1)
				{
					status = true;
				}

			}
			catch (Exception ex)
			{
				return status;
			}
			finally
			{
				if (sqlCommand != null)
				{
					sqlCommand.Dispose();
				}
				if (connection != null)
				{
					connection.Close();
				}
			}

			return status;
		}

		public static FilesDTO GetFileByFileIdAndUserId(int FileId, int UserId)
		{
			string connectionString = null;
			SqlConnection connection = null;
			SqlCommand sqlCommand = null;
			SqlParameter sqlParameter = null;
			SqlDataReader sqlDataReader = null;
			FilesDTO filesDTO = null;
			try
			{
				connectionString = System.Configuration.ConfigurationManager.ConnectionStrings["ConnectionString"].ConnectionString;
				connection = new SqlConnection(connectionString);
				connection.Open();
				string sql = @"SELECT * 
							   FROM dbo.Files 
							   WHERE IsActive = 'true'
							   AND Id = @FileId
							   AND CreatedBy = @UserId";
				sqlCommand = new SqlCommand(sql, connection);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "FileId";
				sqlParameter.SqlDbType = System.Data.SqlDbType.Int;
				sqlParameter.Value = FileId;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "UserId";
				sqlParameter.SqlDbType = System.Data.SqlDbType.Int;
				sqlParameter.Value = UserId;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlDataReader = sqlCommand.ExecuteReader();

				while (sqlDataReader.Read())
				{
					filesDTO = new FilesDTO();
					filesDTO.Id = sqlDataReader.GetInt32(sqlDataReader.GetOrdinal("Id"));
					filesDTO.Name = sqlDataReader.GetString(sqlDataReader.GetOrdinal("Name"));
					filesDTO.ParentFolderId = sqlDataReader.GetInt32(sqlDataReader.GetOrdinal("ParentFolderId"));
					filesDTO.FileExt = sqlDataReader.GetString(sqlDataReader.GetOrdinal("FileExt"));
					filesDTO.FileSizeInKB = sqlDataReader.GetInt32(sqlDataReader.GetOrdinal("FileSizeInKB"));
					filesDTO.CreatedBy = sqlDataReader.GetInt32(sqlDataReader.GetOrdinal("CreatedBy"));
					filesDTO.UploadedOn = sqlDataReader.GetDateTime(sqlDataReader.GetOrdinal("UploadedOn"));
					filesDTO.IsActive = sqlDataReader.GetBoolean(sqlDataReader.GetOrdinal("IsActive"));
				}
			}
			catch (Exception ex)
			{
				return null;
			}
			finally
			{
				if (sqlDataReader != null)
				{
					sqlDataReader.Close();
				}
				if (sqlCommand != null)
				{
					sqlCommand.Dispose();
				}
				if (connection != null)
				{
					connection.Close();
				}
			}

			return filesDTO;
		}
	}
}
