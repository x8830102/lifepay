<?php
require_once('Connections/lp.php');mysql_query("set names utf8");
header('Content-Type: application/json; charset=UTF-8');

$e_name = $_POST['ee'];
$accont =$_POST['aa'];
$st_dis =$_POST['ss'];
$re_pas =$_POST['re'];

$ok =$_POST['qq'];



if($ok == '1'){
	mysql_select_db($database_lp, $lp);
	$modify_del = sprintf("DELETE FROM lf_user WHERE accont ='$accont'");
	mysql_query($modify_del, $lp) or die(mysql_error());
}


//存確認後的值
mysql_select_db($database_lp, $lp);
$update_up = sprintf("UPDATE lf_user SET password='$st_dis',password2='$re_pas' WHERE accont='$accont'");
$update = mysql_query($update_up, $lp) or die(mysql_error());

?>