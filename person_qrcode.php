<?php require_once('Connections/lp.php');mysql_query("set names utf8");?>
<?php 
session_start();
if ($_SESSION['MM_Username'] == ""){header(sprintf("Location: life_pay.php"));exit;
}
  $m_username = $_SESSION['mem'];//登入帳號
  $m_nick = $_SESSION['nick'];//登入暱稱
  $number = $_SESSION['number'];
  //echo $m_nick;
//echo $moneeey;echo $m_username;
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
</head>

<body style="background-color: #f6f6f6 ">

<div class="mebr_top" align="center">
  <a href="person_main.php"><img src="img/life_pay_logo.png" width="150px" alt=""></a>
  <a href="#"  data-toggle="modal" data-target="#myModal2"><img src=".mg/set_up-01.svg" style="position: absolute; right: 20px; top: 67px" width="25px" alt=""></a>
  <a href="#" data-toggle="modal" data-target="#myModal"><img src="img/ff-01.png" style="position: absolute; left: 20px; top: 67px" alt="" width="25px" ></a>
</div>
<div class="" style="padding: 15px;transform: translateY(10%);">
      <div align="center" style="width: 220px;margin: auto;padding: 10px">
        <img id='qrcode' src='#' style="width: 100%;border:4px solid #666"  />
        <script>
          content = encodeURIComponent('http://livelink.com.tw/table/coupon.php?&u=<?php echo $m_username?>');

          $("#qrcode").attr("src","http://chart.apis.google.com/chart?cht=qr&chl="+ content +"&chs=512x512");
        </script>
        
     
      </div>

<div class="search_bt" align="center" style="margin-top: 22px">
  <div style="width: 88%" >
    <a href="http://livelink.com.tw/table/coupon.php?&u=<?php echo $m_username?>"><input type="button" value="測試" style="background: #52de74; color: #fff;border: 0px ;border-radius: 5px;padding: 4px 50px"></a>
  </div></div>
<div class="search_bt" align="center" style="margin-top: 0px">
  <div style="width: 88%" >
    <a href="person_main.php"><input type="button" value="< 返回" style="background: #e64c60; color: #fff;border: 0px ;border-radius: 5px;padding: 4px 45px"></a>
  </div></div></div>
<div  class="col-lg-12 col-md-12 col-xs-12 st_pe_foot" style=";margin-top: 30px">
  @2016 LIFE LINK 串門子雲盟事業版權所有
</div>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    <div class="modal-content col-lg-12 col-md-12 col-xs-12" style="padding-left: 0px;padding-right: 0px">
    <div class="col-lg-12 col-md-12 col-xs-12 mb_features" align="center" style="padding: 10px">
    <a href="person_main.php"><img src="img/life_pay_logo-2.png" width="50%" alt=""></a>
  </div>
      <!-- <div class="col-lg-6 col-md-6 col-xs-6 mb_features">
    <a href="person_qrcode.php"><img src="../table/img/my_qr-01.png" width="100%" alt=""></a>
  </div>-->
  
  <!--<div class="col-lg-6 col-md-6 col-xs-6 mb_features">
    <a href="person_coupon.php"><img src="../table/img/coupon-01.png" width="100%" alt=""></a>
 
</div>-->
<div class="col-lg-6 col-md-6 col-xs-6 mb_features">
    <a href="person_inquire.php"><img src="img/search-01.png" width="100%" alt=""></a>
  </div>
  <div class="col-lg-6 col-md-6 col-xs-6 mb_features" style="text-align: center;">
    <a href="#"><img src="img/life_pay-01.png" width="100%" alt=""></a>
  </div>
  
    </div>
    </div>
</div>
<div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content " style="padding: 5px 20px;margin-top: 100px">
        <ul class="setting">
          <li><a href="#"><img src="img/user_ch.png" width="25px" alt=""><span style="margin-left: 8px">切換使用者</span></a></li>
          <li><a href="#"><img src="img/sign-out-option.svg" width="25px" alt=""><span style="margin-left: 8px">登出</span></a></li>
        </ul>
      </div>
    </div>
</div>
<div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content " style="padding: 5px 20px;margin-top: 100px">
        <ul class="setting">
          <li><a href="store_person.php"><img src="img/user_ch.png" width="25px" alt=""><span style="margin-left: 8px">切換使用者</span></a></li>
          <li><a href="#"><img src="img/sign-out-option.svg" width="25px" alt=""><span style="margin-left: 8px">登出</span></a></li>
        </ul>
      </div>
    </div>
</div>
</body>
</html>
