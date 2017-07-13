<?php
require_once('Connections/lp.php');mysql_query("set names utf8");
header('Content-Type: application/json; charset=UTF-8');

$sd1 = $_POST['sd1'];
$sd2 = $_POST['sd2'];
$store = $_POST['store'];
$p_user = $_POST['p_user'];


if ($sd1 != "" || $sd2 != "" && $store != "") { //日期&商家
	mysql_select_db($database_lp, $lp);
	$query_strp = "SELECT * FROM complete WHERE p_user ='$p_user' && date >= '$sd1' && date <= '$sd2' && s_nick like '%$store%' ORDER BY id DESC";
	$Restrp = mysql_query($query_strp, $lp) or die(mysql_error());
	$row_strp = mysql_fetch_assoc($Restrp);
} else if($store != "") { //商家
	mysql_select_db($database_lp, $lp);
	$query_strp = "SELECT * FROM complete WHERE p_user ='$p_user' && s_nick like '%$store%' ORDER BY id DESC";
	$Restrp = mysql_query($query_strp, $lp) or die(mysql_error());
	$row_strp = mysql_fetch_assoc($Restrp);
} else {
	mysql_select_db($database_lp, $lp); //無條件
	$query_strp = "SELECT * FROM complete WHERE p_user ='$p_user' ORDER BY id DESC";
	$Restrp = mysql_query($query_strp, $lp) or die(mysql_error());
	$row_strp = mysql_fetch_assoc($Restrp);
}

$s_nick = $row_strp['s_nick'];
$a=false;

do{
	//消費總金額
	$total_consum = $row_strp['total_cost'];
	$consum_sum = $consum_sum + $total_consum;
	//消費積分總額
	$g = $row_strp['g'];
	$g_sum = $g_sum + $g;
	//串串積分總額
	$c = $row_strp['c'];
	$c_sum = $c_sum + $c;

	//每一筆交易的商店
	$nick_nick = $row_strp['s_nick'];

	if(strcmp($s_nick,$nick_nick)!=0){ //比對是否相等
		$a= true;
    }

}while($row_strp = mysql_fetch_assoc($Restrp));
if($a){
	$a = "";
}else{
	$a = $s_nick;
}

//沒有資料
if(mysql_numrows($Restrp)==0){
	$consum_sum =0;
	$g_sum =0;
	$c_sum =0;
}

$arr =array('sd1'=>$sd1,'sd2'=>$sd2,'consum_sum'=>$consum_sum,'g_sum'=>$g_sum,'c_sum'=>$c_sum,'a'=>$a);
$arr_json = json_encode($arr); //陣列轉josn

echo $arr_json;

?>