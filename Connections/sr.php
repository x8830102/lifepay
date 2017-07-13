<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_sr = "localhost";
$database_sr = "twlifeli_storedata";
$username_sr = "twlifeli_winson";
$password_sr = "0980789538";
$sr = mysql_pconnect($hostname_sr, $username_sr, $password_sr) or trigger_error(mysql_error(),E_USER_ERROR); 
?>