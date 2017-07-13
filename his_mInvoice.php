<?php 
require_once('Connections/lp.php');mysql_query("set names utf8");
require_once('Connections/sc.php');mysql_query("set names utf8");
require_once('Connections/tw.php');mysql_query("set names utf8");
?>
<?php 
session_start();
if ($_SESSION['MM_Username'] == ""){header(sprintf("Location: manage_login.php"));exit;
}else{
  $m_username = $_SESSION['mem'];//登入帳號
  $m_nick = $_SESSION['nick'];//登入暱稱
  $number = $_SESSION['number'];
}

$user_id = $_SESSION['MM_Username'];

mysql_select_db($database_lp, $lp);
$query_user = sprintf("SELECT * FROM lf_user WHERE user_id ='$user_id' ");
$Reuser = mysql_query($query_user, $lp) or die(mysql_error());
$row_user = mysql_fetch_assoc($Reuser);
//如果沒有列數就表示是使用者連過來的
$total_user = mysql_num_rows($Reuser);
if($total_user == ''){
  header(sprintf("Location: logout.php"));exit;
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
    $query_Recoc = "SELECT * FROM Invoice, lf_user WHERE lf_user.st_name = Invoice.nick && Invoice.nick like '%$store%' && lf_user.industry like '%$industry%' group by nick";
    $Recoc = mysql_query($query_Recoc, $lp) or die(mysql_error());
    $row_recoc = mysql_fetch_assoc($Recoc);
    $total_recoc = mysql_num_rows($Recoc);
} else {
    //沒有搜尋條件取得交易資料
    mysql_select_db($database_lp, $lp);
    $query_Recoc = "SELECT * FROM manage_invoice group by nick";
    $Recoc = mysql_query($query_Recoc, $lp) or die(mysql_error());
    $row_recoc = mysql_fetch_assoc($Recoc);
    $total_recoc = mysql_num_rows($Recoc);
}

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
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
</head>
<body style="background-color:#f5c1ad;min-height: 860px">

<div class="mebr_top" align="center">
  <a href="manage_main.php"><img src="img/life_pay_logo-01.png" width="220px" alt=""></a>
  <a href="#"  data-toggle="modal" data-target="#myModal2"><img src="img/set_up-01.svg" style="position: absolute; left: 20px; top: 67px" width="25px" alt=""></a>
</div>
<ul class="nav nav-tabs" style="padding: 0px 10px">
  <li class="active"><a data-toggle="tab" href="#home">帳款明細表</a></li>
</ul>
<div style="margin-bottom: 50px;">
  <form action="his_mInvoice.php" method="get">
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
          <ul>
            <li style="display: inline-block;width: 100px;">申請店家</li>
            <li style="display: inline-block;width: 100px;">消費積分</li>
            <li style="display: inline-block;width: 100px;">串串積分</li>
            <li style="display: inline-block;width: 100px;">現金/刷卡</li>
            <li style="display: inline-block;width: 100px;">回饋額</li>
            <li style="display: inline-block;width: 100px;">回饋金額</li>
            <li style="display: inline-block;width: 100px;">查看</li>
          </ul>
        <?php
            if($total_recoc != 0){

              do{
                $nick = $row_recoc['nick'];
                $s_number = $row_recoc['number'];

                //取得商店交易資料
                mysql_select_db($database_lp, $lp);
                $query_ss = "SELECT * FROM manage_invoice WHERE number = '$s_number'";
                $Recoc_ss = mysql_query($query_ss, $lp) or die(mysql_error());
                $row_ss = mysql_fetch_assoc($Recoc_ss);
                $total_ss = mysql_num_rows($Recoc_ss);

                if($total_ss != 0){ //沒有列數就表示沒有新申請的資料
                    
                    $c_total = 0; //換商店就清空
                    $g_total = 0;
                    $q_total = 0;
                    $usage_fee = 0;
                    $spend_total = 0;

                    do{
                          //串串積分
                          $c = $row_ss['c_total'];
                          $c_total = $c_total + $c;
                          //消費積分
                          $g = $row_ss['g_total'];
                          $g_total = $g_total + $g;
                          //回饋金
                          $q = $row_ss['q_total'];
                          $q_total = $q_total + $q;
                          //回饋金額
                          $u = $row_ss['usage_fee'];
                          $usage_fee = $usage_fee + $u;
                          //現金/刷卡
                          $spend = $row_ss['spend'];
                          $spend_total = $spend_total + $spend;

                      }while($row_ss = mysql_fetch_assoc($Recoc_ss));
                }

                    $arr =array("s_number"=>$s_number,"c_total"=>$c_total,"g_total"=>$g_total,"q_total"=>$q_total,"usage_fee"=>$usage_fee);
                    $arr_json = json_encode($arr); //陣列轉josn

                ?>
              <ul>
                <li style="display: inline-block;width: 100px;"><?php echo $nick;?></li>
                <li style="display: inline-block;width: 100px;"><?php echo $g_total;?></li>
                <li style="display: inline-block;width: 100px;"><?php echo $c_total;?></li>
                <li style="display: inline-block;width: 100px;"><?php echo $spend_total;?></li>
                <li style="display: inline-block;width: 100px;"><?php echo $q_total;?></li>
                <li style="display: inline-block;width: 100px;"><?php echo $usage_fee;?></li>
                <?php
                  echo "<li style='display: inline-block;width: 100px;'><button style='background:#487be5;color:#fff;' id='dialog_open' class='dialog_open$total_recoc' value='$arr_json'>查看</button></li>";
                ?>
              </ul>

              <script>
                $(function()
                {

                    $(".dialog_open<?php echo $total_recoc;?>").click(function(event){
                        var c = $(".dialog_open<?php echo $total_recoc;?>").val();
                        var val = JSON.parse(c);
                        var ss_number = val.s_number;//接到ss_number傳送到bb.php產生json

                            $.ajax({
                            type: "POST",
                            url: "get_store_list_bb.php",
                            data: {
                              ss_number:ss_number
                            },
                            dataType: "json",
                                success: function(resultData) {
                                  var opt={"oLanguage":{"sUrl":"dataTables.zh-tw.txt"},//讀取中文模板
                                            "bJQueryUI":true,//套用所選之布景
                                            "bProcessing":true,//如需要一些時間處理時, 表格上會顯示"處理中 ..."
                                            "scrollY": 200,//卷軸
                                            "scrollCollapse": true,
                                            "aoColumns":[{"sTitle":"申請日期"},//自動產生Title
                                                        {"sTitle":"消費積分"},
                                                        {"sTitle":"串串積分"},
                                                        {"sTitle":"現金/刷卡"},
                                                        {"sTitle":"合計"},
                                                        {"sTitle":"回饋金額"}],
                                            "aaData": resultData//自動產生內容 ps.需與aoColumns對應
                                            };
                                  $(".custTable<?php echo $total_recoc;?>").dataTable(opt);
                                }
                            });


                        $( ".dialog<?php echo $total_recoc;?>" ).dialog( "open");
                        $('.ui-dialog-titlebar-close').click(function(){
                          location.reload();
                        });
                        //按下dialog的確認後關閉
                        $("#bt<?php echo $total_recoc;?>").click(function(event){
                          $(".dialog<?php echo $total_recoc;?>").dialog( "close" );
                          location.reload();
                        });
                    });


                    $(".dialog<?php echo $total_recoc;?>").dialog({
                     show: {
                      effect: "fade",
                      },
                      autoOpen: false, //預設不顯示
                      draggable: false, //設定拖拉
                      resizable: false, //設定縮放
                      title:"LIFE PAY",
                      modal: true, //灰色透明背景限制只能按dialog
                      width:800,
                      responsive: true
                    
                    });


                })


              </script>

              <div class="dialog<?php echo $total_recoc;?>" style="display: none;text-align:center;">
                    <span><?php echo $nick?></span>
                    <hr>
                    <table class="custTable<?php echo $total_recoc;?>" width="100%">
                    </table>
                    <div>
                      <button id="bt<?php echo $total_recoc;?>">確認</button>
                    </div>
              </div>

        <?php 

              $total_recoc = $total_recoc-1;

              }while($row_recoc = mysql_fetch_assoc($Recoc));

            }
            ?>

          </ul>
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

</body>
</html>
