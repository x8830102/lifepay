<?php
//連接F站的資料庫

# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_tw_ff = "localhost";
$database_tw_ff = "twlifeli_storedata";
$username_tw_ff = "twlifeli_winson";
$password_tw_ff = "0980789538";
$tw_ff = mysql_pconnect($hostname_tw_ff, $username_tw_ff, $password_tw_ff) or trigger_error(mysql_error(),E_USER_ERROR); 
?>