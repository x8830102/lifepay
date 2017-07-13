<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_kg = "localhost";
$database_kg = "twliveli_a";
$username_kg = "twliveli_winson";
$password_kg = "0980789538";
$kg = mysql_pconnect($hostname_kg, $username_kg, $password_kg) or trigger_error(mysql_error(),E_USER_ERROR); 
?>