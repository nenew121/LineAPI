<?php 
function ConnectDatabase()
{

    $connectstr_dbhost = '31.170.166.134';
    $connectstr_dbname = 'u663869224_line';
    $connectstr_dbusername = 'u663869224_hrmi';
    $connectstr_dbpassword = 'v06dt22ssn';

    $link = mysqli_connect($connectstr_dbhost, $connectstr_dbusername, $connectstr_dbpassword, $connectstr_dbname);

    if (!$link)
    {
        echo "Error: Unable to connect to MySQL." . PHP_EOL;
        echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }
    mysqli_set_charset($link, "utf8");

    return $link;
}

//**เอาไว้ให้ HR ส่งข่าววว*//
/*function NEWS($news)
{
    $link = ConnectDatabase();
    $sql = "SELECT * FROM news WHERE newsid = '".$news."'  AND IsDelete = 0";
    $result = mysqli_query($link, $sql);
    if (mysqli_num_rows($result) > 0) 
    {
      $str = "";
      while($row = mysqli_fetch_assoc($result)) 
      {
          $str.="".$row['newsHD']."\n".$row['newsDT']."";
      }
      return $str;
  }
  return "ยังไม่มีข่าวสารใดๆอัพเดท";
  $link->close();
}
*/

function GetLanguage($LineID){
    
    $url = "https://lineservice.prosofthcm.com/api/LanguageAPI/".$LineID;
    $open = json_decode(file_get_contents($url), true);
    
    return $open;
}

/*
function Leaveinformation($LineID,$Status){
    
    $url = "https://lineservice.prosofthcm.com/api/LeaveinformationAPI/".$LineID."/".$Status;
    $open = json_decode(file_get_contents($url), true);
    $sum = "ข้อมูลการ".$t."ลาล่าสุด 10 รายการ\n----------------------------------------------\n";
    $t =  "";
    if($open != null){
        if($Status = "W"){
            $t = "รออนุมัติ";
        }elseif($Status = "Y"){
            $t = "อนุมัติ";
        }elseif($Status = "N"){
            $t = "ไม่อนุมัติ";
        }
        foreach($open as $text){
            $sum = $sum."วันที่ลา : ".$text['DocuDate']."\n";
            $sum = $sum."ประเภทการลา : ".$text['LeaveTypeName']."\n";
            $sum = $sum."จำนวนวันลา : 1"."\n";
            $sum = $sum."สาเหตุการลา : ".$text['LeaveRemark']."\n";
            $sum = $sum."----------------------------------------------";
        }
    }else{
        return "ไม่มีพบข้อมูล";
    }
    return $sum;
}
*/

function LeaveRemainNum($LineID){
    
    $url = "https://lineservice.prosofthcm.com/api/LeaveNumAPI/".$LineID;
    $open = json_decode(file_get_contents($url), true);
    $sum = "ข้อมูลจำนวนวันลาคงเหลือ\n------------------------------\n";
    if($open != null){
        foreach($open as $text){
            $sum = $sum."ประเภทการลา : ".$text['LeaveTypeName']."\n";
            $sum = $sum."วันอนุญาตลา : ".$text['LeaveTypeDayNum']."\n";
            $sum = $sum."จำนวนวันลา : ".$text['Days']."\n";
            $sum = $sum."วันลาคงเหลือ : ".$text['Hours']."\n";
            $sum = $sum."-----------------------------\n";
        }
    }else{
        return "ไม่พบข้อมูล";
    }
        
    return $sum;
}

function LeaveRemainNumEng($LineID){
    
    $url = "https://lineservice.prosofthcm.com/api/LeaveNumAPI/".$LineID;
    $open = json_decode(file_get_contents($url), true);
    $sum = "Leave Information Days Num\n-----------------------------\n";
    if($open != null){
        foreach($open as $text){
            $sum = $sum."Leave Type : ".$text['LeaveTypeName']."\n";
            $sum = $sum."Leave Approve : ".$text['LeaveTypeDayNum']."\n";
            $sum = $sum."Leave Record : ".$text['Days']."\n";
            $sum = $sum."Leave Days Num : ".$text['Hours']."\n";
            $sum = $sum."-----------------------------\n";
        }
    }else{
        return "No Data.";
    }
        
    return $sum;
}

function EPaySlip($LineID){
    
    $url = "https://lineservice.prosofthcm.com/api/EPaySlipAPI/".$LineID;
    $open = json_decode(file_get_contents($url), true);
    
    return $open;
}
/*
function SendUserID(){
    
    $url = "http://lineservice.prosofthcm.com/api/CountLineID";
    $open = json_decode(file_get_contents($url), true);
    
    return $open;
}
*/
function SendNewsTo($NewsHDID){
    
    $url = "https://lineservice.prosofthcm.com/Api/SendNewsToAPI/".$NewsHDID;
    $open = json_decode(file_get_contents($url), true);
    
    return $open;
}

function LocationOrganization($LineID){
    
    $url = "https://lineservice.prosofthcm.com/Api/LocationOrgAPI/".$LineID;
    $open = json_decode(file_get_contents($url), true);
    
    return $open;
}
/*
function LeaveRequest($LineID){
    
    $url = "http://lineservice.prosofthcm.com/api/ApproveRequestInfoAPI/".$LineID;
    $open = json_decode(file_get_contents($url), true);
    
    return $open;
}
*/

function Calender($LineID){
    $url = "https://lineservice.prosofthcm.com/APi/CalenderAPI/".$LineID;
    $open = json_decode(file_get_contents($url), true);
    $sum = "ปฏิทินวันหยุดองค์กร\n----------------------------------------------\n";
    if($open != null){
        foreach($open as $text){
            $sum = $sum.$text['countholiday'].": ".$text['nameday']." ".$text['numday']." ".$text['namemounth']." ".$text['year']."\n".$text['Subject']."\n";
        }
    }else{
        return "ไม่พบข้อมูล";
    }
    $sum = $sum."----------------------------------------------\n";
    return $sum;
}
?>