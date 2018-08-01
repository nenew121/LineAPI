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
    
    foreach($open as $text){
        array_push($arr,("วันที่ลา :".$text['DocuDate']."\n ประเภทการลา :".$text['LeaveTypeName']."\n จำนวนวันลา : 1"."\n สาเหตุการลา :".$text['LeaveRemark']));
        array_push($arr,"\n--------------");
    }
    return $arr;
}

?>