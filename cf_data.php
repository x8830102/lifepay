<?php
require_once('Connections/lp.php');mysql_query("set names 'utf8'");
header('Content-Type: application/json; charset=UTF-8');

session_start();
if ($_SESSION['MM_Username'] == ""){header(sprintf("Location: store_login.php"));exit;
}else{
  $m_username = $_SESSION['mem'];//登入帳號
  $m_nick = $_SESSION['nick'];//登入暱稱
  $number = $_SESSION['number'];
}

$ok = $_POST['ok'];
$complete_id = $_POST['complete_id'];
$next_discount =$_POST['next_discount'];
$accumulation =$_POST['accumulation'];
$st_dis = $_POST['st_dis'];
$disexp = $_POST['disexp'];


//存確認後的值
mysql_select_db($database_lp, $lp);
$sql = "SELECT * FROM lf_user WHERE number ='$number' && accont = '$m_username'";
$result = mysql_query($sql) or die(mysql_error());
$row = mysql_fetch_assoc($result);
$record_count = mysql_num_rows($result);

$original = $row['st_dis'];
$e_name = $row['e_name'];

if($original != $st_dis){ //表示折扣%數有被修改
  //存確認後的值
  mysql_select_db($database_lp, $lp);
  $query_stcomplete = sprintf("UPDATE complete SET accumulation='$accumulation',confirm ='$ok',next_discount='$next_discount',dis_percent='$st_dis',e_name='$e_name' WHERE ID = '$complete_id'");
  $Restcomplete = mysql_query($query_stcomplete, $lp) or die(mysql_error());
}else{
  //存確認後的值
  mysql_select_db($database_lp, $lp);
  $query_stcomplete = sprintf("UPDATE complete SET accumulation='$accumulation',confirm ='$ok',next_discount='$next_discount',dis_percent='$st_dis' WHERE ID = '$complete_id'");
  $Restcomplete = mysql_query($query_stcomplete, $lp) or die(mysql_error());
}


    mysql_select_db($database_lp, $lp);
    $query_sel = sprintf("SELECT * FROM complete WHERE ID = '$complete_id'");
    $Resel = mysql_query($query_sel, $lp) or die(mysql_error());
    $row_sel = mysql_fetch_assoc($Resel);
    $next_discount = $row_sel['next_discount'];
    $s_number = $row_sel['s_number'];
    $s_nick = $row_sel['s_nick'];
    $m_username = $row_sel['p_user'];
    $number = $row_sel['p_number'];
    $m_nick = $row_sel['p_nick'];

    mysql_select_db($database_lp, $lp);
    $SELECT_user = "SELECT * FROM lf_user WHERE number = '$s_number' ";
    $SELECT = mysql_query($SELECT_user, $lp) or die(mysql_error());
    $row_user = mysql_fetch_assoc($SELECT);
    $s_user = $row_user['accont'];

    //發優惠券
    $num = ceil($next_discount /100);
    $remainder = $next_discount%100;

    for($i=1 ;$i<=$num ;$i++)
    {
      if($i == $num)
      {
        if((int)$remainder != 0){
          mysql_select_db($database_lp, $lp);
          $inser_coupon = "INSERT IGNORE INTO coupon (s_user,s_number,s_nick,p_user,p_number,p_nick,discount,effective_date,supply,complete)value('$s_user','$s_number','$s_nick','$m_username','$number','$m_nick','$remainder','$disexp','','0')";
          $inser = mysql_query($inser_coupon, $lp) or die(mysql_error());
        }else{
          mysql_select_db($database_lp, $lp);
          $inser_coupon = "INSERT IGNORE INTO coupon (s_user,s_number,s_nick,p_user,p_number,p_nick,discount,effective_date,supply,complete)value('$s_user','$s_number','$s_nick','$m_username','$number','$m_nick','100','$disexp','','0')";
          $inser = mysql_query($inser_coupon, $lp) or die(mysql_error());
        }
      }else{
        mysql_select_db($database_lp, $lp);
        $inser_coupon = "INSERT IGNORE INTO coupon (s_user,s_number,s_nick,p_user,p_number,p_nick,discount,effective_date,supply,complete)value('$s_user','$s_number','$s_nick','$m_username','$number','$m_nick','100','$disexp','','0')";
        $inser = mysql_query($inser_coupon, $lp) or die(mysql_error());
      }
    }

	


?>