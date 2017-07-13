<?php 
require_once('Connections/sc.php');mysql_query("set names 'utf8'");
require_once('Connections/lp.php');mysql_query("set names 'utf8'");
?>
<?php 
session_start();
if ($_SESSION['MM_Username'] == ""){header(sprintf("Location: life_pay.php"));exit;
}else{
  $m_username = $_SESSION['mem'];//登入帳號
  $m_nick = $_SESSION['nick'];//登入暱稱
  $number = $_SESSION['number'];
}
//echo $number;
//串串積分
mysql_select_db($database_sc, $sc);
$query_Recf_c = sprintf("SELECT * FROM c_cash WHERE number = '$number' order by id desc");
$Recf_c = mysql_query($query_Recf_c, $sc) or die(mysql_error());
$row_Recf_c = mysql_fetch_assoc($Recf_c);
$totalRows_Recf_c = mysql_num_rows($Recf_c);
//消費積分
mysql_select_db($database_sc, $sc);
$query_Recf_g = sprintf("SELECT * FROM g_cash WHERE number = '$number' order by id desc");
$Recf_g = mysql_query($query_Recf_g, $sc) or die(mysql_error());
$row_Recf_g = mysql_fetch_assoc($Recf_g);
$totalRows_Recf_g = mysql_num_rows($Recf_g);
//抵用券
$date = date("ymd");
mysql_select_db($database_lp, $lp);
$query_coupon = sprintf("SELECT * FROM coupon WHERE p_number = '$number' && effective_date>='$date' && complete='0' order by id desc");
$Recf_coupon = mysql_query($query_coupon, $lp) or die(mysql_error());
$row_coupon = mysql_fetch_assoc($Recf_coupon);
$total_coupon = mysql_num_rows($Recf_coupon);
//echo $row_Recf_r['csum'];

require_once("lp_user4.php");
?>

</head>

<style>
  .pt_list:hover {background-color: #ddd}
</style>
<body >

<div class="mebr_info " align="center" style="margin-top: 95px;background: #84DDCB;width: 90%;height: 135px;padding: 10px;margin-left: 5%;border-radius: 15px;margin-bottom: 10px">
  <ul>
    <li class="col-lg-4 col-md-4 col-xs-4"><div class="member_photo" style="width: 50px;height: 50px;background-size: 50px"><div class="mb_photo_bord"></div></div>  </li>
    <li class="col-lg-8 col-md-8 col-xs-8">
      <ul class="mebr_account">
        <li style="margin-top: 0px;color: #595757;text-align: left;padding: 0px"><p >帳號：<?php echo  $m_username;?></p></li>
        <li style="min-width: 165px;white-space: nowrap; text-overflow:ellipsis;color: #595757;text-align: left;margin-top: 6px;padding: 0px"><p >暱稱：<?php echo  $m_nick;?></p></li>
      </ul>
    </li>
  </ul>

<div class="col-lg-12 col-md-12 col-xs-12 " style="margin-top: 15px" align="center"> 
<a target="_blank" href="http://cmg588.com/life_link/login_mem.php" style="color: #595757">
  <div class="col-lg-4 col-md-4 col-xs-4 mb_integral_detal" >
    <div  align="center"><?php if($totalRows_Recf_c == 0){
      echo '0';
      }else{echo $row_Recf_c['csum'];}?></div>  
  <div align="center">串串積分</div>
  </div></a> 
  <a target="_blank" href="http://cmg588.com/life_link/login_mem.php" style="color: #595757">
  <div class="col-lg-4 col-md-4 col-xs-4 mb_integral_detal">
    <div  align="center"><?php if($totalRows_Recf_g == 0){
      echo '0';
      }else{echo $row_Recf_g['csum'];}?>
      </div>
    <div align="center">消費積分</div>
    
  </div> </a>   
<a href="person_coupon.php" style="color: #595757">
  <div class="col-lg-4 col-md-4 col-xs-4 mb_integral_detal" style="border-right: 0px">
    <div  align="center"><?php $coupon = 0;for($i=1 ;$i<=$total_coupon ;$i++){$coupon = $coupon+$row_coupon['discount']; $row_coupon = mysql_fetch_assoc($Recf_coupon); }  echo $coupon;?>
   </div>
   <div align="center">抵用券</div>
    
  </div></a>
  </div> 
</div>
<div class="col-lg-12 col-md-12 col-xs-12 " style="width: 100%">
  <!--<div class="col-lg-6 col-md-6 col-xs-6 pt_list" style="text-align: center;">
  <a href="">
    <img src="img/point-01.png" width="80%" alt="">
  </a>
</div>-->
<div class="col-lg-6 col-md-6 col-xs-6 pt_list" style="text-align: center;">
  <a href="#" class="open_transfer">

    <img src="img/transfer-01.png" width="100%" alt="">
  </a>
</div>
<!--<div class="col-lg-6 col-md-6 col-xs-6 pt_list" style="text-align: center;">
  <a href="">
    <img src="img/infor-01.png" width="80%" alt="">
  </a>
</div>-->
<div class="col-lg-6 col-md-6 col-xs-6 pt_list" style="text-align: center;">
  <a href="http://shop.lifelink.com.tw/">
    <img src="img/shopmall-01.png" width="100%" alt="">
  </a>
</div>

</div>
<!--轉帳-->
<div id="box_transfer" style="background:#fff;height:100%;width:100%;top:100%; position: fixed;z-index:1001;display: none;">
  <div class="mebr_top" align="center" style="background:#84DDCB;color: #fff;padding: 13px;height: 50px ">
<div><span style="font-size: 22px">轉帳</span><span style="position: absolute; left: 33px; top:5px;color: #fff;font-size: 30px;font-weight: 300;z-index: 999" class="btn_transfer">X</span></div>
</div>

 <!-- <span style="font-size: 18px;color: red;margin-left: 3px"><?php echo $messge;?></span>-->


        <div align="left" style="border-bottom: 1px solid #eee;line-height: 40px;padding: 5px;width: 90%;margin: 0px auto"><div align="left" style="font-size: 16px;color: #999;vertical-align: top;">轉帳對象：</div><div align="left" style="color: #65A89A;font-size: 14px"><input type="text" id="touser" name="touser" style="background:#A1A1A1;color: #fff;border-radius: 6px;border:0px;height: 35px;width: 100%;line-height: 20px " placeholder="請輸入帳號" value="">

        <input type="button" id="cuser" value="檢查帳號" style="width: 100%;border-radius: 6px;background-color: #e97a23;border: 0px;color: #fff;padding: 5px;line-height: 25px"><span id="chu" style="font-size: 12px;color: red;"></span></div></div>   
        <script>
          $(document).ready(function(){
            $("#cuser").click(function(){
              var touser = $('#touser').val();
              if(touser == ""){
                $('#touser').focus();
              }else{
                $.ajax({
                  type:"POST",
                  url:"transfer_c.php",
                  data:{
                    touser:touser
                  },
                  dataType:"text",
                  success: function(data){
                    
                    if(data != ""){
                      $("#chu").html('帳號正確');
                      $("#u_number").val(data);
                      $("#tc_bt").css("display","");
                    }else{
                      $("#chu").html('沒有此帳號');
                    }
                    
                  }
                })
              }
            })
          });

            function tc(){
              var transcc = document.getElementById("transcc");
              var touser = document.getElementById("touser");
              var uu = document.getElementById("u_number");
              var cc = "<?php echo $row_Recf_c['csum'];?>";
                if(touser.value == ""){
                  $("#touser").css("background","pink");
                  $("#tc_bt").css("display","none");
                }else if(transcc.value == ''){
                  $("#transcc").css("background","pink");
                  $("#tc_bt").css("display","none");
                }else if(uu.value == ""){
                  $('#touser').focus();
                }else if(transcc.value > cc){
                  alert('餘額不足!');
                }else if(uu.value != ""){
                  document.getElementById("form100").submit();
              alert('轉帳成功!');
                }
              
            }
        </script>
  
  
      
        <form id="form100" action="transfer_c.php" method="post">
          <div align="left" style="border-bottom: 1px solid #eee;line-height: 40px;padding: 5px;width: 90%;margin: 0px auto"><div align="left" style="font-size: 16px;color: #999;">您目前的串串積分：</div><div align="left" style="color: #65A89A;font-size: 14px"><span style="font-size: 30px"><?php 
          if($row_Recf_c['csum'] == ''){
            echo '0';
          }else{
            echo number_format($row_Recf_c['csum']);
          }?></span>
          <input type="hidden" name="csum" value="<?php echo $row_Recf_c['csum']?>">
          <input type="hidden" id="u_number" name="u_number" value="">
          </div></div>
          <div align="left" style="border-bottom: 1px solid #eee;line-height: 40px;padding: 5px;width: 90%;margin: 0px auto"><div align="left" style="font-size: 16px;color: #999;">您要轉的積分：</div><div align="left" style="color: #65A89A;font-size: 14px"><input type="number" min="0" id="transcc" name="transcc" style="background:#A1A1A1;color: #fff;border-radius: 6px;border:0px;height: 35px;width: 100%;line-height: 20px;padding: 5px "></div></div>
          <div align="center" style="line-height: 40px;padding: 5px;width: 90%;margin: 0px auto"><a style="font-size: 14px;color: #999;background: #" class="open_transfer2"><div align="left" >您的積分紀錄>>></div></a></div>
      
     
          <div class="" align="center" style="width: 100%;position: absolute;bottom: 10px;left: 0px"> <button id="tc_bt" style="background: #84DDCB;border: 1px solid #84DDCB;border-radius: 6px;color: #fff;font-size: 16px;width: 80%;line-height: 35px" type="button" onClick="tc()">確定轉帳</button></div>
        </form>
</div>
<div id="box_transfer2" style="background:#fff;height:100%;width:100%;top:100%; position: fixed;z-index:1001;overflow: auto;display: none">
  <div class="mebr_top" align="center" style="background:#84DDCB;color: #fff;padding: 13px;height: 50px ">
<div><span style="font-size: 22px">您的積分紀錄</span><span style="position: absolute; left: 33px; top:5px;color: #fff;font-size: 30px;font-weight: 300;z-index: 999;" class="btn_transfer2">X</span></div>
</div>
<div style="padding: 5px;color: #333">
    <?php 
      do{?>
        <ul style="border-bottom: 1px solid #eee;width: 90%;margin-left: 5%">
          <li style="color: #999"><?php echo $row_Recf_c['date'];?> <?php echo $row_Recf_c['time'];?></li>
          <li><?php echo $row_Recf_c['note'];?></li>
          <li style="font-size: 14px">轉出/使用積分：<?php echo number_format($row_Recf_c['cout']);?></li>
          <li style="font-size: 14px">剩餘積分：<?php 
          if($row_Recf_c['csum'] == ''){
            echo '0';
          }else{
            echo number_format($row_Recf_c['csum']);
          }?></li>
          
        </ul>
      <?php }while($row_Recf_c = mysql_fetch_assoc($Recf_c));?>
          
</div>
</div>
<script>
$(document).ready(function()
  {
  $(".open_transfer").click(function(){
    $("#box_transfer").animate({top:"0%"});
    $("#box_transfer").css("display","block");
  });
  $(".btn_transfer").click(function(){
    $("#box_transfer").animate({top:"100%"});
    $("#box_transfer").css("display","none");
  });
  $(".open_transfer2").click(function(){
    $("#box_transfer2").animate({top:"0%"});
    $("#box_transfer2").css("display","block");
  });
  $(".btn_transfer2").click(function(){
    $("#box_transfer2").animate({top:"100%"});
    $("#box_transfer2").css("display","none");
  });
 
});

</script>


