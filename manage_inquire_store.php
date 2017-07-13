<?php 
require_once('Connections/lp.php');mysql_query("set names utf8");
require_once('Connections/sc.php');mysql_query("set names utf8");

/* AJAX check  */
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

    $data_value = $_POST['data_value'];

    mysql_select_db($database_lp, $lp);
    $query_stro = "SELECT * FROM lf_user WHERE st_name ='$data_value' && level = 'boss'";
    $Restro = mysql_query($query_stro, $lp) or die(mysql_error());
    $row_stro = mysql_fetch_assoc($Restro);
    $row_numo = mysql_num_rows($Restro);

    $industry = $row_stro['industry'];
    $store = $row_stro['st_name'];

?>
<script>

$(document).ready(function() {
  $('#menu a').click(function () {
    $('#wrapper').scrollTo($(this).attr('href'), 1000);
    //alert(this);
    return false;
  });
});

</script>

        <div align="left"><span style="font-size: 22px;color: #999;margin-left: 15%"><?php echo $industry;?></span><span style="font-size: 36px;font-weight: 500;margin-left: 19%"><?php echo $store;?></span>
          </div>

          <div class="search_bt" align="center" style="display: inline-block;text-align: left;">
            <ul style="float: left;margin-right: 8px;position: absolute;top: 25px;left: 25px" id="menu">
              <li style="padding-left: 5px">
                 <a href="#section0"><img src="img/next-01.png" width="22px" alt="" style="transform: rotate(180deg)"></a>
              </li>
            </ul>

            <div  align="center" >
              <ul class="search_bar" style="margin-top: 25px">
                <li><input type="date" name="sd1"  id="sd1" style="height: 40px;min-width: 120px; display: inline-block"></li>
                <li><span style="margin: 4px;line-height: 40px">至</span></li>
                <li><input type="date" name="sd2"  id="sd2" style="height: 40px;min-width: 120px;display: inline-block;"></li>
                <li><button type="button" style="margin-left: 8px;line-height: 35px;background-color:#8e78de;border-radius: 6px;color: #fff;border: 0px;width: 80px;font-size: 17px " class="date_but" id="check_section">查詢</button></li>
              </ul>
            </div>
          </div>

          <div class="search_table table-responsive " align="center" style="overflow-y: visible;white-space: nowrap;padding-top: 25px">
            <table align="center" class="table coupon_table" style="background: #fff;border-radius: 10px;width: 88%">
                <tr style="background-color: #903bff;color: #fff;">
                  <th style="border-radius: 10px 0px 0px 0px">商店名稱</th>
                  <th>產業別</th>
                  <th>帳號</th>
                  <th>信箱</th>
                  <th>營業時間</th>
                  <th>優惠券預設%數</th>
                  <th>回饋%數</th>
                  <th style="border-radius: 0px 10px 0px 0px">簽約時間</th>
                </tr>
                <tr class="search_tr" style="background-color: #f5f5f5">
                  <td  style="border-radius: 0px 0px 0px 10px"><?php echo $row_stro['st_name'];?></td>
                  <td><?php echo $row_stro['industry'];?></td>
                  <td><?php echo $row_stro['accont'];?></td>
                  <td><?php echo $row_stro['email'];?></td>
                  <td><?php echo $row_stro['time1'];?> ~ <?php echo $row_stro['time2'];?></td>
                  <td><?php echo $row_stro['st_dis'];?></td>
                  <td><input type="number" max="100" min="0" name="usage" id="usage" value="<?php echo $row_stro['usage_fee'];?>"><button type="button" id="btt">設定</button></td>
                  <td style="border-radius: 0px 0px 10px 0px"><?php echo $row_stro['contract'];?></td>
                  
                </tr>
                <input type="hidden" name="store" id="store" value="<?php echo $store;?>">
                <?php 
                mysql_select_db($database_lp, $lp); //無條件
                $query_strp = "SELECT * FROM Invoice WHERE nick ='$store' ORDER BY id DESC";
                $Restrp = mysql_query($query_strp, $lp) or die(mysql_error());

                for($i=0;$i<mysql_numrows($Restrp);$i++){

                    $row_strp = mysql_fetch_assoc($Restrp);
                    //銷售金額
                    $total_sell = $row_strp['total'];
                    $sell_sum = $sell_sum + $total_sell;
                    //消費積分總額
                    $g = $row_strp['g'];
                    $g_sum = $g_sum + $g;
                    //串串積分總額
                    $c = $row_strp['c'];
                    $c_sum = $c_sum + $c;
                    //使用券總額
                    $discount = $row_strp['discount'];
                    $discount_sum = $discount_sum + $discount;
                    //現金/信用卡
                    $spend = $row_strp['spend'];
                    $spend_sum = $spend_sum + $spend;
                    //支付金額
                    $usage_fee = $row_strp['usage_fee'];
                    $usage_fee_sum = $usage_fee_sum + $usage_fee;
                }
                ?>
                <script>
                  $("#btt").click(function(){
                      var usage = $("#usage").val();
                      var store = $("#store").val();

                      $.ajax({
                        type: "POST",
                        url: "manage_up.php",
                        dataType: "text",
                        data: {
                          usage:usage,
                          store:store
                        },
                        success: function(data) {
                          alert("更改完成!");
                        }

                      })
                  })

                  $("#check_section").click(function(){
                      var sd1 = $("#sd1").val();
                      var sd2 = $("#sd2").val();
                      var store = $("#store").val();
                      if(sd1 == "" && sd2 != ""){
                        $("#sd1").css("background","pink");
                        $("#sd1").focus();
                      }else if(sd1 != "" && sd2 == ""){
                        $("#sd2").css("background","pink");
                        $("#sd2").focus();
                      }else if(sd1 > sd2){
                        $("#sd1,#sd2").css("background","pink");
                        $("#sd1,#sd2").focus();
                      }else{
                        $("#sd1,#sd2").css("background","white");
                        $.ajax({
                          type: "POST",
                          url: "manage_up.php",
                          dataType: "text",
                          data: {
                            sd1:sd1,
                            sd2:sd2,
                            store:store
                          },
                          success: function(data) {
                            var data = JSON.parse(data);
                            var sd1 = data.sd1;
                            var sd2 = data.sd2;
                            var sell_sum = data.sell_sum;//銷售金額
                            var g_sum = data.g_sum;//消費積分總額
                            var c_sum = data.c_sum;//串串積分總額
                            var discount_sum = data.discount_sum;//使用券總額
                            var spend_sum = data.spend_sum;//現金/信用卡
                            var usage_fee_sum = data.usage_fee_sum;//支付金額

                            $('#clock1').html(sd1);
                            $('#clock2').html(sd2);
                            $('#sell_sum').html(number_format(sell_sum));
                            $('#g_sum').html(number_format(g_sum));
                            $('#c_sum').html(number_format(c_sum));
                            $('#discount_sum').html(number_format(discount_sum));
                            $('#spend_sum').html(number_format(spend_sum));
                            $('#usage_fee_sum').html(number_format(usage_fee_sum));

                          }

                        })
                      }

                  })

                  function number_format(n) {
                      n += "";
                      var arr = n.split(".");
                      var re = /(\d{1,3})(?=(\d{3})+$)/g;
                      return arr[0].replace(re,"$1,") + (arr.length == 2 ? "."+arr[1] : "");
                  }
                </script>
          
              </table>
            
            <div class="search_bt" align="center">
              <div style="width: 88%" align="center"><span>銷售紀錄</span></div>
            </div>

              <table align="center" class="table" style="width: 88%;margin: 10px auto 0px auto;line-height: 45px;background-color: #fff;border-radius: 10px" >
                <tr style="background-color: #903bff;color: #fff ">
                  <th style="border-radius: 10px 0px 0px 0px">日期</th>
                  <th>銷售金額</th>
                  <th>消費積分</th>
                  <th>串串積分</th>
                  <th>抵用券</th>
                  <th>現金/信用卡</th>
                  <th style="border-radius: 0px 10px 0px 0px">支付金額</th>
                </tr>
                <tr class="search_tr" style="background-color: #f5f5f5">
                  <td style="border-radius: 0px 0px 0px 10px"><span id="clock1"></span>~<span id="clock2"></span></td>
                  <td><span id="sell_sum"><?php echo number_format($sell_sum);?></span></td>
                  <td><span id="g_sum"><?php echo number_format($g_sum);?></span></td>
                  <td><span id="c_sum"><?php echo number_format($c_sum);?></span></td>
                  <td><span id="discount_sum"><?php echo number_format($discount_sum);?></span></td>
                  <td><span id="spend_sum"><?php echo number_format($spend_sum);?></span></td>
                  <td style="border-radius: 0px 0px 10px 0px"><span id="usage_fee_sum"><?php echo number_format($usage_fee_sum);?></span></td>
                </tr>
              </table>
            </div>

<?php

}else{

?>
<?php 
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

if($_GET['store'] != "" || $_GET['industry'] != "") { //商家
  $store=$_GET['store'];
  $industry=$_GET['industry'];
  mysql_select_db($database_lp, $lp);
  $query_str = "SELECT * FROM lf_user WHERE level ='boss' && st_name like '%$store%' && industry like '%$industry%' ORDER BY contract ASC";
  $Restr = mysql_query($query_str, $lp) or die(mysql_error());
  $row_str = mysql_fetch_assoc($Restr);
  $row_num = mysql_num_rows($Restr);
} else {
  mysql_select_db($database_lp, $lp); //無條件
  $query_str = "SELECT * FROM lf_user WHERE level ='boss'  ORDER BY contract ASC";
  $Restr = mysql_query($query_str, $lp) or die(mysql_error());
  $row_str = mysql_fetch_assoc($Restr);
  $row_num = mysql_num_rows($Restr);
}
//echo $row_num;

$nick = $row_str['s_nick'];

require_once("lifepay_user3.php");
?>


<script>

$(document).ready(function() {
  $('#menu a').click(function () {
    $('#wrapper').scrollTo($(this).attr('href'), 1000);
    //alert(this);
    return false;
  });  
});

function check(){
  document.getElementById('form3').submit();
}


</script>

</head>

<body >

<div class="management_desk">
  <div id="wrapper">
  <ul id="mask">
    <li id="section0" class="box">
      <div class="contant">
        <h3>商店資訊</h3> 
        <div class="store_block">
          <form id="form3" action="manage_inquire_store.php" method="get">
            <div class="search_block" align="center" style="display: inline-block;text-align: left;">
              <div align="left" >
              <ul class="search_bar">
                
                <li> <input type="text" name="store" placeholder="輸入店家名稱"  value="<?php echo $_GET['store'];?>" style="height: 40px;-webkit-appearance:none;line-height: 40px;width: 80%;margin-left: 0px"></li>
               
                <li> <select name="industry" id="industry" placeholder="產業 : " style="width: 200px;text-align: left;height: 40px;line-height: 40px;border: 2px solid #8e78de">
                        <option value="" style="color:#999999"><span style="color:#999999">請選擇產業</span></option>
                        <option value="餐飲業">餐飲業</option>
                        <option value="服飾業">服飾業</option>
                        <option value="資訊業">資訊業</option>
                        <option value="飯店業">飯店業</option>
                        <option value="零售業">零售業</option>
                        <option value="其他">其他</option>
                        </select></li>
                <li><button type="button" style="margin-left: 8px;line-height: 35px;background-color:#8e78de;border-radius: 6px;color: #fff;border: 0px;width: 80px;font-size: 17px " class="date_but" onClick="check()">查詢</button></li>
              </ul>
              </div>
            </div>  
            <div align="left" >
              
              <?php if($row_num != 0){
                do{?>
                <div class="store_detail">
                  <ul style="float: left;margin-right: 15px">
                    <li style="color: #8a8a8a;font-size: 12px;font-weight: 400">商店名稱</li>
                    <li><?php echo $row_str['st_name'];?></li>
                    <li style="color: #8a8a8a;font-size: 12px;font-weight: 400">簽約時間</li>
                    <li><?php echo $row_str['contract'];?></li>
                  </ul>
                  <ul style="float: left;">
                    <li style="color: #8a8a8a;font-size: 12px;font-weight: 400">帳號</li>
                    <li><?php echo $row_str['accont'];?></li>
                    <li style="color: #8a8a8a;font-size: 12px;font-weight: 400">產業別</li>
                    <li><?php echo $row_str['industry'];?></li>
                  </ul>
                  <ul style="float: right;margin-right: 8px" id="menu">
                    <li style="border-left: 1px solid #fff;padding-left: 5px">
                       <a href="#section2" data-value=<?php echo $row_str['st_name'];?>><img src="img/next-01.png" width="22px" alt=""></a>
                    </li>
                  </ul>
                 
                
              </div>
                <?php 
                  }while($row_str = mysql_fetch_assoc($Restr));

                }?>

                 <script>
                  $("#menu a").click(function(){
                      var data_value = $(this).attr('data-value');
                      $.ajax({
                        type: "POST",
                        url: "",
                        dataType: "html",
                        data: {
                          data_value:data_value
                        },
                        success: function(data) {
                          //alert(data);
                          $('#section2_in').html(data);
                        }

                      })
                  })
                </script>

              </div>
            </form>
        </div>
      </div>
    </li>

    <!--section2-->
    <li id="section2" class="box">
      <div class="contant">
        <h3>商店詳細資訊</h3>
          <div id="section2_in" class="store_block">
          </div>
      </div>
    </li>
  </ul>
  </div>
</div>

</body>
</html>
<?php 
}?>