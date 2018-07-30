<?php
require('Code3.php');
include('Code2.php');

$text = (isset($_POST['AddIDTH'])? $_POST['AddIDTH']:"");
$userId = "U7fb3dc484426fb164c424df09b7a42ba";

if(!empty($text)){
    $CheckID = CheckID($userId); 
    if ($CheckID == null) // ไม่มี ID ให้ลงทะเบียน
    {
        if($text == "ภาษาไทย" || $text == "Thai" || $text == "อังกฤษ" || $text == "English")
        {
            $resul = SetLanguage($userId,$text); // ตั้งค่า ภาษา
            $bot->replyMessageNew($bot->replyToken,$resul."กรอก EmpCode"); // แสดงข้อความกรอก EmpCode
        }
        else
        { 
            $bot->SendLanguage($bot->replyToken); // แสดงปุ่มภาษา
        }
    }
    else
    { // มี ID พร้อมใช้งาน
        $CheckActive = CheckActive($userId); 
        if($CheckActive == 0)  // สถาณะการใช้งาน  0 = ไม่ login
        {
            $ShowMenu = ShowMenu($userId); // แสดงว่าทำงานอะไรอยู่
            if ($ShowMenu > 0) 
            {
                switch ($ShowMenu) 
                {
                    case 1: 
                        // กรอก EmpCode ไทย
                        UpMenu($userId,2); // ถ้าถูก UpMenu เป็น 2
                        $bot->replyMessageNew($bot->replyToken,"กรอก Pass"); // แสดงข้อความกรอก Pass 
                        break;
                    case 2: 
                        // กรอก Pass ไทย
                        UpMenu($userId,3); // ถ้าถูก UpMenu เป็น 3
                        $bot->replyMessageNew($bot->replyToken,"ยืนยันข้อมูล"); // แสดงข้อความยืนยัน
                        break;
                    case 3: // ยืนยัน ไทย
                        UpMenu($userId,0); // ถ้าถูก UpMenu เป็น 0 
                        UpActive($userId); //UpIsActive เป็น 1
                        $bot->replyMessageNew($bot->replyToken,"หน้าหลัก พร้อมใช้งาน"); // แสดงข้อความ เมนูเริ่มต้น                   
                        break;
                    case 4: // กรอก EmpCode Eng
                        // ถ้าถูก UpMenu เป็น 2
                        // แสดงข้อความกรอก Pass 
                        break;
                    case 5: // กรอก Pass Eng
                        // ถ้าถูก UpMenu เป็น 3 
                        // อาจจะ Get Cookie EmpCode,Pass เพื่อให้แสดงในข้อความการยันยืนข้อมูล
                        // แสดงข้อความยืนยัน
                        break;
                    case 6: // ยืนยัน Eng
                        // ถ้าถูก UpMenu เป็น 0 , UpIsActive เป็น 1
                        // แสดงข้อความ เมนูเริ่มต้น                   
                        break;
                    default:
                        $bot->replyMessageNew($bot->replyToken,"Error");
                    break;
                }
            }
        }
        else
        { // 1 = login แล้วพร้อมทำงาน
            $Language = ShowLanguage($userId); // แสดงภาษาตอบกลับ
            if ($Language == "th-TH") 
            {
                $ShowMenu = ShowMenu($userId); // แสดงว่าทำงานอะไรอยู่
                if ($ShowMenu > 0) 
                {
                    switch ($ShowMenu) 
                    {
                        case 1: // ขอลา
                            if($text == "ยกเลิก" || $text == "ย้อนกลับ"){
                                UpMenu($userId,0); // UpMenu เป็น 0
                                $bot->replyMessageNew($bot->replyToken,"หน้าหลัก พร้อมใช้งาน"); // แสดงข้อความ เมนูเริ่มต้น 
                            }else{
                                $result = LeaveRequest($userId, $text); // เช็คการลา
                                if($result == 1){
                                    UpMenu($userId,2); // UpMenu เป็น 2
                                    $bot->replyMessageNew($bot->replyToken,"กรอก สาเหตุ");
                                }elseif($result == 2){
                                    $bot->SendLeaveType($bot->replyToken,$Language,"ไม่มีประเภทการลานี้");
                                }else{
                                    UpMenu($userId,0); // UpMenu เป็น 0
                                    $bot->replyMessageNew($bot->replyToken,$result);
                                }
                            }
                            break;
                        case 2: // สาเหตุ
                            GetRemark($Remark); // เก็บ Cookie Remark
                            // UpMenu เป็น 3
                            // อาจจะ Get Cookie LeaveType,Remark เพื่อให้แสดงในข้อความการยันยืนข้อมูล
                            // แสดงข้อความ ยืนยันข้อมูล
                            break;
                        case 3: // ยืนยันข้อมูล
                            // Get Cookie LeaveType,Remark
                            // UpMenu เป็น 0 แล้ว ส่ง URL
                            // แสดงข้อความ เมนูเริ่มต้น
                            break;
                        case 4: // เปลี่ยนภาษา
                            if($text == "ภาษาไทย" || $text == "Thai" || $text == "อังกฤษ" || $text == "English")
                            {
                                $resul = SetLanguage($userId,$text); // ตั้งค่า ภาษา
                                $bot->replyMessageNew($bot->replyToken,$resul);
                            }
                            else
                            { 
                                $bot->SendLanguage($bot->replyToken,$Language); // แสดงปุ่มภาษา
                            }
                            break;
                        default:
                            $bot->replyMessageNew($bot->replyToken,"Error");
                        break;
                    }
                }
                else
                { // หน้าเมนูหลัก 
                    if($text == "ApproveCenter"){
                        $bot->ApproveCenter($bot->replyToken);
                    }elseif($text == "TimeAttendance"){
                        $bot->TimeAttendance($bot->replyToken);
                    }elseif($text == "Payroll"){
                        $bot->Payroll($bot->replyToken);
                    }elseif($text == "Organization"){
                        $bot->Organization($bot->replyToken);
                    }elseif($text == "Setting"){
                        $bot->Setting($bot->replyToken);
                    }elseif($text == "AboutUs"){
                        $bot->AboutUs($bot->replyToken);
                    }elseif($text == "Leave"){
                        UpMenu($userId,1); // ถ้าถูก UpMenu เป็น 1
                        $bot->SendLeaveType($bot->replyToken,$Language);
                    }elseif($text == "เปลี่ยนภาษา"){
                        UpMenu($userId,4); // ถ้าถูก UpMenu เป็น 4
                        $bot->SendLanguage($bot->replyToken,$Language);
                    }else{
                    $bot->replyMessageNew($bot->replyToken,"ไม่มีรายการที่เลือก");
                    }
                }
            }elseif($Language == "eu-US"){
                // เหมือนของ TH
            }else{
                return "Error";
            }
        }
    }
}else{
    return "error";
}

exit();
?>