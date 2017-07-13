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
$st_number = $row_em['number'];
$usage_fee = $row_em['usage_fee'];
$st_name = $row_em['st_name'];
$st_dis = $row_em['st_dis'];
$pas_two =$row_em['password2'];
$time1 = $row_em['time1'];
$time2 = $row_em['time2'];
$disexp = $row_em['disexp'];
$accont = $row_em['accont'];

$user = $_POST['user'];
$pas = $_POST['pas'];
$epas = $_POST['epas'];
$p_nick =$_POST['p_nick'];

$st_em = $accont.'_'.$user;

$number = $_POST['number'];

$date = date("Y-m-d");
if($user && $pas)
{
  mysql_select_db($database_lp, $lp);
  $select_stuser = sprintf("SELECT * FROM lf_user WHERE accont = '$st_em'");
  $sel = mysql_query($select_stuser, $lp) or die(mysql_error());
  $sel_row = mysql_fetch_assoc($sel);
  $trow_sel = mysql_num_rows($sel);

  if($trow_sel != 0){
    header(sprintf("Location: store_establish.php?err=帳號重複"));exit;
  }else{
    mysql_select_db($database_lp, $lp);
    $inser_stuser = sprintf("INSERT IGNORE INTO lf_user(accont,password,password2,st_name,e_name,st_dis,disexp,number,usage_fee,date,level,time1,time2)value('$st_em','$pas','$epas','$st_name','$p_nick','$st_dis','$disexp','$st_number','$usage_fee','$date','Employee','$time1','$time2')");
    $inser = mysql_query($inser_stuser, $lp) or die(mysql_error());
    header(sprintf("Location: store_establish.php?suc=建立完成"));exit;
  }

}

?>
<script>

function check(){
	var pas_two = document.getElementById('pas2');
	var pas_twoo = "<?php echo $pas_two;?>";
	var user = document.getElementById('user');
	var p_nick = document.getElementById('p_nick');
	var pas = document.getElementById('pas');


	if(pas_two.value != pas_twoo){
	document.getElementById('pas2').style.background="pink";
	}else if(user.value == ""){
	document.getElementById('user').style.background="pink";
	}else if(p_nick.value == ""){
	document.getElementById('p_nick').style.background="pink";
	}else if(pas.value == ""){
	document.getElementById('pas').style.background="pink";
	}else{
	document.getElementById('form1').submit();
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
  
  <form id="form1" name="form1" method="post" action="store_establish.php">
    <div style="padding: 15px;" align="center">
      <div id="home" align="center" style="background: #fff;border-radius: 6px;padding: 20px">
        <h1 align="center" style="">建立員工帳號</h1>
        <span style="font-size:20px;color:red;"><?php echo $_GET['err'];?></span>
        <span style="font-size:20px;color:red;"><?php echo $_GET['suc'];?></span>
        <div align="center">
          <input name="user" id="user" type="text"  placeholder="帳號 : " style="width: 200px;text-align: left;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;margin-top: 30px"><br>
          <input name="p_nick" id="p_nick" type="text" placeholder="名稱 : " style="width: 200px;text-align: left;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;margin-top: 30px"><br>
    		  <input name="pas" id="pas" type="password" placeholder="密碼 : " style="width: 200px;text-align: left;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;margin-top: 30px"><br>
          <input name="epas" id="epas" type="password" placeholder="二級密碼(員工) : " style="width: 200px;text-align: left;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;margin-top: 30px"><br>
    		  <input name="pas2" id="pas2" type="password" placeholder="二級密碼(老闆) : " style="width: 200px;text-align: left;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;margin-top: 30px"><br>
    		  <input type="hidden" name="number" value="<?php echo $st_number;?>" >
    		  <input type="button" value="提交" style="width: 200px;margin-top: 18px;display: block;margin-bottom: 15px;height: 40px;border:0px;border-radius: 6px;background: #485fe5;color: #fff" onClick="check()">
    		</div>
      </div>
    </div>
  </form>

</div>
<div class="search_bt" align="center" style="margin-top: -30px;">
  <div style="width: 88%" align="center">
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
