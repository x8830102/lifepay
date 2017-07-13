<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_lp = "localhost";
$database_lp = "twliveli_lfpay";
$username_lp = "twliveli_winson";
$password_lp = "0980789538";
$lp = mysql_pconnect($hostname_lp, $username_lp, $password_lp) or trigger_error(mysql_error(),E_USER_ERROR); 
?>