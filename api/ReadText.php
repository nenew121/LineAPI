<!DOCTYPE html>
<html>
<body>

<?php
$myfile = fopen("C:/Users/com/Dropbox/แอพ/Heroku/cherry-pie-82107/ess-linebot/Text.txt", "r") or die("Unable to open file!");
$URL = fgets($myfile).'/aip/EmpID?OrgID=12412124s124___'.date("Y-m-d H:i:s");
echo $URL;


fclose($myfile);
?>

</body>
</html>