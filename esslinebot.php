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
// return echo "success";
}

if (!empty($bot->isEvents)) {

    /*if(CheckLineID($bot->userId))
    {
        $Language = SelectLanguage($bot->userId);
        if(CheckActive($bot->userId))
        {*/
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
            elseif($bot->text == "LeaveNum")
            {
                $bot->LeaveNum($bot->replyToken);
            }
            elseif($bot->text == "ลากิจ")
            {
                $Text = LeaveDayNum($bot->userId,"L-001");
                $bot->replyMessageNew($bot->replyToken,$Text);
            }
            elseif($bot->text == "ลาป่วย")
            {
                $Text = LeaveDayNum($bot->userId,"L-002");
                $bot->replyMessageNew($bot->replyToken,$Text);
            }
            elseif($bot->text == "ลาพักร้อน")
            {
                $Text = LeaveDayNum($bot->userId,"L-003");
                $bot->replyMessageNew($bot->replyToken,$Text);
            }
            elseif($bot->text == "Payroll")
            {
                $bot->Payroll($bot->replyToken);
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
            elseif($bot->text == "Leave")
            {
                $bot->SendLeaveType($bot->replyToken);
            }
            else
            {
            $bot->testt($bot->replyToken,"ไม่มีรายการที่เลือก");
            }
        /*}
        else
        {
            $bot->Register($bot->replyToken,$bot->userId);
        }
    }
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