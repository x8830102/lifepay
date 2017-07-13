<?php
require_once('Connections/sc.php');mysql_query("set names utf8");
header('Content-Type: text/html;charset=UTF-8');

mysql_select_db($database_sc, $sc); //開啟資料庫
$SQL="SELECT * FROM g_cash WHERE number = 'boss588'"; 
$result=mysql_query($SQL, $sc) or die(mysql_error()); //執行 SQL 指令
$store=array(); //儲存表格內容之陣列
for ($i=0; $i<mysql_numrows($result); $i++) { //走訪紀錄集 (列)
     $row=mysql_fetch_array($result); //取得列陣列
     $date=$row["date"];
     $note=$row["note"];
     $cin=$row["cin"];
     $csum=$row["csum"];
     $note2=$row["note2"];
     $store[$i]=array($date, $note, number_format($cin), number_format($csum), $note2); //存入陣列
     } //end of for
//$arr["aaData"]=$store; 
echo json_encode($store);  //將陣列轉成 JSON 資料格式傳回

?>
