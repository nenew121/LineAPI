<?php

require('Code3.php');
include('Code2.php');

$channelSecret = '592e8df851742b42aa264f7e9e5fb26c';
$access_token  = '8YB0v5Ltt9ENVQPRQNExtnowRfWteWwdD13Y7s4+E4pRqNGVjFwVacuauvTYUFFUvhFT8A7JOD0AOTUsYDWqXRGXa5Z1Ta3Qzb38JNSzpmB6CQmllEiHJh0SZSBkgI+EYnR0DSwWJuvwBTXe4PkMeQdB04t89/1O/w1cDnyilFU=';

$NewsHDID = $_POST['NewsHDID'];
$News = $_POST['News'];
$LineIDLeaveRecord = $_POST['LineIDLeaveRecord'];
$Detail = $_POST['Detail'];
$LineID_NextApprove = $_POST['LineID_NextApprove'];
$WaitApprove = $_POST['WaitApprove'];
$LineID_EmpID = $_POST['LineID_EmpID'];
$ApproveStatus = $_POST['ApproveStatus'];
$bot = new BOT_API($channelSecret, $access_token);


// แจ้งข่าวสาร
if(!empty($NewsHDID)){
    $arr = SendNewsTo($NewsHDID);
    $iCount = count($arr);
    for ($i = 0; $i<$iCount; $i++) {
        $bot->SendMessageTo($arr[$i],$News);
    }
    //$ArrID = array("U7fb3dc484426fb164c424df09b7a42ba","U05a39ae3a619678ef4b1b58111980a79");
    //$iCount = count($ArrID);
    //for ($i = 0; $i<$iCount; $i++) {
    //    $bot->SendMessageTo($ArrID[$i],$News);
    //}
    //$bot->SendMessageTo("U7fb3dc484426fb164c424df09b7a42ba",$News);
}

// แจ้งเอกสารลาหาผู้อนุมัติ
if(!empty($LineIDLeaveRecord)){
    $bot->SendMessageApproveTo($LineIDLeaveRecord,$Detail);
    //$bot->SendMessageApproveTo("U7fb3dc484426fb164c424df09b7a42ba",$Detail);
}

// แจ้งเอกสารคนอนุมัติถัดไป
if(!empty($LineID_NextApprove)){
    $bot->SendMessageApproveTo($LineID_NextApprove ,$WaitApprove);
    //$bot->SendMessageApproveTo("U7fb3dc484426fb164c424df09b7a42ba",$WaitApprove);
}

// แจ้งเอกสารหาผู้ขอลา
if(!empty($LineID_EmpID)){
    $bot->SendMessageToEmpRequest($LineID_EmpID ,$ApproveStatus);
    //$bot->SendMessageToEmpRequest("U7fb3dc484426fb164c424df09b7a42ba",$ApproveStatus);
}

if (!empty($bot->isEvents)) {
    $Language = GetLanguage($bot->userId);
    if($Language == "th-TH")
    {
        switch($bot->text){
            case "Approve Center":
                $bot->ApproveCenter($bot->replyToken,$bot->userId);
            break;
            case "Time Attendance":
                $bot->TimeAttendance($bot->replyToken,$bot->userId);
            break;
            case "สิทธิ์การลา/วันลาคงเหลือ":
                $Text = LeaveRemainNum($bot->userId);
                $bot->replyMessageNew($bot->replyToken,$Text);
            break;
            case "Payroll":
                $bot->Payroll($bot->replyToken,$bot->userId);
            break;
            case "ขอสลิปเงินเดือน":
                $Text = EPaySlip($bot->userId);
                $bot->replyMessageNew($bot->replyToken,$Text);
            break;
            case "ขอเอกสาร 50 ทวิ":
                //$Text = Withholdingtaxcertificate($bot->userId);
                $bot->SendMessageTo("U05a39ae3a619678ef4b1b58111980a79","ขอเอกสาร 50 ทวิ"); // ส่งข้อความหาHR
                $bot->SendMessageTo("U7fb3dc484426fb164c424df09b7a42ba","ขอเอกสาร 50 ทวิ"); // ส่งข้อความหาผู้ขอ
            break;
            case "Organization":
                $bot->Organization($bot->replyToken,$bot->userId);
            break;
            case "วันหยุดองค์กร":
                $Text = calendar($bot->userId);
                $bot->replyMessageNew($bot->replyToken,$Text);
            break;
            case "ที่ตั้งองค์กร":
                $Text = LocationOrganization($bot->userId);
                if($Text == "ชื่อผู้ใช้ของคุณ ยังไม่ได้ลงทะเบียน" || $Text == "Please register to use system." || $Text == "ไม่พบที่อยู่องค์กร" || $Text == "not find Locationtion of Organization."){
                    $bot->replyMessageNew($bot->replyToken,$Text);
                }else{
                    $bot->LocationOrg($bot->replyToken,$Text);
                }
            break;
            case "Setting":
                $bot->Setting($bot->replyToken,$bot->userId);
            break;
            case "เปลี่ยนภาษา":
                $bot->SendLanguage($bot->replyToken,$bot->userId);
            break;
            case "ภาษาไทย (Thai)":
                $Text = ChangeLanguage($bot->userId,$bot->text);
                $bot->replyMessageNew($bot->replyToken,$Text);
            break;
            case "ภาษาอังกฤษ (English)":
                $Text = ChangeLanguage($bot->userId,$bot->text);
                $bot->replyMessageNew($bot->replyToken,$Text);
            break;
            case "AboutUs":
                $bot->AboutUs($bot->replyToken);
            break;
            default:
                $bot->BOT_New($bot->replyToken,$bot->text);
            break;
        }
    }
    else if($Language == "en-US") //#####################################################################################
    {
        switch($bot->text){
            case "Approve Center":
                $bot->ApproveCenterEng($bot->replyToken,$bot->userId);
            break;
            case "Time Attendance":
                $bot->TimeAttendanceEng($bot->replyToken,$bot->userId);
            break;
            case "Leave Remain":
                $Text = LeaveRemainNumEng($bot->userId);
                $bot->replyMessageNew($bot->replyToken,$Text);
            break;
            case "Payroll":
                $bot->PayrollEng($bot->replyToken,$bot->userId);
            break;
            case "E-Pay Slip":
                $Text = EPaySlip($bot->userId);
                $bot->replyMessageNew($bot->replyToken,$Text);
            break;
            case "Organization":
                $bot->OrganizationEng($bot->replyToken,$bot->userId);
            break;
            case "Organization Calendar":
                $Text = CalendarEng($bot->userId);
                $bot->replyMessageNew($bot->replyToken,$Text);
            break;
            case "Location of Organization":
                $Text = LocationOrganization($bot->userId);
                if($Text == "ชื่อผู้ใช้ของคุณ ยังไม่ได้ลงทะเบียน" || $Text == "Please register to use system." || $Text == "ไม่พบที่อยู่องค์กร" || $Text == "not find Locationtion of Organization."){
                    $bot->replyMessageNew($bot->replyToken,$Text);
                }else{
                    $bot->LocationOrg($bot->replyToken,$Text);
                }
            break;
            case "Setting":
                $bot->SettingEng($bot->replyToken,$bot->userId);
            break;
            case "Language":
                $bot->SendLanguage($bot->replyToken,$bot->userId);
            break;
            case "ภาษาไทย (Thai)":
                $Text = ChangeLanguage($bot->userId,$bot->text);
                $bot->replyMessageNew($bot->replyToken,$Text);
            break;
            case "ภาษาอังกฤษ (English)":
                $Text = ChangeLanguage($bot->userId,$bot->text);
                $bot->replyMessageNew($bot->replyToken,$Text);
            break;
            case "AboutUs":
                $bot->AboutUs($bot->replyToken);
            break;
            default:
                $bot->BOT_New($bot->replyToken,$bot->text);
            break;
        }
    }
    else if($Language == "NoLang")
    {
        if($bot->text == "ภาษาไทย (Thai)" || $bot->text == "ภาษาอังกฤษ (English)"){
            $Text = ChangeLanguage($bot->userId,$bot->text);
            $bot->replyMessageNew($bot->replyToken,$Text);
        }else{
            $bot->SendLanguage($bot->replyToken,$bot->userId);
        }
    }
    else
    { // Check Connect DB
        $bot->replyMessageNew($bot->replyToken,"ยังไม่ได้เชื่อมต่อกับฐานข้อมูล\nNot connection DB.");
    }
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

