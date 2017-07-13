<?php
require_once('Connections/lp.php');mysql_query("set names utf8");
header('Content-Type: text/html;charset=UTF-8');

$s_number = $_POST['ss_number'];

mysql_select_db($database_lp, $lp); //開啟資料庫
$SQL="SELECT * FROM Invoice WHERE number = '$s_number' and confirm = '1'"; 
$result=mysql_query($SQL, $lp) or die(mysql_error()); //執行 SQL 指令
$store=array(); //儲存表格內容之二維陣列
for ($i=0; $i<mysql_numrows($result); $i++) { //走訪紀錄集 (列)
     $row=mysql_fetch_array($result); //取得列陣列
     $date=$row["date"];
     $c=$row["c"];
     $g=$row["g"];
     $spend=$row['spend'];
     $count=$row['count'];
     $fee=$row["q"];
     $store[$i]=array($date, number_format($g), number_format($c), number_format($spend), number_format($count), number_format($fee)); //存入陣列
     } //end of for
//$arr["aaData"]=$store; //表格內容存入關聯式陣列
echo json_encode($store);  //將陣列轉成 JSON 資料格式傳回

?>
