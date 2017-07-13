<?php
require_once('Connections/lp.php');mysql_query("set names utf8");
header('Content-Type: application/json; charset=UTF-8');

$ex = $_POST['ex'];
$complete_id = $_POST['complete_id'];

//存確認後的值
mysql_select_db($database_lp, $lp);
$query_stcomplete = sprintf("UPDATE complete SET note='$ex' WHERE ID = '$complete_id'");
$Restcomplete = mysql_query($query_stcomplete, $lp) or die(mysql_error());
?>