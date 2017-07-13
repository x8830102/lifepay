<?php require_once('Connections/sc.php');require_once('Connections/tw.php');require_once('Connections/lp.php');mysql_query("set names utf8");?>
<?php 
session_start();
$number = $_SESSION['number'];
$content = $_POST['content'];
//echo $number;
$editFormAction = $HTTP_SERVER_VARS['PHP_SELF'];
if (isset($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $editFormAction .= "?" . $HTTP_SERVER_VARS['QUERY_STRING'];
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
        if ($content != "") {
          mysql_select_db($database_sc, $sc);
          $query_stcomplete = sprintf("UPDATE memberdata SET note = '$content' WHERE number = '$number'");
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
  $m_content = $row_Recf['note'];
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
          <td colspan="3">業務合作：</td>
          <td colspan="3"><input name="content" type="text" class="pay_user lo_user" placeholder="<?php echo $m_content ?>"></td>
        </tr>
		<tr>
		  <input type="hidden"  name="cksum" id="cksum" value="" />
        </tr>
      </table>

      <div class="" align="center"><input class="login_bt" type="submit" onClick="" value="上傳" style="width: 87%;">
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