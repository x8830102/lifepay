<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_tw = "localhost";
$database_tw = "lifelink_cc_fans";
$username_tw = "lifelink_wiiscon";
$password_tw = "0980789538";
$tw = mysql_pconnect($hostname_tw, $username_tw, $password_tw) or trigger_error(mysql_error(),E_USER_ERROR); 
?>