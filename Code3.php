<?php 
include('vendor/autoload.php');
use \LINE\LINEBot;
use \LINE\LINEBot\HTTPClient;
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
//use LINE\LINEBot\Event;
//use LINE\LINEBot\Event\BaseEvent;
//use LINE\LINEBot\Event\MessageEvent;
use \LINE\LINEBot\MessageBuilder;
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use \LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use \LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use \LINE\LINEBot\MessageBuilder\LocationMessageBuilder;
use \LINE\LINEBot\MessageBuilder\AudioMessageBuilder;
use \LINE\LINEBot\MessageBuilder\VideoMessageBuilder;
use \LINE\LINEBot\ImagemapActionBuilder;
use \LINE\LINEBot\ImagemapActionBuilder\AreaBuilder;
use \LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder;
use \LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder;
use \LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;
use \LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use \LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use \LINE\LINEBot\TemplateActionBuilder;
use \LINE\LINEBot\TemplateActionBuilder\DatetimePickerTemplateActionBuilder;
use \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselTemplateBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselColumnTemplateBuilder;

class BOT_API extends LINEBot
{
/* ====================================================================================
     * Variable
     * ==================================================================================== */

    private $httpClient     = null;
    private $endpointBase   = null;
    private $channelSecret  = null;

    public $content         = null;
    public $events          = null;

    public $isEvents        = false;
    public $isText          = false;
    public $isImage         = false;
    public $isSticker       = false;
    public $isImagemap      = false;
    public $isMessage       = false;

    public $text            = null;
    public $replyToken      = null;
    public $source          = null;
    public $message         = null;
    public $timestamp       = null;

    public $response        = null;

    public $userId          = null;

    /* ====================================================================================
     * Custom
     * ==================================================================================== */
    const DEFAULT_ENDPOINT_BASE = 'https://api.line.me';

    public function __construct($channelSecret, $access_token)
    {
        $this->httpClient     = new CurlHTTPClient($access_token);
        $this->channelSecret  = $channelSecret;
        $this->endpointBase   = LINEBot::DEFAULT_ENDPOINT_BASE;

        $this->content        = file_get_contents('php://input');
        $events               = json_decode($this->content, true);

        if (!empty($events['events'])) {
            $this->isEvents = true;
            $this->events   = $events['events'];

            foreach ($events['events'] as $event) {
                $this->replyToken = $event['replyToken'];
                $this->source     = (object) $event['source'];
                $this->message    = (object) $event['message'];
                $this->timestamp  = $event['timestamp'];
                $this->userId     = $event['source']['userId'];
                if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
                    $this->isText = true;
                    $this->text   = $event['message']['text'];
                }

                /*if ($event['type'] == 'message' && $event['message']['type'] == 'image') {
                    $this->isImage = true;
                }

                if ($event['type'] == 'message' && $event['message']['type'] == 'sticker') {
                    $this->isSticker = true;
                }

                if ($event['type'] == 'message' && $event['message']['type'] == 'imagemap') {
                    $this->isImagemap = true;
                }

                if ($event['type'] == 'message' && $event['message']['type'] == 'message') {
                    $this->isMessage = true;
                }*/
            }
        }

        parent::__construct($this->httpClient, [ 'channelSecret' => $channelSecret ]);
    }

public function SendMessageTo($ToLineID = null, $message = null){
    $messageBuilder = new TextMessageBuilder($message);
    $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/push', [
        'to' => $ToLineID,
        // 'toChannel' => 'Channel ID,
        'messages'  => $messageBuilder->buildMessage()
    ]);
}

public function SendMessageApproveTo($ToLineID = null, $message = null){
    $messageBuilder = new TextMessageBuilder($message);
    $Temp = new TemplateMessageBuilder('Approve Center',
        new ConfirmTemplateBuilder(
                'Approve Center', // ข้อความแนะนำหรือบอกวิธีการ หรือคำอธิบาย
                array(
                    new UriTemplateActionBuilder(
                        'Yes', // ข้อความสำหรับปุ่มแรก
                        "https://lineservice.prosofthcm.com/LineService/ApproveRequest/ApproveRequestInfo/".$ToLineID // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                    ),
                    new MessageTemplateActionBuilder(
                        'No', // ข้อความสำหรับปุ่มแรก
                        'NO' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                    )
                )
            )
        );

    $multiMessage = new MultiMessageBuilder;
    $multiMessage->add($messageBuilder);
    $multiMessage->add($Temp);

    $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/push', [
        'to' => $ToLineID,
        // 'toChannel' => 'Channel ID,
        'messages'  => $multiMessage->buildMessage()
    ]);
}

public function replyMessageNew($replyToken = null, $message = null){
    $messageBuilder = new TextMessageBuilder($message);
    $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
        'replyToken' => $replyToken,
        'messages'   => $messageBuilder->buildMessage(),
    ]);
}

public function SendLanguage($replyToken = null, $LineID){
    $img_url = "https://www.prosofthcm.com/upload/5934/eo3hrcpDoM.png";

        $actions = array(
            New UriTemplateActionBuilder("Thai", "https://lineservice.prosofthcm.com/LineService/Language/Language/".$LineID."/th-TH"),
            New UriTemplateActionBuilder("English", "https://lineservice.prosofthcm.com/LineService/Language/Language/".$LineID."/en-US")
        );
        $button = new ButtonTemplateBuilder("Setting","กรุณาเลือกภาษาที่จะใช้\nPlease select a display language.", $img_url, $actions);
        $outputText = new TemplateMessageBuilder("Setting Language", $button);


    $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
      'replyToken' => $replyToken,
      'messages'   => $outputText->buildMessage(),
  ]);
}

public function Register($replyToken = null, $LineID){
    $actions = array(
        New UriTemplateActionBuilder("ลงทะเบียน", "https://lineservice.prosofthcm.com/LineService/Register/RegisterInfo/".$LineID),
        New MessageTemplateActionBuilder("ย้อนกลับ", "ย้อนกลับ")
    );
    $button  = new ConfirmTemplateBuilder("ลงทะเบียนใช้งาน\nYou have not yet registered" , $actions);
    $outputText = new TemplateMessageBuilder("ลงทะเบียนใช้งาน", $button);
    $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
          'replyToken' => $replyToken,
          'messages'   => $outputText->buildMessage(),
      ]);
}

public function ApproveCenter($replyToken = null,$LineID)
{
    $actions = array(
        New UriTemplateActionBuilder("Leave Record", "https://lineservice.prosofthcm.com/LineService/Leave/LeaveInfo/".$LineID),
        New UriTemplateActionBuilder("Approve Request", "https://lineservice.prosofthcm.com/LineService/ApproveRequest/ApproveRequestInfo/".$LineID),
        New UriTemplateActionBuilder("Approve Abstain", "https://lineservice.prosofthcm.com/LineService/ApproveRequestAbstain/ApproveAbstainlnfo/".$LineID),
        New MessageTemplateActionBuilder("Test", "Test")
         );

    $img_url = "https://www.prosofthcm.com/upload/5934/tIn6U0zMf6.jpg";
    $button  = new ButtonTemplateBuilder("Approve Center", "รายการ", $img_url, $actions);
    $outputText = new TemplateMessageBuilder("Approve Center", $button);

    $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
        'replyToken' => $replyToken,
        'messages'   => $outputText->buildMessage(),
    ]);
}

public function TimeAttendance($replyToken = null, $LineID)
{
    $actions = array(
        New UriTemplateActionBuilder("Abstain Time Stamp", "https://lineservice.prosofthcm.com/LineService/AbstainTimeStamp/AbstainInfo/".$LineID),
        New UriTemplateActionBuilder("Leave Information", "https://lineservice.prosofthcm.com/LineService/Leave/LeaveInformation/".$LineID),
        New MessageTemplateActionBuilder("Leave Remain", "Leave Remain"),
        New UriTemplateActionBuilder("Time Stamp", "https://lineservice.prosofthcm.com/LineService/Location/LocationInfo/".$LineID)
         );

    $img_url = "https://www.prosofthcm.com/upload/5934/5d1apZw0Oh.jpg";
    $button  = new ButtonTemplateBuilder("Time Attendence", "รายการ", $img_url, $actions);
    $outputText = new TemplateMessageBuilder("Time Attendence", $button);

    $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
        'replyToken' => $replyToken,
        'messages'   => $outputText->buildMessage(),
    ]);
}

public function Payroll($replyToken = null,$LineID)
{
    $actions = array(
        New MessageTemplateActionBuilder("E-Pay Slip", "E-Pay Slip"),
        New UriTemplateActionBuilder("Tax Calculator", "https://www.prosofthcm.com/Article/Detail/65472"),
        New UriTemplateActionBuilder("Google", "http://www.Google.co.th"),
        New MessageTemplateActionBuilder("Test", "Test")
         );

    $img_url = "https://www.prosofthcm.com/upload/5934/HDIVJszBfE.jpg";
    $button  = new ButtonTemplateBuilder("Payroll", "รายการ", $img_url, $actions);
    $outputText = new TemplateMessageBuilder("Payroll", $button);

    $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
        'replyToken' => $replyToken,
        'messages'   => $outputText->buildMessage(),
    ]);
}

public function Organization($replyToken = null)
{
    $actions = array(
        New MessageTemplateActionBuilder("Calendar", "Calendar"),
        New UriTemplateActionBuilder("HR News", "https://cherry-pie-82107.herokuapp.com/HR.php"),
        New MessageTemplateActionBuilder("Address", "Address"),
        New MessageTemplateActionBuilder("Phone No.", "Phone No.")
         );

    $img_url = "https://www.prosofthcm.com/upload/5934/HDIVJszBfE.jpg";
    $button  = new ButtonTemplateBuilder("Organization", "รายการ", $img_url, $actions);
    $outputText = new TemplateMessageBuilder("Organization", $button);

    $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
        'replyToken' => $replyToken,
        'messages'   => $outputText->buildMessage(),
    ]);
}

public function Setting($replyToken = null, $LineID)
{
    $actions = array(        
        New UriTemplateActionBuilder("Register", "https://lineservice.prosofthcm.com/LineService/Register/RegisterInfo/".$LineID),
        New MessageTemplateActionBuilder("Language", "Language"),
        New MessageTemplateActionBuilder("Choose Profile", "Choose Profile"),
        New MessageTemplateActionBuilder("Web Service URL", "Web Service URL")
         );

    $img_url = "https://www.prosofthcm.com/upload/5934/67m2YbOk6S.jpg";
    $button  = new ButtonTemplateBuilder("Setting", "รายการ", $img_url, $actions);
    $outputText = new TemplateMessageBuilder("Setting", $button);

    $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
        'replyToken' => $replyToken,
        'messages'   => $outputText->buildMessage(),
    ]);
}

public function AboutUs($replyToken = null)
{
    $actions = array(
        New UriTemplateActionBuilder("Redirect", "https://www.prosofthcm.com/"),
        New UriTemplateActionBuilder("Getlocation", "https://lineservice.prosofthcm.com/LineService/GetLocaltion/GetLocaltion"),
        New MessageTemplateActionBuilder("Test", "Test"),
        New MessageTemplateActionBuilder("Test", "Test")
         );

    $img_url = "https://www.prosofthcm.com/upload/5934/HDIVJszBfE.jpg";
    $button  = new ButtonTemplateBuilder("About Us", "รายการ", $img_url, $actions);
    $outputText = new TemplateMessageBuilder("About Us", $button);

    $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
        'replyToken' => $replyToken,
        'messages'   => $outputText->buildMessage(),
    ]);
}

/*
public function Leaveinformation($replyToken = null)
{
    $actions = array(
        New MessageTemplateActionBuilder("Wait Approve", "Wait Approve"),
        New MessageTemplateActionBuilder("Approved", "Approved"),
        New MessageTemplateActionBuilder("Not Approve", "Not Approve")
         );

    $img_url = "https://www.prosofthcm.com/upload/5934/5d1apZw0Oh.jpg";
    $button  = new ButtonTemplateBuilder("Leave information", "รายการ", $img_url, $actions);
    $outputText = new TemplateMessageBuilder("Leave information", $button);

    $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
        'replyToken' => $replyToken,
        'messages'   => $outputText->buildMessage(),
    ]);
}
*/

public function LeaveRemain($replyToken = null)
{
    $actions = array(
        New MessageTemplateActionBuilder("ลากิจ", "ลากิจ"),
        New MessageTemplateActionBuilder("ลาป่วย", "ลาป่วย"),
        New MessageTemplateActionBuilder("ลาพักร้อน", "ลาพักร้อน")
         );

    $img_url = "https://www.prosofthcm.com/upload/5934/5d1apZw0Oh.jpg";
    $button  = new ButtonTemplateBuilder("LeaveDayNum", "รายการ", $img_url, $actions);
    $outputText = new TemplateMessageBuilder("LeaveDayNum", $button);

    $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
        'replyToken' => $replyToken,
        'messages'   => $outputText->buildMessage(),
    ]);
}

public function photoQR($replyToken = null)
{
$outputText = new ImageMessageBuilder("https://lineservice.prosofthcm.com/upload/Resource/Linebot.png", "https://lineservice.prosofthcm.com/upload/Resource/Linebot.png");
$this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
    'replyToken' => $replyToken,
    'messages'   => $outputText->buildMessage(),
]);
//$response = $bot->replyMessage($event->getReplyToken(), $outputText);
}

public function BOT_New($replyToken = null, $text)
{
    $TEXT = substr($text, 0, 2);
    $textsub = substr($text, 2, 100);
    $split = explode(",", $textsub);
    switch($TEXT){
        case "Lo":
            $outputText = new LocationMessageBuilder("GetLocation",$split[0].",".$split[1],$split[0],$split[1]);
            $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
            'replyToken' => $replyToken,
            'messages'   => $outputText->buildMessage(),
            ]);
        break;
        case "Qr":
            $outputText = new ImageMessageBuilder("https://lineservice.prosofthcm.com/upload/Resource/Linebot.png", "https://lineservice.prosofthcm.com/upload/Resource/Linebot.png");
            $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
            'replyToken' => $replyToken,
            'messages'   => $outputText->buildMessage(),
            ]);
        break;
        case "P1":
        //$outputText = new ImageMessageBuilder("https://lineservice.prosofthcm.com/upload/Resource/imgtest.jpg", "https://lineservice.prosofthcm.com/upload/Resource/imgtest.jpg");
        $outputText = new ImageMessageBuilder("https://cherry-pie-82107.herokuapp.com/img/asdf.jpg", "https://cherry-pie-82107.herokuapp.com/img/asdf.jpg");
        $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
        'replyToken' => $replyToken,
        'messages'   => $outputText->buildMessage(),
        ]);
        break;
        case "St":
            $replyData = new StickerMessageBuilder("1","17");
            $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
            'replyToken' => $replyToken,
            'messages'   => $replyData->buildMessage(),
            ]);
        break;
        case "To":
            $messageBuilder = new TextMessageBuilder($split[1]);
            $StickerBuilder = new StickerMessageBuilder($split[2],$split[3]);
            $multiMessage = new MultiMessageBuilder;
            $multiMessage->add($messageBuilder);
            $multiMessage->add($StickerBuilder);
            $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/push', [
            'to' => $split[0],
            'messages'   => $multiMessage->buildMessage(),
            ]);
        break;
        case "Im":
            $base = new BaseSizeBuilder(460,460);
            $arr = array(
                new ImagemapMessageActionBuilder("tt", new AreaBuilder(0,0,10,10)),
                new ImagemapMessageActionBuilder("ttt", new AreaBuilder(0,0,10,10))
            );
            $replyData = new ImagemapMessageBuilder("https://cherry-pie-82107.herokuapp.com/img/asdf.jpg","test",$base,$arr);
            $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
            'replyToken' => $replyToken,
            'messages'   => $replyData->buildMessage(),
        ]);
        break;
        case "T1":
            $imageMapUrl = "https://lineservice.prosofthcm.com/upload/Resource/imgtest.jpg";
            $base = new BaseSizeBuilder(698,1039);
            $imgmap1 = array();
            $imgmap = array(
                new ImagemapMessageActionBuilder("Test", new AreaBuilder(0,0,35,69)),
                new ImagemapMessageActionBuilder("Test", new AreaBuilder(68,0,35,69))
            );
            $replyData = new ImagemapMessageBuilder($imageMapUrl,"Imgmap",$base,$imgmap);
            $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
            'replyToken' => $replyToken,
            'messages'   => $replyData->buildMessage(),
            'imagemap'   => $imgmap1->buildImagemapAction(),
        ]);
        break;
        case "Ur":
        $imageMapUrl = "https://lineservice.prosofthcm.com/upload/Resource/imgtest.jpg";
        $base = new BaseSizeBuilder(698,1039);
        $imgmap = array();
        $imgmap1 = array(
            new ImagemapMessageActionBuilder("Test", new AreaBuilder(0,0,35,69)),
            new ImagemapMessageActionBuilder("Test", new AreaBuilder(68,0,35,69))
        );
        $replyData = new UriTemplateActionBuilder("Imgmap","https://lineservice.prosofthcm.com/upload/Resource/imgtest.jpg");
        $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
        'replyToken' => $replyToken,
        'messages'   => $replyData->buildTemplateAction(),
        ]);
        break;
        default:
            $messageBuilder = new TextMessageBuilder("ไม่มีคำสั่ง ".$text." นี้");
            //$StickerBuilder = new StickerMessageBuilder("1","7");
            //$StickerBuilder = new StickerMessageBuilder("2","527");
            $StickerBuilder = new StickerMessageBuilder("2","159");
            //$StickerBuilder = new StickerMessageBuilder("1","109");
            $multiMessage = new MultiMessageBuilder;
            $multiMessage->add($messageBuilder);
            $multiMessage->add($StickerBuilder);
            $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
            'replyToken' => $replyToken,
            'messages'   => $multiMessage->buildMessage(),
            ]);
        break;
    }
}

/*
public function Leavere($replyToken = null, $text)
{
    $ar = [];
    foreach($text as $out){
        $actionBuilder = array(
            new MessageTemplateActionBuilder(
                'อนุมัติ',// ข้อความแสดงในปุ่ม
                'อนุมัติ'.$out['Docuno'] // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
            ),
            new MessageTemplateActionBuilder(
                'ไม่อนุมัติ',// ข้อความแสดงในปุ่ม
                'ไม่อนุมัติ'.$out['Docuno'] // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
            ),
            new MessageTemplateActionBuilder(
                'ย้อนกลับ',// ข้อความแสดงในปุ่ม
                'ย้อนกลับ' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
            ),     
        );
        array_push($ar,new CarouselColumnTemplateBuilder($out['Docuno'],"วันที่ขอลา : ".$out['DocuDate']."\nประเภทการลา : ".$out['LeaveTypeName'],'https://www.prosofthcm.com/upload/5934/5d1apZw0Oh.jpg',$actionBuilder)); 
    }
    $caro =  new CarouselTemplateBuilder($ar);
$replyData = new TemplateMessageBuilder('Carousel', $caro);
$this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
'replyToken' => $replyToken,
'messages'   => $replyData->buildMessage(),
]);
}*/

/*
case "T1":
            $asd = new AreaBuilder(0,0,50,50);
            $az = new ImagemapUriActionBuilder("https://lineservice.prosofthcm.com/upload/Resource/img.png",$asd);

            $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
            'replyToken' => $replyToken,
            'messages'   => $az->buildImagemapAction(),
            ]);
        break;
        case "T2":
        $asd = new AreaBuilder(0,0,50,50);
        $az = new ImagemapUriActionBuilder("https://lineservice.prosofthcm.com/upload/Resource/img.png",$asd);

        $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
        'replyToken' => $replyToken,
        'messages'   => $az->buildMessage(),
        ]);
        break;
        case "T3":
        $imageMapUrl = "https://lineservice.prosofthcm.com/upload/Resource/img.png";
        $base = new BaseSizeBuilder(699,1040);
        $imgmap = array(
            new ImagemapMessageActionBuilder("Test", new AreaBuilder(0,0,355,699)),
            new ImagemapMessageActionBuilder("Test", new AreaBuilder(686,0,354,699))
        );
        $replyData = new ImagemapMessageBuilder($imageMapUrl,"Imgmap",$base,$imgmap);

        $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
        'replyToken' => $replyToken,
        'messages'   => $replyData->buildImagemapAction(),
        ]);
        break;
        case "T4":
        $imageMapUrl = "https://lineservice.prosofthcm.com/upload/Resource/imgtest.png";
        $base = new BaseSizeBuilder(699,1040);
        $imgmap = array(
            new ImagemapMessageActionBuilder("Test", new AreaBuilder(0,0,355,699)),
            new ImagemapMessageActionBuilder("Test", new AreaBuilder(686,0,354,699))
        );
        $replyData = new ImagemapMessageBuilder($imageMapUrl,"Imgmap",$base,$imgmap);

        $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
        'replyToken' => $replyToken,
        'messages'   => $replyData->buildMessage(),
        ]);
        break;
        case "T5":
        $imageMapUrl = "https://lineservice.prosofthcm.com/upload/Resource/img.png";
        $base = new BaseSizeBuilder(70,104);
        $imgmap = array(
            new ImagemapMessageActionBuilder("Test", new AreaBuilder(0,0,35,6)),
            new ImagemapMessageActionBuilder("Test", new AreaBuilder(68,0,35,69))
        );
        $replyData = new ImagemapMessageBuilder($imageMapUrl,"Imgmap",$base,$imgmap);

        $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
        'replyToken' => $replyToken,
        'messages'   => $replyData->buildImagemapAction(),
        ]);
        break;
        
        case "T7":
        $columns = array();
        $img_url = "https://www.prosofthcm.com/upload/5934/eo3hrcpDoM.png";
            $actions = array(
          new MessageTemplateActionBuilder("ภาษาไทย", "TH"),
          new MessageTemplateActionBuilder("English", "ENG"),
          new MessageTemplateActionBuilder("ยกเลิก(Cancel)", "Cancel"),
        );
            $column = new CarouselColumnTemplateBuilder("Language", "กรุณาเลือกภาษาทต้องการเปลี่ยน\nPlease select a display language.", $img_url, $actions);
            $columns[] = $column;
        $carousel = new CarouselTemplateBuilder($columns);
        $outputText = new TemplateMessageBuilder("Setting Language", $carousel);
        $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
                'replyToken' => $replyToken,
                'messages'   => $outputText->buildMessage(),
            ]);
        break;
        case "T8":
        $img_url = "https://www.prosofthcm.com/upload/5934/eo3hrcpDoM.png";
        $actions = array(
          new MessageTemplateActionBuilder("ภาษาไทย", "TH"),
          new MessageTemplateActionBuilder("English", "ENG"),
          new MessageTemplateActionBuilder("ยกเลิก(Cancel)", "Cancel"),
        );
        $columns = array(
            new CarouselColumnTemplateBuilder("Language", "กรุณาเลือกภาษาทต้องการเปลี่ยน\nPlease select a display language.", $img_url, $actions),
            new CarouselColumnTemplateBuilder("Language", "กรุณาเลือกภาษาทต้องการเปลี่ยน\nPlease select a display language.", $img_url, $actions)
        );
        $carousel = new CarouselTemplateBuilder($columns);
        $outputText = new TemplateMessageBuilder("Setting Language", $carousel);
        $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
                'replyToken' => $replyToken,
                'messages'   => $outputText->buildMessage(),
            ]);
        break;
        case "T9":
        $as = new UriTemplateActionBuilder("Uri Template","https://lineservice.prosofthcm.com/upload/Resource/Leave.png");
        $columns = array(
          new ImageCarouselColumnTemplateBuilder("https://lineservice.prosofthcm.com/upload/Resource/imgtest.png", $as),
          new ImageCarouselColumnTemplateBuilder("https://lineservice.prosofthcm.com/upload/Resource/imgtest.png", $as),
          new ImageCarouselColumnTemplateBuilder("https://lineservice.prosofthcm.com/upload/Resource/imgtest.png", $as)
        );
        $carousel = new ImageCarouselTemplateBuilder($columns);
        $outputText = new TemplateMessageBuilder("Setting Language", $carousel);
        $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
                'replyToken' => $replyToken,
                'messages'   => $outputText->buildMessage(),
            ]);
        break;
*/

public function Sticker($replyToken = null)
{
    $sti = new StickerMessageBuilder("1","17");

        $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
            'replyToken' => $replyToken,
            'messages'   => $replyData->buildMessage(),
        ]);
}

public function LocationMessage($replyToken = null, $text)
{
    $split = explode(",", $text); 
    if($split[1] != null){
        $outputText = new LocationMessageBuilder("GetLocation",$split[0].",".$split[1],$split[0],$split[1]);
        $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
            'replyToken' => $replyToken,
            'messages'   => $outputText->buildMessage(),
        ]);
    }
    else
    {
        $messageBuilder = new TextMessageBuilder($text);
        $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
            'replyToken' => $replyToken,
            'messages'   => $messageBuilder->buildMessage(),
        ]);
    }
}

public function pho123($replyToken = null)
{
    $imageMapUrl = "https://lineservice.prosofthcm.com/upload/Resource/img.png";
    $base = new BaseSizeBuilder(699,1040);
    $imgmap = array(
        new ImagemapMessageActionBuilder("Test", new AreaBuilder(0,0,355,699)),
        new ImagemapMessageActionBuilder("Test", new AreaBuilder(686,0,354,699))
    );
    $replyData = new ImagemapMessageBuilder($imageMapUrl,"Imgmap",$base,$imgmap);

    $test1 = new LocationMessageBuilder("TESTTi","SSS","12.12","21.21");

    $asd = new AreaBuilder(0,0,355,699);
    $az = new ImagemapUriActionBuilder("https://lineservice.prosofthcm.com/upload/Resource/img.png",$asd);

    $sti = new StickerMessageBuilder("1","2563");

        $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
            'replyToken' => $replyToken,
            'messages'   => $replyData->buildMessage(),
        ]);
}

public function pho1234($replyToken = null)
{

    $temp = new UriTemplateActionBuilder("Uri","https://www.google.co.th");
    $actions = array(
        new ImageCarouselColumnTemplateBuilder("https://www.prosofthcm.com/upload/5934/5d1apZw0Oh.jpg",$temp),
        new ImageCarouselColumnTemplateBuilder("https://www.prosofthcm.com/upload/5934/5d1apZw0Oh.jpg",$temp)
    );
    $button = new ImageCarouselTemplateBuilder($actions);
    $outputText = new TemplateMessageBuilder("ImageCarousel", $button);
    $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
        'replyToken' => $replyToken,
        'messages'   => $replyData->buildMessage(),
    ]);
}


}
?>