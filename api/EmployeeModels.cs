using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.Linq;
using System.Web;

namespace ESSLineBotAPI.Models
{
    public class EmployeeModels
    {
        public string EmpID { get; set; }
        public string EmpCode { get; set; }
        public string Title { get; set; }
        public string FirstName { get; set; }
        public string MiddleName { get; set; }
        public string LastName { get; set; }
        public string NickName { get; set; }
        public string TitleEng { get; set; }
        public string FirstNameEng { get; set; }
        public string MiddleNameEng { get; set; }
        public string LastNameEng { get; set; }
        public string NickNameEng { get; set; }
        public string FullName { get; set; }
        public string FullNameEng { get; set; }
        public DateTime? BirthDate { get; set; }
    }
}