<?php require_once('Connections/lpv.php');mysql_query("set names utf8");
require_once('Connections/sc.php');mysql_query("set names utf8");

session_start();
if ($_SESSION['MM_Username'] == ""){
  header(sprintf("Location: manage_login.php"));exit;
}else{
  $number = $_SESSION['number'];
}
//如果沒有列數就表示不是公司端的帳號
mysql_select_db($database_lp, $lp);
$SQL = sprintf("SELECT * FROM manage WHERE number ='$number' ");
$conn = mysql_query($SQL, $lp) or die(mysql_error());
$total = mysql_num_rows($conn);
if($total == 0){
    //表示是使用者連過來的
    mysql_select_db($database_sc, $sc);
    $query_user = sprintf("SELECT * FROM memberdata WHERE number ='$number' ");
    $Reuser = mysql_query($query_user, $sc) or die(mysql_error());
    $total_user = mysql_num_rows($Reuser);
    if($total_user != ''){
      header(sprintf("Location: logout.php"));exit;
    }

    //表示是商店連過來的
    mysql_select_db($database_lp, $lp);
    $query_store = sprintf("SELECT * FROM lf_user WHERE number ='$number' ");
    $Restore = mysql_query($query_store, $lp) or die(mysql_error());
    $total_store = mysql_num_rows($Restore);
    if($total_store != ''){
      header(sprintf("Location: slogout.php"));exit;
    }
}
  
?>
<!DOCTYPE html>
<html>
<head>
<title>LIFE PAY</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="stylesheet" href="css/reset.css">
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/normalize.css" />
<link rel="stylesheet" type="text/css" href="css/default.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>


  function goBack() {
    window.history.back();
  }

  </script>
</head>
<body class="person"  style="min-height: 800px">
<div class="mebr_top" align="center" > <a href="manage_main.php"><img src="img/life_pay_logo-01.png" width="220px" alt=""></a> <a href="#"  data-toggle="modal" data-target="#myModal2"><img src="img/set_up-01.svg" style="position: absolute; left: 20px; top: 67px" width="25px" alt=""></a>
  <div class="search_bt" align="center" style="margin-top: -22px;display: none;" id="back">
    <div style="width: 88%" align="left"> <a style="cursor: pointer;" onClick="goBack()">
      <input type="button" value="< 返回" style="background: #595757; color: #fff;border: 0px ;border-radius: 5px;padding: 4px 25px">
      </a> </div>
  </div>
</div>


<?php
        
        mysql_select_db($database_sc, $sc);
		$query_str = 
		"SELECT a_pud, COUNT( a_pud ) AS member FROM  `memberdata` WHERE a_pud != '0'  GROUP BY a_pud";
        $Restr = mysql_query($query_str, $sc) or die(mysql_error());
        $row_str = mysql_fetch_assoc($Restr);
        $row_num = mysql_num_rows($Restr);
		
		?>

<div class="search_bt" align="center">
  <div style="width: 88%" align="center"><span>LIFE LINK註冊種類統計</span></div>
  		
  		<div class="htmleaf-content">
			<div style="width:70%;margin:0 auto">
			<div>
				<canvas id="canvas" height="280" width="600"></canvas>
			</div>
		</div>
		</div>
        <script src="js/Chart.js"></script>
	<script>
	var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
		var lineChartData = {
			labels : ["粉絲","小資","創業","企業","致富","總裁"],
			
			datasets : [
				{
					label: "My First dataset",
					fillColor : "rgba(220,220,220,0.2)",
					strokeColor : "rgba(220,220,220,1)",
					pointColor : "rgba(220,220,220,1)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "rgba(220,220,220,1)",
					data : [<?php do{
					echo "\"" ,$row_str['member'], "\",";
					}while($row_str = mysql_fetch_assoc($Restr));?>]
				}
			]

		}

	window.onload = function(){
		var ctx = document.getElementById("canvas").getContext("2d");
		window.myLine = new Chart(ctx).Line(lineChartData, {
			responsive: true
		});
	}
		
		
	</script>
<div style="text-align:center;clear:both">
</div>
  
</div>
<div class="search_table table-responsive " align="center" style="overflow-y: visible;white-space: nowrap;padding-top: 10px">
  <div class="search_bt" align="center">
    <div style="width: 88%" align="center"><span>人數統計</span></div>
  </div>
  <table align="center" class="table" style="width: 88%;margin: 10px auto 0px auto;line-height: 45px;background-color: #fff;border-radius: 10px" >
    <tr style="background-color: #4ab3a6;color: #fff ">
      <th style="border-radius: 10px 0px 0px 0px">粉絲</th>
      <th>小資</th>
      <th>創業</th>
      <th>企業</th>
      <th>致富</th>
      <th style="border-radius: 0px 10px 0px 0px">總裁</th>
    </tr>
    <?php

        mysql_select_db($database_sc, $sc);
		$query_str = 
		"SELECT a_pud, COUNT( a_pud ) AS member FROM  `memberdata` WHERE a_pud != '0'  GROUP BY a_pud";
        $Restr = mysql_query($query_str, $sc) or die(mysql_error());
        $row_str = mysql_fetch_assoc($Restr);
        $row_num = mysql_num_rows($Restr);
		?>
    <tr class="search_tr"  style="background-color: #fff">
      <?php do{
			echo "<td>".$row_str['member']. "</td>";
			}while($row_str = mysql_fetch_assoc($Restr));?>
    </tr>
  </table>
</div>
<div class="search_bt" align="center" style="margin-top: -22px;margin-bottom: 60px">
  <div style="width: 88%;" id="return_bt" align="left" > <a  style="cursor: pointer;" onClick="goBack()">
    <input type="button" value="< 返回" style="background: #595757; color: #fff;border: 0px ;border-radius: 5px;padding: 4px 25px">
    </a> </div>
</div>
<div class="col-lg-12 col-md-12 col-xs-12 st_pe_foot" style="height: 48px; background-color:#efefef; width: 100% ;position: fixed;bottom: 0px"> <img src="img/login_footlogo-01.png" width="320px" style="margin-top: 10px" alt=""> </div>
<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content col-lg-12 col-md-12 col-xs-12" style="padding-left: 0px;padding-right: 0px">
      <div class="col-lg-12 col-md-12 col-xs-12 mb_features" align="center" style="padding: 10px"> <a href="person_main.php"><img src="img/life_pay_logo-2.png" width="50%" alt=""></a> </div>
      <div class="col-lg-6 col-md-6 col-xs-6 mb_features"> <a href="person_qrcode.php"><img src="img/my_qr-01.png" width="100%" alt=""></a> </div>
      <div class="col-lg-6 col-md-6 col-xs-6 mb_features"> <a href="person_inquire.php"><img src="img/search-01.png" width="100%" alt=""></a> </div>
      <div class="col-lg-6 col-md-6 col-xs-6 mb_features"> <a href="person_coupon.php"><img src="img/coupon-01.png" width="100%" alt=""></a> </div>
      <div class="col-lg-6 col-md-6 col-xs-6 mb_features"> <a href="#"><img src="img/life_pay-01.png" width="100%" alt=""></a> </div>
    </div>
  </div>
</div>
<div class="modal fade" id="myModal2" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content " style="padding: 5px 20px;margin-top: 100px">
      <ul class="setting">
        <li><a href="mlogout.php"><img src="img/sign-out-option.svg" width="25px" alt=""><span style="margin-left: 8px">登出</span></a></li>
      </ul>
    </div>
  </div>
</div>
</body>
</html>
