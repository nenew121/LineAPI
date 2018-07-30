<?php 
ob_start();
setcookie("EmpCode1","1",time()+3600);

if(isset($_GET['EmpCode'])? $_GET['EmpCode']:""){
    //ob_start();
        $EmpCode = (isset($_GET['EmpCode'])? $_GET['EmpCode']:"");
        setcookie("EmpCode1",$EmpCode,time()+3600); // Expire 1 Hour
        $Code = $_COOKIE["EmpCode1"];
        
        $ee = explode(',', $Code);
        $Resul = $ee[0];
        $Token = $ee[1];

        $sum = $Resul.$Token;

        //$a = 1;
        //$url = "http://localhost:1337/ess-linebot/test.php?Code=".$a;
        //file_get_contents($url);
    //ob_end_flush();
    //return true;
}

function aaaa(){
    $Code = $_COOKIE["EmpCode1"];
    return $Code."Hi";
}
ob_end_flush();
exit();
?>