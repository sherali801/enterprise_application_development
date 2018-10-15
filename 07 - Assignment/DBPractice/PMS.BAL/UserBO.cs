using PMS.Entities;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PMS.BAL
{
	public class UserBO
	{
		public static int Save(UserDTO dto)
		{
			return PMS.DAL.UserDAO.Save(dto);
		}

		public static int UpdatePassword(UserDTO dto)
		{
			return PMS.DAL.UserDAO.UpdatePassword(dto);
		}

		public static int UpdatePasswordWithLogin(UserDTO dto)
		{
			return PMS.DAL.UserDAO.UpdatePasswordWithLogin(dto);
		}

		public static UserDTO ValidateUser(String pLogin, String pPassword)
		{
			return PMS.DAL.UserDAO.ValidateUser(pLogin, pPassword);
		}

		public static UserDTO ValidateLogin(string login)
		{
			return PMS.DAL.UserDAO.ValidateLogin(login);
		}


		public static UserDTO GetUserById(int pid)
		{
			return PMS.DAL.UserDAO.GetUserById(pid);
		}
		public static List<UserDTO> GetAllUsers()
		{
			return PMS.DAL.UserDAO.GetAllUsers();
		}

		public static int DeleteUser(int pid)
		{
			return PMS.DAL.UserDAO.DeleteUser(pid);
		}

	}
}
