<?php 
require_once('Connections/sc.php');require_once('Connections/lp.php');mysql_query("set names utf8");
header('Content-Type: application/json; charset=UTF-8');

session_start();
if ($_SESSION['MM_Username'] == ""){header(sprintf("Location: life_pay.php"));exit;
}else{
  $m_username = $_SESSION['mem'];//登入帳號
  $m_nick = $_SESSION['nick'];//登入暱稱
  $number = $_SESSION['number'];
}

//轉帳積分
$u_number = $_POST['u_number'];
$tcc = $_POST['transcc'];//轉出積分
$csum = $_POST['csum'];//登入者的總積分
$date = date("Y-m-d");
$time = date("H:i:s");


$mcsum = $csum-$tcc;

if($u_number != '' && $tcc != ''){
  mysql_select_db($database_sc, $sc);
  $SQL = "SELECT * FROM memberdata WHERE number = '$u_number'";
  $SQLuser = mysql_query($SQL, $sc) or die(mysql_error());
  $SQL_row = mysql_fetch_assoc($SQLuser);
  $touser = $SQL_row['m_username'];

  //先扣積分
  mysql_select_db($database_sc, $sc);
  $query_trans = "INSERT IGNORE INTO c_cash (number,cin,cout,csum,note,note2,date,time,gdn_y,gdn_w,sncode) value ('$number','0','$tcc','$mcsum','積分轉出<br/>帳號:$touser','轉串串積分到$touser','$date','$time','0','0','')";
  $Recf_utrans = mysql_query($query_trans, $sc) or die(mysql_error());

  //找到轉入者的積分總額
  mysql_select_db($database_sc, $sc);
  $query_un_c = sprintf("SELECT * FROM c_cash WHERE number = '$u_number' order by id desc");
  $Recf_unc = mysql_query($query_un_c, $sc) or die(mysql_error());
  $row_un_c = mysql_fetch_assoc($Recf_unc);
  $unc_sum = $row_un_c['csum'];

  $tcsum = $unc_sum+$tcc;
  //加入積分
  $query_trans2 = "INSERT IGNORE INTO c_cash (number,cin,cout,csum,note,note2,date,time,gdn_y,gdn_w,sncode) value ('$u_number','$tcc','0','$tcsum','收到[ $m_username ]的串串積分','','$date','$time','0','0','')";
  $Recf_utrans2 = mysql_query($query_trans2, $sc) or die(mysql_error());
}


$touser = $_POST['touser'];
if($touser != ''){
  mysql_select_db($database_sc, $sc);
  $query_user = sprintf("SELECT * FROM memberdata WHERE m_username = '$touser'");
  $Recf_user = mysql_query($query_user, $sc) or die(mysql_error());
  $row_user = mysql_fetch_assoc($Recf_user);
  $totaluser = mysql_num_rows($Recf_user);
  $number = $row_user['number'];

  if($totaluser != ""){
    echo $number;exit;//有此帳號
  }else{
    echo $number;exit;//沒有這個帳號
  }
}

$cbcs = $_POST['cbcs'];
$cou_number = $_POST['u_number'];

if($cbcs != ''){
  mysql_select_db($database_sc, $sc);
  $sql = sprintf("SELECT * FROM memberdata WHERE number = '$cou_number'");
  $con = mysql_query($sql, $sc) or die(mysql_error());
  $row = mysql_fetch_assoc($con);
  $cou_nick = $row['m_nick'];//暱稱
  $cou_user = $row['m_username'];//帳號

  for($i=0;$i<count($cbcs);$i++){
    $cou_id = $cbcs[$i];
    mysql_select_db($database_lp, $lp);
    $update = "UPDATE coupon SET p_user = '$cou_user', p_number = '$cou_number', p_nick = '$cou_nick' WHERE ID = '$cou_id' ";
    $con_update = mysql_query($update, $lp) or die(mysql_error());
  }
  
}


header(sprintf("Location: person_transfer.php"));exit;
?>