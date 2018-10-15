using DataTransferObjects;
using System;
using System.Collections.Generic;
using System.Data.SqlClient;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace DataAccessLayer
{
	public static class AdminDAL
	{
		public static bool ValidateAdmin(string login, string password)
		{
			string connectionString = null;
			SqlConnection connection = null;
			SqlCommand sqlCommand = null;
			SqlParameter sqlParameter = null;
			int count = 0;
			bool status = false;
			try
			{
				connectionString = System.Configuration.ConfigurationManager.ConnectionStrings["connectionString"].ConnectionString;
				connection = new SqlConnection(connectionString);
				connection.Open();
				string sql = @"SELECT COUNT(*) 
							   FROM dbo.Admin 
							   WHERE Login = @Login 
							   AND Password = @Password";
				sqlCommand = new SqlCommand(sql, connection);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "Login";
				sqlParameter.SqlDbType = System.Data.SqlDbType.VarChar;
				sqlParameter.Value = login;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "Password";
				sqlParameter.SqlDbType = System.Data.SqlDbType.VarChar;
				sqlParameter.Value = password;
				sqlCommand.Parameters.Add(sqlParameter);

				count = Convert.ToInt32(sqlCommand.ExecuteScalar());

				if (count > 0)
				{
					status = true;
				}
			}
			catch (Exception ex)
			{

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

		public static int GetIdByLogin(string login)
		{
			string connectionString = null;
			SqlConnection connection = null;
			SqlCommand sqlCommand = null;
			SqlParameter sqlParameter = null;
			int id = 0;
			try
			{
				connectionString = System.Configuration.ConfigurationManager.ConnectionStrings["connectionString"].ConnectionString;
				connection = new SqlConnection(connectionString);
				connection.Open();
				string sql = @"SELECT AdminID 
							   FROM dbo.Admin 
							   WHERE Login = @Login";
				sqlCommand = new SqlCommand(sql, connection);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "Login";
				sqlParameter.SqlDbType = System.Data.SqlDbType.VarChar;
				sqlParameter.Value = login;
				sqlCommand.Parameters.Add(sqlParameter);

				id = Convert.ToInt32(sqlCommand.ExecuteScalar());

			}
			catch (Exception ex)
			{

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

		public static List<UserDTO> GetAllUsers()
		{
			string connectionString = null;
			SqlConnection connection = null;
			SqlCommand sqlCommand = null;
			SqlDataReader sqlDataReader = null;
			List<UserDTO> userDTOs = new List<UserDTO>();
			UserDTO userDTO = null;
			try
			{
				connectionString = System.Configuration.ConfigurationManager.ConnectionStrings["connectionString"].ConnectionString;
				connection = new SqlConnection(connectionString);
				connection.Open();
				string sql = @"SELECT * 
							   FROM dbo.Users";
				sqlCommand = new SqlCommand(sql, connection);

				sqlDataReader = sqlCommand.ExecuteReader();

				while (sqlDataReader.Read())
				{
					userDTO = new UserDTO();
					userDTO.UserID = sqlDataReader.GetInt32(sqlDataReader.GetOrdinal("UserID"));
					userDTO.Name = sqlDataReader.GetString(sqlDataReader.GetOrdinal("Name"));
					userDTO.Login = sqlDataReader.GetString(sqlDataReader.GetOrdinal("Login"));
					userDTO.Password = sqlDataReader.GetString(sqlDataReader.GetOrdinal("Password"));
					userDTO.Email = sqlDataReader.GetString(sqlDataReader.GetOrdinal("Email"));
					userDTO.Gender = Convert.ToChar(sqlDataReader.GetString(sqlDataReader.GetOrdinal("Gender")));
					userDTO.Address = sqlDataReader.GetString(sqlDataReader.GetOrdinal("Address"));
					userDTO.Age = sqlDataReader.GetInt32(sqlDataReader.GetOrdinal("Age"));
					userDTO.NIC = sqlDataReader.GetString(sqlDataReader.GetOrdinal("NIC"));
					userDTO.DOB = sqlDataReader.GetDateTime(sqlDataReader.GetOrdinal("DOB"));
					userDTO.IsCricket = sqlDataReader.GetBoolean(sqlDataReader.GetOrdinal("IsCricket"));
					userDTO.Hockey = sqlDataReader.GetBoolean(sqlDataReader.GetOrdinal("Hockey"));
					userDTO.Chess = sqlDataReader.GetBoolean(sqlDataReader.GetOrdinal("Chess"));
					userDTO.ImageName = sqlDataReader.GetString(sqlDataReader.GetOrdinal("ImageName"));
					userDTO.CreatedOn = sqlDataReader.GetDateTime(sqlDataReader.GetOrdinal("CreatedOn"));

					userDTOs.Add(userDTO);
				}
			}
			catch (Exception ex)
			{

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

			return userDTOs;
		}

	}
}
