<?php require_once('Connections/lp.php');mysql_query("set names utf8");require_once('Connections/sc.php');mysql_query("set names utf8");?>
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

//版型
require_once('lifepay_user.php');

//新開通店家
mysql_select_db($database_lp, $lp);
$aa = "SELECT * FROM lf_user WHERE level ='boss' ORDER BY user_id desc ";
$con_aa = mysql_query($aa, $lp) or die(mysql_error());

//新加入用戶
mysql_select_db($database_sc, $sc);
$bb = "SELECT * FROM memberdata ORDER BY m_id desc ";
$con_bb = mysql_query($bb, $sc) or die(mysql_error());

//取得交易資料
mysql_select_db($database_lp, $lp);
$query_Recoc = "SELECT * FROM Invoice group by nick";
$Recoc = mysql_query($query_Recoc, $lp) or die(mysql_error());
$row_recoc = mysql_fetch_assoc($Recoc);
$total_recoc = mysql_num_rows($Recoc);


?>
<!DOCTYPE html>
<html>
    <div>
      <div class="main_feature">
        <a href="signup.php" class="visible-xs"><img src="img/open_mobile.png" alt=""></a>
        <a href="signup.php" class="hidden-xs"><img src="img/open.png" alt=""></a>
      </div>  
    </div>
    <div class="index_desk"> 
      <div class="latest_info">
        <h3>新開通店家</h3>
        <div class="last_list" align="center">
          <ul>
            <?php 
            for($i=0;$i<7;$i++){

              $row_aa = mysql_fetch_array($con_aa);

              $host = $row_aa['host'];//負責人
              $m_phone = $row_aa['m_phone'];//電話
              $fee = $row_aa['usage_fee'];//回饋%數
              $accont = $row_aa['accont'];//帳號
              $password = $row_aa['password'];//密碼
              $password2 = $row_aa['password2'];//二級密碼
              $email = $row_aa['email'];//信箱
              $time1 = $row_aa['time1'];//營業時間1
              $time2 = $row_aa['time2'];//營業時間2
              $st_dis = $row_aa['st_dis'];//折扣券%數
              $s_name = $row_aa['st_name'];//商店名
              $ind = $row_aa['industry'];//產業別
              $con = $row_aa['contract'];//簽約日期


              $number = $row_aa['number'];
              //取得商店交易資料
              mysql_select_db($database_lp, $lp);
              $query_Recocs = "SELECT * FROM complete WHERE s_number = '$number' ";
              $Recocs = mysql_query($query_Recocs, $lp) or die(mysql_error());
              for($cs=0;$cs<mysql_numrows($Recocs);$cs++){
                $row_recocs = mysql_fetch_assoc($Recocs);

                //銷售金額
                $cs_total = $row_recocs['total_cost'];
                $cs_total_cost = $cs_total_cost + $cs_total;
                //抵用券
                $cs_dis = $row_recocs['discount'];
                $cs_discount = $cs_discount + $cs_dis;
                //串串
                $cs_c = $row_recocs['c'];
                $cs_c_total = $cs_c_total + $cs_c;
                //消費
                $cs_g = $row_recocs['g'];
                $cs_g_total = $cs_g_total + $cs_g;
                //現金
                $cs_sp = $row_recocs['spend'];
                $cs_spend = $cs_spend + $cs_sp;
                //回饋
                $cs_fee = $row_recocs['fee_amount'];
                $cs_fee_amount = $cs_fee_amount + $cs_fee;
              }
              if(mysql_numrows($Recocs)==""){
                $cs_total_cost = 0;
                $cs_discount = 0;
                $cs_c_total = 0;
                $cs_g_total = 0;
                $cs_spend = 0;
                $cs_fee_amount = 0;
              }


              $arr =array("host"=>$host,"m_phone"=>$m_phone,"fee"=>$fee,"accont"=>$accont,"password"=>$password,"password2"=>$password2,"email"=>$email,"time1"=>$time1,"time2"=>$time2,"st_dis"=>$st_dis,"s_name"=>$s_name,"ind"=>$ind,"con"=>$con,"cs_total_cost"=>$cs_total_cost,"cs_discount"=>$cs_discount,"cs_c_total"=>$cs_c_total,"cs_g_total"=>$cs_g_total,"cs_spend"=>$cs_spend,"cs_fee_amount"=>$cs_fee_amount);
              $arr_json = json_encode($arr); //陣列轉josn

              ?>
              <li class="store_list">
                <a class="opener<?php echo $i;?>" data-value='<?php echo $arr_json;?>' style="color: black;">
                  <ul class="user_detail " style="float: right">
                      <li style="text-align: right;"><span style="font-weight: 800;color: #333;">...</span></li>
                      
                      <li><span style=""><?php echo $fee."%";?></span></li>
                  </ul>
             
                  <ul class="user_detail" style="float: left;overflow: auto;width: 90px;height: 122px;margin-left: 3px;margin-top: 3px">
                    <li> <span style=""><?php echo $s_name;?></span></li>
                    <li><span style=""><?php echo $ind;?></span></li>
                    <li><span style="">簽約日</span></li>
                    <li><span style=""><?php echo $con;?></span></li>
                  </ul>
                </a>
              </li>
              <script>
              $( function() {
                $( ".store<?php echo $i;?>" ).dialog({
                  show: {
                      effect: "fade",
                  },
                  autoOpen: false, //預設不顯示
                  draggable: false, //設定拖拉
                  resizable: false, //設定縮放
                  title:"商家明細",
                  modal: true, //灰色透明背景限制只能按dialog
                  width:400,
                  responsive: true
                });

                $( ".opener<?php echo $i;?>" ).on( "click", function() {
                  //alert($(this).attr('data-value'));
                  var s = $(this).attr('data-value');
                  var val = JSON.parse(s);
                  var host = val.host;
                  var m_phone = val.m_phone;
                  var fee = val.fee;
                  var accont = val.accont;
                  var password = val.password;
                  var password2 = val.password2;
                  var email = val.email;
                  var time1 = val.time1;
                  var time2 = val.time2;
                  var st_dis = val.st_dis;
                  var s_name = val.s_name;
                  var ind = val.ind;
                  var con = val.con;

                  //商店營業紀錄
                  var cs_total_cost = val.cs_total_cost;
                  var cs_discount = val.cs_discount;
                  var cs_c_total = val.cs_c_total;
                  var cs_g_total = val.cs_g_total;
                  var cs_spend = val.cs_spend;
                  var cs_fee_amount = val.cs_fee_amount;

                  $("#host<?php echo $i;?>").html(host);
                  $("#m_phone<?php echo $i;?>").html(m_phone);
                  $("#fee<?php echo $i;?>").html(fee);
                  $("#accont<?php echo $i;?>").html(accont);
                  $("#password<?php echo $i;?>").html(password);
                  $("#password2<?php echo $i;?>").html(password2);
                  $("#email<?php echo $i;?>").html(email);
                  $("#time1<?php echo $i;?>").html(time1);
                  $("#time2<?php echo $i;?>").html(time2);
                  $("#st_dis<?php echo $i;?>").html(st_dis);
                  $("#s_name<?php echo $i;?>").html(s_name);
                  $("#ind<?php echo $i;?>").html(ind);
                  $("#con<?php echo $i;?>").html(con);

                  //商店營業紀錄
                  $("#cs_total_cost<?php echo $i;?>").html(cs_total_cost);
                  $("#cs_discount<?php echo $i;?>").html(cs_discount);
                  $("#cs_c_total<?php echo $i;?>").html(cs_c_total);
                  $("#cs_g_total<?php echo $i;?>").html(cs_g_total);
                  $("#cs_spend<?php echo $i;?>").html(cs_spend);
                  $("#cs_fee_amount<?php echo $i;?>").html(cs_fee_amount);

                  $( ".store<?php echo $i;?>" ).dialog( "open" );
                });
              });
              </script>
              <div class="store<?php echo $i;?>" style="display: none">
              <ul>
                <li style="float: left;line-height: 45px" ><span id="s_name<?php echo $i;?>"></span></li>
                <li><div align="right">產業別<br><span style="font-weight: 300" id="ind<?php echo $i;?>"></span></div></li>
              </ul>
              <ul>
                <li style="float: left;">負責人<span id="host<?php echo $i;?>" style="font-weight: 300"></span></li>
                <li><div align="right">電話<span id="m_phone<?php echo $i;?>" style="font-weight: 300"></span></div></li>
              </ul>
            <hr style="margin-bottom: 10px">
                <ul>
                  <li class="col-lg-4 col-md-4 col-xs-4" style="padding-left:0px "><div style="display: inline-block;">帳號<br><span style="font-weight: 300" id="accont<?php echo $i;?>"></span></div></li>
                  <li class="col-lg-4 col-md-4 col-xs-4" style="padding-left:0px "><div >密碼<br><span style="font-weight: 300" id="password<?php echo $i;?>"></span></div></li>
                  <li class="col-lg-4 col-md-4 col-xs-4" style="padding-left:0px "><div>二級密碼<br><span style="font-weight: 300" id="password2<?php echo $i;?>"></span></div></li>
                </ul>
                <ul>
                  <li class="col-lg-6 col-md-6 col-xs-6" style="padding-left:0px "><div>信箱<br><span id="email<?php echo $i;?>"></span></div></li>
                  <li class="col-lg-6 col-md-6 col-xs-6" style="padding-left:0px "><div>營業時間<br>
                  <span id="time1<?php echo $i;?>"></span>
                  <span id="time2<?php echo $i;?>"></span>
                </div></li>
                </ul>
                <ul>
                  <li class="col-lg-6 col-md-6 col-xs-6" style="padding-left:0px "><div>簽約時間<br><span id="con<?php echo $i;?>"></span></div></li>
                  <li class="col-lg-6 col-md-6 col-xs-6" style="padding-left:0px "><div>回饋%數<span style="font-weight: 300" id="fee<?php echo $i;?>"></span></div><div>優惠券預設%數<span id="st_dis<?php echo $i;?>"></span></div></li>
                </ul>
                <!--商店營業紀錄-->
                <ul class="store_fancy col-lg-12 col-md-12 col-xs-12">
                  <li class="col-lg-6 col-md-6 col-xs-6" style="padding: 5px">
                    <ul class="store_fancy_detail">
                      <li><span>銷售金額</span></li>
                      <li><span>抵用券</span></li>
                      <li><span>串串積分</span></li>
                    </ul>
                    <ul class="store_fancy_detail" >
                      <li><span  id="cs_total_cost<?php echo $i;?>"></span></li>
                      <li><span   id="cs_discount<?php echo $i;?>"></span></li>
                      <li><span   id="cs_c_total<?php echo $i;?>">></span></li>
                    </ul>
                  </li>
                  <li style="float: right;padding:5px"  class="col-lg-6 col-md-6 col-xs-6">
                    <ul class="store_fancy_detail">
                      <li><span>消費積分</span></li>
                      <li><span>現金/信用卡</span></li>
                      <li><span>回饋金額</span></li>
                    </ul>
                    <ul class="store_fancy_detail" >
                      <li><span   id="cs_g_total<?php echo $i;?>"></span></li>
                      <li><span id="cs_spend<?php echo $i;?>"></span></li>
                      <li><span  id="cs_fee_amount<?php echo $i;?>"></span></li>
                    </ul>
                  </li>
                </ul>
      

              </div>
            <?php 
            } ?>
            <a href="signup.php"><li class="store_list hidden-xs" style="background-color: #e3e3e3;color: #b5b5b5;font-weight: 700;font-size: 60px;line-height: 110px">+</li></a>
            
          </ul>
        </div>
        <!--11-->

        <!--1-->
      </div>
      <div class="latest_info account_info">
        <h3>帳款收支</h3>
          
          <ul >
            <li style="display: inline-block;width: 100px;">申請店家</li>
            <li style="display: inline-block;width: 100px;">申請金額</li>  
            <li style="display: inline-block;width: 100px;">消費積分</li>
            <li style="display: inline-block;width: 100px;">串串積分</li>
            <li style="display: inline-block;width: 100px;">現金/刷卡</li>
            <li style="display: inline-block;width: 100px;">回饋額</li>
            <li style="display: inline-block;width: 100px;">確認撥款</li>
          </ul>
          <?php 
          if($total_recoc != 0){

              do{
                $nick = $row_recoc['nick'];
                $s_number = $row_recoc['number'];

                //取得商店交易資料
                mysql_select_db($database_lp, $lp);
                $query_ss = "SELECT * FROM Invoice WHERE number = '$s_number' and confirm = '0'";
                $Recoc_ss = mysql_query($query_ss, $lp) or die(mysql_error());
                $row_ss = mysql_fetch_assoc($Recoc_ss);
                $total_ss = mysql_num_rows($Recoc_ss);

                if($total_ss != 0){ //沒有列數就表示沒有新申請的資料

                    $usage_fee = 0; //換商店就清空
                    $c_total = 0;
                    $g_total = 0;
                    $q_total = 0;
                    $spend_total = 0;

                    do{
                        //申請金額
                        $fee = $row_ss['usage_fee'];
                        $usage_fee = $usage_fee + $fee;
                        //串串積分
                        $c = $row_ss['c'];
                        $c_total = $c_total + $c;
                        //消費積分
                        $g = $row_ss['g'];
                        $g_total = $g_total + $g;
                        //回饋金額
                        $q = $row_ss['q'];
                        $q_total = $q_total + $q;
                        //現金/刷卡
                        $spend = $row_ss['spend'];
                        $spend_total = $spend_total + $spend;

                    }while($row_ss = mysql_fetch_assoc($Recoc_ss));
                    
                }else{
                    $usage_fee = 0;
                    $c_total = 0;
                    $g_total = 0;
                    $q_total = 0;
                    $spend_total = 0;
                }

                    $arr =array("nick"=>$nick,"s_number"=>$s_number,"usage_fee"=>$usage_fee,"c_total"=>$c_total,"g_total"=>$g_total,"q_total"=>$q_total,"spend_total"=>$spend_total);
                    $arr_json = json_encode($arr); //陣列轉josn
                ?>
              <ul class="acc_list">
                <li class="store_nick" style="display: inline-block;width: 100px;white-space: nowrap;overflow: hidden;"><a href="manage_inquire_store.php?store=<?php echo $nick;?>"><?php echo $nick;?></a></li>
                <li style="display: inline-block;width: 100px;"><?php echo $usage_fee;?></li>
                <li style="display: inline-block;width: 100px;"><?php echo $g_total;?></li>
                <li style="display: inline-block;width: 100px;"><?php echo $c_total;?></li>
                <li style="display: inline-block;width: 100px;"><?php echo $spend_total;?></li>
                <li style="display: inline-block;width: 100px;"><?php echo $q_total;?></li>
                <script>
                  $(".store_nick").on("mouseenter mouseleave", function (event) { //挷定滑鼠進入及離開事件
                    if (event.type == "mouseenter") {
                      $(this).css({"overflow": "auto"}); //滑鼠進入
                    } else {
                      $(this).scrollTop(0).css({"overflow": "hidden"}); //滑鼠離開
                    }
                  });
                </script>
                <?php
                if($total_ss != 0){
                  echo "<li style='display: inline-block;width: 100px;'><button style='background:#6d4ede;color:#fff;border:0px;border-radius:5px;height:30px;vertical-align: middle;line-height: 27px' id='dialog_open' class='dialog_open$total_recoc' value='$arr_json'>確認</button></li>";
                }else{
                  echo "<li style='display: inline-block;width: 100px;'><button style='background:#c6bfde;color:#333;;border:0px;border-radius:5px;height:30px;vertical-align: middle;line-height: 27px' id='dialog_opp' class='dialog_opp$total_recoc' value='$arr_json'>查看</button></li>";
                }
                ?>
              </ul>

              <script>
                $(function()
                {

                    $(".dialog_open<?php echo $total_recoc;?>").click(function(event){
                        var c = $(".dialog_open<?php echo $total_recoc;?>").val();
                        var val = JSON.parse(c);
                        var ss_number = val.s_number;//接到ss_number傳送到aa.php產生json

                            $.ajax({
                            type: "POST",
                            url: "get_store_list_aa.php",
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
                        });
                    });

                    $(".dialog<?php echo $total_recoc;?>").dialog({
                     show: {
                      effect: "fade",
                      },
                      autoOpen: false, //預設不顯示
                      draggable: false, //設定拖拉
                      resizable: false, //設定縮放
                      title:"撥款資料",
                      modal: true, //灰色透明背景限制只能按dialog
                      width:800,
                      responsive: true
                    
                    });


                    //已確認
                    $( ".dialog_opp<?php echo $total_recoc;?>" ).on( "click", function() {
                      var c = $(".dialog_opp<?php echo $total_recoc;?>").val();
                        var val = JSON.parse(c);
                        var ss_number = val.s_number;//接到ss_number傳送到aa.php產生json

                            $.ajax({
                            type: "POST",
                            url: "get_store_list_cc.php",
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
                                  $(".checkTable<?php echo $total_recoc;?>").dataTable(opt);
                                  
                                }
                            });


                        $( ".dialog_aa<?php echo $total_recoc;?>" ).dialog( "open");
                        $('.ui-dialog-titlebar-close').click(function(){
                          location.reload();
                        });
                        //按下dialog的確認後關閉
                        $("#opt<?php echo $total_recoc;?>").click(function(event){
                          $(".dialog_aa<?php echo $total_recoc;?>").dialog( "close" );
                          location.reload();
                        });
                    });

                    $(".dialog_aa<?php echo $total_recoc;?>").dialog({
                     show: {
                      effect: "fade",
                      },
                      autoOpen: false, //預設不顯示
                      draggable: false, //設定拖拉
                      resizable: false, //設定縮放
                      title:"撥款資料",
                      modal: true, //灰色透明背景限制只能按dialog
                      width:800,
                      responsive: true
                    
                    });



                })



                //送表單
                function kk(value){

                    var bt = "#bt"+value;
                    var oo = $(bt).val();
                    var val = JSON.parse(oo);
                    var s_nick = val.nick;
                    var s_number = val.s_number;
                    var s_usage_fee = val.usage_fee;
                    var s_c_total = val.c_total;
                    var s_g_total = val.g_total;
                    var s_q_total = val.q_total;
                    var s_spend_total = val.spend_total;

                    $(document).ready(function() {
                          //ajax 送表單
                          $.ajax({
                              type: "POST",
                              url: "allotment.php",
                              dataType: "text",
                              data: {
                                    s_nick:s_nick,
                                    s_number:s_number,
                                    s_usage_fee:s_usage_fee,
                                    s_c_total:s_c_total,
                                    s_g_total:s_g_total,
                                    s_q_total:s_q_total,
                                    s_spend_total:s_spend_total
                              },
                              success: function(data) {

                                    location.reload();
                              }

                          })
                    })

                }

              </script>

              <div class="dialog<?php echo $total_recoc;?>" style="display: none;text-align:center;">
                    <span><?php echo $nick?></span>
                    <hr>
                    <table class="custTable<?php echo $total_recoc;?>" width="100%">
                    </table>
                    <div>
                      <button id="bt<?php echo $total_recoc;?>" value='<?php echo $arr_json;?>' onClick="kk(<?php echo $total_recoc;?>)">提交</button>
                    </div>
              </div>

              <div class="dialog_aa<?php echo $total_recoc;?>" style="display: none;text-align:center;">
                    <span><?php echo $nick?></span>
                    <hr>
                    <table class="checkTable<?php echo $total_recoc;?>" width="100%">
                    </table>
                    <div>
                      <button id="opt<?php echo $total_recoc;?>">確認</button>
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

      
    </div>
    <div class="index_desk user_info" >
      <div class="latest_info latest_user">
        <h3>新加入用戶</h3>
        <div class="last_list" align="center">
          <ul>
            <?php 
            $total_consum = 0; 
            $today = date("Y-m-d");
            for($k=0;$k<8;$k++){

              $row_bb = mysql_fetch_array($con_bb);

              $u_nick = $row_bb['m_nick'];//暱稱
              $fname = $row_bb['fname'];//推薦人
              $u_accont = $row_bb['accont'];//帳號
              $u_password = $row_bb['password'];//密碼
              $u_password2 = $row_bb['password2'];//二級密碼
              $birthday = $row_bb['m_birthday'];//年紀
              $age = $today - $birthday;
              if($age == date("Y")){
                $age = "";
              }
              $sex = '';//性別
              if($row_bb['m_sex'] == M){
                $sex = '男';
              }else if($row_bb['m_sex'] == F){
                $sex = '女';
              }
              $u_email = $row_bb['m_email'];//信箱

              $p_user = $row_bb['m_username'];
              //個人消費金額
              mysql_select_db($database_lp, $lp);
              $query_str = "SELECT * FROM complete WHERE p_user ='$p_user' ORDER BY id DESC";
              $Restr = mysql_query($query_str, $lp) or die(mysql_error());
              $row_str = mysql_fetch_assoc($Restr);
              //消費金額
              $total_consum = $row_str['total_cost'];
              $consum_sum = $consum_sum + $total_consum;
              //消費積分
              $u_g = $row_str['g'];
              $u_totalg = $u_totalg + $u_g;
              //串串積分
              $u_c = $row_str['c'];
              $u_totalc = $u_totalc + $u_c;
              

              $arr_u =array("u_nick"=>$u_nick,"fname"=>$fname,"u_accont"=>$u_accont,"u_password"=>$u_password,"u_password2"=>$u_password2,"sex"=>$sex,"u_email"=>$u_email,"age"=>$age,"consum_sum"=>$consum_sum,"u_totalg"=>$u_totalg,"u_totalc"=>$u_totalc);
              $arru_json = json_encode($arr_u); //陣列轉josn
              
              ?>
              <a class="open_u<?php echo $k;?>" data-value='<?php echo $arru_json;?>'>
                <li class="store_list user_list">
                  <ul class="user_detail" style="float: right">
                      <li style="text-align: right;"><span style="font-weight: 800;color: #333;">...</span></li>
                      <li><span style=""><?php echo $sex;?></span></li>
                  </ul>
                  <ul class="user_detail" style="float: left;overflow: hidden;width: 95px;height: 122px;margin-left: 3px;margin-top: 3px">
                    <li> <span style=""><?php echo $u_nick;?></span></li>
                    <li><span style="">消費金額</span></li>
                    <li><span style=""><?php echo $consum_sum;?></span></li>
                    <li><span style=""><?php echo $u_email;?></span></li>
                  </ul>
                  <script>
                    $(".user_detail").on("mouseenter mouseleave", function (event) { //挷定滑鼠進入及離開事件
                      if (event.type == "mouseenter") {
                        $(this).css({"overflow": "auto"}); //滑鼠進入
                      } else {
                        $(this).scrollTop(0).css({"overflow": "hidden"}); //滑鼠離開
                      }
                    });
                  </script>
                </li>
              </a>
              <script>
              $( function() {
                $( ".user<?php echo $k;?>" ).dialog({
                  show: {
                      effect: "fade",
                  },
                  autoOpen: false, //預設不顯示
                  draggable: false, //設定拖拉
                  resizable: false, //設定縮放
                  title:"個人明細",
                  modal: true, //灰色透明背景限制只能按dialog
                  width:350,
                  responsive: true
                });

                $( ".open_u<?php echo $k;?>" ).on( "click", function() {
                  //alert($(this).attr('data-value'));
                  var s = $(this).attr('data-value');
                  var val = JSON.parse(s);
                  var u_nick = val.u_nick;
                  var fname = val.fname;
                  var u_accont = val.u_accont;
                  var u_password = val.u_password;
                  var u_password2 = val.password2;
                  var sex = val.sex;
                  var u_email = val.u_email;
                  var age = val.age;

                  //商店營業紀錄
                  var consum_sum = val.consum_sum;
                  var u_totalg = val.u_totalg;
                  var u_totalc = val.u_totalc;

                  $("#u_nick<?php echo $k;?>").html(u_nick);
                  $("#fname<?php echo $k;?>").html(fname);
                  $("#u_accont<?php echo $k;?>").html(u_accont);
                  $("#u_password<?php echo $k;?>").html(u_password);
                  $("#u_password2<?php echo $k;?>").html(u_password2);
                  $("#sex<?php echo $k;?>").html(sex);
                  $("#u_email<?php echo $k;?>").html(u_email);
                  $("#age<?php echo $k;?>").html(age);


                  //商店營業紀錄
                  $("#consum_sum<?php echo $k;?>").html(consum_sum);
                  $("#u_totalg<?php echo $k;?>").html(u_totalg);
                  $("#u_totalc<?php echo $k;?>").html(u_totalc);

                  $( ".user<?php echo $k;?>" ).dialog( "open" );
                });
              });
              </script>
              <div class="user<?php echo $k;?>" style="display: none;text-align:center;">
              <ul>
                <li style="float: left;" ><div align="left">用戶<br><span style="font-weight: 300" id="u_nick<?php echo $k;?>"></span></div></li>
                <li><div align="right">推薦人<br><span style="font-weight: 300"  id="fname<?php echo $k;?>"></span></div></li>
              </ul>
              
            <hr style="margin-bottom: 10px;margin-top: 10px">

              <ul style="text-align: left">
                  <li class="col-lg-4 col-md-4 col-xs-4" style="padding-left:0px "><div style="display: inline-block;">帳號<br><span style="font-weight: 300" id="u_accont<?php echo $k;?>"></span></div></li>
                  <li class="col-lg-4 col-md-4 col-xs-4" style="padding-left:0px "><div >密碼<br><span style="font-weight: 300" id="u_password<?php echo $k;?>"></span></div></li>
                  <li class="col-lg-4 col-md-4 col-xs-4" style="padding-left:0px "><div>二級密碼<br><span style="font-weight: 300" id="u_password2<?php echo $k;?>"></span></div></li>
                </ul>
                <ul style="text-align: left">
                  <li class="col-lg-4 col-md-4 col-xs-4" style="padding-left:0px "><div style="display: inline-block;">年齡<br><span style="font-weight: 300" id="age<?php echo $k;?>"></span></div></li>
                  <li class="col-lg-4 col-md-4 col-xs-4" style="padding-left:0px "><div >性別<br><span style="font-weight: 300" id="sex<?php echo $k;?>"></span></div></li>
                  <li class="col-lg-4 col-md-4 col-xs-4" style="padding-left:0px "></li>
                </ul>       
                
                <ul style="text-align: left">
                  <li class="col-lg-12 col-md-12 col-xs-12" style="padding-left:0px "><div>信箱<br><span id="u_email<?php echo $k;?>"></span></div></li>
                </ul>


                <!--商店營業紀錄-->
                <ul class="store_fancy col-lg-12 col-md-12 col-xs-12">
                  <li class="col-lg-6 col-md-6 col-xs-6" style="padding: 5px">
                    <ul class="store_fancy_detail">
                      <li><span>消費總金額</span></li>
                      <li><span>消費積分</span></li>
                      <li><span>串串積分</span></li>
                    </ul>
                    <ul class="store_fancy_detail" style="margin-left: 10px">
                      <li><span  id="consum_sum<?php echo $k;?>"></span></li>
                      <li><span  id="u_totalg<?php echo $k;?>"></span></li>
                      <li><span  id="u_totalc<?php echo $k;?>"></span></li>
                    </ul>
                  </li>
                </ul>
              </div>
            <?php 
            } ?>

            
          </ul>

        </div>
      </div>
    </div>
</body>

</html>
