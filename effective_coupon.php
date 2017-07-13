<?php require_once('Connections/lp.php');mysql_query("set names utf8");
 
session_start();
if ($_SESSION['MM_Username'] == ""){header(sprintf("Location: life_pay.php"));exit;
}else{
  $m_username = $_SESSION['mem'];//登入帳號
  $m_nick = $_SESSION['nick'];//登入暱稱
  $number = $_SESSION['number'];
}

$date = date("Y-m-d");
//echo $date;
  mysql_select_db($database_lp, $lp);
  $query_str = sprintf("SELECT * FROM coupon WHERE p_user ='$m_username' && p_number ='$number' && effective_date < '$date' order by complete ASC, effective_date ASC, s_nick ASC");
  $Restr = mysql_query($query_str, $lp) or die(mysql_error());
  $row_str = mysql_fetch_assoc($Restr);
  $row_num = mysql_num_rows($Restr);


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
  <script src="js/main.js"></script>
  <style>
  @media (max-width: 400px){
    #back {
      position: absolute;
      top: 110px;
      left: -10px;
      display: none;
    }
  }
  </style>
  <script>
  function goBack() {
  window.history.back();
}

function cel(){
  $(document).ready(function() {
      //ajax 送表單
    $.ajax({
        type: "POST",
        url: "del_coupon.php",
        dataType: "text",
        success: function(data) {
        location.reload();
          //alert("完成結帳。");

        }

      })
    })
}
</script>
</head>
<body class="person" style="min-height: 800px ">


<div class="mebr_top" align="center">
  <a href="person_main.php"><img src="img/life_pay_logo-01.png" width="220px" alt=""></a>
  <a href="#"  data-toggle="modal" data-target="#myModal2"><img src="img/set_up-01.svg" style="position: absolute; left: 20px; top: 67px" width="25px" alt=""></a>
 <div class="search_bt " align="center" style="margin-top: -22px;display: none;" id="back">
  <div style="width: 88%" align="left">
    <a style="cursor: pointer;" onClick="goBack()"><input type="button" value="< 返回" style="background: #595757; color: #fff;border: 0px ;border-radius: 5px;padding: 4px 25px"></a>
  </div></div>
</div>

<div class="search_bt" align="center">
  <div style="width: 88%" align="center"><span ><?php echo $m_username;?></span><span>的過期優惠券</span></div>
  <div  align="center">
  <button type="button" style="background: #487be5;width: 110px;margin-top: 12px" class="date_but" onclick="cel()">清空</button>  
  </div>
</div>

  <div class="search_table table-responsive " align="center" style="overflow-y: visible;white-space: nowrap;padding-top: 10px">
    <table align="center" class="table coupon_table" style="background-color: #fff;border-radius: 10px">
    
    <tr  style="background-color: #4ab3a6;color: #fff ">
      <th style="border-radius: 10px 0px 0px 0px">有效期限</th>
      <th >優惠券額</th>
      <th >優惠店家</th>
      <th   style="border-radius: 0px 10px 0px 0px">使用狀態</th>
    </tr>
    <?php if($row_num != 0){
      do{?>
        <tr class="search_tr"  style="text-align:center; background-color: #fff">
          <td style="border-radius: 0px 0px 0px 10px"><?php echo $row_str['effective_date'];?></td>
          <td><?php echo $row_str['discount'];?></td>
          <td><?php echo $row_str['s_nick'];?></td>
          <?php if($row_str['complete']==1){?>
            <td   style="border-radius: 0px 0px 10px 0px"><span style="color: #22a363">已使用</span></td>
            <?php }else{?>
            <td  style="border-radius: 0px 0px 10px 0px"><span style="color: #ff5e59">未使用</span></td>
            <?php }?>
        </tr>
      <?php }while($row_str = mysql_fetch_assoc($Restr));}?>
    </table>
</div>
 <div class="search_bt" align="center" style="margin-top: -22px;margin-bottom: 60px">
  <div style="width: 88%" align="left" id="return_bt">
    <a  style="cursor: pointer;" onclick="goBack()"><input type="button" value="< 返回" style="background: #595757; color: #fff;border: 0px ;border-radius: 5px;padding: 4px 25px" ></a>
  </div></div>
  <div class="col-lg-12 col-md-12 col-xs-12 st_pe_foot" style="height: 48px; background-color:#efefef; width: 100%;position: fixed;bottom: 0px">
   <img src="img/login_footlogo-01.png" width="320px" style="margin-top: 10px" alt="">
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
<div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content " style="padding: 5px 20px;margin-top: 100px">
        <ul class="setting">
          
          <li><a href="logout.php"><img src="img/sign-out-option.svg" width="25px" alt=""><span style="margin-left: 8px">登出</span></a></li>
        </ul>
      </div>
    </div>
</div>
</body>
</html>
