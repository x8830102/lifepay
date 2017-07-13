<?php
require_once('Connections/tw.php');mysql_query("set names utf8");

$st_name = $_POST['st_name'];
$store = $_POST['store'];
$m_username = $_POST['m_username'];
$i = 1;

$date = date("ymd");

mysql_select_db($database_tw, $tw);
$query_bt = "SELECT *  FROM fstore WHERE my_us = '$m_username'";
$Restr = mysql_query($query_bt, $tw) or die(mysql_error());
$row_bt = mysql_fetch_assoc($Restr);
		
			do{
				if($row_bt['yu_us'] == $store){
					$i = 1;
					break;
				}else{
					$i = 0;
				}
			}while($row_bt = mysql_fetch_assoc($Restr));

					
						if($i == 0){
							$add = "INSERT IGNORE INTO fstore (my_us, yu_us, s_name, date) value('$m_username', '$store', '$st_name', '$date')";
							mysql_query($add , $tw);
							echo 1; 
						}else{
							$delete = "DELETE FROM fstore WHERE my_us = '$m_username' && yu_us = '$store'";
							mysql_query($delete);
							echo 0;
						}

?>