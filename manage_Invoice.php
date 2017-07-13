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
    $query_Recoc = "SELECT * FROM Invoice, lf_user WHERE lf_user.st_name = Invoice.nick && Invoice.nick like '%$store%' && lf_user.industry like '%$industry%' group by nick";
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


if($_GET['store'] != "" || $_GET['industry'] != "") { //商家
    $store=$_GET['store'];
    $industry=$_GET['industry'];

    mysql_select_db($database_lp, $lp);
    $select_lfuser = "SELECT * FROM lf_user WHERE level ='boss' && st_name like '%$store%' && industry like '%$industry%'";
    $query_lfuser = mysql_query($select_lfuser, $lp) or die(mysql_error());
    $row_lfuser = mysql_fetch_assoc($query_lfuser);
    $nick = $row_lfuser['st_name'];

    //有搜尋條件取得交易資料
    mysql_select_db($database_lp, $lp);
    $select_invoice = "SELECT * FROM Invoice, lf_user WHERE lf_user.st_name = Invoice.nick && Invoice.nick like '%$store%' && lf_user.industry like '%$industry%' group by nick";
    $query_invoice = mysql_query($select_invoice, $lp) or die(mysql_error());
    $row_invoice = mysql_fetch_assoc($query_invoice);
    $total_invoice = mysql_num_rows($query_invoice);
} else {
    //沒有搜尋條件取得交易資料
    mysql_select_db($database_lp, $lp);
    $select_invoice = "SELECT * FROM Invoice group by nick";
    $query_invoice = mysql_query($select_invoice, $lp) or die(mysql_error());
    $row_invoice = mysql_fetch_assoc($query_invoice);
    $total_invoice = mysql_num_rows($query_invoice);
}

require_once('lifepay_user.php');
?>
<script>
    $(document).ready(function() {
        $('.menu a').click(function () {
            $('#wrapper').scrollTo($(this).attr('href'), 1000);
            //alert(this);
            return false;
        });
        $("#leftNav ul a .active").removeClass('active');
        $("#leftNav #a2").addClass('active');
        $("#bottomNav #a2").addClass('active');
    });

</script>
</head>
<body>
    <div class="management_desk"> 
        <div id="wrapper">
            <ul id="mask">
                <li id="section1" class="box">
             
                    <div class="contant">
                    <h3>帳款收支</h3>
                        
                    <div class="store_block" >
                        <ul style="float: right;margin-right: 8px;position: absolute;top: 25px;right: 25px" class="menu">
                            <li style="padding-left: 5px">
                                <a href="#section2"><span style="font-size: 22px;color: #8E92DE;vertical-align: middle;margin-right: 6px" class="hidden-xs">歷史紀錄</span><img src="img/next-01.png" width="22px" alt="" ></a>
                            </li>
                        </ul>

                        <div style="line-height: 40px">
                            <button class="dialog_G" style="margin-left: 8px;line-height: 35px;background-color:#8e78de;border-radius: 6px;color: #fff;border: 0px;min-width: 100px;font-size: 15px ">查消費積分</button></a><!--manage_totalG-->
                            <button class="dialog_C" style="margin-left: 8px;line-height: 35px;background-color:#8e78de;border-radius: 6px;color: #fff;border: 0px;min-width: 100px;font-size: 15px ">查串串積分</button></a><!--manage_totalC-->
                            <button class="dialog_R" style="margin-left: 8px;line-height: 35px;background-color:#8e78de;border-radius: 6px;color: #fff;border: 0px;min-width: 100px;font-size: 15px ">查紅利積分</button></a><!--manage_totalR-->
                        </div>
                        <script>
                        $(function(){
                            //GGGGG
                            $(".dialog_G").click(function(event){
                                $.ajax({
                                    type: "POST",
                                    url: "get_total_g.php",
                                    data: "",
                                    dataType: "json",
                                    success: function(resultData) {
                                        var opt={"oLanguage":{"sUrl":"dataTables.zh-tw.txt"},//讀取中文模板
                                                "bJQueryUI":true,//套用所選之布景
                                                "bProcessing":true,//如需要一些時間處理時, 表格上會顯示"處理中 ..."
                                                "scrollY": 200,//卷軸
                                                "scrollCollapse": true,
                                                "destroy":true,
                                                "footerCallback": function ( row, data, start, end, display ) {
                                                    var api = this.api(), data;
                                                    // Remove the formatting to get integer data for summation
                                                    var intVal = function ( i ) {
                                                        return typeof i === 'string' ?
                                                            i.replace(/[\$,]/g, '')*1 :
                                                            typeof i === 'number' ?
                                                                i : 0;
                                                    };
                                                    // Total over all pages
                                                    sum = api
                                                        .column( 2 )
                                                        .data()
                                                        .reduce( function (a, b) {
                                                            return intVal(a) + intVal(b);
                                                        }, 0 );
                                                    // Update footer
                                                    $( api.column( 3 ).footer() ).html(
                                                        '$'+sum
                                                    );
                                                },
                                                "aoColumns":[{"sTitle":"日期"},
                                                    {"sTitle":"商店名稱"},
                                                    {"sTitle":"消費積分入帳"},
                                                    {"sTitle":"擁有積分"},
                                                    {"sTitle":"狀態"}],
                                                "aaData": resultData
                                                };
                                        $(".Gopen").dataTable(opt);
                                    }
                                });


                                $( ".dialog_Gopen" ).dialog( "open");
                                //按下dialog的確認後關閉
                                $("#Gclose").click(function(event){
                                  $(".dialog_Gopen").dialog( "close" );
                                  //location.reload();
                                });
                            });

                            $(".dialog_Gopen").dialog({
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

                            //CCCC
                            $(".dialog_C").click(function(event){
                                $.ajax({
                                    type: "POST",
                                    url: "get_total_c.php",
                                    data: "",
                                    dataType: "json",
                                    success: function(resultData) {
                                        var opt={"oLanguage":{"sUrl":"dataTables.zh-tw.txt"},//讀取中文模板
                                                "bJQueryUI":true,//套用所選之布景
                                                "bProcessing":true,//如需要一些時間處理時, 表格上會顯示"處理中 ..."
                                                "scrollY": 200,//卷軸
                                                "scrollCollapse": true,
                                                "destroy":true,
                                                "footerCallback": function ( row, data, start, end, display ) {
                                                    var api = this.api(), data;
                                                    // Remove the formatting to get integer data for summation
                                                    var intVal = function ( i ) {
                                                        return typeof i === 'string' ?
                                                            i.replace(/[\$,]/g, '')*1 :
                                                            typeof i === 'number' ?
                                                                i : 0;
                                                    };
                                                    // Total over all pages
                                                    sum = api
                                                        .column( 2 )
                                                        .data()
                                                        .reduce( function (a, b) {
                                                            return intVal(a) + intVal(b);
                                                        }, 0 );

                                                    // Update footer
                                                    $( api.column( 3 ).footer() ).html(
                                                        '$'+sum
                                                    );
                                                },
                                                "aoColumns":[{"sTitle":"日期"},
                                                    {"sTitle":"商店名稱"},
                                                    {"sTitle":"串串積分入帳"},
                                                    {"sTitle":"擁有積分"},
                                                    {"sTitle":"狀態"}],
                                                "aaData": resultData
                                                };
                                        $(".Copen").dataTable(opt);
                                    }
                                });


                                $( ".dialog_Copen" ).dialog( "open");
                                //按下dialog的確認後關閉
                                $("#Cclose").click(function(event){
                                  $(".dialog_Copen").dialog( "close" );
                                  //location.reload();
                                });
                            });

                            $(".dialog_Copen").dialog({
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

                            //RRRRR
                            $(".dialog_R").click(function(event){
                                $.ajax({
                                    type: "POST",
                                    url: "get_total_r.php",
                                    data: "",
                                    dataType: "json",
                                    success: function(resultData) {
                                        var opt={"oLanguage":{"sUrl":"dataTables.zh-tw.txt"},//讀取中文模板
                                                "bJQueryUI":true,//套用所選之布景
                                                "bProcessing":true,//如需要一些時間處理時, 表格上會顯示"處理中 ..."
                                                "scrollY": 200,//卷軸
                                                "scrollCollapse": true,
                                                "destroy":true,
                                                "footerCallback": function ( row, data, start, end, display ) {
                                                    var api = this.api(), data;
                                                    // Remove the formatting to get integer data for summation
                                                    var intVal = function ( i ) {
                                                        return typeof i === 'string' ?
                                                            i.replace(/[\$,]/g, '')*1 :
                                                            typeof i === 'number' ?
                                                                i : 0;
                                                    };
                                                    // Total over all pages
                                                    sum = api
                                                        .column( 2 )
                                                        .data()
                                                        .reduce( function (a, b) {
                                                            return intVal(a) + intVal(b);
                                                        }, 0 );

                                                    // Update footer
                                                    $( api.column( 3 ).footer() ).html(
                                                        '$'+sum
                                                    );
                                                },
                                                "aoColumns":[{"sTitle":"日期"},
                                                    {"sTitle":"商店名稱"},
                                                    {"sTitle":"紅利積分入帳"},
                                                    {"sTitle":"擁有積分"},
                                                    {"sTitle":"狀態"}],
                                                "aaData": resultData
                                                };
                                        $(".Ropen").dataTable(opt);
                                    }
                                });


                                $( ".dialog_Ropen" ).dialog( "open");
                                //按下dialog的確認後關閉
                                $("#Rclose").click(function(event){
                                  $(".dialog_Ropen").dialog( "close" );
                                  //location.reload();
                                });
                            });

                            $(".dialog_Ropen").dialog({
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
                        </script>
                        <!--GGG-->
                        <div class="dialog_Gopen" style="display: none;text-align:center;">
                            <span><?php echo $nick?></span>
                            <hr>
                            <table class="Gopen" width="100%">
                                <tfoot>
                                    <tr>
                                    <th style="text-align:right">合計:</th>
                                    <th style="text-align:center;"></th>
                                    <th style="text-align:center;"></th>
                                    <th style="text-align:center;"></th>
                                    <th style="text-align:center;"></th>
                                    </tr>
                                </tfoot>
                            </table>
                            <div>
                                <button id="Gclose">退出</button>
                            </div>
                        </div>
                        <!--CCC-->
                        <div class="dialog_Copen" style="display: none;text-align:center;">
                            <span><?php echo $nick?></span>
                            <hr>
                            <table class="Copen" width="100%">
                                <tfoot>
                                    <tr>
                                    <th style="text-align:right">合計:</th>
                                    <th style="text-align:center;"></th>
                                    <th style="text-align:center;"></th>
                                    <th style="text-align:center;"></th>
                                    <th style="text-align:center;"></th>
                                    </tr>
                                </tfoot>
                            </table>
                            <div>
                                <button id="Cclose">退出</button>
                            </div>
                        </div>
                        <!--RRRR-->
                        <div class="dialog_Ropen" style="display: none;text-align:center;">
                            <span><?php echo $nick?></span>
                            <hr>
                            <table class="Ropen" width="100%">
                                <tfoot>
                                    <tr>
                                    <th style="text-align:right">合計:</th>
                                    <th style="text-align:center;"></th>
                                    <th style="text-align:center;"></th>
                                    <th style="text-align:center;"></th>
                                    <th style="text-align:center;"></th>
                                    </tr>
                                </tfoot>
                            </table>
                            <div>
                                <button id="Rclose">退出</button>
                            </div>
                        </div>

                        <br>
                        <form action="manage_Invoice.php" method="get">
                            <div class="search_block" align="center" style="display: inline-block;text-align: left;">
                                <ul class="search_bar">
                                    <li> <input type="text" name="store" placeholder="輸入店家名稱"  value="<?php echo $_GET['store'];?>" style="height: 40px;-webkit-appearance:none;line-height: 40px;width: 80%;margin-left: 0px"></li>
                                    <li><select name="industry" id="industry" placeholder="產業 : " style="width: 200px;text-align: left;height: 40px;line-height: 40px;border: 2px solid #8e78de">
                                          <option value="" ><span style="color:#999999">請選擇產業</span></option>
                                          <option value="餐飲業">餐飲業</option>
                                          <option value="服飾業">服飾業</option>
                                          <option value="資訊業">資訊業</option>
                                          <option value="飯店業">飯店業</option>
                                          <option value="零售業">零售業</option>
                                          <option value="其他">其他</option>
                                          </select></li>
                                    <li><button type="submit" style="margin-left: 8px;line-height: 35px;background-color:#8e78de;border-radius: 6px;color: #fff;border: 0px;width: 80px;font-size: 17px "  class="date_but">查詢</button></li>
                                </ul>
                            </div>
                        </form>
              
              
                        <div id="home" class="tab-pane fade in active">
                            <div class="table-responsive search_table account_info" style="overflow-y: visible;white-space: nowrap;height: 400px;width: 90%" align="center">
                              <ul>
                                <li style="display: inline-block;width: 100px;">申請店家</li>
                                <li style="display: inline-block;width: 100px;">消費積分</li>
                                <li style="display: inline-block;width: 100px;">串串積分</li>
                                <li style="display: inline-block;width: 100px;">現金/刷卡</li>
                                <li style="display: inline-block;width: 100px;">回饋額</li>
                                <li style="display: inline-block;width: 100px;">撥款金額</li>  
                                <li style="display: inline-block;width: 100px;">撥款明細</li>
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
                                            //撥款金額
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
                                    <style>
                                    .acc_list  {
                                      background: #F5F5F5 !important;
                                       
                                    }
                                    </style>
                                    <ul class="acc_list">
                                        <li style="display: inline-block;width: 100px;"><a href="manage_inquire_store.php?store=<?php echo $nick;?>"><?php echo $nick;?></a></li>
                                        <li style="display: inline-block;width: 100px;"><?php echo $g_total;?></li>
                                        <li style="display: inline-block;width: 100px;"><?php echo $c_total;?></li>
                                        <li style="display: inline-block;width: 100px;"><?php echo $spend_total;?></li>
                                        <li style="display: inline-block;width: 100px;"><?php echo $q_total;?></li>
                                        <li style="display: inline-block;width: 100px;"><?php echo $usage_fee;?></li>
                                        <?php
                                        if($total_ss != 0){
                                          echo "<li style='display: inline-block;width: 100px;'><button style='background:#6d4ede;color:#fff;border:0px;border-radius:5px;height:30px;vertical-align: middle;line-height: 27px' id='dialog_open' class='dialog_open$total_recoc' value='$arr_json'>明細</button></li>";
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
                                                                "destroy":true,
                                                                "footerCallback": function ( row, data, start, end, display ) {
                                                                    var api = this.api(), data;
                                                                    // Remove the formatting to get integer data for summation
                                                                    var intVal = function ( i ) {
                                                                        return typeof i === 'string' ?
                                                                            i.replace(/[\$,]/g, '')*1 :
                                                                            typeof i === 'number' ?
                                                                                i : 0;
                                                                    };
                                                                    // Total over all pages
                                                                    total = api
                                                                        .column( 5 )
                                                                        .data()
                                                                        .reduce( function (a, b) {
                                                                            return intVal(a) + intVal(b);
                                                                        }, 0 );
                                                                        /*
                                                                    // Total over this page
                                                                    pageTotal = api
                                                                        .column( 5, { page: 'current'} )
                                                                        .data()
                                                                        .reduce( function (a, b) {
                                                                            return intVal(a) + intVal(b);
                                                                        }, 0 );
                                                                        */
                                                                    total_g = api
                                                                        .column( 1 )
                                                                        .data()
                                                                        .reduce( function (a, b) {
                                                                            return intVal(a) + intVal(b);
                                                                        }, 0 );
                                                                    total_c = api
                                                                        .column( 2 )
                                                                        .data()
                                                                        .reduce( function (a, b) {
                                                                            return intVal(a) + intVal(b);
                                                                        }, 0 );
                                                                    total_spend = api
                                                                        .column( 3 )
                                                                        .data()
                                                                        .reduce( function (a, b) {
                                                                            return intVal(a) + intVal(b);
                                                                        }, 0 );
                                                                    sum = api
                                                                        .column( 4 )
                                                                        .data()
                                                                        .reduce( function (a, b) {
                                                                            return intVal(a) + intVal(b);
                                                                        }, 0 );

                                                                    // Update footer
                                                                    $( api.column( 5 ).footer() ).html(
                                                                        '$'+ total
                                                                    );
                                                                    $( api.column( 1 ).footer() ).html(
                                                                        '$'+total_g
                                                                    );
                                                                    $( api.column( 2 ).footer() ).html(
                                                                        '$'+total_c
                                                                    );
                                                                    $( api.column( 3 ).footer() ).html(
                                                                        '$'+total_spend
                                                                    );
                                                                    $( api.column( 4 ).footer() ).html(
                                                                        '$'+sum
                                                                    );
                                                                },
                                                                "aoColumns":[{"sTitle":"申請日期"},//自動產生Title
                                                                            {"sTitle":"消費積分"},
                                                                            {"sTitle":"串串積分"},
                                                                            {"sTitle":"現金/刷卡"},
                                                                            {"sTitle":"合計"},
                                                                            {"sTitle":"回饋金額"}],
                                                                "aaData": resultData,//自動產生內容 ps.需與aoColumns對應

                                                      };
                                                      $(".custTable<?php echo $total_recoc;?>").dataTable(opt);
                                                      
                                                    }
                                                });


                                            $( ".dialog<?php echo $total_recoc;?>" ).dialog( "open");
                                            $('.ui-dialog-titlebar-close').click(function(){
                                              //location.reload();
                                            });
                                            //按下dialog的確認後關閉
                                            $("#bt<?php echo $total_recoc;?>").click(function(event){
                                              $(".dialog<?php echo $total_recoc;?>").dialog( "close" );
                                              //location.reload();
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
                                                                "destroy":true,
                                                                "footerCallback": function ( row, data, start, end, display ) {
                                                                    var api = this.api(), data;
                                                                    // Remove the formatting to get integer data for summation
                                                                    var intVal = function ( i ) {
                                                                        return typeof i === 'string' ?
                                                                            i.replace(/[\$,]/g, '')*1 :
                                                                            typeof i === 'number' ?
                                                                                i : 0;
                                                                    };
                                                                    // Total over all pages
                                                                    total = api
                                                                        .column( 5 )
                                                                        .data()
                                                                        .reduce( function (a, b) {
                                                                            return intVal(a) + intVal(b);
                                                                        }, 0 );
                                                                    total_g = api
                                                                        .column( 1 )
                                                                        .data()
                                                                        .reduce( function (a, b) {
                                                                            return intVal(a) + intVal(b);
                                                                        }, 0 );
                                                                    total_c = api
                                                                        .column( 2 )
                                                                        .data()
                                                                        .reduce( function (a, b) {
                                                                            return intVal(a) + intVal(b);
                                                                        }, 0 );
                                                                    total_spend = api
                                                                        .column( 3 )
                                                                        .data()
                                                                        .reduce( function (a, b) {
                                                                            return intVal(a) + intVal(b);
                                                                        }, 0 );
                                                                    sum = api
                                                                        .column( 4 )
                                                                        .data()
                                                                        .reduce( function (a, b) {
                                                                            return intVal(a) + intVal(b);
                                                                        }, 0 );

                                                                    // Update footer
                                                                    $( api.column( 5 ).footer() ).html(
                                                                        '$'+total
                                                                    );
                                                                    $( api.column( 1 ).footer() ).html(
                                                                        '$'+total_g
                                                                    );
                                                                    $( api.column( 2 ).footer() ).html(
                                                                        '$'+total_c
                                                                    );
                                                                    $( api.column( 3 ).footer() ).html(
                                                                        '$'+total_spend
                                                                    );
                                                                    $( api.column( 4 ).footer() ).html(
                                                                        '$'+sum
                                                                    );
                                                                },
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
                                              //location.reload();
                                            });
                                            //按下dialog的確認後關閉
                                            $("#opt<?php echo $total_recoc;?>").click(function(event){
                                              $(".dialog_aa<?php echo $total_recoc;?>").dialog( "close" );
                                              //location.reload();
                                            });
                                        });

                                        $(".dialog_aa<?php echo $total_recoc;?>").dialog({
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
                                        <tfoot>
                                          <tr>
                                            <th style="text-align:right">合計:</th>
                                            <th style="text-align:center;"></th>
                                            <th style="text-align:center;"></th>
                                            <th style="text-align:center;"></th>
                                            <th style="text-align:center;"></th>
                                            <th style="text-align:center;"></th>
                                          </tr>
                                        </tfoot>
                                        </table>
                                        <div>
                                            <button id="bt<?php echo $total_recoc;?>" value='<?php echo $arr_json;?>' onClick="kk(<?php echo $total_recoc;?>)">撥款提交</button>
                                        </div>
                                    </div>
                                    <div class="dialog_aa<?php echo $total_recoc;?>" style="display: none;text-align:center;">
                                        <span><?php echo $nick?></span>
                                        <hr>
                                        <table class="checkTable<?php echo $total_recoc;?>" width="100%">
                                        <tfoot>
                                          <tr>
                                            <th style="text-align:right">合計:</th>
                                            <th style="text-align:center;"></th>
                                            <th style="text-align:center;"></th>
                                            <th style="text-align:center;"></th>
                                            <th style="text-align:center;"></th>
                                            <th style="text-align:center;"></th>
                                          </tr>
                                        </tfoot>
                                        </table>
                                        <div>
                                            <button id="opt<?php echo $total_recoc;?>">退出</button>
                                        </div>
                                    </div>

                            <?php 

                                    $total_recoc = $total_recoc-1;

                                    }while($row_recoc = mysql_fetch_assoc($Recoc));

                                }
                                ?>

                            </div>
                        </div>

                </li>
                <li id="section2" class="box">
                    <div style="">
                        <div class="contant">
                        <h3>帳款歷史紀錄</h3> 
                        <div class="store_block" >
                            <ul style="float: left;margin-right: 8px;position: absolute;top: 25px;left: 25px" class="menu">
                                <li style="padding-left: 5px">
                                    <a href="#section1"><img src="img/next-01.png" width="22px" alt="" style="transform: rotate(180deg)"></a>
                                </li>
                            </ul>
                            <div class="search_block" align="center" style="display: inline-block;text-align: left;">
                                <form action="manage_Invoice.php#section2" method="get">
                                    <ul class="search_bar">
                                        <li> <input type="text" name="store" placeholder="輸入店家名稱"  value="<?php echo $_GET['store'];?>" style="height: 40px;-webkit-appearance:none;padding: 5px;line-height: 40px;width: 70%;margin-left: 45px"></li>
                                        <li><select name="industry" id="industry" placeholder="產業 : " style="width: 200px;text-align: left;height: 40px;line-height: 40px;border: 2px solid #8e78de">
                                                <option value="" style="color:#999999"><span style="color:#999999">請選擇產業</span></option>
                                                <option value="餐飲業">餐飲業</option>
                                                <option value="服飾業">服飾業</option>
                                                <option value="資訊業">資訊業</option>
                                                <option value="飯店業">飯店業</option>
                                                <option value="零售業">零售業</option>
                                                <option value="其他">其他</option>
                                                </select>
                                        </li>
                                        <li><button type="submit" style="margin-left: 8px;line-height: 35px;background-color:#8e78de;border-radius: 6px;color: #fff;border: 0px;width: 80px;font-size: 17px "  class="date_but">查詢</button></li>
                                    </ul>
                                </form>
                            </div>
                            <div id="home2" class="tab-pane fade in active">
                                <div class="table-responsive search_table account_info" style="overflow-y: visible;white-space: nowrap;height: 400px;width: 90%" align="center">
                                    <ul >
                                        <li style="display: inline-block;width: 100px;">申請店家</li>
                                        <li style="display: inline-block;width: 100px;">消費積分</li>
                                        <li style="display: inline-block;width: 100px;">串串積分</li>
                                        <li style="display: inline-block;width: 100px;">現金/刷卡</li>
                                        <li style="display: inline-block;width: 100px;">回饋額</li>
                                        <li style="display: inline-block;width: 100px;">回饋金額</li>
                                        <li style="display: inline-block;width: 100px;">查看</li>
                                    </ul>
                                    <?php
                                    if($total_invoice != 0){

                                        do{
                                            $nick = $row_invoice['nick'];
                                            $s_number = $row_invoice['number'];

                                            //取得商店交易資料
                                            mysql_select_db($database_lp, $lp);
                                            $query_aa = "SELECT * FROM manage_invoice WHERE number = '$s_number'";
                                            $Recoc_aa = mysql_query($query_aa, $lp) or die(mysql_error());
                                            $row_aa = mysql_fetch_assoc($Recoc_aa);
                                            $total_aa = mysql_num_rows($Recoc_aa);

                                            if($total_aa != 0){ //沒有列數就表示沒有新申請的資料

                                            $c_total = 0; //換商店就清空
                                            $g_total = 0;
                                            $q_total = 0;
                                            $usage_fee = 0;
                                            $spend_total = 0;

                                            do{
                                                //串串積分
                                                $c = $row_aa['c_total'];
                                                $c_total = $c_total + $c;
                                                //消費積分
                                                $g = $row_aa['g_total'];
                                                $g_total = $g_total + $g;
                                                //回饋金
                                                $q = $row_aa['q_total'];
                                                $q_total = $q_total + $q;
                                                //回饋金額
                                                $u = $row_aa['usage_fee'];
                                                $usage_fee = $usage_fee + $u;
                                                //現金/刷卡
                                                $spend = $row_aa['spend'];
                                                $spend_total = $spend_total + $spend;

                                            }while($row_aa = mysql_fetch_assoc($Recoc_aa));
                                        }

                                        $arr =array("s_number"=>$s_number,"c_total"=>$c_total,"g_total"=>$g_total,"q_total"=>$q_total,"usage_fee"=>$usage_fee);
                                        $arr_json = json_encode($arr); //陣列轉josn

                                        ?>
                                    <ul class="acc_list">
                                        <li style="display: inline-block;width: 100px;"><?php echo $nick;?></li>
                                        <li style="display: inline-block;width: 100px;"><?php echo $g_total;?></li>
                                        <li style="display: inline-block;width: 100px;"><?php echo $c_total;?></li>
                                        <li style="display: inline-block;width: 100px;"><?php echo $spend_total;?></li>
                                        <li style="display: inline-block;width: 100px;"><?php echo $q_total;?></li>
                                        <li style="display: inline-block;width: 100px;"><?php echo $usage_fee;?></li>
                                        <?php
                                        echo "<li style='display: inline-block;width: 100px;'><button style='background:#c6bfde;color:#333;;border:0px;border-radius:5px;height:30px;vertical-align: middle;line-height: 27px;margin-bottom:10px' id='dialog_open' class='dialog_open$total_recoc' value='$arr_json'>查看</button></li>";
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
                                                                "destroy":true,
                                                                "footerCallback": function ( row, data, start, end, display ) {
                                                                    var api = this.api(), data;
                                                                    // Remove the formatting to get integer data for summation
                                                                    var intVal = function ( i ) {
                                                                        return typeof i === 'string' ?
                                                                            i.replace(/[\$,]/g, '')*1 :
                                                                            typeof i === 'number' ?
                                                                                i : 0;
                                                                    };
                                                                    // Total over all pages
                                                                    total = api
                                                                        .column( 5 )
                                                                        .data()
                                                                        .reduce( function (a, b) {
                                                                            return intVal(a) + intVal(b);
                                                                        }, 0 );
                                                                    total_g = api
                                                                        .column( 1 )
                                                                        .data()
                                                                        .reduce( function (a, b) {
                                                                            return intVal(a) + intVal(b);
                                                                        }, 0 );
                                                                    total_c = api
                                                                        .column( 2 )
                                                                        .data()
                                                                        .reduce( function (a, b) {
                                                                            return intVal(a) + intVal(b);
                                                                        }, 0 );
                                                                    total_spend = api
                                                                        .column( 3 )
                                                                        .data()
                                                                        .reduce( function (a, b) {
                                                                            return intVal(a) + intVal(b);
                                                                        }, 0 );
                                                                    sum = api
                                                                        .column( 4 )
                                                                        .data()
                                                                        .reduce( function (a, b) {
                                                                            return intVal(a) + intVal(b);
                                                                        }, 0 );

                                                                    // Update footer
                                                                    $( api.column( 5 ).footer() ).html(
                                                                        '$'+total
                                                                    );
                                                                    $( api.column( 1 ).footer() ).html(
                                                                        '$'+total_g
                                                                    );
                                                                    $( api.column( 2 ).footer() ).html(
                                                                        '$'+total_c
                                                                    );
                                                                    $( api.column( 3 ).footer() ).html(
                                                                        '$'+total_spend
                                                                    );
                                                                    $( api.column( 4 ).footer() ).html(
                                                                        '$'+sum
                                                                    );
                                                                },
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
                                              //location.reload();
                                            });
                                            //按下dialog的確認後關閉
                                            
                                            $("#bt<?php echo $total_recoc;?>").click(function(event){
                                              $(".dialog<?php echo $total_recoc;?>").dialog( "close" );
                                              //location.reload();
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
                                          width:710,
                                          responive: true,
                                          paging: false,
                                         
                                        
                                        });


                                    })


                                    </script>

                                    <div class="dialog<?php echo $total_recoc;?>" style="display: none;text-align:center;">
                                        <span><?php echo $nick?></span>
                                        <hr>
                                        <table class="custTable<?php echo $total_recoc;?>" width="100%">
                                        <tfoot>
                                            <tr>
                                                <th style="text-align:right">合計:</th>
                                                <th style="text-align:center;"></th>
                                                <th style="text-align:center;"></th>
                                                <th style="text-align:center;"></th>
                                                <th style="text-align:center;"></th>
                                                <th style="text-align:center;"></th>
                                            </tr>
                                        </tfoot>
                                        </table>
                                        <div>
                                            <button id="bt<?php echo $total_recoc;?>">確認</button>
                                        </div>
                                    </div>

                                    <?php 

                                        $total_recoc = $total_recoc-1;

                                        }while($row_invoice = mysql_fetch_assoc($query_invoice));

                                    }
                                    ?>

            </ul>
        </div>
    </div>
</body>

</html>
