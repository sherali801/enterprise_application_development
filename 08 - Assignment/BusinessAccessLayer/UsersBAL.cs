using DataAccessLayer;
using DataTransferObjects;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace BusinessAccessLayer
{
	public static class UsersBAL
	{
		public static bool ValidateUser(UsersDTO usersDTO)
		{
			return UsersDAL.ValidateUser(usersDTO);
		}

		public static UsersDTO GetUserByLogin(string Login)
		{
			return UsersDAL.GetUserByLogin(Login);
		}
	}
}
