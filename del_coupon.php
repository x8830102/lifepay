<?php
require_once('Connections/lp.php');mysql_query("set names utf8");
header('Content-Type: application/json; charset=UTF-8');

session_start();
if ($_SESSION['MM_Username'] == ""){header(sprintf("Location: life_pay.php"));exit;
}else{
  $m_username = $_SESSION['mem'];//登入帳號
  $m_nick = $_SESSION['nick'];//登入暱稱
  $number = $_SESSION['number'];
}

$date = date("Y-m-d");

  mysql_select_db($database_lp, $lp);
  $query_del = sprintf("DELETE FROM coupon WHERE p_user ='$m_username' && p_number ='$number' && effective_date < '$date'");
  $Restrdel = mysql_query($query_del, $lp) or die(mysql_error());
  $row_del = mysql_fetch_assoc($Restrdel);


?>