<?php
header("Content-type: text/plain");
header("Content-Disposition: attachment; filename=syncit.reg");

$reg_client = str_replace ("reg.php","client.php",($HTTP_SERVER_VARS["REQUEST_URI"])); 
$reg_root = $_SERVER['SERVER_NAME'];
$reginfo = 'Windows Registry Editor Version 5.00

[HKEY_CURRENT_USER\Software\SyncIT\BookmarkSync]
"LastEmail"="Enter_Your_Email"
"root"="http://'.$reg_root.'/"
"file3"="'.$reg_client.'"
"LastMD5"=""
';
echo $reginfo
?>