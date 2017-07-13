<?php
require_once('Connections/lp.php');mysql_query("set names utf8");
header('Content-Type: application/json; charset=UTF-8');

session_start();
if ($_SESSION['MM_Username'] == ""){header(sprintf("Location: store_login.php"));exit;
}else{
  $m_username = $_SESSION['mem'];//登入帳號
  $m_nick = $_SESSION['nick'];//登入暱稱
  $number = $_SESSION['number'];
}

$id = $_POST['id'];
$rt_level = $_POST['rt_level'];
$rt_content = $_POST['rt_content'];
$date = date("Y-m-d");

	if($rt_level != 0){
		mysql_select_db($database_lp, $lp);
		$rate = "UPDATE complete SET rt_level = '$rt_level', rt_content = '$rt_content', rt_status = '1', rt_date = '$date' WHERE ID = '$id'";
		mysql_query($rate);
	}
	$arr = array("id"=>"$id","level"=>"$rt_level");
	$arr = json_encode($arr);
	echo $arr;
	exit;
	

?>