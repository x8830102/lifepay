<?php
require_once('Connections/lp.php');mysql_query("set names utf8");
require_once('Connections/sc.php');mysql_query("set names utf8");
header('Content-Type: application/json; charset=UTF-8');

$s_nick = $_POST['s_nick'];
$s_number = $_POST['s_number'];
$s_usage_fee = $_POST['s_usage_fee'];
$s_c_total = $_POST['s_c_total'];
$s_g_total = $_POST['s_g_total'];
$s_q_total = $_POST['s_q_total'];
$s_spend_total = $_POST['s_spend_total'];
$date = date("Y-m-d");
$time = date("H:i:s");

//Invoice的confirm數值變1
mysql_select_db($database_lp, $lp);
$confirm = "UPDATE Invoice SET confirm = '1' WHERE number ='$s_number'";
$confirm_in = mysql_query($confirm, $lp) or die(mysql_error());

//新增撥款manage_invoice
mysql_select_db($database_lp, $lp);
$manage_invoice = "INSERT IGNORE INTO manage_invoice(nick,number,c_total,g_total,spend,q_total,usage_fee,date)value('$s_nick','$s_number','$s_c_total','$s_g_total','$s_spend_total','$s_q_total','$s_usage_fee','$date')";
$manage_invoice_mi = mysql_query($manage_invoice, $lp) or die(mysql_error());

//找到轉入者的串串積分總額
mysql_select_db($database_sc, $sc);
$query_un_c = sprintf("SELECT * FROM c_cash WHERE number = 'boss588' order by id desc");
$Recf_unc = mysql_query($query_un_c, $sc) or die(mysql_error());
$row_un_c = mysql_fetch_assoc($Recf_unc);
$unc_sum = $row_un_c['csum'];

$tcsum = $unc_sum+$s_c_total;
//回收串串積分
mysql_select_db($database_sc, $sc);
$manage_rc_C = "INSERT IGNORE INTO c_cash(number,cin,cout,csum,note,note2,date,time,gdn_y,gdn_w,sncode)value('boss588','$s_c_total','0','$tcsum','$s_nick','回收積分','$date','$time','0','0','')";
$manage_totalrc_C = mysql_query($manage_rc_C, $sc) or die(mysql_error());

//找到轉入者的消費積分總額
mysql_select_db($database_sc, $sc);
$query_un_G = sprintf("SELECT * FROM g_cash WHERE number = 'boss588' order by id desc");
$Recf_unG = mysql_query($query_un_G, $sc) or die(mysql_error());
$row_un_G = mysql_fetch_assoc($Recf_unG);
$ung_sum = $row_un_G['csum'];

$tgsum = $ung_sum+$s_g_total;
//回收串串積分
mysql_select_db($database_sc, $sc);
$manage_rc_G = "INSERT IGNORE INTO g_cash(number,cin,cout,csum,note,note2,date,time,sncode)value('boss588','$s_g_total','0','$tgsum','$s_nick','回收積分','$date','$time','')";
$manage_totalrc_G = mysql_query($manage_rc_G, $sc) or die(mysql_error());


?>