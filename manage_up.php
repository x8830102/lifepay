<?php
require_once('Connections/lp.php');mysql_query("set names utf8");
header('Content-Type: application/json; charset=UTF-8');

$usage = $_POST['usage'];
$store = $_POST['store'];

if($usage != ''){
	mysql_select_db($database_lp, $lp);
	$update_stuser = sprintf("UPDATE lf_user SET usage_fee='$usage' WHERE st_name='$store'");
	$update = mysql_query($update_stuser, $lp) or die(mysql_error());
}

$sd1 = $_POST['sd1'];
$sd2 = $_POST['sd2'];

if ($sd1 != "" && $sd2 != "") { //日期&商家
	mysql_select_db($database_lp, $lp);
	$query_strp = "SELECT * FROM Invoice WHERE nick ='$store' && date >= '$sd1' && date <= '$sd2' ORDER BY id DESC";
	$Restrp = mysql_query($query_strp, $lp) or die(mysql_error());
} else {
	mysql_select_db($database_lp, $lp); //無條件
	$query_strp = "SELECT * FROM Invoice WHERE nick ='$store' ORDER BY id DESC";
	$Restrp = mysql_query($query_strp, $lp) or die(mysql_error());
}
for($i=0;$i<mysql_numrows($Restrp);$i++){

	$row_strp = mysql_fetch_assoc($Restrp);
	//銷售金額
	$total_sell = $row_strp['total'];
	$sell_sum = $sell_sum + $total_sell;
	//消費積分總額
	$g = $row_strp['g'];
	$g_sum = $g_sum + $g;
	//串串積分總額
	$c = $row_strp['c'];
	$c_sum = $c_sum + $c;
	//使用券總額
	$discount = $row_strp['discount'];
	$discount_sum = $discount_sum + $discount;
	//現金/信用卡
	$spend = $row_strp['spend'];
	$spend_sum = $spend_sum + $spend;
	//支付金額
	$usage_fee = $row_strp['usage_fee'];
	$usage_fee_sum = $usage_fee_sum + $usage_fee;

}

$arr =array("sd1"=>$sd1,"sd2"=>$sd2,"sell_sum"=>$sell_sum,"g_sum"=>$g_sum,"c_sum"=>$c_sum,"discount_sum"=>$discount_sum,"spend_sum"=>$spend_sum,"usage_fee_sum"=>$usage_fee_sum);
$arr_json = json_encode($arr); //陣列轉josn

echo $arr_json;

?>