<?php require_once('Connections/sc.php');require_once('Connections/tw.php');require_once('Connections/lp.php');mysql_query("set names utf8");?>
<?php 
session_start();
$number = $_SESSION['number'];
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
        header(sprintf("Location: person_main9.php"));
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
<form id="form1" name="form1" method="post" action="<?php echo $editFormAction; ?>">
<div style="width:100%"> 
  <div class="pay_login" align="center" style="margin: 18px auto 0px auto;">
      <table class="login"  width="100%">
        <tr>
          <td colspan="3">姓名：</td>
          <td colspan="3"><?php echo  $m_name;?></td>
        </tr>
        <tr>
          <td colspan="3">性別：</td>
          <td colspan="3"><?php echo  $m_sex;?></td>
        </tr>
        <tr>
          <td colspan="3">生日：</td>
          <td colspan="3"><?php echo  $m_age;?></td>
        </tr>
        <tr>
          <td colspan="3">電話：</td>
          <td colspan="3"><input name="phone" type="text" class="pay_user lo_user" style="background-image:url(img/p_login_password-01.svg);background-repeat:no-repeat;background-size: 20px;background-position: 20px 11px " placeholder="<?php echo $m_phone ?>"></td>
        </tr>
        <tr>
          <td colspan="3">信箱：</td>
          <td colspan="3"><input name="email" type="text" class="pay_user lo_user" style="background-image:url(img/p_login_password-01.svg);background-repeat:no-repeat;background-size: 20px;background-position: 20px 11px " placeholder="<?php echo $m_email ?>"></td>
        </tr>
        <tr>
          <td colspan="3">居住地：</td>
          <td colspan="3"><input name="address" type="text" class="pay_user lo_user" style="background-image:url(img/p_login_password-01.svg);background-repeat:no-repeat;background-size: 20px;background-position: 20px 11px " placeholder="<?php echo $m_address ?>"></td>
        </tr>
		<tr>
		  <input type="hidden"  name="cksum" id="cksum" value="" />
        </tr>
      </table>

      <div class="" align="center"><input class="login_bt" type="submit" onClick="" value="更新" style="width: 87%;">
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