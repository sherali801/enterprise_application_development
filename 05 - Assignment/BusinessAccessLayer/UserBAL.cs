using DataAccessLayer;
using DataTransferObjects;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace BusinessAccessLayer
{
	public static class UserBAL
	{
		public static bool DuplicateLogin(UserDTO userDTO)
		{
			return UserDAL.DuplicateLogin(userDTO);
		}

		public static bool DuplicateLoginWithId(UserDTO userDTO)
		{
			return UserDAL.DuplicateLoginWithId(userDTO);
		}

		public static int CreateUser(UserDTO userDTO)
		{
			return UserDAL.CreateUser(userDTO);
		}

		public static UserDTO GetUserByLogin(string login)
		{
			return UserDAL.GetUserByLogin(login);
		}

		public static bool ValidateUser(string login, string password)
		{
			return UserDAL.ValidateUser(login, password);
		}

		public static int GetIdByLogin(string login)
		{
			return UserDAL.GetIdByLogin(login);
		}

		public static UserDTO GetUserById(int id)
		{
			return UserDAL.GetUserById(id);
		}

		public static bool UpdateUser(UserDTO userDTO)
		{
			return UserDAL.UpdateUser(userDTO);
		}

		public static UserDTO GetUserByEmail(string email)
		{
			return UserDAL.GetUserByEmail(email);
		}

		public static bool ValidateEmail(string email)
		{
			return UserDAL.ValidateEmail(email);
		}
	}
}
