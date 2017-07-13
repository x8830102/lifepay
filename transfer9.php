<?php 
require_once('Connections/sc.php');mysql_query("set names utf8");
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
//echo $number;
//串串積分
mysql_select_db($database_sc, $sc);
$query_Recf_c = sprintf("SELECT * FROM c_cash WHERE number = '$number' order by id desc");
$Recf_c = mysql_query($query_Recf_c, $sc) or die(mysql_error());
$row_Recf_c = mysql_fetch_assoc($Recf_c);
$totalRows_Recf_c = mysql_num_rows($Recf_c);
//消費積分
mysql_select_db($database_sc, $sc);
$query_Recf_g = sprintf("SELECT * FROM g_cash WHERE number = '$number' order by id desc");
$Recf_g = mysql_query($query_Recf_g, $sc) or die(mysql_error());
$row_Recf_g = mysql_fetch_assoc($Recf_g);
$totalRows_Recf_g = mysql_num_rows($Recf_g);
//優惠券
$date = date("ymd");
mysql_select_db($database_lp, $lp);
$query_coupon = sprintf("SELECT * FROM coupon WHERE p_number = '$number' && effective_date>='$date' && complete='0' order by id desc");
$Recf_coupon = mysql_query($query_coupon, $lp) or die(mysql_error());
$row_coupon = mysql_fetch_assoc($Recf_coupon);
$total_coupon = mysql_num_rows($Recf_coupon);
//echo $row_Recf_r['csum'];


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
  <script>
    function tc(){
      var transcc = document.getElementById("transcc");
      var touser = document.getElementById("touser");
      var qqq = document.getElementById("qqq");
        if(touser.value == ""){
          $("#touser").css("background","pink");
          $("#tc_bt").css("display","none");
        }else if(transcc.value == ''){
          $("#transcc").css("background","pink");
          $("#tc_bt").css("display","none");
        }else if(qqq.value == "1"){
          document.getElementById("form1").submit();
        }
      
    }
</script>

</head>
<body class="person" style="height: 60vh">
<div class="mebr_top" align="center">
  <a href="person_main9.php"><img src="img/life_pay_logo-01.png" width="220px" alt=""></a>
  <!--<a href="#"  data-toggle="modal" data-target="#myModal2"><img src="img/set_up-01.svg" style="position: absolute; left: 20px; top: 67px" width="25px" alt=""></a>-->
</div>
<div class="mebr_info " align="center">
  <ul>
    <li class="col-lg-12"><div class="member_photo"><div class="mb_photo_bord"></div></div>  </li>
    <li class="col-lg-12">
      <ul class="mebr_account">
        <li style="margin-top: 5px;color: #595757;text-align: center;"><p style="margin-left: 15px">帳號：<?php echo  $m_username;?></p></li>
        <li style="min-width: 165px;white-space: nowrap; text-overflow:ellipsis;color: #595757;text-align: center;margin-top: 6px"><p style="margin-left: 15px">暱稱：<?php echo  $m_nick;?></p></li>
      </ul>
    </li>
  </ul>
</div>
<div class="maincontain">
<div class="mb_integral">
<a target="_blank" href="http://cmg588.com/life_link/login_mem.php" style="color: #595757">
  <div class="col-lg-4 col-md-4 col-xs-4 mb_integral_detal" >
    <div  align="center"><?php if($totalRows_Recf_c == 0){
      echo '0';
      }else{echo $row_Recf_c['csum'];}?></div>  
  <div align="center">串串積分</div>
  </div></a> 
  <a target="_blank" href="http://cmg588.com/life_link/login_mem.php" style="color: #595757">
  <div class="col-lg-4 col-md-4 col-xs-4 mb_integral_detal">
    <div  align="center"><?php if($totalRows_Recf_g == 0){
      echo '0';
      }else{echo $row_Recf_g['csum'];}?>
      </div>
    <div align="center">消費積分</div>
    
  </div> </a>   
<a href="person_coupon.php" style="color: #595757">
  <div class="col-lg-4 col-md-4 col-xs-4 mb_integral_detal" style="border-right: 0px">
    <div  align="center"><?php $coupon = 0;for($i=1 ;$i<=$total_coupon ;$i++){$coupon = $coupon+$row_coupon['discount']; $row_coupon = mysql_fetch_assoc($Recf_coupon); }  echo $coupon;?>
   </div>
   <div align="center">折扣券</div>
    
  </div></a>
  
</div>

<?php 
$touser = $_GET['touser'];
if($touser != ''){
  mysql_select_db($database_sc, $sc);
  $query_user = sprintf("SELECT * FROM memberdata WHERE m_username = '$touser'");
  $Recf_user = mysql_query($query_user, $sc) or die(mysql_error());
  $row_user = mysql_fetch_assoc($Recf_user);
  $totaluser = mysql_num_rows($Recf_user);
  if($totaluser != 0){
    $user_number = $row_user['number'];
    $chu = "正確";
    $qqq = "1"; 
  }else{
    $chu = "沒有此帳號";
    $qqq = '0';
  }
}

?>
<div class="col-lg-12 col-xs-12 pr_features" style="background-color: #fff;" align="center">
  <span style="font-size: 18px;color: red;margin-left: 3px"><?php echo $messge;?></span>
  <div style="margin:15px auto;overflow-y: visible;white-space: nowrap;" align="center" class=" table-responsive ">
  <table class="table" style="width: 40%;margin: 10px auto 0px auto;line-height: 45px;background-color: #fff;border-radius: 10px"> 
    <tr>
      <td style="text-align: left;border-top: 0px">
        <form action="transfer9.php" method="get">轉帳對象：</td>
      <td  colspan="2"  style="text-align: left;border-top: 0px"><input type="text" id="touser" name="touser" style="width: 135px" placeholder="請輸入帳號" value="<?php echo $_GET['touser']?>"></td>
    </tr>
    <tr >
      <td colspan="3" style="text-align: left;border-top: 0px;line-height: 20px;margin-bottom: 10px"><input type="submit" value="檢查帳號" style="margin-left: 56%;border-radius: 5px;background-color: #e97a23;border: 0px;color: #fff;padding: 5px"><span style="font-size: 12px;color: red;margin-left: 3px"><?php echo $chu?></span>
        </form></td>
    </tr>
    <tr >
      <td style="text-align: left;border-top: 0px">
        <form id="form1" action="transfer_c.php" method="post">您目前的串串積分：</td>
        <input type="hidden" id="touser" name="touser" value="<?php echo $_GET['touser']?>">
        <input type="hidden" name="user_number" value="<?php echo $user_number?>">
        <input type="hidden" name="csum" value="<?php echo $row_Recf_c['csum']?>">
        <input type="hidden" id="qqq" name="qqq" value="<?php echo $qqq;?>">
      <td colspan="2" style="text-align: left;border-top: 0px"><?php echo $row_Recf_c['csum']?></td>
    </tr>
    <tr>
      <td style="text-align: left;border-top: 0px">您要轉的積分：</td>
      <td colspan="2" style="text-align: left;border-top: 0px"><input type="number" min="0" id="transcc" name="transcc" style="width: 135px"></td>
    </tr>
    <tr>
      <td  colspan="3" style="border-top: 0px;text-align:center;"><input id="tc_bt" style="width: 60%;background-color: #0059c8;border-radius: 35px;color: #fff;height: 40px;border:0px" type="button" value="確定轉帳" onClick="tc()">
        </form></td>
    </tr>
  </table>
    
</div></div>
  
 <div class="col-lg-12 col-md-12 col-xs-12 st_pe_foot" style="height: 50px; background-color:#efefef; width: 100% ;position: fixed;">
   <img src="img/login_footlogo-01.png" width="320px" style="margin-top: 10px" alt="">
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
