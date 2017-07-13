<?php require_once('Connections/lp.php');mysql_query("set names utf8");

session_start();
if ($_SESSION['MM_Username'] == ""){header(sprintf("Location: life_pay.php"));exit;
}else{
  $m_username = $_SESSION['mem'];//登入帳號
  $m_nick = $_SESSION['nick'];//登入暱稱
  $number = $_SESSION['number'];
}

$p_nick = $_POST['p_nick'];
$p_user = $_POST['p_user'];
$p_number = $_POST['p_number'];
$deadline = $_POST['eff'];
$dis = $_POST['dis'];
$acc = $_POST['acc'];
//$data = date("Y-m-d H:i:s");
//$dis = $_POST[''];
//$spend = $_POST['spend'];
mysql_select_db($database_lp, $lp);
$inser_strecord = "INSERT IGNORE INTO coupon (ID,s_user,s_number,s_nick,p_user,p_number,p_nick,	accumulation,discount,effective_date,complete)value(NULL,'$m_username','$number','$m_nick','$p_user','$p_number','$p_nick','$acc','$dis','$deadline','0')";
$inser = mysql_query($inser_strecord, $lp) or die(mysql_error());

//$_SESSION['time'] = $data;

header(sprintf("Location: store_coupon.php"));exit;
?>