<?php require_once('Connections/sc.php');mysql_query("set names utf8");
require_once('Connections/sr.php');mysql_query("set names utf8");
require_once('Connections/tw.php');mysql_query("set names utf8");
require_once('Connections/lp.php');mysql_query("set names utf8");?>
<?php 
session_start();
if ($_SESSION['MM_Username'] == ""){header(sprintf("Location: life_pay.php"));exit;
}else{
  $number = $_SESSION['number'];
  $m_username = $_SESSION['mem'];
  $oldpass = $_POST['oldpass'];
  $passwd = $_POST['passwd'];
  $passtoo = $_POST['passtoo'];
}
//echo $number;

$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
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
          $Restcomplete = mysql_query($query_stcomplete, $sc) or die(mysql_error());

          $pas = MD5($passwd);
          mysql_select_db($database_tw, $tw);
          $up_pass="UPDATE wp_users SET user_pass='$pas' WHERE user_login = '$m_username'";
          $query_pass = mysql_query($up_pass,$tw)or die(mysql_error());
          
          mysql_select_db($database_sr, $sr);
          $up_pass="UPDATE wp_users SET user_pass='$pas' WHERE user_login = '$m_username'";
          $query_pass = mysql_query($up_pass,$sr)or die(mysql_error());

          $salt = substr(md5(uniqid(rand(), true)), 0, 9);
          $pdo = new PDO("mysql:host=localhost;dbname=twlifeli_ocmall;charset=utf8","twlifelinkcom","rgn26842");
          $query = $pdo->query("UPDATE oc_customer SET password = '".sha1($salt . sha1($salt . sha1($passwd)))."', salt = '$salt' WHERE m_username = '$m_username'"); ?>
          <script>
            alert('??');
          </script><?php

        }
        if ($passtoo != "") {
          mysql_select_db($database_sc, $sc);
          $query_stcomplete = sprintf("UPDATE memberdata SET m_passtoo = '$passtoo' WHERE number = '$number'");
          $Restcomplete = mysql_query($query_stcomplete, $sc) or die(mysql_error());
        }
        header(sprintf("Location: person_main9.php"));
/*        if ($_POST['sum'] != "" && $_POST['sum'] == $_POST['cksum']){
          $card = strtoupper(trim($_POST['card']));$passwd = $_POST['passwd'];
          //if ($ads == 0) {$ads2=" && a_pud = 1";} else {$ads2=" && a_pud >= 2 && a_pud < 6";}
          mysql_select_db($database_sc, $sc);
          $query_Recf = sprintf("SELECT * FROM memberdata WHERE m_username = '$card' && m_passwd = '$passwd'  && m_ok = 1");//
          $Recf = mysql_query($query_Recf, $sc) or die(mysql_error());
          $row_Recf = mysql_fetch_assoc($Recf);
          $totalRows_Recf = mysql_num_rows($Recf);//
          if ($totalRows_Recf != 0)
          {//echo "ok";exit;
            $_SESSION['mem'] = $row_Recf['m_username'];//登入帳號
            $_SESSION['number'] = $row_Recf['number'];//
            $_SESSION['nick'] = $row_Recf['m_nick'];//登入暱稱
            $_SESSION['MM_Username'] = $row_Recf['m_id'];//
            $_SESSION['pastoo'] = $row_Recf['m_passtoo'];//二級密碼
            $pud=$row_Recf['a_pud'];
            $mkey = $_GET['a'];

            //透過key取得交易資料
            mysql_select_db($database_lp, $lp);
            $query_str = sprintf("SELECT * FROM st_record WHERE verification ='$mkey' ");
            $Restr = mysql_query($query_str, $lp) or die(mysql_error());
            $row_str = mysql_fetch_assoc($Restr);
            $totalRows_row_str = mysql_num_rows($Restr);
            if($totalRows_row_str != 0){
              header(sprintf("Location: checkout.php?a=$mkey"));
            }else if($row_str['verification'] != $mkey){
              $a = "交易資料不對!!! 請重新掃描取得交易資料";
            }else{header(sprintf("Location: person_main9.php"));}
          }else {$a = "會員資料不對!!!";}
        }else {$a = "驗證碼錯誤!!!";}*/
      }else {$a = "會員資料不對!!!";}
    }else {$a="密碼和二級密碼不可兩個都空白!!!";}
  }else {$a="舊密碼不可空白!!!";}
}

/*
$i = 6;$pd[0] = 0;  $sum = "";
while ($i != 0) {$md = rand(0, 9);if (in_array($md, $pd) == false) {$pd[$i] = $md;$i--;}}
$j = 6;while ($j != 0) {$sum = $sum.(int)$pd[$j];$j--;}
*/
?>
<!DOCTYPE html>
<html>
<head>
  <title>LIFE PAY登入系統</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
  @media (max-height:430px ){
  .st_pe_foot{
      display: none;
  }
}
  </style>
</head>
<body style="background: #84ddcb;width: 100%;">
<!--
<script>
  var code;
  function see(){

    code = "";
    var codeLength = 6;
    var changeCode = document.getElementById("changeCode");
    var checkCode = document.getElementById("checkCode");

    var codeChars = new Array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
    for (var i = 0; i < codeLength; i++){
        var charNum = Math.floor(Math.random() * 10);
        code += codeChars[charNum];
        if (changeCode) 
        {
            changeCode.className = "code";
            changeCode.innerHTML = code;
			
        }
    }
    
	  var cksum = document.getElementById("cksum");
    cksum.value= code;
    
  }

</script>
-->
<form id="form1" name="form1" method="post" action="<?php echo $editFormAction; ?>">
<div style="width:100%"> 
  <!--<div class="container_pay lifepay_bg" align="center" style="background-color:#FFFFFF">
  </div>
  <div class="login_subtitle">LIFE PAY 會員消費結帳系統</div> 
  </div>-->
  <div class="pay_login" align="center" style="margin: 18px auto 0px auto;">
      <table class="login"  width="100%">
        <tr>
          <td colspan="3"><input name="oldpass" type="password" class="pay_user lo_user" style="background-image:url(img/p_login_password-01.svg);background-repeat:no-repeat;background-size: 20px;background-position: 20px 11px " placeholder="請輸入舊的使用者密碼"></td>
        </tr>
        <tr>
          <td colspan="3"><input name="passwd" type="password" class="pay_user lo_user" style="background-image:url(img/p_login_password-01.svg);background-repeat:no-repeat;background-size: 20px;background-position: 20px 11px " placeholder="請輸入使用者密碼"></td>
        </tr>
        <tr>
          <td colspan="3"><input name="passtoo" type="password" class="pay_user lo_user" style="background-image:url(img/p_login_password-01.svg);background-repeat:no-repeat;background-size: 20px;background-position: 20px 11px " placeholder="<?php echo $username; ?>"></td>
        </tr>
        <!--<tr>
          <td colspan="3"><input name="card" type="text" class="pay_user lo_user" style="margin-top: 0px;background-image:url(img/p_login_account-01.svg);background-repeat:no-repeat;background-size: 20px;background-position: 20px 11px  " placeholder="請輸入二級密碼"></td>
        </tr>-->
		<tr>
          <!--<td colspan="3"><input name="sum" id="sum" type="tel" class="pay_user lo_user" style="background-image:url(img/p_login_verification-01.svg);background-repeat:no-repeat;background-size: 20px;background-position: 20px 11px " placeholder="請輸入驗證碼" AutoComplete="Off"></td>-->
		  <input type="hidden"  name="cksum" id="cksum" value="" />
        </tr>
        <!--
        <tr>
          <td ><div class="code" id="changeCode" ><?php //echo $sum;?></div>
          </td>
          <td></td>
          <td ><a href="#" onClick="see()" style="width: 130px;font-size: 16px;color: #595757; "><span style="line-height: 21px"><img src="img/refreash-01.svg" alt="" style="width: 18px;margin-bottom: 3px"></span>刷新驗證碼</a>
          </td>
          <td></td>
        </tr>
        <script>see();</script>
        -->
      </table>

 <!-- <?php

  mysql_select_db($database_lp, $lp);
    $b = $_GET['a'];
    $query_Recf = sprintf("SELECT * FROM st_record WHERE verification = '$b' ");//
          $Recf = mysql_query($query_Recf, $lp) or die(mysql_error());
          $row_Recf = mysql_fetch_assoc($Recf);
    $c = $row_Recf['s_user'];
          ?> -->
      <div class="" align="center"><input class="login_bt" type="submit" onClick="" value="更改密碼" style="width: 87%;">
      <div style="color: #fff;padding: 10px"><? echo $a;?></div>
      <input type="hidden" name="MM_insert" value="form1" />
      
      
      </div>
    </div>
</form>
<div class="col-lg-12 col-md-12 col-xs-12 st_pe_foot" style="height: 50px; background-color:#efefef; width: 100% ;position: fixed; ">
   <img src="img/life_pay_logo-01.png" width="320px" style="margin-top: 10px" alt="">
 </div>
</body>
</html>