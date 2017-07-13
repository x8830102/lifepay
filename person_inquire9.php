<?php 
require_once('Connections/lp.php');mysql_query("set names utf8");
?>
<?php 
session_start();
if ($_SESSION['MM_Username'] == ""){header(sprintf("Location: life_pay.php"));exit;
}else{
  $m_username = $_SESSION['mem'];//登入帳號
  $m_nick = $_SESSION['nick'];//登入暱稱
  $number = $_SESSION['number'];
}
if ($_GET['sd1'] != "" && $_GET['sd2'] || $_GET['store'] != "") { //日期&商家
$sd1=$_GET['sd1'];
$sd2=$_GET['sd2'];
$store=$_GET['store'];
mysql_select_db($database_lp, $lp);
$query_str = "SELECT * FROM complete WHERE p_number ='$number' && date >= '$sd1' && date <= '$sd2' && s_nick like '%$store%' ORDER BY id DESC";
$Restr = mysql_query($query_str, $lp) or die(mysql_error());
$row_str = mysql_fetch_assoc($Restr);
$row_num = mysql_num_rows($Restr);
} else if($_GET['store'] != "") { //商家
$store=$_GET['store'];
mysql_select_db($database_lp, $lp);
$query_str = "SELECT * FROM complete WHERE p_number ='$number' && s_nick like '%$store%' ORDER BY id DESC";
$Restr = mysql_query($query_str, $lp) or die(mysql_error());
$row_str = mysql_fetch_assoc($Restr);
$row_num = mysql_num_rows($Restr);
} else {
mysql_select_db($database_lp, $lp); //無條件
$query_str = "SELECT * FROM complete WHERE p_number ='$number'  ORDER BY id DESC";
$Restr = mysql_query($query_str, $lp) or die(mysql_error());
$row_str = mysql_fetch_assoc($Restr);
$row_num = mysql_num_rows($Restr);
}
//echo $row_num;

$nick = $row_str['s_nick'];
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>
  <script>

function check(){ //日期搜尋檢定
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
<style>
  @media (min-width: 1200px){
    .search_bt{padding-left: 7.1%}
  }
  @media (min-width: 1024px){
    .search_bt{padding-left: 7.1%}
  }
</style>
<body class="person" style="min-height: 800px">


<div class="mebr_top" align="center">
  <a href="person_main9.php"><img src="img/life_pay_logo-01.png" width="220px" alt=""></a>
  <!--<a href="#"  data-toggle="modal" data-target="#myModal2"><img src="img/set_up-01.svg" style="position: absolute; left: 20px; top: 67px" width="25px" alt=""></a>-->
 <div class="search_bt " align="center" style="margin-top: -22px;display: none;" id="back">
  <div style="width: 88%" align="left">
    <a style="cursor: pointer;" onClick="goBack()"><input type="button" value="< 返回" style="background: #595757; color: #fff;border: 0px ;border-radius: 5px;padding: 4px 25px"></a>
  </div></div>
</div>

<form id="form1" action="person_inquire.php" method="get">
	<div class="search_bt" align="center" style="display: inline-block;text-align: left;">
		<div style="width: 90%" align="center" >
    <ul class="person_search">
      <li><input type="date" name="sd1"  id="sd1" value="<?php echo $_GET['sd1'];?>" style="height: 40px;min-width: 120px; display: inline-block"></li>
      <li><span style="margin: 4px;line-height: 40px">至</span></li>
      <li><input type="date" name="sd2"  id="sd2" value="<?php echo $_GET['sd2'];?>" style="height: 40px;min-width: 120px;display: inline-block;"></li>
      <li><span style="margin: 4px;line-height: 40px">在</span></li>
      <li> <input type="text" name="store" placeholder="輸入店家名稱"  value="<?php echo $_GET['store'];?>" style="height: 40px;border:1px solid #fff;-webkit-appearance:none;padding: 5px;line-height: 40px"></li>
      <li><button type="button" style="margin-left: 8px;line-height: 35px" class="date_but" onClick="check()">查詢</button></li>
    </ul>
    </div>
	</div>  
</form>
  <div class="search_table table-responsive " align="center" style="overflow-y: visible;white-space: nowrap;">
    <table align="center" class="table coupon_table" style="background-color: #fff;border-radius: 10px" >
    <tr style="background-color: #4ab3a6;color: #fff ">
	  <th style="border-radius: 10px 0px 0px 0px">消費日期</th>
	  <th>消費店家</th>
	  <th>消費金額</th>
    <th>使用券額</th>
    <th>使用券折抵後額</th>
	  <th>消費積分</th>
	  <th>串串積分</th>
    <th>付費金額</th>
    <th>付款情形</th>
    <th  style="border-radius: 0px 10px 0px 0px">折扣券額</th>
		</tr>
    <?php if($row_num != 0){
      do{?>
        <tr class="search_tr"  style="background-color: #fff">
          <td  style="border-radius: 0px 0px 0px 10px"><?php echo $row_str['date'];?></td>
          <td><?php echo $row_str['s_nick'];?></td>
          <td><?php echo number_format($row_str['total_cost']);?></td>
          <td><?php echo number_format($row_str['discount']);?></td>
          <td><?php echo number_format($row_str['total_cost']-$row_str['discount']);?></td>
          <td><?php echo number_format($row_str['g']);?></td>
          <td><?php echo number_format($row_str['c']);?></td>
          <td><?php echo number_format($row_str['spend']);?></td>
          <?php if($row_str['confirm']==1){?>
            <td  ><span style="color: #22a363">已扣款</span></td>
            <?php }else{?>
            <td ><span style="color: #ff5e59">未扣款</span></td>
            <?php }?>
          <td class="table_br" ><a href="person_coupon.php"><?php echo number_format($row_str['next_discount']);?></a></td>
        </tr>
      <?php 
  	  
  	  }while($row_str = mysql_fetch_assoc($Restr));

          

      }?>
        
    </table>
    
    
    
    <table align="center" class="table" style="width: 88%;margin: 10px auto 0px auto;line-height: 45px;background-color: #fff;border-radius: 10px" >
    <tr style="background-color: #4ab3a6;color: #fff ">
	  <th style="border-radius: 10px 0px 0px 0px">個人日期</th>
	  <th>消費店家</th>
	  <th>消費金額</th>
    <th>抵用券</th>
	  <th>消費積分</th>
	  <th>串串積分</th>
    <th style="border-radius: 0px 10px 0px 0px">現金/信用卡</th>
		</tr>
    <?php 

    if ($_GET['sd1'] != "" && $_GET['sd2'] || $_GET['store'] != "") { //日期&商家
        $sd1=$_GET['sd1'];
        $sd2=$_GET['sd2'];
        $store=$_GET['store'];
        mysql_select_db($database_lp, $lp);
        $query_str = "SELECT * FROM complete WHERE p_number ='$number' && date >= '$sd1' && date <= '$sd2' && s_nick like '%$store%' ORDER BY id DESC";
        $Restr = mysql_query($query_str, $lp) or die(mysql_error());
        $row_str = mysql_fetch_assoc($Restr);
        $row_num = mysql_num_rows($Restr);
        $s_nick = $row_str['s_nick'];
        } else if($_GET['store'] != "") { //商家
        $store=$_GET['store'];
        mysql_select_db($database_lp, $lp);
        $query_str = "SELECT * FROM complete WHERE p_number ='$number' && s_nick like '%$store%' ORDER BY id DESC";
        $Restr = mysql_query($query_str, $lp) or die(mysql_error());
        $row_str = mysql_fetch_assoc($Restr);
        $row_num = mysql_num_rows($Restr);
        $s_nick = $row_str['s_nick'];
        } else {
        mysql_select_db($database_lp, $lp); //無條件
        $query_str = "SELECT * FROM complete WHERE p_number ='$number'  ORDER BY id DESC";
        $Restr = mysql_query($query_str, $lp) or die(mysql_error());
        $row_str = mysql_fetch_assoc($Restr);
        $row_num = mysql_num_rows($Restr);
    }

    $a=false;

    if($row_num != 0){
      do{ 
        $tatal_cost = $row_str['total_cost'];
        $consume_sum = $consume_sum + $tatal_cost;
        //使用券總額
        $discount = $row_str['discount'];
        $discount_sum = $discount_sum + $discount;
        //消費積分總額
        $g = $row_str['g'];
        $g_sum = $g_sum + $g;
        //串串積分總額
        $c = $row_str['c'];
        $c_sum = $c_sum + $c;
        //實際消費額
        $spend = $row_str['spend'];
        $spend_sum = $spend_sum + $spend;

        $nick_nick = $row_str['s_nick'];

        if(strcmp($s_nick,$nick_nick)!=0){ //比對是否相等
            $a= true;
        }

      }while($row_str = mysql_fetch_assoc($Restr));}?>

        <tr class="search_tr"  style="background-color: #fff">
          <td style="border-radius: 0px 0px 0px 10px"><?php echo $sd1."~".$sd2;?></td>
          <?php
          if($a){
            echo '<td></td>';
          }else{
            echo '<td>'.$s_nick.'</td>';
          }
          ?>
          <td><?php echo number_format($consume_sum);?></td>
          <td><?php echo number_format($discount_sum);?></td>
          <td><?php echo number_format($g_sum);?></td>
          <td><?php echo number_format($c_sum);?></td>
          <td class="table_br" ><?php echo number_format($spend_sum);?></td>
        </tr>
      
    </table>
  </div>
  <div class="search_bt" align="center" style="margin-top: -22px;margin-bottom: 20px">
  <div style="width: 88%; " align="left" id="return_bt">
    <a href="person_main.php"><input type="button" value="< 返回" style="background: #595757; color: #fff;border: 0px ;border-radius: 5px;padding: 4px 25px"></a>
  </div></div>
<div class="col-lg-12 col-md-12 col-xs-12 st_pe_foot" style="height: 48px; background-color:#efefef; width: 100% ;position: relative;top: 1px">
   <img src="img/login_footlogo-01.png" width="320px" style="margin-top: 10px" alt="">
 </div>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    <div class="modal-content col-lg-12 col-md-12 col-xs-12" style="padding-left: 0px;padding-right: 0px">
    <div class="col-lg-12 col-md-12 col-xs-12 mb_features" align="center" style="padding: 10px">
    <a href="person_main.php"><img src="img/life_pay_logo-2.png" width="50%" alt=""></a>
  </div>
       <!--<div class="col-lg-6 col-md-6 col-xs-6 mb_features">
    <a href="person_qrcode.php"><img src="../table/img/my_qr-01.png" width="100%" alt=""></a>
  </div>-->
  <div class="col-lg-6 col-md-6 col-xs-6 mb_features">
    <a href="person_inquire.php"><img src="img/search-01.png" width="100%" alt=""></a>
  </div>
  <!--<div class="col-lg-6 col-md-6 col-xs-6 mb_features">
    <a href="person_coupon.php"><img src="../table/img/coupon-01.png" width="100%" alt=""></a>
 
</div>-->
  <div class="col-lg-6 col-md-6 col-xs-6 mb_features">
    <a href="#"><img src="img/life_pay-01.png" width="100%" alt=""></a>
  </div>
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
