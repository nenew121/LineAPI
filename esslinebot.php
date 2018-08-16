<?php

require('Code3.php');
include('Code2.php');

$channelSecret = '592e8df851742b42aa264f7e9e5fb26c';
$access_token  = '8YB0v5Ltt9ENVQPRQNExtnowRfWteWwdD13Y7s4+E4pRqNGVjFwVacuauvTYUFFUvhFT8A7JOD0AOTUsYDWqXRGXa5Z1Ta3Qzb38JNSzpmB6CQmllEiHJh0SZSBkgI+EYnR0DSwWJuvwBTXe4PkMeQdB04t89/1O/w1cDnyilFU=';


$bot = new BOT_API($channelSecret, $access_token);
$idnews = $_POST['txtNews'];
$LineID = $_POST['LineID'];
$Docuno = $_POST['Docuno'];
$LineID_EmpID = $_POST['LineID_EmpID'];
$Apprequest = $_POST['Apprequest'];

// Check News
if(!empty($idnews)){

    $str = NEWS($idnews);
    
    $arr = SendUserID();
    $iCount = count($arr);
    for ($i = 0; $i<$iCount; $i++) {
        $bot->SendMessageTo($arr[$i],$str);
    }
// return echo "success";
}

// Check Approve MS
if(!empty($LineID)){
    $str = "มีเอกสารอนุมัติ";
    $bot->SendMessageApproveTo($LineID ,$str." ".$Docuno);
}

// Check MS request
if(!empty($LineID_EmpID)){
    if($Apprequest == "Y"){
        $str = "เอกสาร อนุมัติ";
    }else{
        $str = "เอกสาร ไม่อนุมัติ";
    }
    $bot->SendMessageApproveTo($LineID_EmpID ,$str);
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
                $bot->Organization($bot->replyToken);
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

