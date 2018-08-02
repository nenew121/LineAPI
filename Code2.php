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
            array_push($arr,("วันที่ลา :".$text['DocuDate']."\n"));
            array_push($arr,("ประเภทการลา :".$text['LeaveTypeName']."\n"));
            array_push($arr,("จำนวนวันลา : 1"."\n"));
            array_push($arr,("สาเหตุการลา :".$text['LeaveRemark']."\n"));
            array_push($arr,("----------------------------------------------\n"));
        }
        for($i=0;$i<count($arr);$i++){
            $sum = $sum.$arr[$i];
        }
    }else{
        return "ไม่มีพบข้อมูล";
    }
    return $sum;
}

?>