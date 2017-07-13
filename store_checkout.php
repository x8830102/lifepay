<?php 
require_once('Connections/sc.php');mysql_query("set names utf8");
require_once('Connections/lp.php');mysql_query("set names utf8");

session_start();
if ($_SESSION['MM_Username'] == ""){header(sprintf("Location: store_login.php"));exit;
}else{
  $number = $_SESSION['number'];//驗證帳號
  $user_id = $_SESSION['MM_Username'];
}

//如果沒有列數就表示不是商店端的帳號
mysql_select_db($database_lp, $lp);
$SQL = sprintf("SELECT * FROM lf_user WHERE number ='$number' ");
$conn = mysql_query($SQL, $lp) or die(mysql_error());
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
$dis = $row_em['st_dis'];
$disexp = $row_em['disexp'];

$date = date("Y-m-d");

?>
<script>
function error(){
	var moneeey = document.getElementById('moneeey').value;
	if(moneeey == "" || moneeey <= 0)
	{
		document.getElementById('error').style.display="";
	}else{
		document.getElementById('form_ney').submit();
	}
}
function ValidateNumber(e, pnumber){
	if (!/^\d+$/.test(pnumber))
	{
		e.value = /^\d+/.exec(e.value);
	}
		return false;
	}
</script>
<!DOCTYPE html>
<html>
<head>
  <title>LIFE PAY</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" href="css/iziToast.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  

</head>
<?php 
if($disexp<$date)
{?>
  <input type="hidden" id="warning" name="warning" value="1">
<?php }
?>
<body style="background-color:#f5c1ad ">

<div class="mebr_top" align="center">
  <a href="store_main.php"><img src="img/life_pay_logo-01.png" width="220px" alt=""></a>
  <a href="#" data-toggle="modal" data-target="#myModal2"><img src="img/set_up-01.svg" style="position: absolute; left: 20px; top: 67px" width="25px" alt=""></a>

</div>
<div class="navtop" >
<div class="col-lg-6 col-md-6 col-xs-6" align="center" style="top: -2px;border-right: 2px solid #fff"><a href="store_search.php"><img src="img/long_search-01.png" alt="" width="120px" ></a></div>
  <div class="col-lg-6 col-md-6 col-xs-6" align="center" style="top: -2px"><a href="store_checkout.php"><img src="img/long_checkout-01.png" alt="" width="120px"></a></div>
</div>
<div class="" style=";padding: 15px">
  
  <form id="form_ney" name="form_ney" method="post" action="store_qrcode.php">
    <div style="padding: 20px;" align="center">
      <div id="home" align="center" style="padding: 20px">
        <div><img src="img/money-01.png" alt="" width="40px"></div>
        <h1 align="center" style="">結帳金額</h1>
        <div align="center">
          <input name="moneeey" id="moneeey" type="tel" min="1" placeholder="輸入交易金額" style="width: 22%;min-width: 220px;text-align: center;height: 50px;border:0px;line-height: 35px;font-size: 20px;margin-top: 30px; webkit-inner-spin-button: none;" autocomplete="off" onKeyUp="ValidateNumber(this,value)"><br>
		    <script> //限制不能超過100
		  /*function limit()
		  {
			  
			  var diss = document.getElementById("diss");
			  if(Number(diss.value)>100)
			  {diss.value = "";}
		  }*/</script>
          <input name="diss" id="diss" type="hidden" max="100" placeholder="輸入折扣%數" value="<?php echo $dis;?>" style="width: 22%;min-width: 220px;text-align: right;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;margin-top: 30px" onKeyUp="limit()">
		
          <input type="hidden" name="MM_insert" value="form_ney" />
		  <input type="hidden" name="key" value="<?php $datetime = date("Y-m-d H:i:s"); echo $datetime;?>">
		  <input type="button" value="提交" style="width:  22%;min-width: 220px;margin-top: 18px;display: block;margin-bottom: 15px;height: 40px;border:0px;border-radius: 50px;background: #fff;color: #ee9078" onClick="error()">
		  <span style="color:red;display:none;" id="error">請輸入結帳金額!!!</span>
          <div class="search_bt" align="center" style="margin-top: -30px;padding: 12px 6px">
  <div style="width: 100%" align="center">
    <a href="store_main.php"><input type="button" value="上一步" style="border: 0px ;width:  22%;min-width: 220px;margin-top: 18px;display: block;margin-bottom: 15px;height: 40px;border:0px;border-radius: 50px;background: #ee9078;color: #fff"></a>
  </div></div>
		  </div>
        </div>
      </div>
    </div>
  </form>
</div>
<div class="col-lg-12 col-md-12 col-xs-12 st_pe_foot" style="height: 48px; background-color:#efefef; width: 100% ;position: relative;">
   <img src="img/login_footlogo-01.png" width="300px" style="margin-top: 10px" alt="">
 </div>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    <div class="modal-content col-lg-12 col-md-12 col-xs-12" style="padding-left: 0px;padding-right: 0px">
    <div class="col-lg-12 col-md-12 col-xs-12 mb_features" align="center" style="padding: 10px">
    <a href="store_main.php"><img src="img/life_pay_logo-2.png" width="50%" alt=""></a>
  </div>
      <!-- <div class="col-lg-6 col-md-6 col-xs-6 mb_features">
    <a href="qrcodestart.html"><img src="../table/img/my_qr-01.png" width="100%" alt=""></a>
  </div>-->
  <div class="col-lg-6 col-md-6 col-xs-6 mb_features">
    <a href="store_search.php"><img src="img/search-01.png" width="100%" alt=""></a>
  </div>
  <!--<div class="col-lg-6 col-md-6 col-xs-6 mb_features">
    <a href="store_coupon.php"><img src="../table/img/coupon-01.png" width="100%" alt=""></a>
 
</div>-->
  <div class="col-lg-6 col-md-6 col-xs-6 mb_features" >
    <a href="store_checkout.php"><img src="img/checkout-01.png" width="100%" alt=""></a>
  </div>
    </div>
    </div>
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
          <li><a href="store_login.php"><img src="img/sign-out-option.svg" width="25px" alt=""><span style="margin-left: 8px">登出</span></a></li>
        </ul>
      </div>
    </div>
</div>
</body>
<script src="js/iziToast.min.js" type="text/javascript"></script>
<script src="js/demo.js" type="text/javascript"></script>
</html>
