<?php
require_once('Connections/lp.php');mysql_query("set names utf8");
header('Content-Type: application/json; charset=UTF-8');

$ok = $_POST['ok'];
$data = $_POST['date'];
$p_nick = $_POST['p_nick'];
mysql_select_db($database_lp, $lp);
$query_stcomplete = sprintf("UPDATE complete SET confirm ='$ok' WHERE p_nick = '$p_nick' && data = '$data'");
$Restcomplete = mysql_query($query_stcomplete, $lp) or die(mysql_error());

?>