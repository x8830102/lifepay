<?php
require_once('Connections/lp.php');mysql_query("set names utf8");
header('Content-Type: text/html;charset=UTF-8');

$sd1 = $_POST['sd1'];
$sd2 = $_POST['sd2'];
$store = $_POST['store'];
$p_user = $_POST['p_user'];

if ($sd1 != "" || $sd2 != "" && $store != "") { //日期&商家
    mysql_select_db($database_lp, $lp);
    $query_str = "SELECT * FROM complete WHERE p_user ='$p_user' && date >= '$sd1' && date <= '$sd2' && s_nick like '%$store%' ORDER BY date DESC";
    $Restr = mysql_query($query_str, $lp) or die(mysql_error());
} else if($store != "") { //商家
    mysql_select_db($database_lp, $lp);
    $query_str = "SELECT * FROM complete WHERE p_user ='$p_user' && s_nick like '%$store%' ORDER BY date DESC";
    $Restr = mysql_query($query_str, $lp) or die(mysql_error());
} else {
    mysql_select_db($database_lp, $lp); //無條件
    $query_str = "SELECT * FROM complete WHERE p_user ='$p_user'  ORDER BY date DESC";
    $Restr = mysql_query($query_str, $lp) or die(mysql_error());
}

for ($i=0; $i<mysql_num_rows($Restr); $i++) { //走訪紀錄集 (列)

	$row_str = mysql_fetch_assoc($Restr);
    $num = mysql_num_rows($Restr);
	$date=$row_str["date"];
	$s_nick=$row_str["s_nick"];
	$total_cost=$row_str["total_cost"];
	$g=$row_str["g"];
	$c=$row_str["c"];

    $aa = "<td style='border-radius: 0px 0px 0px 10px'><span>".$date."</span></td>
			<td><span>".$s_nick."</span></td>
			<td><span>".$total_cost."</span></td>
			<td><span>".$g."</span></td>
			<td style='border-radius: 0px 0px 10px 0px'><span>".$c."</span></td>";

	$user[$i]=array($aa,$num); //存入陣列
	}
	//end of for
//$arr["aaData"]=$store; //表格內容存入關聯式陣列
echo json_encode($user);  //將陣列轉成 JSON 資料格式傳回


?>
