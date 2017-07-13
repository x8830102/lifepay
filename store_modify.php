<?php 
require_once('Connections/lp.php');mysql_query("set names utf8");
require_once('Connections/sc.php');mysql_query("set names utf8");
?>
<?php 
session_start();
if ($_SESSION['MM_Username'] == ""){header(sprintf("Location: store_login.php"));exit;
}else{
  $number = $_SESSION['number'];//驗證帳號
  $user_id = $_SESSION['MM_Username'];
}

//如果沒有列數就表示不是商店端的帳號
mysql_select_db($database_lp, $lp);
$SQL = sprintf("SELECT * FROM lf_user WHERE number ='$number' ");
$conn = mysql_query($SQL, $lp) or die(mysql_error());
$row = mysql_fetch_assoc($conn);
$total = mysql_num_rows($conn);
if($total == 0){
    //表示是使用者連過來的
    mysql_select_db($database_sc, $sc);
    $query_user = sprintf("SELECT * FROM memberdata WHERE number ='$number' ");
    $Reuser = mysql_query($query_user, $sc) or die(mysql_error());
    $row_user = mysql_fetch_assoc($Reuser);
    $total_user = mysql_num_rows($Reuser);
    if($total_user != ''){
      header(sprintf("Location: logout.php"));exit;
    }
}

//
mysql_select_db($database_lp, $lp);
$emp = sprintf("SELECT * FROM lf_user WHERE user_id ='$user_id' ");
$con_em = mysql_query($emp, $lp) or die(mysql_error());
$row_em = mysql_fetch_assoc($con_em);
$level = $row_em['level'];
$st_name =$row_em['st_name'];
$time1 = $row_em['time1'];
$time2 = $row_em['time2'];

$date = date("Y-m-d");
$plus = strtotime("+1 day");
$date2 = date('Y-m-d',$plus);
$month1 = date("Y-m-01");
$pluss = strtotime("+1 month");
$month2 = date('Y-m-01',$pluss);


?>

<!DOCTYPE html>
<html>
<head>
  <title>LIFE PAY</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" href="/wp-content/themes/the-rex/css/jquery-ui.css" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 
  <style>
    .ui-widget-header
    {
      background:#e37d00 !important;
      color:#fff !important;
    }
  
</style>
</head>
<body style="background-color:#f5c1ad ">
<script type="text/JavaScript">

//修改
function sub_m(value){

    var e = ".e_name"+value;
    var a = ".accont"+value;
    var p = ".password"+value;
    var st = ".st_dis"+value;
    var re = ".re_pas"+value;

    var e_name = $(e).val();
    var accont = $(a).val();
    var password = $(p).val();
    var st_dis =$(st).val();
    var re_pas =$(re).val();
    
    //alert(st_dis);
    $(document).ready(function() {
      //ajax 送表單
    $.ajax({
        type: "POST",
        url: "modify.php",
        dataType: "text",
        data: {
          ee: e_name,
          pp: password,
          aa: accont,
          ss: st_dis,
          re: re_pas
        },
        success: function(data) {
          window.history.go(0);
        }

      })
    })

}


function c(v){
    var okk =".ok"+v;
    var r=confirm("確定要刪除嗎!");
    $(okk).val("1");

    if(r == true){
      sub(v);
    }
    //sub(v);
}
//刪除
function sub(value){

    var okk =".ok"+value;
    $(okk).val("1");
    var e = ".e_name"+value;
    var a = ".accont"+value;
    var p = ".password"+value;
    var st = ".st_dis"+value;
    var bt = ".btoon"+value;


    var btt = $(bt).val();
    var ok = $(okk).val();
    var e_name = $(e).val();
    var accont = $(a).val();
    var password = $(p).val();
    var st_dis =$(st).val();
    

    var val = JSON.parse(btt);
    $(document).ready(function() {
      //ajax 送表單
    $.ajax({
        type: "POST",
        url: "modify.php",
        dataType: "text",
        data: {
          qq: ok,
          ee: val.e_name,
          aa: val.accont,
          pp: val.password,
          ss: st_dis
        },
        success: function(data) {
          location.reload();
        }

      })
    })
    
}



/*
  $(function () {
        $('#myForm').submit(function () {

            //var active_nav_tab = $('ul.nav-tabs li.active').attr('index'); //get index
            var active_nav_content = $('.tab-content .active').attr('id'); //get id
            alert(active_nav_content);
            $(active_nav_content).attr('aria-expanded') = "true";
        });
    });
*/

</script>

<div class="mebr_top" align="center">
  <a href="store_main.php"><img src="img/life_pay_logo-01.png" width="220px" alt=""></a>
  <a href="#" data-toggle="modal" data-target="#myModal2"><img src="img/set_up-01.svg" style="position: absolute; left: 20px; top: 67px" width="25px" alt=""></a>
 
</div>
<div class="navtop">
<div class="col-lg-6 col-md-6 col-xs-6" align="center" style="top: -2px;border-right: 2px solid #fff"><a href="store_search.php"><img src="img/long_search-01.png" alt="" width="120px" ></a></div>
  <div class="col-lg-6 col-md-6 col-xs-6" align="center" style="top: -2px"><a href="store_checkout.php"><img src="img/long_checkout-01.png" alt="" width="120px"></a></div>
</div>
<div class="tab-content">
  <div id="home" class="tab-pane fade in active">
    <?php //

      $query_user = sprintf("SELECT * FROM lf_user WHERE number ='$number' ");
      $Reuser = mysql_query($query_user, $lp) or die(mysql_error());
      $row_user = mysql_fetch_assoc($Reuser);
      $total_user = mysql_num_rows($Reuser);


    ?>
    <div class="search_table table-responsive " align="center" style="overflow-y: visible;white-space: nowrap;">
        <table align="center" class="table coupon_table" style="background: #fff;border-radius: 10px">
            <tr  style="background-color: #ee9078;color: #fff ">
          <th style="border-radius: 10px 0px 0px 0px">#</th>
          <th >員工名稱</th>
          <th >員工帳號</th>
          <th >員工密碼</th>
          <th >員工二級密碼</th>
          <th style="width: 12%"></th>
          <th style="width: 12%;border-radius: 0px 10px 0px 0px"></th>
        </tr>
        <?php
        //輸出資料列
          if($total_user != 0)
          {
            //$level = $row_user['level'];
            
            do{

              if($row_user['level'] != boss){
                

              $e_name = $row_user['e_name'];
              $acc = $row_user['accont'];
              $pas = $row_user['password'];             
              $pass2 = $row_user['password2'];
              
              //echo $fcount;
              $arr =array("e_name"=>$e_name,"accont"=>$acc,"password"=>$pas,"password2"=>$pass2);
              $arr_json = json_encode($arr); //陣列轉josn
              

              echo "<tr class='search_tr' style='text-align:center;background-color: #fff'>";
              echo "<td style='text-align:center;border-radius: 0px 0px 0px 10px'> ".$total_user."</td>";
              echo "<td style='text-align:center;'>".$e_name."</td>";
              echo "<td style='text-align:center;'> ".$acc."</td>";
              echo "<td style='text-align:center;'> ".$pas."</td>";
              echo "<td style='text-align:center;'> ".$pass2."</td>";
              echo "<input type='hidden' class='ok$total_user' name='ok' value=''>";


              ?>
              <script>
              $(function() 
              {
                y=$(window).width(); //螢幕寬度
                x=$(window).height();//螢幕高度
                if(y>"1024"){y=y/2;}else{y=y/1;}
                
                
                  //按下修改密碼
                  $(".dialog_open<?php echo $total_user;?>").click(function(event) 
                  { //按下超連結
                    var y = $(".dialog_open<?php echo $total_user;?>").val();
                    var val = JSON.parse(y);
                    var e_name = val.e_name;
                    var accont = val.accont;
                    var password = val.password;
                    var password2 = val.password2;
   
                    //alert(val.e_name);
                    $(".e_name<?php echo $total_user;?>").val(val.e_name); 
                    $(".accont<?php echo $total_user;?>").val(accont);
                    $(".password<?php echo $total_user;?>").val(password);
                    $(".pass2<?php echo $total_user;?>").val(password2);
     
                    $( ".dialog<?php echo $total_user;?>" ).dialog( "open");
                    event.preventDefault();  //防止上方連結打開
                  });
                  
                    y = y/1.1;
                    x = x/1.5;
                    
                    $( ".dialog<?php echo $total_user;?>" ).dialog
                    ({
                     show: {
                      effect: "fade",
                      },
                      autoOpen: false, //預設不顯示
                      draggable: false, //設定拖拉
                      resizable: true, //設定縮放
                      //title:" ",
                      //dialogClass: "no-close", //關閉標題
                      height: "auto",
                      width: "auto",
                      modal: true //灰色透明背景限制只能按dialog
                    
                    })
                    $(".sb<?php echo $total_user;?>").click(function(event){
                      $(".dialog<?php echo $total_user;?>").dialog( "close" );
                    })

              })
                  //$('.ui-dialog-titlebar-close').hide(); //關閉標題的叉叉
              </script>
              <div class="dialog<?php echo $total_user;?>" title="LIFE PAY" style="display:none;text-align:center;">
                <span style="font-size:20px">員工名稱：</span><br>
                <input name="e_name" class="e_name<?php echo $total_user;?>" type="text" readonly="readonly" style="width: 200px;text-align: center;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;;font-size: 20px;" value="" /><br>
                
                <span style="font-size:20px;margin-top: 30px">員工帳號：</span><br>
                <input name="accont" class="accont<?php echo $total_user;?>" type="text" readonly="readonly" style="width: 200px;text-align: center;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;" value="" /><br>
                
                <span style="font-size:20px;margin-top: 30px">員工密碼：</span><br>
                <input name="password" id="password" class="password<?php echo $total_user;?>" type="text" readonly="readonly" style="width: 200px;text-align: center;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;" value="" /><br>

                <span style="font-size:20px;margin-top: 30px">員工二級密碼：</span><br>
                <input name="pass2" id="pass2" class="pass2<?php echo $total_user;?>" type="text" readonly="readonly" style="width: 200px;text-align: center;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;" value="" /><br>
                
                <input name="complete_id" class="complete_id<?php echo $total_user;?>" type="hidden" value="" />
                
                 <script>
                 //密碼
                 function h(v){
                   var s =".st_dis"+v;
                   var b =".hb"+v;
                   var g =".hg"+v;
                   var sub =".sb"+v;

                   $(s).attr("disabled", false);
                   $(s).css("background", "#FFF");
                   $(s).css("border", "1px #ccc solid");
                   $(b).css("display", "none");
                   $(g).css("display", "");
                   $(sub).css("display","none");
                   
                 }
                 //二級密碼
                 function p(v){
                   var s =".re_pas"+v;
                   var b =".pb"+v;
                   var g =".hg"+v;
                   var sub =".sb"+v;

                   $(s).attr("disabled", false);
                   $(s).css("background", "#FFF");
                   $(s).css("border", "1px #ccc solid");
                   $(b).css("display", "none");
                   $(g).css("display", "");
                   $(sub).css("display","none");
                   
                 }
                 function g(v){
                   var s =".st_dis"+v;
                   var r =".re_pas"+v
                   var b =".hb"+v;
                   var e =".pb"+v;
                   var g =".hg"+v;
                   var sub =".sb"+v;
                   var st_dis = $(s).val();
                   var re_pas = $(r).val();
                   //alert(re_pas);
                   $(s).attr("disabled", "disabled");
                   $(s).css("background", "#e3e3e3");
                   $(s).css("border", "0px");
                   $(r).attr("disabled", "disabled");
                   $(r).css("background", "#e3e3e3");
                   $(r).css("border", "0px");
                   $(g).css("display", "none");
                   $(b).css("display", "");
                   $(e).css("display", "");
                   $(sub).css("display","");
                  
                 }

                 //限制不能輸入文字和字母
                 function ValidateNumber(e, pnumber)
                  {
                    if (!/^\d+$/.test(pnumber))
                    {
                      e.value = /^\d+/.exec(e.value);
                    }
                    return false;
                  }
                 
                 

                 </script>
                 <!--修改密碼-->
                <input name="st_dis" id="st_dis" class="st_dis<?php echo $total_user;?>" onKeyUp="value=value.replace(/[\W]/g,'')" type="text" style="width: 100px;text-align: center;height: 30px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;margin-top: 15px;margin-bottom: 15px" value="<?php echo $pas;?>" disabled="disabled" >
                <button class="hb<?php echo $total_user;?> date_but" onClick="h(<?php echo $total_user;?>)" style="background: #ff5454;height: 30px;line-height: 20px">修改密碼</button>
                <br>
                <!--修改二級密碼-->
                <input name="re_pas" id="re_pas" class="re_pas<?php echo $total_user;?>" onKeyUp="value=value.replace(/[\W]/g,'')" type="text" style="width: 100px;text-align: center;height: 30px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;margin-top: 15px;margin-bottom: 15px" value="<?php echo $pass2;?>" disabled="disabled" >
                <button class="pb<?php echo $total_user;?> date_but" onClick="p(<?php echo $total_user;?>)" style="background: #ff5454;height: 30px;line-height: 20px">二級密碼</button>
                <br>
                <button class="hg<?php echo $total_user;?> date_but" onClick="g(<?php echo $total_user;?>)" style="display:none;background: #2bcb3a;height: 30px;line-height: 20px">確認</button><br>
                <button class="sb<?php echo $total_user;?> date_but" onClick="sub_m(<?php echo $total_user;?>);" style="width:100%;background: #487be5">送出</button>
                
              </div>

              <?php
              
                echo "<td style='text-align:center;'><button style='background:#487be5;color:#fff;border:0px;border-radius:5px;padding:0px 20px' class='dialog_open$total_user' id='dialog_open' value='$arr_json'>修改密碼</button></td>";
                echo "<td style='text-align:center;border-radius: 0px 0px 10px 0px'><button style='background:#ff5454;color:#fff;border:0px;border-radius:5px;padding:0px 20px' id='dialog_open2' class='btoon$total_user' onclick='c($total_user);window.history.go(0)' value='$arr_json'>刪除員工</button></td>";

              $total_user =$total_user-1;
            }
            }while($row_user = mysql_fetch_assoc($Reuser));

            
              
          }
        ?>
      </table>
    </div>
  </div>
  
  <div class="search_bt" align="center" style="margin-top: -22px">
  <div style="width: 88%;margin-left: 15px" align="left">
  <a href="store_main.php"><input type="button" value="< 返回" style="background: #595757; color: #fff;border: 0px ;border-radius: 5px;padding: 4px 25px"></a>
  </div></div>
<div class="col-lg-12 col-md-12 col-xs-12 st_pe_foot" style="height: 48px; background-color:#efefef; width: 100% ">
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
          <?php if($level =="boss")
		  {?>
		  <li><a href="store_update.php" ><img src="img/sign-out-option.svg" width="25px" alt=""><span style="margin-left: 8px">更改資料</span></a></li>
		  <li><a href="store_establish.php"><img src="img/user_ch.png" width="25px" alt=""><span style="margin-left: 8px">建立員工帳號</span></a></li>
      <li><a href="Invoice.php"><img src="img/user_setup.png" width="25px" alt=""><span style="margin-left: 8px">請款</span></a></li>
		  <?php 
		  }?>
          <li><a href="store_login.php"><img src="img/sign-out-option.svg" width="25px" alt=""><span style="margin-left: 8px">登出</span></a></li>
        </ul>
      </div>
    </div>
    </div>
</div>
</body>
</html>
