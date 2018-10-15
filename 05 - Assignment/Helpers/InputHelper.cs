using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Text.RegularExpressions;
using System.Threading.Tasks;

namespace Helpers
{
    public static class InputHelper
    {
        public static bool Empty(string str)
        {
            return str == "";
        }

        public static bool Length(string str, int min, int max)
        {
            return str.Length >= 1 && str.Length <= max;
        }

        public static bool MatchPattern(string input, string pattern)
        {
            return Regex.IsMatch(input, pattern);
        }

        public static bool Gender(char gender)
        {
            return gender == 'M' || gender == 'F';
        }

        public static bool Age(int age)
        {
            return age > 0;
        }
    }
}
