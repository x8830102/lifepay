<?php
require_once('../Connections/lp.php');mysql_query("set names utf8");

$accont = $_POST['accont'];
$password = $_POST['password'];
$password2= $_POST['password2'];
$st_name= $_POST['st_name'];
$usage_fee= $_POST['usage_fee'];
$number1 = $_POST['number1'];
$number2= $_POST['number2'];
$dis= $_POST['dis'];
$disexp= $_POST['disexp'];
$industry = $_POST['industry'];
$email = $_POST['email'];
$contract = $_POST['contract'];
$date = date("ymd");
$level = "boss";

if($accont !='' && $password !='' && $password2 !='' &&  $st_name !='' && $usage_fee !='' && $level !=''){
	mysql_select_db($database_lp, $lp);
	$query_user = "SELECT * FROM lf_user  ORDER BY user_id DESC";
	$query = mysql_query($query_user, $lp) or die(mysql_error());
	$row_user = mysql_fetch_assoc($query);
	$user_id = $row_user['user_id'];

	if($user_id<=9 && $user_id>0)
	{
		$number = "SN".$date."000".($user_id+1);
	}else if($user_id<99 && $user_id>9)
	{
		$number = "SN".$date."00".($user_id+1);
	}else if($user_id<999 && $user_id>99)
	{
		$number = "SN".$date."0".($user_id+1);
	}else if($user_id<9999 && $user_id>999)
	{
		$number = "SN".$date."".($user_id+1);
	}else if($user_id>10000)
	{
		$number = "SN".$date."".($user_id+1);
	}
	if($accont){
	mysql_select_db($database_lp, $lp);
	$select_stuser = sprintf("SELECT * FROM lf_user WHERE accont = '$accont'");
	$sel = mysql_query($select_stuser, $lp) or die(mysql_error());
	$trow_sel = mysql_num_rows($sel);

	if($trow_sel != 0){
    echo 1;
	}else{
	$inser_user = "INSERT IGNORE INTO lf_user (accont,password,password2,st_name,e_name,number,usage_fee,date,level,time1,time2,st_dis,disexp,industry,email,contract)value('$accont','$password','$password2','$st_name','$level','$number','$usage_fee','$date','$level','$number1','$number2','$dis','$disexp','$industry','$email','$contract')";
	$inser = mysql_query($inser_user, $lp) or die(mysql_error());
	echo 2;
}
}
}

?>