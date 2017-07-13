<?php 
require_once('Connections/lp.php');mysql_query("set names utf8");
require_once('Connections/sc.php');mysql_query("set names utf8");
session_start();
if ($_SESSION['MM_Username'] == ""){header(sprintf("Location: store_login.php"));exit;
}else{
  $user = $_SESSION['MM_Username'];
  $number = $_SESSION['number'];//驗證帳號
}



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

//抓商店資料
$level = $row['level'];
$st_number = $row['number'];
$pas_two =$row['password2'];

$dis = $_POST['dis'];
$disexp = $_POST['disexp'];
$number1 = $_POST['number1'];
$number2 = $_POST['number2'];

$date = date("Y-m-d");

if($number1 != '' && $number2 !=''){
  $time = date("$number1");
  $time2 = date("$number2");
  mysql_select_db($database_lp, $lp);
  $update_stuser = sprintf("UPDATE lf_user SET time1='$time' , time2='$time2' WHERE number='$st_number'");
  $update = mysql_query($update_stuser, $lp) or die(mysql_error());
}
if($dis != '')
{
	//$date = date("Y-m-d");
	//echo $date;
	mysql_select_db($database_lp, $lp);
	$update_stuser = sprintf("UPDATE  lf_user SET st_dis='$dis' WHERE number='$st_number'");
	$update = mysql_query($update_stuser, $lp) or die(mysql_error());

}
if($disexp != '')
{
	//$date = date("Y-m-d");
	//echo $date;
	mysql_select_db($database_lp, $lp);
	$update_stuser = sprintf("UPDATE  lf_user SET disexp='$disexp' WHERE number='$st_number'");
	$update = mysql_query($update_stuser, $lp) or die(mysql_error());

}


		$name = $row['accont'];
		$_FILES['file']['name'] = $name;
		move_uploaded_file($_FILES['file']['tmp_name'],'img/profile-'.$name.'.png');
		$destination = "profile-".$name.".png";
		$query_dest = "UPDATE lf_user SET profile = '$destination' WHERE number='$st_number' && level = 'boss'";
		mysql_query($query_dest);
?>
<script>

function check(){
  var number1 = document.getElementById('number1');
  var number2 = document.getElementById('number2');
	var pas_two = document.getElementById('pas2');
	var pas_twoo = "<?php echo $pas_two;?>";
	var dis = document.getElementById('dis');

	if(pas_two.value != pas_twoo){
    document.getElementById('pas2').style.background="pink";
	}else if(number1.value == '' && number2.value != ''){
    document.getElementById('number1').style.background="pink";
	}else if(number1.value != '' && number2.value == ''){
    document.getElementById('number2').style.background="pink";
	}else if(dis.value >= 0 && dis.value <= 100){

	document.getElementById('form1').submit();
  alert("修改完成！");
	}else{
	document.getElementById('dis').style.background="pink";
	}
}

function ValidateNumber(e, pnumber){
  if (!/^\d+$/.test(pnumber))
  {
    e.value = /^\d+/.exec(e.value);
  }
  return false;

  if(e.value > 100){
    document.getElementById('bt').style.display="none";
  }
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>
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
  
  <form id="form1" name="form1" enctype="multipart/form-data" method="post" action="store_update.php">
    <div style="padding: 15px;" align="center">
      <div id="home" align="center" style="background: #fff;border-radius: 6px;padding: 20px">
        <h1 align="center" style="">更改資料</h1>
        <div align="center" style=";margin-top: 30px">
        營業時間<br>
        <input name="number1" id="number1" type="time" style="width: 200px;text-align: left;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px"><br>
        <span style="line-height: 40px">至</span><br>
        <input name="number2" id="number2" type="time" style="width: 200px;text-align: left;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px"><br>
        <input name="dis" id="dis" type="tel" placeholder="抵用券%數 : " onKeyUp="ValidateNumber(this,value)" style="width: 200px;text-align: left;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;margin-top: 30px"><br>
        <span style="line-height: 40px">抵用券期限</span><br> 
        <input name="disexp" id="disexp" type="date" placeholder="抵用券期限 : "  min="<?php echo $date;?>" style="width: 200px;text-align: left;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px"><br>
        <span style="line-height: 40px">上傳封面照片</span><br> 
        <input type="file" name="file" style="width: 200px;text-align: left;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px"> <br>
        <img src="img/<?php echo $row['profile'];?>" style="max-width:300px " alt="尚未設定封面，無法推播至消費者頁面"> <br>
  		  <input name="pas2" id="pas2" type="password" placeholder="二級密碼 : " style="width: 200px;text-align: left;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;margin-top: 30px"><br>
  		  <input type="button" id="bt" value="提交" style="width: 200px;margin-top: 18px;display: block;margin-bottom: 15px;height: 40px;border:0px;border-radius: 6px;background: #485fe5;color: #fff" onClick="check()">
  		  </div>
        </div>
      </div>
    </div>
  </form>
</div>
<div class="search_bt"  style="margin-top: -30px;">
  <div style="width: 88%" align="left">
    <a href="store_main.php"><input type="button" value="< 返回" style="background: #595757; color: #fff;border: 0px ;border-radius: 5px;padding: 4px 55px"></a>
  </div></div>
<div class="col-lg-12 col-md-12 col-xs-12 st_pe_foot" style="height: 48px; background-color:#efefef; width: 100% ;position: relative;top: 20px">
   <img src="img/login_footlogo-01.png" width="320px" style="margin-top: 10px" alt="">
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
    <a href="store_coupon.php"><img src="img/coupon-01.png" width="100%" alt=""></a>
 
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
		      <li><a href="store_establish.php"><img src="img/user_ch.png" width="25px" alt=""><span style="margin-left: 8px">建立員工帳號</span></a></li>
          <li><a href="store_modify.php"><img src="img/user_setup.png" width="25px" alt=""><span style="margin-left: 8px">修改員工帳號</span></a></li>
          <li><a href="Invoice.php"><img src="img/user_setup.png" width="25px" alt=""><span style="margin-left: 8px">請款</span></a></li>
		  <?php 
		  }?>
          <li><a href="store_login.php"><img src="img/sign-out-option.svg" width="25px" alt=""><span style="margin-left: 8px">登出</span></a></li>
        </ul>
      </div>
    </div>
</div>
</body>
</html>