<?php 
if (!empty($bot->isEvents) {
    if(isset($_COOKIE["Language"])){
        $Language = $_COOKIE["Language"];

        if($Language == "ภาษาไทย"){ //TH
            if($bot->text == "Leave"){
                $GoLeaveType = "GoLeaveType";
                setcookie("GoLeaveType",$GoLeaveType,time()+3600); // Expire 1 Hour
                // แสดงปุ่ม ประเภทการลา
            }
            elseif(isset($_COOKIE["GoLeaveType"]))
            {
                $LeaveType = $bot->text;
                setcookie("LeaveType",$LeaveType,time()+3600); // Expire 1 Hour
                setcookie("GoLeaveType");
                $GoRemark = "GoRemark";
                setcookie("GoRemark",$GoRemark,time()+3600); // Expire 1 Hour
                // แสดงข้อความกรอก สาเหตุ การลา
            }
            elseif(isset($_COOKIE["GoRemark"]))
            {
                $GetLeaveType = $_COOKIE["LeaveType"];
                $GetRemark = $bot->text;
                setcookie("GoRemark");
                // แสดงปุ่มยันยันข้อมูลก่อนส่ง URL $GetLeaveType,$GetRemark
            }
            elseif($bot->text == "Setting")
            {
                // แสดงปุ่ม เปลี่ยนภาษา , Login , Logout
            }
            elseif($bot->text == "Language")
            {
                // แสดงปุ่มเลือกภาษา
            }
            elseif($bot->text == "ภาษาไทย" || $bot->text == "English")
            {
                $Language = $bot->text;
                setcookie("Language",$Language,time()+3600); // Expire 1 Hour
                // แสดงข้อความ พร้อมใช้งาน
            }
            elseif($bot->text == "Login")
            {
                $ID = "ID";
                setcookie("ID",$ID,time()+3600); // Expire 1 Hour
                // แสดงข้อความกรอก EmpCode
            }
            elseif(isset($_COOKIE["ID"]))
            {

            }
            elseif($bot->text == "Logout")
            {

            }
            else
            {
                // แสดงข้อความ ไม่มีรายที่ต้องการ
            }
        }
        elseif($Language == "English")
        { //Eng
            // เหมือนไทย
        }
        else
        {
            // แสดงข้อความ ไม่มีภาษาที่ใช้
        }
    }
    elseif($bot->text == "ภาษาไทย" || $bot->text == "English")
    { // Set Language Cookie 
        $Language = $bot->text;
        setcookie("Language",$Language,time()+3600); // Expire 1 Hour
        // แสดงข้อความ พร้อมใช้งาน
    }
    else
    {
        // แสดงปุ่มเลือกภาษา
    }
}
else
{
    return "ไม่มีการทำงาน";
}

if ($bot->isSuccess()) 
{
  echo 'Succeeded!';
  exit();
}

// Failed
echo $bot->response->getHTTPStatus . ' ' . $bot->response->getRawBody();
exit();
?>