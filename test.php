<?php
require('Code3.php');
include('Code2.php');

$text = (isset($_POST['AddIDTH'])? $_POST['AddIDTH']:"");
$userId = "U7fb3dc484426fb164c424df09b7a42ba";

if(!empty($text)){

    $Language = GetLanguage($userId);
    if($Language != null)
    {
        $arr = SendUserID();
        $iCount = count($arr);
        for ($i = 0; $i<$iCount; $i++) {
            $asdf = ($arr[$i]);
        }


        $a = $asdf;
    }


}

exit();
?>