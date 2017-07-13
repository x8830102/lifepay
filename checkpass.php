<?php
require_once('Connections/lp.php');mysql_query("set names utf8");
header('Content-Type: application/json; charset=UTF-8');

session_start();
if ($_SESSION['MM_Username'] == ""){header(sprintf("Location: store_login.php"));exit;
}else{
  $m_username = $_SESSION['mem'];//登入帳號
  $m_nick = $_SESSION['nick'];//登入暱稱
  $number = $_SESSION['number'];
}

$passtwo = $_POST['passtwo'];

mysql_select_db($database_lp, $lp);
$query_stuser = sprintf("SELECT * FROM lf_user WHERE number ='$number'");
$query = mysql_query($query_stuser, $lp) or die(mysql_error());
$row_stuser = mysql_fetch_assoc($query);
$total_ruser = mysql_num_rows($query);

if($passtwo != ''){
	$pas2 = $row_stuser['password2'];

		if ($pas2 == $passtwo) {
	        echo 0; //密碼正確
	        exit;
	    }
	    if ($pas2 != $passtwo) {
	        //echo 1; //密碼不正確
	        goto c;
	        //exit;
	    }
}else{
	echo 1;
	exit;
}

c:
if($total_ruser !=0 ){
	do{

		$pas2 = $row_stuser['password2'];

		if ($pas2 == $passtwo) {
	        echo 0; //密碼正確
	        exit;
	    }

	}while($row_stuser = mysql_fetch_assoc($query));
}
echo 1;
exit;
?>