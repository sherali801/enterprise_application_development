using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace DataTransferObjects
{
    public class AdminDTO
    {
        public int AdminID { get; set; }
        public string AdminName { get; set; }
        public string Login { get; set; }
        public string Password { get; set; }
    }
}
