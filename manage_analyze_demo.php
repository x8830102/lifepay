<?php
require_once('Connections/lp.php');mysql_query("set names utf8");
require_once('Connections/sc.php');mysql_query("set names utf8");

session_start();
if ($_SESSION['MM_Username'] == ""){
  header(sprintf("Location: manage_main.php"));exit;
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
  function check(){ //日期搜尋檢定
  <?php 
  $industry=$_GET['industry'];
	$store =$_GET['store'];?>
  var sd1 = document.getElementById('sd1');
  var sd2 = document.getElementById('sd2');


  if(sd1.value == "" && sd2.value != ""){
  document.getElementById('sd1').style.background="pink";
  }else if(sd1.value != "" && sd2.value == ""){
  document.getElementById('sd2').style.background="pink";
  }else if(sd1.value > sd2.value){
  document.getElementById('sd1').style.background="pink";
  document.getElementById('sd2').style.background="pink";
  }else{
  document.getElementById('form1').submit();
  }
}

  function goBack() {
    window.history.back();
  }

  </script>
</head>
<body class="person"  style="min-height: 800px">
<div class="mebr_top" align="center" > <a href="person_main.php"><img src="img/life_pay_logo-01.png" width="220px" alt=""></a> <a href="#"  data-toggle="modal" data-target="#myModal2"><img src="img/set_up-01.svg" style="position: absolute; left: 20px; top: 67px" width="25px" alt=""></a>
  <div class="search_bt" align="center" style="margin-top: -22px;display: none;" id="back">
    <div style="width: 88%" align="left"> <a style="cursor: pointer;" onClick="goBack()">
      <input type="button" value="< 返回" style="background: #595757; color: #fff;border: 0px ;border-radius: 5px;padding: 4px 25px">
      </a> </div>
  </div>
</div>
<form id="form1" action="manage_analyze_demo.php" method="get">
  <div class="search_bt" align="center" style="display: inline-block;text-align: left;">
    <div style="width: 90%" align="center" >
      <ul class="person_search">
        <li>
          <input type="date" name="sd1"  id="sd1" value="<?php echo $_GET['sd1'];?>" style="height: 40px;min-width: 120px; display: inline-block">
        </li>
        <li><span style="margin: 4px;line-height: 40px">至</span></li>
        <li>
          <input type="date" name="sd2"  id="sd2" value="<?php echo $_GET['sd2'];?>" style="height: 40px;min-width: 120px;display: inline-block;">
        </li>
        <li> <input type="text" name="store" placeholder="輸入店家名稱"  value="<?php echo $_GET['store'];?>" style="height: 40px;border:1px solid #fff;-webkit-appearance:none;padding: 5px;line-height: 40px"></li>
        <li> <select name="industry" id="industry" placeholder="產業 : " style="width: 200px;text-align: left;height: 40px;border:1px solid #fff;line-height: 40px">
	            <option value="" style="color:#999999">選擇產業別</option>
	            <option value="餐飲業">餐飲業</option>
	            <option value="服飾業">服飾業</option>
	            <option value="資訊業">資訊業</option>
	            <option value="飯店業">飯店業</option>
	            <option value="零售業">零售業</option>
	            <option value="其他">其他</option>
	            </select></li>
                                
        <li>
          <button type="button" style="margin-left: 8px;line-height: 35px" class="date_but" onClick="check()">查詢</button>
        </li>
      </ul>
    </div>
  </div>
</form>

<?php
        if ($_GET['sd1'] != "") { //日期&商家
        $sd1=$_GET['sd1'];
        $sd2=$_GET['sd2'];
        mysql_select_db($database_lp, $lp);
		$query_str = 
		"SELECT Invoice.nick, SUM( Invoice.total ) AS total,SUM( Invoice.usage_fee) AS total_usage, YEAR( Invoice.date ) AS YEAR, MONTH( Invoice.date ) AS MONTH , lf_user.industry
		FROM Invoice, lf_user 
		WHERE Invoice.accont = lf_user.accont && Invoice.nick like '%$store%' && Invoice.date >= '$sd1' && Invoice.date <= '$sd2' && lf_user.industry like '%$industry%' 
		GROUP BY YEAR( DATE ) , MONTH( DATE ) 
		ORDER BY MONTH";
        $Restr = mysql_query($query_str, $lp) or die(mysql_error());
        $row_str = mysql_fetch_assoc($Restr);
        $row_num = mysql_num_rows($Restr);
		$year = $row_str['YEAR'];
		
        } else {
        mysql_select_db($database_lp, $lp); //無日期
		$query_str = 
		"SELECT Invoice.nick, SUM( Invoice.total ) AS total,SUM( Invoice.usage_fee) AS total_usage, YEAR( Invoice.date ) AS YEAR, MONTH( Invoice.date ) AS MONTH , lf_user.industry
		FROM Invoice, lf_user 
		WHERE Invoice.accont = lf_user.accont && Invoice.nick like '%$store%' && lf_user.industry like '%$industry%' 
		GROUP BY YEAR( DATE ) , MONTH( DATE ) 
		ORDER BY MONTH";
        $Restr = mysql_query($query_str, $lp) or die(mysql_error());
        $row_str = mysql_fetch_assoc($Restr);
        $row_num = mysql_num_rows($Restr);
		$year = $row_str['YEAR'];
    }
		?>

<div class="search_bt" align="center">
  <div style="width: 88%" align="center"><span><?php echo $_GET['store'], $year;?>交易統計分析</span></div>
  		
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
			labels : ["January","February","March","April","May","June","July","August","September","October","November","December"],
			
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
					echo "\"" ,$row_str['total'], "\",";
					}while($row_str = mysql_fetch_assoc($Restr));?>]
				},
				
				<?php
        if ($_GET['sd1'] != "") { //日期&商家
        $sd1=$_GET['sd1'];
        $sd2=$_GET['sd2'];
        mysql_select_db($database_lp, $lp);
		$query_str = 
		"SELECT Invoice.nick, SUM( Invoice.total ) AS total,SUM( Invoice.usage_fee) AS total_usage, YEAR( Invoice.date ) AS YEAR, MONTH( Invoice.date ) AS MONTH , lf_user.industry
		FROM Invoice, lf_user 
		WHERE Invoice.accont = lf_user.accont && Invoice.nick like '%$store%' && Invoice.date >= '$sd1' && Invoice.date <= '$sd2' && lf_user.industry like '%$industry%' 
		GROUP BY YEAR( DATE ) , MONTH( DATE ) 
		ORDER BY MONTH";
        $Restr = mysql_query($query_str, $lp) or die(mysql_error());
        $row_str = mysql_fetch_assoc($Restr);
        $row_num = mysql_num_rows($Restr);
		
        } else {
        mysql_select_db($database_lp, $lp); //無日期
		$query_str = 
		"SELECT Invoice.nick, SUM( Invoice.total ) AS total,SUM( Invoice.usage_fee) AS total_usage, YEAR( Invoice.date ) AS YEAR, MONTH( Invoice.date ) AS MONTH , lf_user.industry
		FROM Invoice, lf_user 
		WHERE Invoice.accont = lf_user.accont && Invoice.nick like '%$store%' && lf_user.industry like '%$industry%' 
		GROUP BY YEAR( DATE ) , MONTH( DATE ) 
		ORDER BY MONTH";
        $Restr = mysql_query($query_str, $lp) or die(mysql_error());
        $row_str = mysql_fetch_assoc($Restr);
        $row_num = mysql_num_rows($Restr);
    }
		?>
				{
					label: "My Second dataset",
					fillColor : "rgba(151,187,205,0.2)",
					strokeColor : "rgba(151,187,205,1)",
					pointColor : "rgba(151,187,205,1)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "rgba(151,187,205,1)",
					data : [<?php do{
					echo "\"" ,$row_str['total_usage'], "\",";
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
    <div style="width: 88%" align="center"><span>銷售紀錄</span></div>
  </div>
  <table align="center" class="table" style="width: 88%;margin: 10px auto 0px auto;line-height: 45px;background-color: #fff;border-radius: 10px" >
    <tr style="background-color: #4ab3a6;color: #fff ">
      <th style="border-radius: 10px 0px 0px 0px">日期</th>
      <th>商店名稱</th>
      <th>產業別</th>
      <th>銷售金額</th>
      <th>消費積分</th>
      <th>串串積分</th>
      <th style="border-radius: 0px 10px 0px 0px">支付店家</th>
    </tr>
    <?php
    if ($_GET['sd1'] != "") { //日期&商家
        $sd1=$_GET['sd1'];
        $sd2=$_GET['sd2'];
        mysql_select_db($database_lp, $lp);
		$query_str = 
		"SELECT Invoice.*, YEAR( Invoice.date ) AS YEAR, MONTH( Invoice.date ) AS MONTH , lf_user.industry 
		FROM Invoice, lf_user 
		WHERE Invoice.accont = lf_user.accont && Invoice.nick like '%$store%' && Invoice.date >= '$sd1' && Invoice.date <= '$sd2' && lf_user.industry like '%$industry%' 
		GROUP BY Invoice.total, YEAR( DATE ) , MONTH( DATE )  
		ORDER BY id DESC";
        $Restr = mysql_query($query_str, $lp) or die(mysql_error());
        $row_str = mysql_fetch_assoc($Restr);
        $row_num = mysql_num_rows($Restr);
		$nick = $row_str['nick'];
		$industcheck = $row_str['industry'];
		
        } else {
        mysql_select_db($database_lp, $lp); //無日期
		$query_str = 
		"SELECT Invoice.*, YEAR( Invoice.date ) AS YEAR, MONTH( Invoice.date ) AS MONTH , lf_user.industry 
		FROM Invoice, lf_user 
		WHERE Invoice.accont = lf_user.accont && Invoice.nick like '%$store%' && lf_user.industry like '%$industry%' 
		GROUP BY Invoice.total, YEAR( DATE ) , MONTH( DATE ) 
		ORDER BY id DESC";
        $Restr = mysql_query($query_str, $lp) or die(mysql_error());
        $row_str = mysql_fetch_assoc($Restr);
        $row_num = mysql_num_rows($Restr);
		$nick = $row_str['nick'];
		$industcheck = $row_str['industry'];
    }

	$a=false;
	$b=false;
	
    if($row_num != 0){
      do{ 
	  	//銷售金額
        $total_sell = $row_str['total'];
        $sell_sum = $sell_sum + $total_sell;
        //消費積分總額
        $g = $row_str['g'];
        $g_sum = $g_sum + $g;
        //串串積分總額
        $c = $row_str['c'];
        $c_sum = $c_sum + $c;
        //支付店家
        $usage_fee = $row_str['usage_fee'];
        $usage_fee_sum = $usage_fee_sum + $usage_fee;
		
		$nick_nick = $row_str['nick'];
		$industcheck_check = $row_str['industry'];

        if(strcmp($nick,$nick_nick)!=0){ //比對是否相等
            $a= true;
        }
		
		if(strcmp($industcheck,$industcheck_check)!=0){
			$b= true;
			}


      }while($row_str = mysql_fetch_assoc($Restr));}?>
    <tr class="search_tr"  style="background-color: #fff">
      <td style="border-radius: 0px 0px 0px 10px"><?php echo $sd1."~".$sd2;?></td>
      <?php
          if($a){
            echo '<td></td>';
          }else{
            echo '<td>'.$nick.'</td>';
          }
          ?>
      <?php
          if($b){
            echo '<td></td>';
          }else{
            echo '<td>'.$industcheck.'</td>';
          }
          ?>
      <td><?php echo number_format($sell_sum);?></td>
      <td><?php echo number_format($g_sum);?></td>
      <td><?php echo number_format($c_sum);?></td>
      <td style="border-radius: 0px 0px 10px 0px"><?php echo number_format($usage_fee_sum);?></td>
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
        <li><a href="logout.php"><img src="img/sign-out-option.svg" width="25px" alt=""><span style="margin-left: 8px">登出</span></a></li>
      </ul>
    </div>
  </div>
</div>
</body>
</html>
