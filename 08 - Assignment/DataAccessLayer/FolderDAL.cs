using DataTransferObjects;
using System;
using System.Collections.Generic;
using System.Data.SqlClient;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace DataAccessLayer
{
	public static class FolderDAL
	{
		public static List<FolderDTO> GetFoldersByParentFolderIdAndUserId(int ParentFolderId, int UserId)
		{
			string connectionString = null;
			SqlConnection connection = null;
			SqlParameter sqlParameter = null;
			SqlCommand sqlCommand = null;
			SqlDataReader sqlDataReader = null;
			List<FolderDTO> folderDTOs = new List<FolderDTO>();
			FolderDTO folderDTO = null;
			try
			{
				connectionString = System.Configuration.ConfigurationManager.ConnectionStrings["ConnectionString"].ConnectionString;
				connection = new SqlConnection(connectionString);
				connection.Open();
				string sql = @"SELECT * 
							   FROM dbo.Folder
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
					folderDTO = new FolderDTO();
					folderDTO.Id = sqlDataReader.GetInt32(sqlDataReader.GetOrdinal("Id"));
					folderDTO.Name = sqlDataReader.GetString(sqlDataReader.GetOrdinal("Name"));
					folderDTO.ParentFolderId = sqlDataReader.GetInt32(sqlDataReader.GetOrdinal("ParentFolderId"));
					folderDTO.CreatedBy = sqlDataReader.GetInt32(sqlDataReader.GetOrdinal("CreatedBy"));
					folderDTO.CreatedOn = sqlDataReader.GetDateTime(sqlDataReader.GetOrdinal("CreatedOn"));
					folderDTO.IsActive = sqlDataReader.GetBoolean(sqlDataReader.GetOrdinal("IsActive"));

					folderDTOs.Add(folderDTO);
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

			return folderDTOs;
		}

		public static List<FolderDTO> GetFoldersWithParentFolderNameByParentFolderIdAndUserId(int ParentFolderId, int UserId)
		{
			string connectionString = null;
			SqlConnection connection = null;
			SqlParameter sqlParameter = null;
			SqlCommand sqlCommand = null;
			SqlDataReader sqlDataReader = null;
			List<FolderDTO> folderDTOs = new List<FolderDTO>();
			FolderDTO folderDTO = null;
			try
			{
				connectionString = System.Configuration.ConfigurationManager.ConnectionStrings["ConnectionString"].ConnectionString;
				connection = new SqlConnection(connectionString);
				connection.Open();
				string sql = @"SELECT f1.Id, f1.Name, f1.ParentFolderId, f1.CreatedBy, f1.CreatedOn, f1.IsActive, f2.Name ParentFolderName
							   FROM dbo.Folder f1, dbo.Folder f2
							   WHERE f1.ParentFolderId = f2.Id
							   AND f1.IsActive = 'true'
							   AND f1.ParentFolderId = @ParentFolderId
							   AND f1.CreatedBy = @UserId";
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
					folderDTO = new FolderDTO();
					folderDTO.Id = sqlDataReader.GetInt32(sqlDataReader.GetOrdinal("Id"));
					folderDTO.Name = sqlDataReader.GetString(sqlDataReader.GetOrdinal("Name"));
					folderDTO.ParentFolderId = sqlDataReader.GetInt32(sqlDataReader.GetOrdinal("ParentFolderId"));
					folderDTO.CreatedBy = sqlDataReader.GetInt32(sqlDataReader.GetOrdinal("CreatedBy"));
					folderDTO.CreatedOn = sqlDataReader.GetDateTime(sqlDataReader.GetOrdinal("CreatedOn"));
					folderDTO.IsActive = sqlDataReader.GetBoolean(sqlDataReader.GetOrdinal("IsActive"));
					folderDTO.ParentFolderName = sqlDataReader.GetString(sqlDataReader.GetOrdinal("ParentFolderName"));

					folderDTOs.Add(folderDTO);
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

			return folderDTOs;
		}

		public static int CreateNewFolder(FolderDTO folderDTO)
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
				string sql = @"INSERT INTO dbo.Folder (
							   Name, ParentFolderId, CreatedBy, CreatedOn, IsActive
							   ) VALUES (
							   @Name, @ParentFolderId, @CreatedBy, @CreatedOn, @IsActive
							   ); SELECT SCOPE_IDENTITY()";
				sqlCommand = new SqlCommand(sql, connection);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "Name";
				sqlParameter.SqlDbType = System.Data.SqlDbType.VarChar;
				sqlParameter.Value = folderDTO.Name;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "ParentFolderId";
				sqlParameter.SqlDbType = System.Data.SqlDbType.Int;
				sqlParameter.Value = folderDTO.ParentFolderId;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "CreatedBy";
				sqlParameter.SqlDbType = System.Data.SqlDbType.Int;
				sqlParameter.Value = folderDTO.CreatedBy;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "CreatedOn";
				sqlParameter.SqlDbType = System.Data.SqlDbType.DateTime;
				sqlParameter.Value = folderDTO.CreatedOn;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "IsActive";
				sqlParameter.SqlDbType = System.Data.SqlDbType.Bit;
				sqlParameter.Value = folderDTO.IsActive;
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

		public static FolderDTO GetFolderByFolderIdAndUserId(int FolderId, int UserId)
		{
			string connectionString = null;
			SqlConnection connection = null;
			SqlCommand sqlCommand = null;
			SqlParameter sqlParameter = null;
			SqlDataReader sqlDataReader = null;
			FolderDTO folderDTO = null;
			try
			{
				connectionString = System.Configuration.ConfigurationManager.ConnectionStrings["ConnectionString"].ConnectionString;
				connection = new SqlConnection(connectionString);
				connection.Open();
				string sql = @"SELECT * 
							   FROM dbo.Folder 
							   WHERE IsActive = 'true'
							   AND Id = @FolderId
							   AND CreatedBy = @UserId";
				sqlCommand = new SqlCommand(sql, connection);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "FolderId";
				sqlParameter.SqlDbType = System.Data.SqlDbType.Int;
				sqlParameter.Value = FolderId;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "UserId";
				sqlParameter.SqlDbType = System.Data.SqlDbType.Int;
				sqlParameter.Value = UserId;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlDataReader = sqlCommand.ExecuteReader();

				while (sqlDataReader.Read())
				{
					folderDTO = new FolderDTO();
					folderDTO.Id = sqlDataReader.GetInt32(sqlDataReader.GetOrdinal("Id"));
					folderDTO.Name = sqlDataReader.GetString(sqlDataReader.GetOrdinal("Name"));
					folderDTO.ParentFolderId = sqlDataReader.GetInt32(sqlDataReader.GetOrdinal("ParentFolderId"));
					folderDTO.CreatedBy = sqlDataReader.GetInt32(sqlDataReader.GetOrdinal("CreatedBy"));
					folderDTO.CreatedOn = sqlDataReader.GetDateTime(sqlDataReader.GetOrdinal("CreatedOn"));
					folderDTO.IsActive = sqlDataReader.GetBoolean(sqlDataReader.GetOrdinal("IsActive"));
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

			return folderDTO;
		}

		public static bool DeleteFolderByFolderIdAndUserId(int FolderId, int UserId)
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
				string sql = @"UPDATE dbo.Folder SET
							   IsActive = 'false'
							   WHERE Id = @FolderId
							   AND CreatedBy = @UserId";
				sqlCommand = new SqlCommand(sql, connection);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "FolderId";
				sqlParameter.SqlDbType = System.Data.SqlDbType.Int;
				sqlParameter.Value = FolderId;
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

	}
}
