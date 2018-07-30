<?php 

function Login(){
    return "login";
}
function Regis(){
    return "regis";
}
function UpLanguage($Language){
    setcookie("Language",$Language,time()+3600); // Expire 1 Hour
    setcookie("GotoUplanguage");
}
function SetLeaveType($LeaveType){
    setcookie("LeaveType",$LeaveType,time()+3600); // Expire 1 Hour
    setcookie("GoLeaveType");
    $GotoRemark = "GotoRemark";
    setcookie("GotoRemark",$GotoRemark,time()+3600); // Expire 1 Hour
}
function SetRemark($Remark){
    $LeaveType = $_COOKIE["LeaveType"];
    $url = "http://localhost:4040/LineAPI/LineLeaveRequest/".$LeaveType.",".$Remark;
    setcookie("GotoRemark");
    setcookie("LeaveType");
    return $url;
}

function UrlLeave($LineID,$text){
    $url = "http://localhost:4040/LineAPI/Leave?LineID=".$LineID;
    //$url = "http://thanapathcm.prosoft.co.th/api/Leave?LeaveType=".$text."&LineID=".$LineID;
    header('location:' .$url);
    //$open = json_decode(file_get_contents($url), true);
    exit();
    //return $open;
}
function UrlRemark($text,$LineID){
    
        $url = "http://localhost:56232/api/Remark?Remark=".$text."&LineID=".$LineID;
        $open = json_decode(file_get_contents($url), true);

        return $open;
}

function UrlLeaves($text,$LineID){
    
        $url = "http://localhost:4040/api/TestHi/U7fb3dc484426fb164c424df09b7a42ba";
        $open = json_decode(file_get_contents($url), true);

        return $open;
}
function UrlLeavess($LineID,$text){
    
        $url = "http://localhost:4040/api/TestHello/".$text;
        $open = json_decode(file_get_contents($url), true);

        return $open;
}
function CheckLanguage($LineID,$Language){
    if($Language == "ภาษาไทย" || $Language == "Thai" || $Language == "English"){
        if($Language == "ภาษาไทย" || $Language == "Thai"){
            $ReLang = "th-TH";
        }elseif($Language == "English"){
            $ReLang = "eu-US";
        }
        $_str = $LineID.",".$ReLang;
        $url = "http://localhost:4040/api/SetLanguage/".$_str;
        //$url = "http://localhost:4040/LineAPI/LineSetting/".$_str;
        //header('location:' .$url);
        $open = json_decode(file_get_contents($url), true);
        return $open;
        //exit();
    }
    else
    {
        return "เลือกภาษาไม่ถูกต้อง";
    }
}

?>