<?php 
function CheckLineID($LineID){
    
    $url = "http://thanapathcm.prosoft.co.th/api/CheckID/".$LineID;
    $open = json_decode(file_get_contents($url), true);
    
    return $open;
}

function SelectLanguage($LineID){
    
    $url = "http://thanapathcm.prosoft.co.th/api/SelectLanguage/".$LineID;
    $open = json_decode(file_get_contents($url), true);
    
    return $open;
}

function CheckActive($LineID){
    
    $url = "http://thanapathcm.prosoft.co.th/api/CheckActive/".$LineID;
    $open = json_decode(file_get_contents($url), true);
    
    return $open;
}

function Leaveinformation($LineID,$Status){
    
    $url = "http://lineservice.prosofthcm.com/api/LeaveinformationAPI/".$LineID."/".$Status;
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
            $sum = $sum."----------------------------------------------\n";
        }
    }else{
        return "ไม่มีพบข้อมูล";
    }
    return $sum;
}

function LeaveDayNum($LineID,$LeavetypeCode){
    
    $url = "http://lineservice.prosofthcm.com/api/LeaveNumAPI/".$LineID."/".$LeavetypeCode;
    $open = json_decode(file_get_contents($url), true);
    $sum = "ข้อมูลจำนวนวันลาคงเหลือ\n----------------------------------------------\n";
    if($open != null){
        foreach($open as $text){
            $sum = $sum."ประเภทการลา : ".$text['LeaveTypeName']."\n";
            $sum = $sum."จำนวนวันอนุญาต: ".$text['LeaveTypeDayNum']."\n";
            $sum = $sum."จำนวนวันลา : ".$text['Days']."\n";
            $day = $text['LeaveTypeDayNum']-$text['Days'];
            $sum = $sum."วันลาคงเหลือ : ".$day."\n";
            $sum = $sum."----------------------------------------------\n";
        }
    }else{
        return "ไม่พบข้อมูล";
    }
        
    return $sum;
}

?>