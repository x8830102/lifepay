<?php 
require_once('Connections/sc.php');mysql_query("set names utf8");
require_once('Connections/lp.php');mysql_query("set names utf8");
?>
<?php 
session_start();
if ($_SESSION['MM_Username'] == ""){header(sprintf("Location: life_pay9.php"));exit;
}else{
  $m_username = $_SESSION['mem'];//登入帳號
  $m_nick = $_SESSION['nick'];//登入暱稱
  $number = $_SESSION['number'];
}
//echo $number;

mysql_select_db($database_lp, $lp); //無條件
$query_str = "SELECT * FROM lf_user WHERE level = 'boss' && profile != ''";
$Restr = mysql_query($query_str, $lp) or die(mysql_error());
$row_str = mysql_fetch_assoc($Restr);
$row_num = mysql_num_rows($Restr);

//會員資料//
$phone = $_POST['phone'];
$email = $_POST['email'];
$address = $_POST['address'];
//echo $number;
$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
        if ($phone != "") {
          mysql_select_db($database_sc, $sc);
          $query_stcomplete = sprintf("UPDATE memberdata SET m_phone = '$phone' WHERE number = '$number'");
          $Restcomplete = mysql_query($query_stcomplete, $lp) or die(mysql_error());
        }
        if ($email != "") {
          mysql_select_db($database_sc, $sc);
          $query_stcomplete = sprintf("UPDATE memberdata SET m_email = '$email' WHERE number = '$number'");
          $Restcomplete = mysql_query($query_stcomplete, $lp) or die(mysql_error());
        }
        if ($address != "") {
          mysql_select_db($database_sc, $sc);
          $query_stcomplete = sprintf("UPDATE memberdata SET m_address = '$address' WHERE number = '$number'");
          $Restcomplete = mysql_query($query_stcomplete, $lp) or die(mysql_error());
        }
       
}
mysql_select_db($database_sc, $sc);
$query_Recf = sprintf("SELECT * FROM memberdata WHERE number = '$number'");
$Recf = mysql_query($query_Recf, $sc) or die(mysql_error());
$row_Recf = mysql_fetch_assoc($Recf);
$totalRows_Recf = mysql_num_rows($Recf);//
if ($totalRows_Recf != 0)
{
  $m_name = $row_Recf['m_name'];//姓名
  $m_sex = $row_Recf['m_sex'];//性別
  $m_age = $row_Recf['m_birthday'];//年齡
  $m_phone = $row_Recf['m_phone'];
  $m_email = $row_Recf['m_email'];
  $m_address = $row_Recf['m_address'];
}else {$a = "會員資料不對!!!";}

//密碼修改//
$oldpass = $_POST['oldpass'];
$passwd = $_POST['passwd'];
$passtoo = $_POST['passtoo'];
//echo $number;

$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
  if ($_POST['oldpass'] != "") 
  {
    if ($_POST['passwd'] != "" || $_POST['passtoo'] != "") {
      mysql_select_db($database_sc, $sc);
      $query_Recf = sprintf("SELECT * FROM memberdata WHERE number = '$number' && m_passwd = '$oldpass'");
      $Recf = mysql_query($query_Recf, $sc) or die(mysql_error());
      $row_Recf = mysql_fetch_assoc($Recf);
      $totalRows_Recf = mysql_num_rows($Recf);
      //echo $totalRows_Recf;
      if ($totalRows_Recf != 0){
        if ($passwd != "") {
          mysql_select_db($database_sc, $sc);
          $query_stcomplete = sprintf("UPDATE memberdata SET m_passwd = '$passwd' WHERE number = '$number'");
          $Restcomplete = mysql_query($query_stcomplete, $lp) or die(mysql_error());
        }
        if ($passtoo != "") {
          mysql_select_db($database_sc, $sc);
          $query_stcomplete = sprintf("UPDATE memberdata SET m_passtoo = '$passtoo' WHERE number = '$number'");
          $Restcomplete = mysql_query($query_stcomplete, $lp) or die(mysql_error());
        }
      
      }else {$a = "會員資料不對!!!";}
    }else {$a="密碼和二級密碼不可兩個都空白!!!";}
  }else {$a="舊密碼不可空白!!!";}
}

//意見回饋//
$content = $_POST['content'];
//echo $number;
$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form3")) {
        if ($content != "") {
          mysql_select_db($database_sc, $sc);
          $query_stcomplete = sprintf("UPDATE memberdata SET note = '$content' WHERE number = '$number'");
          $Restcomplete = mysql_query($query_stcomplete, $lp) or die(mysql_error());
        }
        header(sprintf("Location: person_main.php"));
}

mysql_select_db($database_sc, $sc);
$query_Recf = sprintf("SELECT * FROM memberdata WHERE number = '$number'");
$Recf = mysql_query($query_Recf, $sc) or die(mysql_error());
$row_Recf = mysql_fetch_assoc($Recf);
$totalRows_Recf = mysql_num_rows($Recf);//
if ($totalRows_Recf != 0)
{
  $m_content = $row_Recf['note'];
}else {$a = "會員資料不對!!!";}

//業務合作//
$business_content = $_POST['business_content'];
//echo $number;
$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form4")) {
        if ($business_content != "") {
          mysql_select_db($database_sc, $sc);
          $query_stcomplete = sprintf("UPDATE memberdata SET note = '$business_content' WHERE number = '$number'");
          $Restcomplete = mysql_query($query_stcomplete, $lp) or die(mysql_error());
        }
        header(sprintf("Location: person_main.php"));
}

mysql_select_db($database_sc, $sc);
$query_Recf = sprintf("SELECT * FROM memberdata WHERE number = '$number'");
$Recf = mysql_query($query_Recf, $sc) or die(mysql_error());
$row_Recf = mysql_fetch_assoc($Recf);
$totalRows_Recf = mysql_num_rows($Recf);//
if ($totalRows_Recf != 0)
{
  $m_content = $row_Recf['note'];
}else {$a = "會員資料不對!!!";}
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
  <link rel="stylesheet" href="/wp-content/themes/the-rex/css/jquery-ui.css" type="text/css">
  <link rel="stylesheet" href="css/jquery.dataTables.min.css">
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="css/jquery.bxslider.css">
  <script src="js/jquery.bxslider.min.js" type="text/javascript"></script>
</head>
<style>
::-webkit-input-placeholder { /* WebKit browsers */
    color:    #fff !important;
    line-height: 10px !important;
}
:-moz-placeholder { /* Mozilla Firefox 4 to 18 */
    color:    #fff !important;
    line-height: 10px !important;
}
::-moz-placeholder { /* Mozilla Firefox 19+ */
    color:    #fff !important;
    line-height: 10px !important;
}
:-ms-input-placeholder { /* Internet Explorer 10+ */
    color:    #fff !important;
    line-height: 10px !important;
}
.bottomNav {
  position: fixed;
  width: 100%;
  bottom: 0px;
background: #EFEFEF
}
.bottomNav li {
  float: left;
}
.bottomNav img {
  padding: 5px 0px
}
.setting1 li{
  line-height: 35px;
  padding:10px;
  font-size: 16px;
  width: 95%;
  border-bottom: 1px solid #EDEDED

}
.setting1 a {
    color: #949494
}
 @media (max-height:430px ){
  .st_pe_foot{
      display: none;
  }
}

</style>
<body>
<div style="position: fixed;top: 0px;width: 100%;z-index: 999">
<div class="mebr_top" align="center" style="padding:15px ">
  <img src="img/life_pay_logo-01.png" width="160px" alt="">
  <a href="#" class="open"><img src="img/set_up-01.svg" style="position: absolute; left: 20px; top: 40px" width="25px" alt=""></a>
  <a href="#cl" class="close hidden">del</a>
</div>
<div class=" " align="center" style="">
  <!--<form id="form" action="person_search.php" method="get">
  <ul style="margin-top: -10px">
    <li  class="col-lg-12 col-md-12 col-xs-12" style="list-style: none;background: #84ddcb;height: 60px"><input id="st_name" name="st_name" type="text" placeholder="全站搜尋" style="width: 80%;background: #77C7B6;border-radius: 6px;border: 0px;color: #fff;padding:8px;margin-top: 10px;vertical-align: middle;"></li></ul>
</form>-->
</div></div>
<nav class="bottomNav" style="z-index: 999">
  <ul class="">
  <a href="person_main.php" style="color: #595757">
    <li style="width: 20%">
      
      <div class=" ">
        <div align="center"><img src="img/lp_home.png" width="30px" alt=""></div>
      </div>  
    </li> 
    </a>
    <a href="person_like.php" style="color: #595757">
    <li style="width: 20%"> 
      
        <div class=" " >
          <div align="center"><img src="img/lp_like.png" width="30px" alt=""></div>
        </div>
    </li>
    </a> 
    <a href="person_map.php" style="color: #595757">
    <li style="width: 20%">
      
    <div class=" " >
      <div align="center"><img src="img/lp_map-active.png" width="30px" alt=""></div>
    </div>
    </li>
    </a> 
    <a href="person_transfer.php" style="color: #595757">
    <li style="width: 20%">
        
      <div class=" " >
        <div align="center"><img src="img/lp_point.png" width="30px" alt=""></div>
      </div>
    </li>
    </a>
    <a href="person_inquire.php" style="color: #595757"> 
    <li style="width: 20%">
    <div class=" " style="border-right: 0px">
      <div align="center"><img src="img/lp_search.png" width="30px" alt=""></div>
    </div>
    </li>
    </a>
  </ul>
</nav>
</body>
</html>

<div id="box" style="background:#fff;height:100%;width:100%;top:100%; position: fixed;z-index:1000;display: none;">
<div class="mebr_top" align="center" style="background:#84DDCB;color: #fff;padding: 13px;height: 50px ">
<div><span style="position: absolute; left: 20px; top:-2px;color: #fff;font-size: 30px;font-weight: 300;z-index: 999" class="btn">X</span></div>
</div>

<ul class="setting1" style="padding-left:5% ">
          <li><a class="open1" href="#"><img src="img/lp_user.png" width="22px" alt=""><span style="margin-left: 8px">會員資料</span></a></li>
          <li><a  class="open2" href="#"><img src="img/lp_password-01.png" width="22px" alt=""><span style="margin-left: 8px">密碼修改</span></a></li>
          <li><a class="open3" href="#"><img src="img/lp_feedback.png" width="22px" alt=""><span style="margin-left: 8px">意見回饋</span></a></li>
          <li><a class="open4" href="#"><img src="img/lp_business.png" width="22px" alt=""><span style="margin-left: 8px">業務合作</span></a></li>
          <li><a class="open5" href="#"><img src="img/lp_infor-01.png" width="20px" alt=""><span style="margin-left: 8px">使用說明</span></a></li>
          <!--<li><a href="logout9.php"><img src="img/lp_signout.png" width="22px" alt=""><span style="margin-left: 8px">登出</span></a></li>-->
          <?php $user = $_SESSION['mem'];?>
          <li><a href="http://lifelink.cc/logout_s.php?m=<?php echo $user;?>"><img src="img/lp_signout.png" width="22px" alt=""><span style="margin-left: 8px">登出</span></a></li>
        </ul>

</div>
<div id="box2" style="background:#fff;height:100%;width:100%;top:100%; position: fixed;z-index:1001;display: none">
  <div class="mebr_top" align="center" style="background:#84DDCB;color: #fff;padding: 13px;height: 50px ">
<div><span style="font-size: 22px">會員資料</span><span style="position: absolute; left: 33px; top:5px;color: #fff;font-size: 30px;font-weight: 300;z-index: 999" class="btn2">X</span></div>
</div>
  <form id="form1" name="form1" method="post" action="<?php echo $editFormAction; ?>">
  <div align="left" style="border-bottom: 1px solid #eee;line-height: 40px;padding: 5px;width: 90%;margin: 0px auto"><div align="left" style="font-size: 16px;color: #999;display: inline-block;width: 30%;padding-left: 10px">姓名：</div><div align="left" style="color: #65A89A;font-size: 14px;display: inline-block;width: 70%;padding-left: 20px"> <?php echo  $m_name;?></div></div>
  <div align="left" style="border-bottom: 1px solid #eee;line-height: 40px;padding: 5px;width: 90%;margin: 0px auto"><div align="left" style="font-size: 16px;color: #999;display: inline-block;width: 30%;padding-left: 10px">性別：</div><div align="left" style="color: #65A89A;font-size: 14px;display: inline-block;width: 70%;padding-left: 20px"><?php echo  $m_sex;?></div></div>
  <div align="left" style="border-bottom: 1px solid #eee;line-height: 40px;padding: 5px;width: 90%;margin: 0px auto"><div align="left" style="font-size: 16px;color: #999;display: inline-block;width: 30%;padding-left: 10px">生日：</div><div align="left" style="color: #65A89A;font-size: 14px;display: inline-block;width: 70%;padding-left: 20px"><?php echo  $m_age;?></div></div>
  <div align="left" style="border-bottom: 1px solid #eee;line-height: 40px;padding: 5px;width: 90%;margin: 0px auto"><div align="left" style="font-size: 16px;color: #999;display: inline-block;width: 30%;padding-left: 10px">電話：</div><div align="left" style="color: #65A89A;font-size: 14px;display: inline-block;width: 70%;padding-left: 20px"><input name="phone" type="text" class="pay_user lo_user" style="background:#C9C9C9;color: #fff;border-radius: 6px;border:0px;height: 35px;width: 100%;line-height: 20px "  placeholder="<?php echo $m_phone ?>"></div></div>
  <div align="left" style="border-bottom: 1px solid #eee;line-height: 40px;padding: 5px;width: 90%;margin: 0px auto"><div align="left" style="font-size: 16px;color: #999;display: inline-block;width: 30%;padding-left: 10px">信箱：</div><div align="left" style="color: #65A89A;font-size: 14px;display: inline-block;width: 70%;padding-left: 20px"><input name="email" type="text" class="pay_user lo_user" style="background:#C9C9C9;color: #fff;border-radius: 6px;border:0px;height: 35px;width: 100%;line-height: 20px "  placeholder="<?php echo $m_email ?>"></div></div>
  <div align="left" style="border-bottom: 1px solid #eee;line-height: 40px;padding: 5px;width: 90%;margin: 0px auto"><div align="left" style="font-size: 16px;color: #999;display: inline-block;width: 30%;padding-left: 10px">居住地：</div><div align="left" style="color: #65A89A;font-size: 14px;display: inline-block;width: 70%;padding-left: 20px"><input name="address" type="text" class="pay_user lo_user" style="background:#C9C9C9;color: #fff;border-radius: 6px;border:0px;height: 35px;width: 100%;line-height: 20px " placeholder="<?php echo $m_address ?>"></div></div>
    
      

      <div class="" align="center" style="width: 100%;position: absolute;bottom: 10px;left: 0px"><input class="login_bt" type="submit" onClick="" value="更新" style="background: #84DDCB;border: 1px solid #84DDCB;border-radius: 6px;color: #fff;font-size: 16px;width: 80%;line-height: 35px">
      <div style="color: red;padding: 10px"><? echo $a;?></div>
      <input type="hidden" name="MM_insert" value="form1" />
      </div>
    </div>
  </form>
</div>
<div id="box3" style="background:#fff;height:100%;width:100%;top:100%; position: fixed;z-index:1001;display: none">
  <div class="mebr_top" align="center" style="background:#84DDCB;color: #fff;padding: 13px;height: 50px ">
<div><span style="font-size: 22px">密碼修改</span><span style="position: absolute; left: 33px; top:5px;color: #fff;font-size: 30px;font-weight: 300;z-index: 999" class="btn3">X</span></div>
</div>
<form id="form2" name="form2" method="post" action="<?php echo $editFormAction; ?>">

 <div align="left" style="border-bottom: 1px solid #eee;line-height: 40px;padding: 5px;width: 90%;margin: 0px auto"><div align="left" style="color: #65A89A;font-size: 14px;display: inline-block;width: 70%;margin-left: 15%;margin-top: 20px"><input name="oldpass" type="password" class="pay_user lo_user" style="background:#C9C9C9;color: #fff;border-radius: 6px;border:0px;height: 35px;width: 100%;line-height: 20px " placeholder="請輸入舊的使用者密碼"></div></div>
 <div align="left" style="border-bottom: 1px solid #eee;line-height: 40px;padding: 5px;width: 90%;margin: 0px auto"><div align="left" style="color: #65A89A;font-size: 14px;display: inline-block;width: 70%;margin-left: 15%"><input name="passwd" type="password" class="pay_user lo_user" style="background:#C9C9C9;color: #fff;border-radius: 6px;border:0px;height: 35px;width: 100%;line-height: 20px " placeholder="請輸入使用者密碼"></div></div>
 <div align="left" style="border-bottom: 1px solid #eee;line-height: 40px;padding: 5px;width: 90%;margin: 0px auto"><div align="left" style="color: #65A89A;font-size: 14px;display: inline-block;width: 70%;margin-left: 15%"><input name="passtoo" type="password" class="pay_user lo_user" style="background:#C9C9C9;color: #fff;border-radius: 6px;border:0px;height: 35px;width: 100%;line-height: 20px " placeholder="請輸入二級密碼"></div></div>
      <div class="" align="center" style="width: 100%;position: absolute;bottom: 10px;left: 0px"><input class="login_bt" type="submit" onClick="" value="更改密碼" style="background: #84DDCB;border: 1px solid #84DDCB;border-radius: 6px;color: #fff;font-size: 16px;width: 80%;line-height: 35px">
      <div style="color: red;padding: 10px"><? echo $a;?></div>
      <input type="hidden" name="MM_insert" value="form2" />

    </div>
</form>
</div>
<div id="box4" style="background:#fff;height:100%;width:100%;top:100%; position: fixed;z-index:1001;display: none">
  <div class="mebr_top" align="center" style="background:#84DDCB;color: #fff;padding: 13px;height: 50px ">
<div><span style="font-size: 22px">意見回饋</span><span style="position: absolute; left: 33px; top:5px;color: #fff;font-size: 30px;font-weight: 300;z-index: 999" class="btn4">X</span></div>
</div>
  <form id="form3" name="form3" method="post" action="<?php echo $editFormAction; ?>">
  <div align="left" style="border-bottom: 1px solid #eee;line-height: 40px;padding: 5px;width: 90%;margin: 0px auto;margin-top: 20px"><div align="left" style="font-size: 14px;color: #999;display: inline-block;width: 30%;padding-left: 10px;vertical-align: top;">意見回饋：</div><div align="left" style="color: #65A89A;font-size: 14px;display: inline-block;width: 70%;padding-left: 20px"><textarea rows="4" cols="50" name="content" type="text" class="pay_user " placeholder="感謝您的寶貴意見，串門子雲盟事業將不斷進步，為使用者創造更優秀的商務平台！" style="background: #C9C9C9;color: #fff;border-radius: 6px;border:0px;height: 40vh;width: 100%;padding: 5px"> </textarea></div></div>
      <div class="" align="center" style="width: 100%;position: absolute;bottom: 10px;left: 0px"><input class="login_bt" type="submit" onClick="" value="上傳" style="background: #84DDCB;border: 1px solid #84DDCB;border-radius: 6px;color: #fff;font-size: 16px;width: 80%;line-height: 35px">
      <div style="color: red;padding: 10px"><? echo $a;?></div>
      <input type="hidden" name="MM_insert" value="form3" />
      
      
      </div>
  
</form>
</div>
<div id="box5" style="background:#fff;height:100%;width:100%;top:100%; position: fixed;z-index:1001;display: none">
  <div class="mebr_top" align="center" style="background:#84DDCB;color: #fff;padding: 13px;height: 50px ">
<div><span style="font-size: 22px">業務合作</span><span style="position: absolute; left: 33px; top:5px;color: #fff;font-size: 30px;font-weight: 300;z-index: 999" class="btn5">X</span></div>
</div>
  <form id="form4" name="form4" method="post" action="<?php echo $editFormAction; ?>">
<div align="left" style="border-bottom: 1px solid #eee;line-height: 40px;padding: 5px;width: 90%;margin: 0px auto;margin-top: 20px"><div align="left" style="font-size: 14px;color: #999;display: inline-block;width: 30%;padding-left: 10px;vertical-align: top;">業務合作：</div><div align="left" style="color: #65A89A;font-size: 14px;display: inline-block;width: 70%;padding-left: 20px"><textarea rows="4" cols="50" name="business_content" type="text" class="pay_user " placeholder="感謝您的來信，串門子雲盟事業是您擴展事業的好夥伴！" style="background: #C9C9C9;color: #fff;border-radius: 6px;border:0px;height: 40vh;width: 100%;padding: 5px"> </textarea></div></div>
      <div class="" align="center" style="width: 100%;position: absolute;bottom: 10px;left: 0px"><input class="login_bt" type="submit" onClick="" value="上傳" style="background: #84DDCB;border: 1px solid #84DDCB;border-radius: 6px;color: #fff;font-size: 16px;width: 80%;line-height: 35px">
      <div style="color: red;padding: 10px"><? echo $a;?></div>
      <input type="hidden" name="MM_insert" value="form4" />
    </div>
</form>
</div>
<div id="box6" style="background:#fff;height:100%;width:100%;top:100%; position: fixed;z-index:1001;overflow: auto;display: none">
  <div class="mebr_top" align="center" style="background:#84DDCB;color: #fff;padding: 13px;height: 50px ">
<div><span style="font-size: 22px">使用說明</span><span style="position: absolute; left: 33px; top:5px;color: #fff;font-size: 30px;font-weight: 300;z-index: 999" class="btn6">X</span></div>
</div>
  <div align="left" style="border-bottom: 1px solid #eee;line-height: 40px;padding: 5px;width: 90%;margin: 0px auto"><div align="left" style="font-size: 16px;color: #999;display: inline-block;width: 40%;padding-left: 10px;vertical-align: top;">【串串積分】</div><div align="left" style="color: #65A89A;font-size: 14px;display: inline-block;width: 60%;padding-left: 20px;line-height: 25px">凡購買專案產品(小資、企業)的會員，享有紅利積分及消費積分外，會依照您購買的方案，贈送串串積分，成功推薦串門子產品方案，也會送您串串積分喲。串串積分除了在商城可全額折抵外，還可以用於串門子全台特約商店做消費折抵！好康紅利環環相扣響不停！快點加入串門子的專案會員吧！</div></div>
  <div align="left" style="border-bottom: 1px solid #eee;line-height: 40px;padding: 5px;width: 90%;margin: 0px auto"><div align="left" style="font-size: 16px;color: #999;display: inline-block;width: 40%;padding-left: 10px;vertical-align: top;">【紅利積分】</div><div align="left" style="color: #65A89A;font-size: 14px;display: inline-block;width: 60%;padding-left: 20px;line-height: 25px">註冊免費網站，獲得100點紅利積分，推廣好友、文章分享、評分或參加串門子準備的各種紅利獎勵活動。粉絲經營得越多，積分就累積的越快，在購物商城裡就可以獲得越多的折扣！</div></div>
  <div align="left" style="border-bottom: 1px solid #eee;line-height: 40px;padding: 5px;width: 90%;margin: 0px auto"><div align="left" style="font-size: 16px;color: #999;display: inline-block;width: 40%;padding-left: 10px;vertical-align: top;">【消費積分】</div><div align="left" style="color: #65A89A;font-size: 14px;display: inline-block;width: 60%;padding-left: 20px;line-height: 25px">商城消費會依照消費金額給予等比消費積分(紅利積分折扣購物也適用)；您推廣加入的粉絲在商城購物時，同時也獲得消費積分，加乘效益共創紅利！
消費積分為1點折抵新台幣1元，可在商城作全額折抵喔</div></div>
<div align="left" style="border-bottom: 1px solid #eee;line-height: 40px;padding: 5px;width: 90%;margin: 0px auto"><div align="left" style="font-size: 16px;color: #999;display: inline-block;width: 40%;padding-left: 10px;vertical-align: top;">【折扣券】</div><div align="left" style="color: #65A89A;font-size: 14px;display: inline-block;width: 60%;padding-left: 20px;line-height: 25px">於特殊活動或實體店面配合店家優惠回饋，可獲得指定店面全額折抵折扣券，且不設立消費門檻喔!</div></div>
</div>
<script>
$(document).ready(function()
  {
  $(".open").click(function(){
    $("#box").animate({top:"0%"});
    $("#box").css("display","block");
  });
  $(".btn").click(function(){
    $("#box").animate({top:"100%"},function(){
      $("#box").css("display","none");
    });
  });
  $(".open1").click(function(){
    $("#box2").animate({top:"0%"});
     $("#box2").css("display","block");
  });
  $(".btn2").click(function(){
    $("#box2").animate({top:"100%"},function(){
      $("#box2").css("display","none");
    });
  });
  $(".open2").click(function(){
    $("#box3").animate({top:"0%"});
     $("#box3").css("display","block");
  });
  $(".btn3").click(function(){
    $("#box3").animate({top:"100%"},function(){
      $("#box3").css("display","none");
    });
  });
  $(".open3").click(function(){
    $("#box4").animate({top:"0%"});
     $("#box4").css("display","block");
  });
  $(".btn4").click(function(){
    $("#box4").animate({top:"100%"},function(){
      $("#box4").css("display","none");
    });
  });
  $(".open4").click(function(){
    $("#box5").animate({top:"0%"});
     $("#box5").css("display","block");
  });
  $(".btn5").click(function(){
    $("#box5").animate({top:"100%"},function(){
      $("#box5").css("display","none");
    });
  });
  $(".open5").click(function(){
    $("#box6").animate({top:"0%"});
     $("#box6").css("display","block");
  });
  $(".btn6").click(function(){
    $("#box6").animate({top:"100%"},function(){
      $("#box6").css("display","none");
    });
  });
});
</script>
