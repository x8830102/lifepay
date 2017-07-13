<?php 
require_once('Connections/lp.php');mysql_query("set names utf8");
require_once('Connections/sc.php');mysql_query("set names utf8");
?>
<?php 
session_start();
if ($_SESSION['MM_Username'] == ""){
  header(sprintf("Location: manage_login.php"));exit;
}else{
  $number = $_SESSION['number'];
}
//如果沒有列數就表示不是公司端的帳號
mysql_select_db($database_lp, $lp);
$SQL = sprintf("SELECT * FROM manage WHERE number ='$number' ");
$conn = mysql_query($SQL, $lp) or die(mysql_error());
$total = mysql_num_rows($conn);
if($total == 0){
    //表示是使用者連過來的
    mysql_select_db($database_sc, $sc);
    $query_user = sprintf("SELECT * FROM memberdata WHERE number ='$number' ");
    $Reuser = mysql_query($query_user, $sc) or die(mysql_error());
    $total_user = mysql_num_rows($Reuser);
    if($total_user != ''){
      header(sprintf("Location: logout.php"));exit;
    }

    //表示是商店連過來的
    mysql_select_db($database_lp, $lp);
    $query_store = sprintf("SELECT * FROM lf_user WHERE number ='$number' ");
    $Restore = mysql_query($query_store, $lp) or die(mysql_error());
    $total_store = mysql_num_rows($Restore);
    if($total_store != ''){
      header(sprintf("Location: slogout.php"));exit;
    }
}


if($_GET['store'] != "" || $_GET['industry'] != "") { //商家
    $store=$_GET['store'];
    $industry=$_GET['industry'];

    mysql_select_db($database_lp, $lp);
    $query_str = "SELECT * FROM lf_user WHERE level ='boss' && st_name like '%$store%' && industry like '%$industry%'";
    $Restr = mysql_query($query_str, $lp) or die(mysql_error());
    $row_str = mysql_fetch_assoc($Restr);
    $nick = $row_str['st_name'];

    //有搜尋條件取得交易資料
    mysql_select_db($database_lp, $lp);
    $query_Recoc = "SELECT * FROM Invoice WHERE nick = '$nick' group by nick";
    $Recoc = mysql_query($query_Recoc, $lp) or die(mysql_error());
    $row_recoc = mysql_fetch_assoc($Recoc);
    $total_recoc = mysql_num_rows($Recoc);
} else {
    //沒有搜尋條件取得交易資料
    mysql_select_db($database_lp, $lp);
    $query_Recoc = "SELECT * FROM Invoice group by nick";
    $Recoc = mysql_query($query_Recoc, $lp) or die(mysql_error());
    $row_recoc = mysql_fetch_assoc($Recoc);
    $total_recoc = mysql_num_rows($Recoc);
}


//版型
require_once('lifepay_user.php');
?>
<!DOCTYPE html>
<html>

<body style="background-color:#f5c1ad;min-height: 860px">

<div class="management_desk"> 
  <div class="latest_info">
    <div style="margin-bottom: 50px;">
      <form action="manage_Invoice.php" method="get">
        <ul class="person_search">
          <li><span style="margin: 4px;line-height: 40px"></span></li>
          <li> <input type="text" name="store" placeholder="輸入店家名稱"  value="<?php echo $_GET['store'];?>" style="height: 40px;border:1px solid #fff;-webkit-appearance:none;padding: 5px;line-height: 40px"></li>
          <li><span style="margin: 4px;line-height: 40px"></span></li>
          <li><select name="industry" id="industry" placeholder="產業 : " style="width: 200px;text-align: left;height: 40px;border:1px solid #fff;line-height: 40px">
                  <option value="" style="color:#999999"><span style="color:#999999">請選擇產業</span></option>
                  <option value="餐飲業">餐飲業</option>
                  <option value="服飾業">服飾業</option>
                  <option value="資訊業">資訊業</option>
                  <option value="飯店業">飯店業</option>
                  <option value="零售業">零售業</option>
                  <option value="其他">其他</option>
                  </select></li>
          <li><button type="submit" style="margin-left: 8px;line-height: 35px" class="date_but">查詢</button></li>
        </ul>
      </form>
    </div>
      
      <div id="home" class="tab-pane fade in active">
          <div class="table-responsive search_table " style="overflow-y: visible;white-space: nowrap;height: 400px" align="center">
            <div>
              <a target="_blank" href="manage_totalG.php"><button>查消費積分</button></a><!--manage_totalG-->
              <a target="_blank" href="manage_totalC.php"><button>查串串積分</button></a><!--manage_totalC-->
              <a target="_blank" href="manage_totalR.php"><button>查紅利積分</button></a><!--manage_totalR-->
            </div>
          </div>
      </div>


      <div class="col-lg-12 col-md-12 col-xs-12 st_pe_foot" style="height: 48px; background-color:#efefef; width: 100% ;position: relative;bottom: 0px">
         <img src="img/login_footlogo-01.png" width="320px" style="margin-top: 10px" alt="">
      </div>

      <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content col-lg-12 col-md-12 col-xs-12" style="padding-left: 0px;padding-right: 0px">
            <div class="col-lg-12 col-md-12 col-xs-12 mb_features" align="center" style="padding: 10px"><a href="store_main.php"><img src="img/life_pay_logo-2.png" width="50%" alt=""></a></div>
            <!--<div class="col-lg-6 col-md-6 col-xs-6 mb_features"><a href="qrcodestart.html"><img src="../table/img/my_qr-01.png" width="100%" alt=""></a></div>-->
            <div class="col-lg-6 col-md-6 col-xs-6 mb_features"><a href="store_search.php"><img src="img/search-01.png" width="100%" alt=""></a></div>
            <!--<div class="col-lg-6 col-md-6 col-xs-6 mb_features"><a href="store_coupon.php"><img src="../table/img/coupon-01.png" width="100%" alt=""></a></div>-->
            <div class="col-lg-6 col-md-6 col-xs-6 mb_features"><a href="store_checkout.php"><img src="img/life_pay-01.png" width="100%" alt=""></a></div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="myModal2" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content " style="padding: 5px 20px;margin-top: 100px">
            <ul class="setting">
              <li><a href="mlogout.php"><img src="img/sign-out-option.svg" width="25px" alt=""><span style="margin-left: 8px">登出</span></a></li>
            </ul>
          </div>
        </div>
      </div>
  </div>
</div>

</body>
</html>
