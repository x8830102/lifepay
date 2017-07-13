<?php require_once('Connections/sc.php');require_once('Connections/lp.php');mysql_query("set names utf8");

/* AJAX check  */
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  $data_value = $_POST['data_value'];
  $p_user = $_POST['nick'];

  mysql_select_db($database_sc, $sc);
  $query_strp = "SELECT * FROM memberdata WHERE m_username = '$data_value' ";
  $Restrp = mysql_query($query_strp, $sc) or die(mysql_error());
  $row_strp = mysql_fetch_assoc($Restrp);
  $row_nump = mysql_num_rows($Restrp);

  $m_nick = $row_strp['m_nick'];
  $fname = $row_strp['fname'];
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
              <div align="left">
                <div style="font-size: 18px;color: #999;margin-left: 15%;display: inline-block;">
                  <ul>
                    <li>推薦人</li>
                    <li style="font-weight: 600;color: #333"><?php echo $fname;?></li>
                  </ul>
                </div>
                <div style="font-size: 36px;font-weight: 500;margin-left: 19%;display: inline-block;"><?php echo $m_nick;?></div>
              </div>
              <div class="search_bt" align="center" style="display: inline-block;text-align: left;">
                <ul style="float: left;margin-right: 8px;position: absolute;top: 25px;left: 25px" id="menu">
                  <li style="padding-left: 5px">
                    <a href="#section0"><img src="img/next-01.png" width="22px" alt="" style="transform: rotate(180deg)"></a>
                  </li>
                </ul>
                <div  align="center" >
                  <ul class="search_bar"  style="margin-top: 25px">
                    <li><input type="date" name="sd1"  id="sd1" value="<?php echo $_GET['sd1'];?>" style="height: 40px;min-width: 120px; display: inline-block"></li>
                    <li><span style="margin: 4px;line-height: 40px">至</span></li>
                    <li><input type="date" name="sd2"  id="sd2" value="<?php echo $_GET['sd2'];?>" style="height: 40px;min-width: 120px;display: inline-block;"></li>
                    <li><span style="margin: 4px;line-height: 40px;width: 120px"></span></li>
                    <li> <input type="text" id="store" name="store" placeholder="輸入店家名稱"  value="<?php echo $_GET['store'];?>" style="-webkit-appearance:none;line-height: 40px;min-width: 80%;margin-left: 0px"></li>
                    <input type="hidden" id="p_user" name="p_user" value="<?php echo $m_nick;?>">
                    <li><button type="button" style="margin-left: 8px;line-height: 35px;background-color:#8e78de;border-radius: 6px;color: #fff;border: 0px;width: 80px;font-size: 17px " class="date_but" id="check_section">查詢</button></li>
                  </ul>
                </div>
              </div>  

            <div class="search_table table-responsive " align="center" style="overflow-y: visible;white-space: nowrap;padding-top: 10px">
              <div class="search_bt" align="center">
                <div style="width: 88%" align="center"><span>消費總額</span></div>
              </div>

              <table align="center" class="table" style="width: 88%;margin: 10px auto 0px auto;line-height: 45px;background-color: #fff;border-radius: 10px" >
                <tr style="background-color: #4ab3a6;color: #fff ">
                  <th style="border-radius: 10px 0px 0px 0px">日期</th>
                  <th>消費店家</th>
                  <th>消費總金額</th>
                  <th>消費積分</th>
                  <th style="border-radius: 0px 10px 0px 0px">串串積分</th>
                </tr>
                <?php 
                  mysql_select_db($database_lp, $lp); //無條件
                  $query_strp = "SELECT * FROM complete WHERE p_user ='$p_user' ORDER BY id DESC";
                  $Restrp = mysql_query($query_strp, $lp) or die(mysql_error());
                  $row_strp = mysql_fetch_assoc($Restrp);

                  $s_nick = $row_strp['s_nick'];
                  $a=false;
                  for($i=0;$i<mysql_numrows($Restrp);$i++){

                    $row_strp = mysql_fetch_assoc($Restrp);
                    //消費總金額
                    $total_consum = $row_strp['total_cost'];
                    $consum_sum = $consum_sum + $total_consum;
                    //消費積分總額
                    $g = $row_strp['g'];
                    $g_sum = $g_sum + $g;
                    //串串積分總額
                    $c = $row_strp['c'];
                    $c_sum = $c_sum + $c;

                    //每一筆交易的商店
                    $nick_nick = $row_strp['s_nick'];

                    if(strcmp($s_nick,$nick_nick)!=0){ //比對是否相等
                      $a= true;
                    }

                  }
                ?>
                <script>
                $("#check_section").click(function(){
                      var sd1 = $("#sd1").val();
                      var sd2 = $("#sd2").val();
                      var store = $("#store").val();
                      var p_user = $("#p_user").val();
                      if(sd1 == "" && sd2 != ""){
                        $("#sd1").css("background","pink");
                        $("#sd1").focus();
                      }else if(sd1 != "" && sd2 == ""){
                        $("#sd2").css("background","pink");
                        $("#sd2").focus();
                      }else if(sd1 > sd2){
                        $("#sd1,#sd2").css("background","pink");
                        $("#sd1,#sd2").focus();
                      }else if( (store != "") || (store == "" && sd1 != "" && sd2 != "")){
                          $.ajax({
                            type: "POST",
                            url: "manage_pr.php",
                            dataType: "text",
                            data: {
                              sd1:sd1,
                              sd2:sd2,
                              store:store,
                              p_user:p_user
                            },
                            success: function(data) {
                              var data = JSON.parse(data);
                              var sd1 = data.sd1;
                              var sd2 = data.sd2;
                              var consum_sum = data.consum_sum;//消費總金額
                              var g_sum = data.g_sum;//消費積分總額
                              var c_sum = data.c_sum;//串串積分總額
                              var a = data.a;//比對是否相等

                              $('#clock1').html(sd1);
                              $('#clock2').html(sd2);
                              $('#consum_sum').html(number_format(consum_sum));
                              $('#g_sum').html(number_format(g_sum));
                              $('#c_sum').html(number_format(c_sum));
                              $('#a').html(a);

                            }

                          })
                      }else if( (store != "" && sd1 != "" && sd2 != "")){
                        $.ajax({
                          type: "POST",
                          url: "manage_pr.php",
                          dataType: "text",
                          data: {
                            sd1:sd1,
                            sd2:sd2,
                            store:store,
                            p_user:p_user
                          },
                          success: function(data) {
                            var data = JSON.parse(data);
                            var sd1 = data.sd1;
                            var sd2 = data.sd2;
                            var consum_sum = data.consum_sum;//消費總金額
                            var g_sum = data.g_sum;//消費積分總額
                            var c_sum = data.c_sum;//串串積分總額
                            var a = data.a;//比對是否相等

                            $('#clock1').html(sd1);
                            $('#clock2').html(sd2);
                            $('#consum_sum').html(number_format(consum_sum));
                            $('#g_sum').html(number_format(g_sum));
                            $('#c_sum').html(number_format(c_sum));
                            $('#a').html(a);

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

                <tr class="search_tr"  style="background-color: #fff">
                  <td style="border-radius: 0px 0px 0px 10px"><span id="clock1"></span>~<span id="clock2"></span></td>
                  <td><span id="a"><?php if($a==false){
                    echo $$s_nick;
                    }?></span></td>
                  <td><span id="consum_sum"><?php echo number_format($consum_sum);?></span></td>
                  <td><span id="g_sum"><?php echo number_format($g_sum);?></span></td>
                  <td style="border-radius: 0px 0px 10px 0px"><span id="c_sum"><?php echo number_format($c_sum);?></span></td>
                </tr>
              </table>


              <!--消費紀錄-->
              <div class="search_bt" align="center">
                <div style="width: 88%" align="center"><span><?php echo $p_user;?>消費紀錄</span></div>
              </div>
              <script>
              $("#check_section").click(function(){
                var dis_num = ($('#aa tr:last').attr("data-value"));
                var sd1 = $("#sd1").val();
                var sd2 = $("#sd2").val();
                var store = $("#store").val();
                var p_user = $("#p_user").val();
                  $.ajax({
                    type: "POST",
                    url: "m_d_prson.php",
                    dataType: "json",
                    data: {
                      sd1:sd1,
                      sd2:sd2,
                      store:store,
                      p_user:p_user
                    },
                        success: function(resultData) {
                          /*
                          console.log(resultData);  // for testing only
                          jQuery.each(resultData, function(index, value){
                              console.log(value);
                          });
                          */
                          var num = resultData[0][1];
                          for(var ni=0;ni<num; ni++){
                            var tr = "#tr"+ni;
                            var rtr = "tr"+ni;
                            $('#aa').append("<tr id='"+rtr+"' data-value='"+num+"'>");
                            $(tr).html(resultData[ni][0]);
                          }
                          if(dis_num > num){
                            for(num;num<dis_num;num++){
                              var dis_tr = "#tr"+num;
                              $(dis_tr).remove();
                            }
                          }

                        }

                  })
              })
              </script>
                <table id="aa" align="center" class="table" style="width: 88%;margin: 10px auto 0px auto;line-height: 45px;background-color: #fff;border-radius: 10px" >
                  <tr style="background-color: #4ab3a6;color: #fff ">
                    <th style="border-radius: 10px 0px 0px 0px">日期</th>
                    <th>消費店家</th>
                    <th>消費總金額</th>
                    <th>消費積分</th>
                    <th style="border-radius: 0px 10px 0px 0px">串串積分</th>
                  </tr>
                </table>
            </div>
<?php
}else{
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

if ($_GET['search'] != ""  && $_GET['fname'] != ""){
  $search=$_GET['search'];
  $fname = $_GET['fname'];
  mysql_select_db($database_sc, $sc);
  $query_str = "SELECT * FROM memberdata WHERE m_nick like '%$search%' or m_username like '%$search%' && fname like '%$fname%' ORDER BY fname DESC";
  $Restr = mysql_query($query_str, $sc) or die(mysql_error());
  $row_str = mysql_fetch_assoc($Restr);
  $row_num = mysql_num_rows($Restr);
}
if($_GET['search'] != ""  ) { //名稱 帳號
  $search=$_GET['search'];
  mysql_select_db($database_sc, $sc);
  $query_str = "SELECT * FROM memberdata WHERE m_nick like '%$search%' or m_username like '%$search%'  ORDER BY fname DESC";
  $Restr = mysql_query($query_str, $sc) or die(mysql_error());
  $row_str = mysql_fetch_assoc($Restr);
  $row_num = mysql_num_rows($Restr);
}else if ($_GET['fname'] != ""){
  $fname = $_GET['fname'];
  mysql_select_db($database_sc, $sc);
  $query_str = "SELECT * FROM memberdata WHERE fname like '%$fname%' ORDER BY fname DESC";
  $Restr = mysql_query($query_str, $sc) or die(mysql_error());
  $row_str = mysql_fetch_assoc($Restr);
  $row_num = mysql_num_rows($Restr);
}else {
  mysql_select_db($database_sc, $sc); //無條件
  $query_str = "SELECT * FROM memberdata ORDER BY fname DESC";
  $Restr = mysql_query($query_str, $sc) or die(mysql_error());
  $row_str = mysql_fetch_assoc($Restr);
  $row_num = mysql_num_rows($Restr);
}
//echo $row_num;

//版型
require_once('lifepay_user4.php');
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
  document.getElementById('form1').submit();
}

</script>
</head>

<body >

<div class="management_desk"> 
  <div id="wrapper">
    <ul id="mask">
      <li id="section0" class="box">
        <div class="contant">
          <h3>用戶資訊</h3> 
          <div class="store_block" style="height: auto">
            <form id="form1" action="manage_inquire_person.php" method="get">
              <div class="search_block" align="center" style="display: inline-block;text-align: left;">
                <div  align="left" >
                  <ul class="search_bar">
                    <li> <input type="text" name="search" placeholder="輸入會員姓名/帳號" value="<?php echo $_GET['search'];?>" style="height: 40px;-webkit-appearance:none;padding: 5px;line-height: 40px;width: 88%;margin-left: 0px;padding-left: 15px"></li>

                    <li> <input type="text" name="fname" placeholder="輸入推薦人帳號" value="<?php echo $_GET['fname'];?>" style="height: 40px;-webkit-appearance:none;padding: 5px;line-height: 40px;width: 80%;margin-left: 0px;padding-left: 15px"></li>
                    <li><button type="submit" style="margin-left: 8px;line-height: 35px;background-color:#8e78de;border-radius: 6px;color: #fff;border: 0px;width: 80px;font-size: 17px " class="date_but">查詢</button></li>
                  </ul>
                </div>
              </div>  
            </form>
        <?php if($row_num != 0){
          do{?>
          <div class="store_detail">
            <ul style="float: left;margin-right: 15px">
              <li style="color: #8a8a8a;font-size: 12px;font-weight: 400">會員姓名</li>
              <li><?php echo $row_str['m_nick'];?></li>
              <li style="color: #8a8a8a;font-size: 12px;font-weight: 400">帳號</li>
              <li><?php echo $row_str['m_username'];?></li>
              <li style="color: #8a8a8a;font-size: 12px;font-weight: 400">信箱</li>
              <li style="width: 180px;overflow: auto"><?php echo $row_str['m_email'];?></li>
            </ul>
              <ul style="float: left;">
              <li style="color: #8a8a8a;font-size: 12px;font-weight: 400">推薦人</li>
              <li><?php echo $row_str['fname'];?></li>
              <li style="color: #8a8a8a;font-size: 12px;font-weight: 400">性別</li>
              <li><?php echo $row_str['m_sex'];?></li>
              <li style="color: #8a8a8a;font-size: 12px;font-weight: 400">生日</li>
              <li><?php echo $row_str['m_birthday'];?></li>
            </ul>
              <ul style="float: right;margin-right: 8px" id="menu">
              <li style="border-left: 1px solid #fff;padding-left: 5px;height: 119px">
                 <a href="#section2" data-value=<?php echo $row_str['m_username']?> nick="<?php echo $row_str['m_nick'];?>"><img src="img/next-01.png" width="22px" alt="" style="margin-top: 12px"></a>
                 <!--<a href="manage_detail_person.php?p_user=<?php echo $row_str['m_username'];?>">消費紀錄</a>-->
              </li>
            </ul>
          </div>
          <?php 
          
          }while($row_str = mysql_fetch_assoc($Restr));

            
          }?>

          <script>
            $("#menu a").click(function(){
                var data_value = $(this).attr('data-value');
                var nick = $(this).attr('nick');
                $.ajax({
                  type: "POST",
                  url: "",
                  dataType: "html",
                  data: {
                    data_value:data_value,
                    nick:nick
                  },
                  success: function(data) {
                    $('#section2_in').html(data);
                  }

                })
            })
          </script>

      </li>


      <!--section2-->
      <li id="section2" class="box">
        <div class="contant">
          <h3>消費記錄</h3> 
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