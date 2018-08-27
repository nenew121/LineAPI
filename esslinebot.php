<?php

require('Code3.php');
include('Code2.php');

$channelSecret = '592e8df851742b42aa264f7e9e5fb26c';
$access_token  = '8YB0v5Ltt9ENVQPRQNExtnowRfWteWwdD13Y7s4+E4pRqNGVjFwVacuauvTYUFFUvhFT8A7JOD0AOTUsYDWqXRGXa5Z1Ta3Qzb38JNSzpmB6CQmllEiHJh0SZSBkgI+EYnR0DSwWJuvwBTXe4PkMeQdB04t89/1O/w1cDnyilFU=';


$bot = new BOT_API($channelSecret, $access_token);
//$idnews = $_POST['txtNews'];
$LineID_NextApprove = $_POST['LineID_NextApprove'];
$Docuno = $_POST['Docuno'];
$LineID_EmpID = $_POST['LineID_EmpID'];
$ApproveStatus = $_POST['ApproveStatus'];
$NewsHDID = $_POST['NewsHDID'];
$News = $_POST['News'];

/*
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

if(!empty($NewsHDID)){
    /*
    $arr = SendNewsTo($NewsHDID);
    $iCount = count($arr);
    for ($i = 0; $i<$iCount; $i++) {
        $bot->SendMessageTo($arr[$i],$News);
    }
    */
    $bot->SendMessageTo("U7fb3dc484426fb164c424df09b7a42ba",$News);
}

$LineIDLeaveRecord = $_POST['LineIDLeaveRecord'];
$Detail = $_POST['Detail'];
if(!empty($LineIDLeaveRecord)){
    $bot->SendMessageTo($LineID_Grant,$Detail);
}

// Check Approve MS
if(!empty($LineID_NextApprove)){
    $str = "มีเอกสารอนุมัติ";
    $bot->SendMessageApproveTo($LineID_NextApprove ,$str." ".$Docuno);
}

// Check MS request
if(!empty($LineID_EmpID)){
    if($ApproveStatus == "Y"){
        $str = "อนุมัติ";
    }else{
        $str = "ไม่อนุมัติ";
    }
    $bot->SendMessageTo($LineID_EmpID ,"เอกสาร ".$Docuno." ".$str);
}

if (!empty($bot->isEvents)) {

    /*if(CheckLineID($bot->userId))
    {*/
        $Language = GetLanguage($bot->userId);
        if($Language != null)
        {
            if($bot->text == "ApproveCenter")
            {
                $bot->ApproveCenter($bot->replyToken,$bot->userId);
            }
            elseif($bot->text == "TimeAttendance")
            {
                $bot->TimeAttendance($bot->replyToken,$bot->userId);
            }
            elseif($bot->text == "Leave Information")
            {
                $bot->Leaveinformation($bot->replyToken);
            }
            elseif($bot->text == "Leave Remain")
            {
                $bot->LeaveRemain($bot->replyToken);
            }
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
            elseif($bot->text == "Location of Organization")
            {
                $Text = LocationOrganization($bot->userId);
                $bot->LocationOrg($bot->replyToken,$Text);
            }
            elseif($bot->text == "Setting")
            {
                $bot->Setting($bot->replyToken,$bot->userId);
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

/*
            elseif($bot->text == "Wait Approve")
            {
                $Text = Leaveinformation($bot->userId,"W");
                $bot->replyMessageNew($bot->replyToken,$Text);
            }
            elseif($bot->text == "Approved")
            {
                $Text = Leaveinformation($bot->userId,"Y");
                $bot->replyMessageNew($bot->replyToken,$Text);
            }
            elseif($bot->text == "Not Approve")
            {
                $Text = Leaveinformation($bot->userId,"N");
                $bot->replyMessageNew($bot->replyToken,$Text);
            }
*/
?>

