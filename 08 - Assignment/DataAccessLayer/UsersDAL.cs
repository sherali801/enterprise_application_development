using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using DataTransferObjects;
using System.Data.SqlClient;

namespace DataAccessLayer
{
	public static class UsersDAL
	{
		public static bool ValidateUser(UsersDTO usersDTO)
		{
			string connectionString = null;
			SqlConnection connection = null;
			SqlCommand sqlCommand = null;
			SqlParameter sqlParameter = null;
			int count = 0;
			bool status = false;
			try
			{
				connectionString = System.Configuration.ConfigurationManager.ConnectionStrings["ConnectionString"].ConnectionString;
				connection = new SqlConnection(connectionString);
				connection.Open();
				string sql = @"SELECT COUNT(*) 
							   FROM dbo.Users 
							   WHERE Login = @Login 
							   AND Password = @Password";
				sqlCommand = new SqlCommand(sql, connection);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "Login";
				sqlParameter.SqlDbType = System.Data.SqlDbType.VarChar;
				sqlParameter.Value = usersDTO.Login;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "Password";
				sqlParameter.SqlDbType = System.Data.SqlDbType.VarChar;
				sqlParameter.Value = usersDTO.Password;
				sqlCommand.Parameters.Add(sqlParameter);

				count = Convert.ToInt32(sqlCommand.ExecuteScalar());

				if (count > 0)
				{
					status = true;
				}
			}
			catch (Exception ex)
			{
				status = false;
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

		public static UsersDTO GetUserByLogin(string Login)
		{
			string connectionString = null;
			SqlConnection connection = null;
			SqlCommand sqlCommand = null;
			SqlParameter sqlParameter = null;
			SqlDataReader sqlDataReader = null;
			UsersDTO usersDTO = null;
			try
			{
				connectionString = System.Configuration.ConfigurationManager.ConnectionStrings["ConnectionString"].ConnectionString;
				connection = new SqlConnection(connectionString);
				connection.Open();
				string sql = @"SELECT * 
							   FROM dbo.Users 
							   WHERE Login = @Login";
				sqlCommand = new SqlCommand(sql, connection);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "Login";
				sqlParameter.SqlDbType = System.Data.SqlDbType.VarChar;
				sqlParameter.Value = Login;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlDataReader = sqlCommand.ExecuteReader();

				while (sqlDataReader.Read())
				{
					usersDTO = new UsersDTO();
					usersDTO.Id = sqlDataReader.GetInt32(sqlDataReader.GetOrdinal("Id"));
					usersDTO.Name = sqlDataReader.GetString(sqlDataReader.GetOrdinal("Name"));
					usersDTO.Login = sqlDataReader.GetString(sqlDataReader.GetOrdinal("Login"));
					usersDTO.Password = sqlDataReader.GetString(sqlDataReader.GetOrdinal("Password"));
					usersDTO.Email = sqlDataReader.GetString(sqlDataReader.GetOrdinal("Email"));
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

			return usersDTO;
		}
	}
}
