using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Data.SqlClient;
using DataTransferObjects;

namespace DataAccessLayer
{
	public static class UserDAL
	{
		public static bool DuplicateLogin(UserDTO userDTO)
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
							   FROM dbo.Users 
							   WHERE Login = @Login";
				sqlCommand = new SqlCommand(sql, connection);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "Login";
				sqlParameter.SqlDbType = System.Data.SqlDbType.VarChar;
				sqlParameter.Value = userDTO.Login;
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

		public static bool DuplicateLoginWithId(UserDTO userDTO)
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
							   FROM dbo.Users 
							   WHERE Login = @Login 
							   AND UserID != @UserID";
				sqlCommand = new SqlCommand(sql, connection);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "Login";
				sqlParameter.SqlDbType = System.Data.SqlDbType.VarChar;
				sqlParameter.Value = userDTO.Login;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "UserID";
				sqlParameter.SqlDbType = System.Data.SqlDbType.Int;
				sqlParameter.Value = userDTO.UserID;
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

		public static int CreateUser(UserDTO userDTO)
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
				string sql = @"INSERT INTO dbo.Users (
							   Name, Login, Password, Email, Gender, Address, Age, NIC, DOB, IsCricket, Hockey, Chess, ImageName, CreatedOn
							   ) VALUES (
							   @Name, @Login, @Password, @Email, @Gender, @Address, @Age, @NIC, @DOB, @IsCricket, @Hockey, @Chess, @ImageName, @CreatedOn
							   ); SELECT SCOPE_IDENTITY()";
				sqlCommand = new SqlCommand(sql, connection);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "Name";
				sqlParameter.SqlDbType = System.Data.SqlDbType.VarChar;
				sqlParameter.Value = userDTO.Name;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "Login";
				sqlParameter.SqlDbType = System.Data.SqlDbType.VarChar;
				sqlParameter.Value = userDTO.Login;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "Password";
				sqlParameter.SqlDbType = System.Data.SqlDbType.VarChar;
				sqlParameter.Value = userDTO.Password;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "Email";
				sqlParameter.SqlDbType = System.Data.SqlDbType.VarChar;
				sqlParameter.Value = userDTO.Email;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "Gender";
				sqlParameter.SqlDbType = System.Data.SqlDbType.Char;
				sqlParameter.Value = userDTO.Gender;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "Address";
				sqlParameter.SqlDbType = System.Data.SqlDbType.VarChar;
				sqlParameter.Value = userDTO.Address;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "Age";
				sqlParameter.SqlDbType = System.Data.SqlDbType.Int;
				sqlParameter.Value = userDTO.Age;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "NIC";
				sqlParameter.SqlDbType = System.Data.SqlDbType.VarChar;
				sqlParameter.Value = userDTO.NIC;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "DOB";
				sqlParameter.SqlDbType = System.Data.SqlDbType.Date;
				sqlParameter.Value = userDTO.DOB;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "IsCricket";
				sqlParameter.SqlDbType = System.Data.SqlDbType.Bit;
				sqlParameter.Value = userDTO.IsCricket;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "Hockey";
				sqlParameter.SqlDbType = System.Data.SqlDbType.Bit;
				sqlParameter.Value = userDTO.Hockey;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "Chess";
				sqlParameter.SqlDbType = System.Data.SqlDbType.Bit;
				sqlParameter.Value = userDTO.Chess;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "ImageName";
				sqlParameter.SqlDbType = System.Data.SqlDbType.VarChar;
				sqlParameter.Value = userDTO.ImageName;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "CreatedOn";
				sqlParameter.SqlDbType = System.Data.SqlDbType.DateTime;
				sqlParameter.Value = userDTO.CreatedOn;
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

		public static UserDTO GetUserByLogin(string login)
		{
			string connectionString = null;
			SqlConnection connection = null;
			SqlCommand sqlCommand = null;
			SqlParameter sqlParameter = null;
			SqlDataReader sqlDataReader = null;
			UserDTO userDTO = null;
			try
			{
				connectionString = System.Configuration.ConfigurationManager.ConnectionStrings["connectionString"].ConnectionString;
				connection = new SqlConnection(connectionString);
				connection.Open();
				string sql = @"SELECT * 
							   FROM dbo.Users 
							   WHERE Login = @Login";
				sqlCommand = new SqlCommand(sql, connection);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "Login";
				sqlParameter.SqlDbType = System.Data.SqlDbType.VarChar;
				sqlParameter.Value = login;
				sqlCommand.Parameters.Add(sqlParameter);

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

			return userDTO;
		}

		public static bool ValidateUser(string login, string password)
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
							   FROM dbo.Users 
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
			bool status = false;
			try
			{
				connectionString = System.Configuration.ConfigurationManager.ConnectionStrings["connectionString"].ConnectionString;
				connection = new SqlConnection(connectionString);
				connection.Open();
				string sql = @"SELECT UserID 
							   FROM dbo.Users 
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

		public static bool UpdateUser(UserDTO userDTO)
		{
			string connectionString = null;
			SqlConnection connection = null;
			SqlCommand sqlCommand = null;
			SqlParameter sqlParameter = null;
			int id = 0;
			bool status = false;
			try
			{
				connectionString = System.Configuration.ConfigurationManager.ConnectionStrings["connectionString"].ConnectionString;
				connection = new SqlConnection(connectionString);
				connection.Open();
				string sql = @"UPDATE dbo.Users SET
							   Name = @Name, 
							   Login = @Login, 
							   Password = @Password, 
							   Email = @Email, 
							   Gender = @Gender, 
							   Address = @Address, 
							   Age = @Age, 
							   NIC = @NIC, 
							   DOB = @DOB, 
							   IsCricket = @IsCricket, 
							   Hockey = @Hockey, 
							   Chess = @Chess, 
							   ImageName = @ImageName
							   WHERE UserID = @UserID";
				sqlCommand = new SqlCommand(sql, connection);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "Name";
				sqlParameter.SqlDbType = System.Data.SqlDbType.VarChar;
				sqlParameter.Value = userDTO.Name;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "Login";
				sqlParameter.SqlDbType = System.Data.SqlDbType.VarChar;
				sqlParameter.Value = userDTO.Login;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "Password";
				sqlParameter.SqlDbType = System.Data.SqlDbType.VarChar;
				sqlParameter.Value = userDTO.Password;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "Email";
				sqlParameter.SqlDbType = System.Data.SqlDbType.VarChar;
				sqlParameter.Value = userDTO.Email;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "Gender";
				sqlParameter.SqlDbType = System.Data.SqlDbType.Char;
				sqlParameter.Value = userDTO.Gender;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "Address";
				sqlParameter.SqlDbType = System.Data.SqlDbType.VarChar;
				sqlParameter.Value = userDTO.Address;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "Age";
				sqlParameter.SqlDbType = System.Data.SqlDbType.Int;
				sqlParameter.Value = userDTO.Age;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "NIC";
				sqlParameter.SqlDbType = System.Data.SqlDbType.VarChar;
				sqlParameter.Value = userDTO.NIC;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "DOB";
				sqlParameter.SqlDbType = System.Data.SqlDbType.Date;
				sqlParameter.Value = userDTO.DOB;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "IsCricket";
				sqlParameter.SqlDbType = System.Data.SqlDbType.Bit;
				sqlParameter.Value = userDTO.IsCricket;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "Hockey";
				sqlParameter.SqlDbType = System.Data.SqlDbType.Bit;
				sqlParameter.Value = userDTO.Hockey;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "Chess";
				sqlParameter.SqlDbType = System.Data.SqlDbType.Bit;
				sqlParameter.Value = userDTO.Chess;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "ImageName";
				sqlParameter.SqlDbType = System.Data.SqlDbType.VarChar;
				sqlParameter.Value = userDTO.ImageName;
				sqlCommand.Parameters.Add(sqlParameter);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "UserID";
				sqlParameter.SqlDbType = System.Data.SqlDbType.Int;
				sqlParameter.Value = userDTO.UserID;
				sqlCommand.Parameters.Add(sqlParameter);

				id = Convert.ToInt32(sqlCommand.ExecuteNonQuery());

				if (id == 1)
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

		public static UserDTO GetUserById(int id)
		{
			string connectionString = null;
			SqlConnection connection = null;
			SqlCommand sqlCommand = null;
			SqlParameter sqlParameter = null;
			SqlDataReader sqlDataReader = null;
			UserDTO userDTO = null;
			try
			{
				connectionString = System.Configuration.ConfigurationManager.ConnectionStrings["connectionString"].ConnectionString;
				connection = new SqlConnection(connectionString);
				connection.Open();
				string sql = @"SELECT * 
							   FROM dbo.Users 
							   WHERE UserID = @UserID";
				sqlCommand = new SqlCommand(sql, connection);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "UserID";
				sqlParameter.SqlDbType = System.Data.SqlDbType.VarChar;
				sqlParameter.Value = id;
				sqlCommand.Parameters.Add(sqlParameter);

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

			return userDTO;
		}

		public static UserDTO GetUserByEmail(string email)
		{
			string connectionString = null;
			SqlConnection connection = null;
			SqlCommand sqlCommand = null;
			SqlParameter sqlParameter = null;
			SqlDataReader sqlDataReader = null;
			UserDTO userDTO = null;
			try
			{
				connectionString = System.Configuration.ConfigurationManager.ConnectionStrings["connectionString"].ConnectionString;
				connection = new SqlConnection(connectionString);
				connection.Open();
				string sql = @"SELECT * 
							   FROM dbo.Users 
							   WHERE Email = @Email";
				sqlCommand = new SqlCommand(sql, connection);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "Email";
				sqlParameter.SqlDbType = System.Data.SqlDbType.VarChar;
				sqlParameter.Value = email;
				sqlCommand.Parameters.Add(sqlParameter);

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

			return userDTO;
		}

		public static bool ValidateEmail(string email)
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
							   FROM dbo.Users 
							   WHERE Email = @Email";
				sqlCommand = new SqlCommand(sql, connection);

				sqlParameter = new SqlParameter();
				sqlParameter.ParameterName = "Email";
				sqlParameter.SqlDbType = System.Data.SqlDbType.VarChar;
				sqlParameter.Value = email;
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

	}
}
