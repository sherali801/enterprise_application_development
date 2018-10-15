using DataAccessLayer;
using DataTransferObjects;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace BusinessAccessLayer
{
	public static class AdminBAL
	{
		public static bool ValidateAdmin(string login, string password)
		{
			return AdminDAL.ValidateAdmin(login, password);
		}

		public static int GetIdByLogin(string login)
		{
			return AdminDAL.GetIdByLogin(login);
		}

		public static List<UserDTO> GetAllUsers()
		{
			return AdminDAL.GetAllUsers();
		}
	}
}
