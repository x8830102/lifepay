<?php
require_once('Connections/lp.php');require_once('Connections/sc.php');mysql_query("set names utf8");
header('Content-Type: application/json; charset=UTF-8');

session_start();
if ($_SESSION['MM_Username'] == ""){header(sprintf("Location: store_login.php"));exit;
}else{
  $m_username = $_SESSION['mem'];//登入帳號
  $m_nick = $_SESSION['nick'];//登入暱稱
  $number = $_SESSION['number'];
  $coupon_id = $_SESSION['coupon_id'];
}


$p_user = $_POST['p_user'];
$p_nick = $_POST['p_nick'];
$mkey = $_POST['mkey'];
if(isset($p_user) && isset($p_nick) && isset($mkey)){
	mysql_select_db($database_lp, $lp);
	$query_str = "SELECT * FROM complete WHERE keyy ='$mkey'";
	$Restr = mysql_query($query_str, $lp) or die(mysql_error());
	$row_str = mysql_fetch_assoc($Restr);

	$confirm = $row_str['confirm'];
	$next_discount = $row_str['next_discount'];
	$s_nick = $row_str['s_nick'];
	$s_number = $row_str['s_number'];
	$ct = $row_str['ct']; //餘額
	$gt = $row_str['gt'];
	$spend = $row_str['spend'];
	$total_cost = $row_str['total_cost'];
	$c = $row_str['c'];
	$g = $row_str['g'];
	$next_discount = $row_str['next_discount'];
	$paid = $spend;
	$ID = $row_str['ID'];
	$coupon_id = $row_str['coupon_id'];

	$data2 = date("Y-m-d");
	$time2 = date("H:i:s");

	if($confirm != 1){

		$arr =array("confirm"=>$confirm,"next_discount"=>$next_discount);
		$arr_json = json_encode($arr); //陣列轉josn

		echo $arr_json;
		exit;
	}else{
		//使用優惠券
	    $coupon_id = explode(",", $coupon_id);

	    $a =count($coupon_id);

	    for($j=0 ; $j < $a ;$j++)
	    {
	      mysql_select_db($database_lp, $lp);
	      $update_coupon = "UPDATE coupon SET complete ='1' WHERE ID = '$coupon_id[$j]' ";
	      $update = mysql_query($update_coupon, $lp) or die(mysql_error());
	    }
	    mysql_select_db($database_lp, $lp);
        $del = "DELETE FROM st_record WHERE verification ='$mkey'";
        mysql_query($del, $lp) or die(mysql_error());
	    //扣積分
	    if($c != 0)
	    {
	      mysql_select_db($database_sc, $sc);
	      $inser_pay = "INSERT IGNORE INTO c_cash (number,cin,cout,csum,note,note2,date,time,gdn_y,gdn_w,sncode)value('$number','0','$c','$ct','於[$s_nick]消費','','$data2','$time2','0','0','')";
	      $inser = mysql_query($inser_pay, $sc) or die(mysql_error());
	    }
	    if($g != 0)
	    {
	      mysql_select_db($database_sc, $sc);
	      $inser_pay = "INSERT IGNORE INTO g_cash (number,cin,cout,csum,note,note2,date,time,sncode)value('$number','0','$g','$gt','於[$s_nick]消費','','$data2','$time2','')";
	      $inser = mysql_query($inser_pay, $sc) or die(mysql_error());
	    }


		$arr =array("confirm"=>$confirm,"next_discount"=>$next_discount,"m_nick"=>$m_nick,"total_cost"=>$total_cost,"paid"=>$paid,"ID"=>$ID);
		$arr_json = json_encode($arr); //陣列轉josn

		echo $arr_json;
		exit;
	}
    
	
}else if(isset($mkey)){
	$confirm_st = "SELECT * FROM st_record WHERE verification ='$mkey'";
	$confirm = mysql_query($confirm_st, $lp) or die(mysql_error());
	$check_row = mysql_fetch_assoc($confirm);

	$check = $check_row['confirm'];
	echo $check;
	exit;
}


?>