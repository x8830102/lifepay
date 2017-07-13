<?php require_once('Connections/lp.php');mysql_query("set names utf8");
require_once('Connections/sc.php');mysql_query("set names utf8");

session_start();
if ($_SESSION['MM_Username'] == ""){
  header(sprintf("Location: manage_main.php"));exit;
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

//版型
require_once('lifepay_user.php');
?>

<!DOCTYPE html>
<html>
  <script>
  function goBack() {
    location.assign('manage_Invoice.php');
  }


  $(document).ready(function() {
      $.ajax({
          type: "POST",
          url: "http://livelink.com.tw/test_table/get_total_r.php",
          data: "",
          dataType: "json",
          success: function(resultData) {
          var opt={"oLanguage":{"sUrl":"dataTables.zh-tw.txt"},
                 "bJQueryUI":true,
                 "aoColumns":[{"sTitle":"日期"},
                            {"sTitle":"商店名稱"},
                            {"sTitle":"紅利積分入帳"},
                            {"sTitle":"紅利積分出帳"},
                            {"sTitle":"擁有積分"},
                            {"sTitle":"狀態"}],
                 "aaData": resultData
                 };         
           $("#custTable").dataTable(opt);
           }
      });
  });
  </script>
</head>
<body style="background-color:#f5c1ad;min-height: 800px">

<div class="management_desk"> 
  <div class="latest_info">
    <div class="mebr_top" align="center" >
      <a href="person_main.php"><img src="img/life_pay_logo-01.png" width="220px" alt=""></a>
      <a href="#"  data-toggle="modal" data-target="#myModal2"><img src="img/set_up-01.svg" style="position: absolute; left: 20px; top: 67px" width="25px" alt=""></a>
      <div class="search_bt" align="center" style="margin-top: -22px;display: none;" id="back">
      <div style="width: 88%" align="left">
        <a style="cursor: pointer;" onClick="goBack()"><input type="button" value="< 返回" style="background: #595757; color: #fff;border: 0px ;border-radius: 5px;padding: 4px 25px"></a>
      </div></div>
    </div>

    <div class="search_bt" align="center">
      <div style="width: 88%" align="center"><span>紅利積分總額</span></div>
    </div>

    <table id="custTable" class="display" cellspacing="0" width="100%">
            
    </table>


     <div class="search_bt" align="center" style="margin-top: -22px;margin-bottom: 60px">
      <div style="width: 88%;" id="return_bt" align="left" >
        <a  style="cursor: pointer;" onClick="goBack()"><input type="button" value="< 返回" style="background: #595757; color: #fff;border: 0px ;border-radius: 5px;padding: 4px 25px"></a>
      </div></div>
      <div class="col-lg-12 col-md-12 col-xs-12 st_pe_foot" style="height: 48px; background-color:#efefef; width: 100% ;position: fixed;bottom: 0px">
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
  </div>
</div>
</body>
</html>
