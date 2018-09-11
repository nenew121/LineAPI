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

/*
$idnews = $_POST['txtNews'];
// Check News
if(!empty($idnews)){

    $str = NEWS($idnews);
    
    $arr = SendUserID();
    $iCount = count($arr);
    for ($i = 0; $i<$iCount; $i++) {
        $bot->SendMessageTo($arr[$i],$str);
    }
// return echo "success";
}*/
// แจ้งข่าวสาร
if(!empty($NewsHDID)){
    //$arr = SendNewsTo($NewsHDID);
    //$iCount = count($arr);
    //for ($i = 0; $i<$iCount; $i++) {
    //    $bot->SendMessageTo($arr[$i],$News);
    //}
    $ArrID = array("U7fb3dc484426fb164c424df09b7a42ba","U05a39ae3a619678ef4b1b58111980a79");
    $iCount = count($ArrID);
    for ($i = 0; $i<$iCount; $i++) {
        $bot->SendMessageTo($ArrID[$i],$News);
    }
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

    /*if(CheckLineID($bot->userId))
    {*/
        $Language = GetLanguage($bot->userId);
        if($Language == "th-TH")
        {
            if($bot->text == "Approve Center")
            {
                $bot->ApproveCenter($bot->replyToken,$bot->userId);
            }
            elseif($bot->text == "Time Attendance")
            {
                $bot->TimeAttendance($bot->replyToken,$bot->userId);
            }
            elseif($bot->text == "วันอนุญาตลา")
            {
                $Text = LeaveRemainNum($bot->userId);
                $bot->replyMessageNew($bot->replyToken,$Text);
            }
            elseif($bot->text == "Payroll")
            {
                $bot->Payroll($bot->replyToken,$bot->userId);
            }
            elseif($bot->text == "E-Pay Slip")
            {
                $Text = EPaySlip($bot->userId);
                $bot->replyMessageNew($bot->replyToken,$Text);
            }
            elseif($bot->text == "Organization")
            {
                $bot->Organization($bot->replyToken,$bot->userId);
            }
            elseif($bot->text == "วันหยุดองค์กร")
            {
                $Text = Calender($bot->userId);
                $bot->replyMessageNew($bot->replyToken,$Text);
            }
            elseif($bot->text == "ที่ตั้งองค์กร")
            {
                $Text = LocationOrganization($bot->userId);
                $bot->LocationOrg($bot->replyToken,$Text);
            }
            elseif($bot->text == "Setting")
            {
                $bot->Setting($bot->replyToken,$bot->userId);
            }
            elseif($bot->text == "เปลี่ยนภาษา")
            {
                $bot->SendLanguage($bot->replyToken,$bot->userId);
            }
            elseif($bot->text == "AboutUs")
            {
                $bot->AboutUs($bot->replyToken);
            }
            else
            {
            $bot->BOT_New($bot->replyToken,$bot->text);
            }
        }
        else if($Language == "en-US") //#####################################################################################
        {
            if($bot->text == "Approve Center")
            {
                $bot->ApproveCenterEng($bot->replyToken,$bot->userId);
            }
            elseif($bot->text == "Time Attendance")
            {
                $bot->TimeAttendanceEng($bot->replyToken,$bot->userId);
            }
            /*
            elseif($bot->text == "Leave Information")
            {
                $bot->Leaveinformation($bot->replyToken);
            }
            */
            elseif($bot->text == "Leave Remain")
            {
                $Text = LeaveRemainNumEng($bot->userId);
                $bot->replyMessageNew($bot->replyToken,$Text);
                //$bot->LeaveRemain($bot->replyToken);
            }
            /*
            elseif($bot->text == "ลากิจ")
            {
                $Text = LeaveRemainNum($bot->userId,"L-001");
                $bot->replyMessageNew($bot->replyToken,$Text);
            }
            elseif($bot->text == "ลาป่วย")
            {
                $Text = LeaveRemainNum($bot->userId,"L-002");
                $bot->replyMessageNew($bot->replyToken,$Text);
            }
            elseif($bot->text == "ลาพักร้อน")
            {
                $Text = LeaveRemainNum($bot->userId,"L-003");
                $bot->replyMessageNew($bot->replyToken,$Text);
            }
            */
            elseif($bot->text == "Payroll")
            {
                $bot->Payroll($bot->replyToken,$bot->userId);
            }
            elseif($bot->text == "E-Pay Slip")
            {
                $Text = EPaySlip($bot->userId);
                $bot->replyMessageNew($bot->replyToken,$Text);
            }
            elseif($bot->text == "Organization")
            {
                $bot->OrganizationEng($bot->replyToken,$bot->userId);
            }
            elseif($bot->text == "Organization Calender")
            {
                $Text = Calender($bot->userId);
                $bot->replyMessageNew($bot->replyToken,$Text);
            }
            elseif($bot->text == "Location of Organization")
            {
                $Text = LocationOrganization($bot->userId);
                $bot->LocationOrg($bot->replyToken,$Text);
            }
            elseif($bot->text == "Setting")
            {
                $bot->SettingEng($bot->replyToken,$bot->userId);
            }
            elseif($bot->text == "Language")
            {
                $bot->SendLanguage($bot->replyToken,$bot->userId);
            }
            elseif($bot->text == "AboutUs")
            {
                $bot->AboutUs($bot->replyToken);
            }
            else
            {
            $bot->BOT_New($bot->replyToken,$bot->text);
            }
        }
        else
        {
            $bot->SendLanguage($bot->replyToken,$bot->userId);
        }
    /*}
    else
    {
        $bot->SendLanguage($bot->replyToken,$bot->userId);
    }*/
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

