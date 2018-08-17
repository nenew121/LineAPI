<?php
// กรณีต้องการตรวจสอบการแจ้ง error ให้เปิด 3 บรรทัดล่างนี้ให้ทำงาน กรณีไม่ ให้ comment ปิดไป
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 
// include composer autoload
require_once 'vendor/autoload.php';
// กรณีมีการเชื่อมต่อกับฐานข้อมูล
//require_once("dbconnect.php");
 
// import the Intervention Image Manager Class
use Intervention\Image\ImageManager;  
 
// ทดสอบแสดงค่า ตัวแปร $_GET ที่ส่งผ่าน url
echo "<pre>";
print_r($_GET);

if(isset($_GET['file']) && $_GET['file']!=""){
    $picFile = trim($_GET['file']);
    $originalFilePath = 'img/'; // แก้ไขเป็นโฟลเดอร์รูปต้นฉบับ
    $fullFilePath = $originalFilePath.$picFile;
    $fullFilePathJPG = $fullFilePath.'.jpg';
    $fullFilePathPNG = $fullFilePath.'.png';
    $fullFile = '';
    $picType = '';
    if(file_exists($fullFilePathJPG)){
        print_r("case1 ok");
    }
    if(file_exists($fullFilePathPNG)){ 
        print_r("case2 ok");
    }   
    if($picType==''){
        print_r("case3 ok");
        exit;
    }
}
?>