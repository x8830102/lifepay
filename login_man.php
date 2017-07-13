<?php
require_once('Connections/lp.php');mysql_query("set names utf8");
header('Content-Type: application/json; charset=UTF-8');
/* -------------------------- */
/* Check username & password  */
/* -------------------------- */
sleep(1); 
session_start();

$userid = isset($_POST["user_name"]) ? $_POST["user_name"] : $_GET["user_name"]; 
$password = isset($_POST["user_password"]) ? $_POST["user_password"] : $_GET["user_password"]; 

mysql_select_db($database_lp, $lp);
$sql = "SELECT * FROM manage WHERE accont = '$userid' && password='$password'";
$result = mysql_query($sql) or die(mysql_error());
$row = mysql_fetch_assoc($result);
$record_count = mysql_num_rows($result);
$ID = $row['ID'];
$number = $row['number'];

if($record_count<1){
	//無資料回傳no data
    echo 'no data';
}else{
	//若有這筆資料則回傳success
    $_SESSION['MM_Username'] = $ID;
    $_SESSION['number'] = $number;
    echo 'success';
    //echo $row['accont'];    //for debug use
} 
?>