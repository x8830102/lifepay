<?php 
require_once('Connections/lp.php');mysql_query("SET NAMES 'utf8mb4'",$lp);
require_once('Connections/sc.php');mysql_query("SET NAMES 'utf8mb4'",$sc);
require_once('Connections/tw.php');mysql_query("SET NAMES 'utf8mb4'",$tw);


$s_nick = $_POST['st_nm'];
$total_cost = $_POST['tol_cs'];
$c = $_POST['c'];
$ct = $_POST['ct'];
$g = $_POST['g'];
$gt = $_POST['gt'];
$coupon = $_POST['coupon']; //本次贈與之折扣券陣列
$date = date("Y-m-d");
$time = date("H:i:s");
$dis = $_POST['discount'];
$spend = $_POST['spend'];//應付金額
$s_number = $_POST['s_number'];
$coupon_id = $_POST['coupon_id'];
$s_user = $_POST['s_user'];
$m_username = $_POST['m_username'];
$m_nick = $_POST['m_nick'];
$u_number = $_POST['u_number'];
$mkey = $_GET['a'];
$st_dis = $_POST['st_dis'];

//抵用券金額
$next_discount = floor($spend/100*$st_dis);


//
mysql_select_db($database_lp, $lp);
$SELECT_user = "SELECT * FROM lf_user WHERE number = '$s_number' && accont = '$s_user'";
$SELECT = mysql_query($SELECT_user, $lp) or die(mysql_error());
$row_user = mysql_fetch_assoc($SELECT);
$sel_st = $row_user['usage_fee'];
$s_nick = $row_user['st_name'];
$e_name = $row_user['e_name'];
$original = $row_user['st_dis'];

//echo $e_name;exit;
$fee_amount = ($spend/100)*$sel_st;


//計算推播人數
unset($fmu);$fmi=0;$fmi2=1;$fmk=0;
$fmu[0]=$p_user;$fcount = '';$fm = '';
while ($fmk != 1) {
  $fmj=$fmu[$fmi];//從源頭的人開始取值往下代做收尋 例:peggy -> x12345 -> test94
  //echo $fmj;
	mysql_select_db($database_sc, $sc);
	$query_Recfmu = sprintf("SELECT * FROM memberdata WHERE fname = '$fmj'" );//
	$Recfmu = mysql_query($query_Recfmu, $sc) or die(mysql_error());
	$row_Recfmu = mysql_fetch_assoc($Recfmu);
	$totalRows_Recfmu = mysql_num_rows($Recfmu);//echo $totalRows_Recfmu;exit;
	//print_r($row_Recfmu);
	if ($totalRows_Recfmu != 0) {
	do {$fma=$row_Recfmu['m_username'];if (in_array($fma,$fmu) == false) {$fmu[$fmi2]=$fma;$fmi2++;}} while ($row_Recfmu = mysql_fetch_assoc($Recfmu));
	}
	
	//底下有多少人也關注這家商店
	mysql_select_db($database_tw, $tw);
	$query_str = sprintf("SELECT * FROM fstore WHERE my_us = '$fmj' && s_name = '$st_name'");
	$Restr = mysql_query($query_str, $tw) or die(mysql_error());
	$row_str = mysql_fetch_assoc($Restr);
	$row_num = mysql_num_rows($Restr);
	//print_r($query_str);
	//echo $row_num;
	if($row_num != 0){
	  $fcount++;
	}
	if($fcount == "")
	{
		$fcount =0;
	}

	//這個月底下有多少人也關注這家商店
	mysql_select_db($database_tw, $tw);
	$query_mon = sprintf("SELECT * FROM fstore WHERE my_us = '$fmj' && s_name = '$st_name' && date >= '$month1' && date <='$month2'");
	$Restm = mysql_query($query_mon, $tw) or die(mysql_error());
	$row_mon = mysql_fetch_assoc($Restm);
	$row_mon = mysql_num_rows($Restm);
	//print_r($query_mon);
	//echo $row_num;
	if($row_mon != 0){
	  $fm++;
	}
	if($fm == "")
	{
		$fm =0;
	}

  $fmi++;

  if ($fmu[$fmi] == "") {$fmk=1;}

  }


if($original != $st_dis){
	//存結帳資料
	mysql_select_db($database_lp, $lp);
	$inser_strecord = "INSERT IGNORE INTO complete (ID,keyy,coupon_id,s_nick,s_number,p_user,p_nick,p_number,total_cost,c,ct,g,gt,discount,spend,date,time,confirm,fee_amount,next_discount,accumulation,e_name,dis_percent)value(NULL,'$mkey','$coupon_id','$s_nick','$s_number','$m_username','$m_nick','$u_number','$total_cost','$c','$ct','$g','$gt','$dis','$spend','$date','$time','1','$fee_amount','$next_discount','$fcount','$e_name','$st_dis')";
	$inser = mysql_query($inser_strecord, $lp) or die(mysql_error());
}else{
	//存結帳資料
	mysql_select_db($database_lp, $lp);
	$inser_strecord = "INSERT IGNORE INTO complete (ID,keyy,coupon_id,s_nick,s_number,p_user,p_nick,p_number,total_cost,c,ct,g,gt,discount,spend,date,time,confirm,fee_amount,next_discount,accumulation,e_name,dis_percent)value(NULL,'$mkey','$coupon_id','$s_nick','$s_number','$m_username','$m_nick','$u_number','$total_cost','$c','$ct','$g','$gt','$dis','$spend','$date','$time','1','$fee_amount','$next_discount','$fcount','$e_name','$original')";
	$inser = mysql_query($inser_strecord, $lp) or die(mysql_error());
}

//最新一筆交易紀錄
mysql_select_db($database_lp, $lp);
$sql3 = "SELECT * FROM complete ORDER BY ID DESC ";
$conn3 = mysql_query($sql3, $lp) or die(mysql_error());
$row3 = mysql_fetch_assoc($conn3);
$p_user = $row3['p_user'];
$number = $row3['p_number'];
$p_nick = $row3['p_nick'];


//取BOSS的帳號跟暱稱
$sql2 = "SELECT * FROM lf_user WHERE number = '$s_number' ";
$conn2 = mysql_query($sql2, $lp) or die(mysql_error());
$row2 = mysql_fetch_assoc($conn2);
$accont = $row2['accont'];
$nick = $row2['st_name'];
$disexp = $row2['disexp'];

//發優惠券
$num = ceil($next_discount /100);
$remainder = $next_discount%100;
for($i=1 ;$i<=$num ;$i++)
{
	if($i == $num)
	{
		if((int)$remainder != 0){
			mysql_select_db($database_lp, $lp);
			$inser_coupon = "INSERT IGNORE INTO coupon (s_user,s_number,s_nick,p_user,p_number,p_nick,discount,effective_date,supply,complete)value('$accont','$s_number','$nick','$p_user','$number','$p_nick','$remainder','$disexp','','0')";
			$inser = mysql_query($inser_coupon, $lp) or die(mysql_error());
		}else{
			mysql_select_db($database_lp, $lp);
			$inser_coupon = "INSERT IGNORE INTO coupon (s_user,s_number,s_nick,p_user,p_number,p_nick,discount,effective_date,supply,complete)value('$accont','$s_number','$nick','$p_user','$number','$p_nick','100','$disexp','','0')";
			$inser = mysql_query($inser_coupon, $lp) or die(mysql_error());
		}
	}else{
		mysql_select_db($database_lp, $lp);
		$inser_coupon = "INSERT IGNORE INTO coupon (s_user,s_number,s_nick,p_user,p_number,p_nick,discount,effective_date,supply,complete)value('$accont','$s_number','$nick','$p_user','$number','$p_nick','100','$disexp','','0')";
		$inser = mysql_query($inser_coupon, $lp) or die(mysql_error());
	}
}


header(sprintf("Location: store_main.php?check=1"));exit;
?>