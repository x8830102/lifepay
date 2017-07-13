<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_sc = "localhost";
$database_sc = "twliveli_a";
$username_sc = "twliveli_winson";
$password_sc = "0980789538";
$sc = mysql_pconnect($hostname_sc, $username_sc, $password_sc) or trigger_error(mysql_error(),E_USER_ERROR); 
?>