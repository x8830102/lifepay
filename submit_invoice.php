<?php
require_once('Connections/lp.php');mysql_query("set names utf8");
header('Content-Type: application/json; charset=UTF-8');

$ID = $_POST['ID'];
$accont = $_POST['accont'];
$nick = $_POST['nick'];
$number =$_POST['number'];
$usage_fee =$_POST['usage_fee'];
$c = $_POST['c'];
$g = $_POST['g'];
$q = $_POST['q'];
$count = $_POST['count'];
$discount = $_POST['discount'];
$total = $_POST['total'];
$spend = $_POST['spend'];

$date = date("Y-m-d");

//存確認後的值
mysql_select_db($database_lp, $lp);
$query_stcomplete = sprintf("UPDATE complete SET invoice='1' WHERE ID = '$ID'");
$Restcomplete = mysql_query($query_stcomplete, $lp) or die(mysql_error());

//請款
mysql_select_db($database_lp, $lp);
$query_invoice = "INSERT IGNORE INTO Invoice(accont,nick,number,total,discount,usage_fee,c,g,q,spend,count,date,idd)value('$accont','$nick','$number','$total','$discount','$usage_fee','$c','$g','$q','$spend','$count','$date','$ID')";
$ReseI = mysql_query($query_invoice, $lp) or die(mysql_error());


?>