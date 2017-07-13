<?php
require_once('Connections/lp.php');mysql_query("set names utf8");

$sd1 = $_POST['sd1'];
$sd2 = $_POST['sd2'];
$number =$_POST['number'];
$usage_fee =$_POST['usage_feee'];
$c = $_POST['c_sum'];
$g = $_POST['g_sum'];
$m_username = $_POST['m_username'];
$nick = $_POST['m_nick'];
$diss = $_POST['diss'];
$total = $_POST['business_sum'];
$q = $_POST['que'];
$spend = $_POST['spend_sum'];
$count = $_POST['count_sum'];


$date = date("Y-m-d");
$time = date("H:i:s");

//請款
if($sd1 == ''){
	mysql_select_db($database_lp, $lp);
	$query_invoice = "INSERT IGNORE INTO Invoice(accont,nick,number,total,discount,usage_fee,c,g,q,spend,count,date,idd,sd1,sd2,time)value('$m_username','$nick','$number','$total','$diss','$usage_fee','$c','$g','$q','$spend','$count','$date','$ID','$sd1','$date','$time')";
	$ReseI = mysql_query($query_invoice, $lp) or die(mysql_error());
}else{
	mysql_select_db($database_lp, $lp);
	$query_invoice = "INSERT IGNORE INTO Invoice(accont,nick,number,total,discount,usage_fee,c,g,q,count,date,idd,sd1,sd2,time)value('$m_username','$nick','$number','$total','$diss','$usage_fee','$c','$g','$q','$count','$date','$ID','$sd1','$sd2','$time')";
	$ReseI = mysql_query($query_invoice, $lp) or die(mysql_error());
}

//存確認後的值
if($sd1 == ''){
	mysql_select_db($database_lp, $lp);
	$query_stcomplete = sprintf("SELECT * FROM complete WHERE s_number = '$number' and confirm = '1' and invoice='0' and date <= '$date'");
	$Restcomplete = mysql_query($query_stcomplete, $lp) or die(mysql_error());
	$row_stcomplete = mysql_fetch_assoc($Restcomplete);
	$total_stcomplete = mysql_num_rows($Restcomplete);

	//抓取剛剛寫入的筆數
	mysql_select_db($database_lp, $lp);
	$query_Invoice = sprintf("SELECT * FROM Invoice WHERE number = '$number' and c = '$c' and g = '$g' and total = '$total' and  spend = '$spend' and count = '$count' and time = '$time'");
	$RestInvoice = mysql_query($query_Invoice, $lp) or die(mysql_error());
	$row_Invoice = mysql_fetch_assoc($RestInvoice);
	$total_Invoice = mysql_num_rows($RestInvoice);
	$InvoiceID = $row_Invoice['ID'];

	do{
		$ID = $row_stcomplete['ID'];
		if($total_stcomplete != 0){
			mysql_select_db($database_lp, $lp);
			$query_aa = sprintf("UPDATE complete SET invoice='1',invoice_note='$InvoiceID' WHERE ID = '$ID'");
			$Restaa = mysql_query($query_aa, $lp) or die(mysql_error());
		}
	}while($row_stcomplete = mysql_fetch_assoc($Restcomplete));

}else{
	mysql_select_db($database_lp, $lp);
	$query_a2 = sprintf("SELECT * FROM complete WHERE s_number = '$number' and confirm = '1' and invoice='0' and date BETWEEN '$sd1' and '$sd2'");
	$Resta2 = mysql_query($query_a2, $lp) or die(mysql_error());
	$row_a2 = mysql_fetch_assoc($Resta2);
	$total_a2 = mysql_num_rows($Resta2);

	//抓取剛剛寫入的筆數
	mysql_select_db($database_lp, $lp);
	$query_Inv = sprintf("SELECT * FROM Invoice WHERE number = '$number' and c = '$c' and g = '$g' and total = '$total' and count = '$count' and time = '$time'");
	$RestInv = mysql_query($query_Inv, $lp) or die(mysql_error());
	$row_Inv = mysql_fetch_assoc($RestInv);
	$total_Inv = mysql_num_rows($RestInv);
	$InID2 = $row_Inv['ID'];

	do{
		if($total_a2 != 0){
			$IDD = $row_a2['ID'];
			mysql_select_db($database_lp, $lp);
			$query_bb = sprintf("UPDATE complete SET invoice='1',invoice_note='$InID2' WHERE ID = '$IDD'");
			$Restcomplete = mysql_query($query_bb, $lp) or die(mysql_error());
		}
	}while($row_a2 = mysql_fetch_assoc($Resta2));
}


header(sprintf("Location: Invoice.php"));exit;
?>