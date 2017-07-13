<?php require_once('Connections/sc.php');require_once('Connections/tw.php');mysql_query("set names utf8");
session_start();
if ($_SESSION['MM_Username'] == ""){header(sprintf("Location: life_pay.php"));exit;
}else{
  $m_username = $_SESSION['mem'];//登入帳號
  $m_nick = $_SESSION['nick'];//登入暱稱
  $number = $_SESSION['number'];
}
$user = $_GET['u'];

unset($fmu);$fmi=0;$fmi2=1;$fmk=0;
$fmu[0]=$user;$fcount = '';
while ($fmk != 1) {
  $fmj=$fmu[$fmi];//從源頭的人開始取值往下代做收尋 例:peggy -> x12345 -> test94
  //echo $fmj;
    mysql_select_db($database_sc, $sc);
    $query_Recfmu = sprintf("SELECT * FROM memberdata WHERE fname = '$fmj'" );//
    $Recfmu = mysql_query($query_Recfmu, $sc) or die(mysql_error());
    $row_Recfmu = mysql_fetch_assoc($Recfmu);
    $totalRows_Recfmu = mysql_num_rows($Recfmu);//echo $totalRows_Recfmu;exit;
    //print_r($row_Recfmu);
    if ($totalRows_Recfmu != 0) {
    do {$fma=$row_Recfmu['m_username'];if (in_array($fma,$fmu) == false) {$fmu[$fmi2]=$fma;$fmi2++;}} while ($row_Recfmu = mysql_fetch_assoc($Recfmu));
    }

    //底下有多少人也關注這家商店
    mysql_select_db($database_tw, $tw);
    $query_str = sprintf("SELECT * FROM fstore WHERE my_us = '$fmj' && yu_us = '$m_username'");
    $Restr = mysql_query($query_str, $tw) or die(mysql_error());
    $row_str = mysql_fetch_assoc($Restr);
    $row_num = mysql_num_rows($Restr);
    //print_r($query_str);
    //echo $row_num;
    if($row_num != 0){
      $fcount++;
    }
    

  $fmi++;

  if ($fmu[$fmi] == "") {$fmk=1;}

  }

  //echo $fcount;

$fmu_total=count($fmu); //資料總數
//echo ($fmu_total-1);

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
  <script>
    function va(){
      //alert(dis);
      if(document.getElementById('dis').value<10){
        alert('輸入錯誤 折扣%數最低10%');
        document.getElementById('dis').value=10;
      }
    }
    function ck(){
      var ds = document.getElementById('dis').value;
      var dline = document.getElementById('deadline').value;
      var form1 = document.getElementById('form1');
      if(ds!='' && dline!=''){
        form1.submit();
      }else if(ds==''){alert('請輸入折扣數');}
      else{alert('請輸入有效期限');}
    }
  </script>
  <style>
  .search_table td {text-align: left !important;}</style>
</head>
<body>
<div class="mebr_top" align="center">
  <a href="store_main.php"><img src="img/life_pay_logo.png" width="220px" alt=""></a>
  <a href="#" data-toggle="modal" data-target="#myModal2"><img src="img/set_up-01.svg" style="position: absolute; right: 20px; top: 67px" width="25px" alt=""></a>
  <a href="#" data-toggle="modal" data-target="#myModal"><img src="img/ff-01.png" style="position: absolute; left: 20px; top: 67px" alt="" width="25px" ></a>
</div>
<div class="search_bt" align="center">
  <div style="width: 88%" align="left"><span>使用者：</span><span ><?php echo $m_nick;?></span></div></div>
<div class="search_table table-responsive " align="center" style="overflow-y: visible;white-space: nowrap;padding-top: 10px">
      <table align="center" class="table" style="width: 88%;margin: 10px auto 0px auto;line-height: 45px;background-color: #fff;border-radius: 10px;" >
      <form id="form1" action="coupon_ch.php" method="post">
        <tr>
          <td class="cus_td" >消費者名稱/暱稱：</td>
          <td><?php echo $user;?></td>
          <input type="hidden" name="user" value="<?php echo $user;?>">
        </tr>
        <tr>
          <td class="cus_td" >推播人數：</td>
          <td><?php if($fcount != ''){echo $fcount;}else{echo 0;}?></td>
          <input type="hidden" name="fc" value="<?php if($fcount != ''){echo $fcount;}else{echo 0;}?>">
        </tr>
        <?php
        //判斷有沒有達到推廣人
        if($fcount >= 0){?>
        <tr>
          <td class="cus_td" >下次消費可折抵</td>
          <td><input type="tel" id="dis" name="dis" placeholder="10" min="10" onblur="va()" value="" style="width: 120px;height: 35px">%</td>
        </tr>
        
        <tr>
          <td class="cus_td" >有效期限</td>
          <td><input type="date" id="deadline" name="deadline" style="background: #b5b5b5;height: 35px;line-height: 35px;color: #fff;width: 140px"></td>
        </tr>
      
        <tr>
          <td colspan="2"><input class="sys_bt" type="button" onclick="ck()" value="下一步" style="margin-bottom: 20px;width: 70%; background: #6480ee;border:0px;border-radius: 20px;margin-left: 15%"></td>
        </tr>
        <?php }else{?>
        <tr>
          <td class="cus_td" colspan="2">目前達成</td>
          <td><input type="text" size="5" readonly="readonly" placeholder=<?php if($fcount != ''){echo $fcount;}else{echo 0;}?> style="border: 0px;">人</td>
          
        </tr>
        <tr>
          <td class="cus_td">還差<input type="text" size="5" readonly="readonly" placeholder='<?php echo (10-$fcount);?>' style="border: 0px;">人</td>
          <td class="cus_td">加油!</td>
        </tr>
        <?php }?>
        
      </form>
      </table>
    </div>
  </div>
</div>
<div  class="col-lg-12 col-md-12 col-xs-12 st_pe_foot" style=";margin-top: 30px">
  @2016 LIFE LINK 串門子雲盟事業版權所有
</div>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    <div class="modal-content col-lg-12 col-md-12 col-xs-12" style="padding-left: 0px;padding-right: 0px">
    <div class="col-lg-12 col-md-12 col-xs-12 mb_features" align="center" style="padding: 10px">
    <a href="store_main.php"><img src="img/life_pay_logo-2.png" width="50%" alt=""></a>
  </div>
       <div class="col-lg-6 col-md-6 col-xs-6 mb_features">
    <a href="qrcodestart.html"><img src="img/my_qr-01.png" width="100%" alt=""></a>
  </div>
  <div class="col-lg-6 col-md-6 col-xs-6 mb_features">
    <a href="store_search.php"><img src="img/search-01.png" width="100%" alt=""></a>
  </div>
  <div class="col-lg-6 col-md-6 col-xs-6 mb_features">
    <a href="store_coupon.php"><img src="img/coupon-01.png" width="100%" alt=""></a>
 
</div>
  <div class="col-lg-6 col-md-6 col-xs-6 mb_features">
    <a href="store_checkout.php"><img src="img/life_pay-01.png" width="100%" alt=""></a>
  </div>
    </div>
    </div>
</div>
 <div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content " style="padding: 5px 20px;margin-top: 100px">
        <ul class="setting">
          <li><a href="store_person.php"><img src="img/user_ch.png" width="25px" alt=""><span style="margin-left: 8px">切換使用者</span></a></li>
          <li><a href="#"><img src="img/sign-out-option.svg" width="25px" alt=""><span style="margin-left: 8px">登出</span></a></li>
        </ul>
      </div>
    </div>
</div>
</body>
</html>
