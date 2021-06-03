<?php
if ($_SERVER["REQUEST_METHOD"]=="POST"){
  $user=$_POST['user'];
  $pswd=$_POST['pswd'];
  $login=True;
}
$ip = $_SERVER['REMOTE_ADDR'];

exec("arp -a",$array);
foreach($array as $value){
  if(strpos($value,$_SERVER["REMOTE_ADDR"]) && preg_match("/(:?[0-9A-F]{2}[:-]){5}[0-9A-F]{2}/i",$value,$mac_array)){
    $mac = $mac_array[0];
    break;
  }
}
$conn = new mysqli("localhost","root","toor","portal");
if(mysqli_connect_error($conn)){
  die("connect error".mysqli_connect_error());
}
$sql = "SELECT * FROM portal WHERE MAC='[".$mac."]';";
$result = $conn->query($sql);
if($result->num_rows>0){
  exec("sudo iptables -I portal 1 -t mangle -m mac --mac-source ".$mac." -j RETURN");
  $conn->close();
  exit;
}
else{
  if($login==TRUE){
    $sql="insert into portal (user,pswd,mac) values('".$user."','".$pswd."','[".$mac."]');";
    if(($conn->query($sql))==TRUE){
      exec("sudo iptables -I portal 1 -t mangle -m mac --mac-source ".$mac." -j RETURN");
      $conn->close();
      exit;
    }
    else{
      echo "Database Error".mysqli_error();
    }
  }
  else{
    echo "Head to Login Page";
    $conn->close();
    header('Location: login.php');
    exit;
  }
}
?>
