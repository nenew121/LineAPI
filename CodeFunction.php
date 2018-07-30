<?php 
function CheckLineID($LineID){
    //$url = "http://thanapathcm.prosoft.co.th/LineAPI/LineSetting/".$LineID;
    $url = "http://localhost:4040/LineAPI/CheckLineID/".$LineID;
    header('location:' .$url);
    //file_get_contents($url);
    //return $url;
    exit();
}
function UpdateLanguage($LineID,$Language){
    if($Language == "ภาษาไทย" || $Language == "English"){
        if($Language == "ภาษาไทย"){
            $ReLang = "th-TH";
        }elseif($Language == "English"){
            $ReLang = "eu-US";
        }
        $all = $LineID.",".$Language;
        //$url = "http://thanapathcm.prosoft.co.th/LineAPI/LineSetting/".$all;
        $url = "http://localhost:4040/LineAPI/LineSetting/".$all;
        header('location:' .$url);
        //file_get_contents($url);
        //return $url;
        exit();
    }
    else
    {
        return "เลือกภาษาไม่ถูกต้อง";
    }
}
function GetLevaType($LeaveType){
    setcookie("LeaveType",$LeaveType,time()+3600); // Expire 1 Hour
    $CLeaveType = $_COOKIE["LeaveType"];
    return $CLeaveType;
}
function GetRemark($Remark){
    setcookie("Remark",$Remark,time()+3600); // Expire 1 Hour
    $CRemark = $_COOKIE["Remark"];
    $CLeaveType = $_COOKIE["LeaveType"];
    $all = $LineID.",".$CLeaveType.",".$CRemark;
    $url = "http://localhost:4040/LineAPI/LineLeaveRequest/".$all;
    header('location:' .$url);
    //file_get_contents($url);
    //return $url;
    exit();
}

?>