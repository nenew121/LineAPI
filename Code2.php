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
    $arr = [];
    $sum = "";
    if($open != null){
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

?>