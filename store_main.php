<?php require_once('Connections/sc.php');mysql_query("set names utf8");
require_once('Connections/lp.php');mysql_query("set names utf8");
?>
<?php 
session_start();
if ($_SESSION['MM_Username'] == ""){header(sprintf("Location: store_login.php"));exit;
}else{
  $m_username = $_SESSION['mem'];
  $m_nick = $_SESSION['nick'];
  $number = $_SESSION['number'];//驗證帳號
  $user_id = $_SESSION['MM_Username'];
}

$date = date("Y-m-d");
$check = $_GET['check'];
//如果沒有列數就表示不是商店端的帳號
mysql_select_db($database_lp, $lp);
$SQL = sprintf("SELECT * FROM lf_user WHERE number ='$number' ");
$conn = mysql_query($SQL, $lp) or die(mysql_error());
$row = mysql_fetch_assoc($conn);
$total = mysql_num_rows($conn);
if($total == 0){
    //表示是使用者連過來的
    mysql_select_db($database_sc, $sc);
    $query_user = sprintf("SELECT * FROM memberdata WHERE number ='$number' ");
    $Reuser = mysql_query($query_user, $sc) or die(mysql_error());
    $row_user = mysql_fetch_assoc($Reuser);
    $total_user = mysql_num_rows($Reuser);
    if($total_user != ''){
      header(sprintf("Location: logout.php"));exit;
    }
}

//
mysql_select_db($database_lp, $lp);
$emp = sprintf("SELECT * FROM lf_user WHERE user_id ='$user_id' ");
$con_em = mysql_query($emp, $lp) or die(mysql_error());
$row_em = mysql_fetch_assoc($con_em);
$level = $row_em['level'];
$disexp = $row_em['disexp'];

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
  <link rel="stylesheet" type="text/css" href="dist/sweetalert.css">
  <link rel="stylesheet" href="css/iziToast.min.css">
  <script src="dist/sweetalert.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  <script>
    function be(){
      swal({
        title: "優惠券核發日期已過期!",
        text: "請到更改資料 重新設定",
        timer: 5000,
        animation: "slide-from-top",
        imageUrl: "img/Warning.png",
        showConfirmButton: false
      });
    }
    function equal(){
      swal({
        title: "優惠券核發日期將要過期!",
        text: "請到更改資料 重新設定",
        timer: 2000,
        animation: "slide-from-top",
        imageUrl: "img/Warning.png",
        showConfirmButton: false
      });
    }
    function complete(){
    swal({
      title: '結帳完成',
      text: '感謝您使用串門子LIFE PAY系統',
      timer: 2000,
      type: 'success',
      animation: "slide-from-top"
    });
  }
  </script>
</head>
<body style="background-color:#f5c1ad">
<?php
if($disexp<$date)
  {?>
  <script>be();</script>
<?php 
  }
  else if($disexp==$date)
  {?>
  <script>equal();</script>
<?php 
  }
  if($check){?>
    <script>complete();</script>
<?php
  }
?>



<div class="mebr_top" align="center">
  <img src="img/life_pay_logo-01.png" width="220px" alt="">
  <a href="#" data-toggle="modal" data-target="#myModal2"><img src="img/set_up-01.svg" style="position: absolute; left: 20px; top: 67px" width="25px" alt=""></a>
</div>
<div class="mebr_info " align="center">
  <ul>
    <li class="col-lg-3 col-md-3 col-xs-4"><div class="member_photo" style=""><div class="mb_photo_bord"></div></div>  </li>
    <li class="col-lg-9 col-md-9 col-xs-8">
      <ul class="mebr_account">
        <li style="margin-top: 5px;">帳號： <?php echo $m_username;?></li>
        <li style="min-width: 165px;white-space: nowrap; text-overflow:ellipsis"> <?php echo $m_nick;?></li>
      </ul>
    </li>
  </ul>
</div>

<div class="col-lg-12 col-md-12 col-xs-12" style="height: 5px; background-color:#f5c1ad;width: 100%;margin-top: 15px">
  
  
  
</div>

  <!--<div class="col-lg-6 col-md-6 col-xs-6 mb_features">
    <a target="_blank" href="https://webqr.com/"><img src="../table/img/my_qr-01.png" width="100%" alt=""></a>
  </div>-->
  <div class="col-lg-6 col-md-6 col-xs-6 mb_features" style="background: #fff;" align="center">
    <a href="store_search.php"><img src="img/st-search-01.png" alt=""></a>
  </div>
  　　<!--<div class="col-lg-6 col-md-6 col-xs-6 mb_features">
    <a href="store_coupon.php"><img src="../table/img/coupon-01.png" width="100%" alt=""></a>
 
</div>-->
  <div class="col-lg-6 col-md-6 col-xs-6 mb_features mt" style="background: #fff;" align="center">
    <a href="store_checkout.php"><img src="img/checkout-01.png"  alt=""></a>
  </div>
  
 <div class="col-lg-12 col-md-12 col-xs-12 st_pe_foot" style="height: 48px; background-color:#efefef; width: 100% ;position: fixed;">
   <img src="img/login_footlogo-01.png" width="320px" style="margin-top: 10px" alt="">
 </div>
  <div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content " style="padding: 5px 20px;margin-top: 100px">
        <ul class="setting">
		  <?php if($level =="boss")
		  {?>
		  <li><a href="store_update.php" ><img src="img/sign-out-option.svg" width="25px" alt=""><span style="margin-left: 8px">更改資料</span></a></li>
		  <li><a href="store_establish.php"><img src="img/user_ch.png" width="25px" alt=""><span style="margin-left: 8px">建立員工帳號</span></a></li>
      <li><a href="store_modify.php"><img src="img/user_setup.png" width="25px" alt=""><span style="margin-left: 8px">修改員工帳號</span></a></li>
      <li><a href="Invoice.php"><img src="img/user_setup.png" width="25px" alt=""><span style="margin-left: 8px">請款</span></a></li>
		  <?php 
		  }else{?>
      <li><a href="store_ur.php"><img src="img/sign-out-option.svg" width="25px" alt=""><span style="margin-left: 8px">切換使用者</span></a></li>
      <?php 
      }?>
          <li><a href="slogout.php" ><img src="img/sign-out-option.svg" width="25px" alt=""><span style="margin-left: 8px">登出</span></a></li>
        </ul>
      </div>
    </div>
</div>
</body>
<script src="js/iziToast.min.js" type="text/javascript"></script>
<script src="js/demo.js" type="text/javascript"></script>
</html>
