<?php
require_once('Connections/lp.php');mysql_query("set names utf8");
header('Content-Type: application/json; charset=UTF-8');
/* -------------------------- */
/* Check username & password  */
/* -------------------------- */
sleep(1); 
session_start();
if ($_SESSION['MM_Username'] == ""){header(sprintf("Location: store_login.php"));exit;
}else{
  $m_username = $_SESSION['mem'];
  $m_nick = $_SESSION['nick'];
  $number = $_SESSION['number'];
}

$userid = isset($_POST["user_name"]) ? $_POST["user_name"] : $_GET["user_name"]; 
$password = isset($_POST["user_password"]) ? $_POST["user_password"] : $_GET["user_password"]; 

mysql_select_db($database_lp, $lp);
$sql = "SELECT * FROM lf_user WHERE number ='$number' && accont = '$userid' && password='$password'";
$result = mysql_query($sql) or die(mysql_error());
$row = mysql_fetch_assoc($result);
$record_count = mysql_num_rows($result);

if($record_count<1){
	//無資料回傳no data
    echo 'no data';
}else{
	//若有這筆資料則回傳success
    $_SESSION['mem'] = $userid; //SESSION替換使用者
    echo 'success';
    //echo $row['accont'];    //for debug use
} 
?>