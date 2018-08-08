<?php 
include('vendor/autoload.php');
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
//use LINE\LINEBot\Event;
//use LINE\LINEBot\Event\BaseEvent;
//use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\LocationMessageBuilder;
use LINE\LINEBot\MessageBuilder\AudioMessageBuilder;
use LINE\LINEBot\MessageBuilder\VideoMessageBuilder;
use LINE\LINEBot\ImagemapActionBuilder;
use LINE\LINEBot\ImagemapActionBuilder\AreaBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder ;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder;
use LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;
use LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\DatetimePickerTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselColumnTemplateBuilder;

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

                if ($event['type'] == 'message' && $event['message']['type'] == 'image') {
                    $this->isImage = true;
                }

                if ($event['type'] == 'message' && $event['message']['type'] == 'sticker') {
                    $this->isSticker = true;
                }
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
            New UriTemplateActionBuilder("Thai", "http://lineservice.prosofthcm.com/LineService/Language/Language/".$LineID."/th-TH"),
            New UriTemplateActionBuilder("English", "http://lineservice.prosofthcm.com/LineService/Language/Language/".$LineID."/en-US")
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
        New UriTemplateActionBuilder("ลงทะเบียน", "http://lineservice.prosofthcm.com/LineService/Register/RegisterInfo/".$LineID),
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
        New UriTemplateActionBuilder("Leave Record", "http://lineservice.prosofthcm.com/LineService/Leave/LeaveInfo/".$LineID),
        New UriTemplateActionBuilder("Approve Request", "http://lineservice.prosofthcm.com/LineService/ApproveRequest/ApproveRequestInfo/".$LineID),
        New UriTemplateActionBuilder("Approve Abstain", "http://lineservice.prosofthcm.com/LineService/ApproveRequestAbstain/ApproveAbstainlnfo/".$LineID),
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
        New UriTemplateActionBuilder("Abstain Time Stamp", "http://lineservice.prosofthcm.com/LineService/AbstainTimeStamp/AbstainInfo/".$LineID),
        New UriTemplateActionBuilder("Leave Information", "http://lineservice.prosofthcm.com/LineService/Leave/LeaveInformation/".$LineID),
        New MessageTemplateActionBuilder("Leave Remain", "Leave Remain"),
        New UriTemplateActionBuilder("Time Stamp", "http://lineservice.prosofthcm.com/LineService/Location/LocationInfo/".$LineID)
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
        New UriTemplateActionBuilder("Register", "http://lineservice.prosofthcm.com/LineService/Register/RegisterInfo/".$LineID),
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

public function SendLeaveType($replyToken = null)
{
        $actions = array(
            new MessageTemplateActionBuilder("ลาป่วย", "ลาป่วย"),
            new MessageTemplateActionBuilder("ลากิจ", "ลากิจ"),
            new MessageTemplateActionBuilder("ลาพักร้อน", "ลาพักร้อน"),
            new MessageTemplateActionBuilder("ยกเลิก", "ยกเลิก"),
        );

    $img_url = "https://www.prosofthcm.com/upload/5934/tIn6U0zMf6.jpg";
    $button = new ButtonTemplateBuilder("ประเภทการลา","เลือกประเภทการลา", $img_url, $actions);
    $outputText = new TemplateMessageBuilder("Type Approved", $button);
    $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
      'replyToken' => $replyToken,
      'messages'   => $outputText->buildMessage(),
  ]);
}

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

public function pho($replyToken = null)
{
$outputText = new ImageMessageBuilder("https://lineservice.prosofthcm.com/upload/Resource/Linebot.png", "https://lineservice.prosofthcm.com/upload/Resource/Linebot.png");
$this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
    'replyToken' => $replyToken,
    'messages'   => $outputText->buildMessage(),
]);
//$response = $bot->replyMessage($event->getReplyToken(), $outputText);
}

public function pho123($replyToken = null)
{
    $imageMapUrl = 'https://lineservice.prosofthcm.com/upload/Resource/Linebot.png';
    $replyData = new ImagemapMessageBuilder(
        $imageMapUrl,
        'This is Title',
        new BaseSizeBuilder(1040,700),
        array(
            new ImagemapMessageActionBuilder(
                'test image map',
                new AreaBuilder(0,0,347,699)
                ),
            new ImagemapUriActionBuilder(
                'http://www.ninenik.com',
                new AreaBuilder(693,0,347,699)
                )
        ));

        $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
            'replyToken' => $replyToken,
            'messages'   => $replyData->buildMessage(),
        ]);
}

public function pho1234($replyToken = null)
{
    $arr = array(new ImageCarouselColumnTemplateBuilder('https://lineservice.prosofthcm.com/upload/Resource/Linebot.png',
    new UriTemplateActionBuilder(
        'Uri Template', // ข้อความแสดงในปุ่ม
        'https://www.google.co.th'
    )
),
new ImageCarouselColumnTemplateBuilder(
    'https://lineservice.prosofthcm.com/upload/Resource/Linebot.png',
    new UriTemplateActionBuilder(
        'Uri Template', // ข้อความแสดงในปุ่ม
        'https://www.google.co.th'
    )
)                                       
    );
    $su = new ImageCarouselTemplateBuilder($arr);
    $replyData = new TemplateMessageBuilder('Image Carousel', $su);
    $this->response = $this->httpClient->post($this->endpointBase . '/v2/bot/message/reply', [
        'replyToken' => $replyToken,
        'messages'   => $replyData->buildMessage(),
    ]);
}


}
?>