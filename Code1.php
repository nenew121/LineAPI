<?php 
require('Code3.php');
include('Code2.php');

$channelSecret = '592e8df851742b42aa264f7e9e5fb26c';
$access_token  = '8YB0v5Ltt9ENVQPRQNExtnowRfWteWwdD13Y7s4+E4pRqNGVjFwVacuauvTYUFFUvhFT8A7JOD0AOTUsYDWqXRGXa5Z1Ta3Qzb38JNSzpmB6CQmllEiHJh0SZSBkgI+EYnR0DSwWJuvwBTXe4PkMeQdB04t89/1O/w1cDnyilFU=';

$bot = new BOT_API($channelSecret, $access_token);
$idnews = $_POST['txtNews'];

if(!empty($idnews)){
    $str = NEWS($idnews);
    
    $arr = SendUserID();
    $iCount = count($arr);
    for ($i = 0; $i<$iCount; $i++) {
        $bot->sendMessageNew($arr[$i],$str);
    }
}
$LineID = "U7fb3dc484426fb164c424df09b7a42ba";

if (!empty($bot->isEvents) {
    $CheckID = CheckID($bot->userId); 
    if ($CheckID == null) // ไม่มี ID ให้ลงทะเบียน
    {
        if($bot->text == "ภาษาไทย" || $bot->text == "Thai" || $bot->text == "อังกฤษ" || $bot->text == "English")
        {
            $resul = SetLanguage($bot->userId,$bot->text); // ตั้งค่า ภาษา
            $bot->replyMessageNew($bot->replyToken,$resul."กรอก EmpCode"); // แสดงข้อความกรอก EmpCode
        }
        else
        { 
            $bot->SendLanguage($bot->replyToken); // แสดงปุ่มภาษา
        }
    }
    else
    { // มี ID พร้อมใช้งาน
        $CheckActive = CheckActive($bot->userId); 
        if($CheckActive == 0)  // สถาณะการใช้งาน  0 = ไม่ login
        {
            $ShowMenu = ShowMenu($bot->userId); // แสดงว่าทำงานอะไรอยู่
            if ($ShowMenu > 0) 
            {
                switch ($ShowMenu) 
                {
                    case 1: 
                        // กรอก EmpCode ไทย
                        UpMenu($bot->userId,2); // ถ้าถูก UpMenu เป็น 2
                        $bot->replyMessageNew($bot->replyToken,"กรอก Pass"); // แสดงข้อความกรอก Pass 
                        break;
                    case 2: 
                        // กรอก Pass ไทย
                        UpMenu($bot->userId,3); // ถ้าถูก UpMenu เป็น 3
                        $bot->replyMessageNew($bot->replyToken,"ยืนยันข้อมูล"); // แสดงข้อความยืนยัน
                        break;
                    case 3: // ยืนยัน ไทย
                        UpMenu($bot->userId,0); // ถ้าถูก UpMenu เป็น 0 
                        UpActive($bot->userId); //UpIsActive เป็น 1
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
            $Language = ShowLanguage($bot->userId); // แสดงภาษาตอบกลับ
            if ($Language == "th-TH") 
            {
                $ShowMenu = ShowMenu($bot->userId); // แสดงว่าทำงานอะไรอยู่
                if ($ShowMenu > 0) 
                {
                    switch ($ShowMenu) 
                    {
                        case 1: // ขอลา
                            if($bot->text == "ยกเลิก" || $bot->text == "ย้อนกลับ"){
                                UpMenu($bot->userId,0); // UpMenu เป็น 0
                                $bot->replyMessageNew($bot->replyToken,"หน้าหลัก พร้อมใช้งาน"); // แสดงข้อความ เมนูเริ่มต้น 
                            }else{
                                $result = LeaveRequest($bot->userId, $bot->text); // เช็คการลา
                                if($result == 1){
                                    UpMenu($bot->userId,2); // UpMenu เป็น 2
                                    $bot->replyMessageNew($bot->replyToken,"กรอก สาเหตุ");
                                }elseif($result == 2){
                                    $Text = "*ไม่มีประเภทการลานี้\n";
                                    $bot->SendLeaveType($bot->replyToken,$Language,$Text);
                                }else{
                                    UpMenu($bot->userId,0); // UpMenu เป็น 0
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
                            if($bot->text == "ไทย" || $bot->text == "Thai" || $bot->text == "อังกฤษ" || $bot->text == "English")
                            {
                                $resul = SetLanguage($bot->userId,$bot->text); // ตั้งค่า ภาษา
                                $bot->replyMessageNew($bot->replyToken,$resul);
                            }
                            else
                            {   
                                $Text = "*ไม่มีภาษาที่เลือก\n";
                                $bot->SendLanguage($bot->replyToken,$Language,$Text); // แสดงปุ่มภาษา
                            }
                            break;
                        default:
                            $bot->replyMessageNew($bot->replyToken,"Error");
                        break;
                    }
                }
                else
                { // หน้าเมนูหลัก 
                    if($bot->text == "ApproveCenter"){
                        $bot->ApproveCenter($bot->replyToken);
                    }elseif($bot->text == "TimeAttendance"){
                        $bot->TimeAttendance($bot->replyToken,$bot->userId);
                    }elseif($bot->text == "Payroll"){
                        $bot->Payroll($bot->replyToken);
                    }elseif($bot->text == "Organization"){
                        $bot->Organization($bot->replyToken);
                    }elseif($bot->text == "Setting"){
                        $bot->Setting($bot->replyToken);
                    }elseif($bot->text == "AboutUs"){
                        $bot->AboutUs($bot->replyToken);
                    }elseif($bot->text == "Leave"){
                        UpMenu($bot->userId,1); // ถ้าถูก UpMenu เป็น 1
                        $bot->SendLeaveType($bot->replyToken,$Language);
                    }elseif($bot->text == "เปลี่ยนภาษา" || $bot->text == "Language"){
                        UpMenu($bot->userId,4); // ถ้าถูก UpMenu เป็น 4
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
    $bot->replyMessageNew($bot->replyToken,"ไม่มีการทำงาน");
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