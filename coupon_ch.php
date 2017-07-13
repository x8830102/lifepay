<?php require_once('Connections/sc.php');mysql_query("set names utf8");
session_start();
if ($_SESSION['MM_Username'] == ""){header(sprintf("Location: life_pay.php"));exit;
}else{
  $m_username = $_SESSION['mem'];//登入帳號
  $m_nick = $_SESSION['nick'];//登入暱稱
  $number = $_SESSION['number'];
}

$user = $_POST['user'];
$fcount = $_POST['fc'];
$dis = $_POST['dis'];
$deadline = $_POST['deadline'];
//echo $dis;echo $deadline;echo $fcount;

  mysql_select_db($database_sc, $sc);
  $query_Recf = sprintf("SELECT * FROM memberdata WHERE m_username = '$user'");//
  $Recf = mysql_query($query_Recf, $sc) or die(mysql_error());
  $row_Recf = mysql_fetch_assoc($Recf);
  $totalRows_Recf = mysql_num_rows($Recf);
  $p_nick = $row_Recf['m_nick'];
  $p_number = $row_Recf['number'];

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
<body>
<div class="mebr_top" align="center">
  <a href="person_main.php"><img src="img/life_pay_logo.png" width="220px" alt=""></a>
  <a href="person_main.php"><img src="img/set_up-01.svg" style="position: absolute; right: 20px; top: 67px" width="25px" alt=""></a>
  <a href="#" data-toggle="modal" data-target="#myModal"><img src="img/ff-01.png" style="position: absolute; left: 20px; top: 67px" alt="" width="25px" ></a>
</div>
<div class="search_bt" align="center">
  <div style="width: 88%" align="left"><span>使用者：</span><span ><?php echo $m_nick;?></span></div></div>
<div class="search_table table-responsive " align="center" style="overflow-y: visible;white-space: nowrap;padding-top: 10px">


      <table align="center" class="table coupon_table" style="" >
      <form action="INSERT_coupon.php" method="post">
        <tr>
          <td class="cus_td" >消費者名稱/暱稱：</td>
          <td><?php echo $user;?></td>
          <input type="hidden" name="p_user" value="<?php echo $user;?>">
        </tr>
        <tr>
          <td class="cus_td" >推播人數：</td>
          <td><?php echo $fcount;?></td>
          <input type="hidden" name="acc" value="<?php echo $fcount;?>">
        </tr>
        <tr>
          <td class="cus_td" >下次折抵：</td>
          <td><?php echo $dis;?>%</td>
          <input type="hidden" name="dis" value="<?php echo $dis;?>">
        </tr>
        <tr>
          <td class="cus_td" >有效期限：</td>
          <td><?php echo $deadline;?></td>
          <input type="hidden" name="eff" value="<?php echo $deadline;?>">
        </tr>
        <tr>
        <input type="hidden" name="p_nick" value="<?php echo $p_nick;?>">
        <input type="hidden" name="p_number" value="<?php echo $p_number;?>">
          <td><a href="#"><input class="sys_bt" type="button" value="上一步" style="display: inline;margin-bottom: 20px;width: 70%; background: #cfcfcf;border:0px;border-radius: 20px"></a></td>
          <td><a href="#"><input class="sys_bt" type="submit" value="送出" style="display: inline;margin-bottom: 20px;width: 100%; background: #f5504e;border:0px;border-radius: 20px"></a></td>
        </tr>
      </form>
      </table>
    </div>
 <div  class="col-lg-12 col-md-12 col-xs-12 st_pe_foot" style=";margin-top: 30px">
  @2016 LIFE LINK 串門子雲盟事業版權所有
</div>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    <div class="modal-content col-lg-12 col-md-12 col-xs-12" style="padding-left: 0px;padding-right: 0px">
    <div class="col-lg-12 col-md-12 col-xs-12 mb_features" align="center" style="padding: 10px">
    <a href="person_main.php"><img src="img/life_pay_logo-2.png" width="50%" alt=""></a>
  </div>
       <div class="col-lg-6 col-md-6 col-xs-6 mb_features">
    <a href="person_qrcode.php"><img src="img/my_qr-01.png" width="100%" alt=""></a>
  </div>
  <div class="col-lg-6 col-md-6 col-xs-6 mb_features">
    <a href="person_inquire.php"><img src="img/search-01.png" width="100%" alt=""></a>
  </div>
  <div class="col-lg-6 col-md-6 col-xs-6 mb_features">
    <a href="person_coupon.php"><img src="img/coupon-01.png" width="100%" alt=""></a>
 
</div>
  <div class="col-lg-6 col-md-6 col-xs-6 mb_features">
    <a href="#"><img src="img/life_pay-01.png" width="100%" alt=""></a>
  </div>
    </div>
    </div>
</div>

</body>
</html>
