using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace DataTransferObjects
{
    public class UserDTO
    {
        public int UserID { get; set; }
        public string Name { get; set; }
        public string Login { get; set; }
        public string Password { get; set; }
        public string Email { get; set; }
        public char Gender { get; set; }
        public string Address { get; set; }
        public int Age { get; set; }
        public string NIC { get; set; }
        public DateTime DOB { get; set; }
        public bool IsCricket { get; set; }
        public bool Hockey { get; set; }
        public bool Chess { get; set; }
        public string ImageName { get; set; }
        public DateTime CreatedOn { get; set; }
    }
}
