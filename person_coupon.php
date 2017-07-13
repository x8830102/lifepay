<?php require_once('Connections/lp.php');mysql_query("set names utf8");
 
session_start();
if ($_SESSION['MM_Username'] == ""){header(sprintf("Location: life_pay.php"));exit;
}else{
  $m_username = $_SESSION['mem'];//登入帳號
  $m_nick = $_SESSION['nick'];//登入暱稱
  $number = $_SESSION['number'];
}

$date = date("Y-m-d");
//echo $date;
if($_GET['store'] != ''){
  $store=$_GET['store'];
  
  mysql_select_db($database_lp, $lp);
  $query_str = "SELECT * FROM coupon WHERE p_number ='$number' && effective_date >= '$date' && s_nick like '%$store%' order by complete ASC, effective_date ASC";
  $Restr = mysql_query($query_str, $lp) or die(mysql_error());
  $row_str = mysql_fetch_assoc($Restr);
  $row_num = mysql_num_rows($Restr);
  

  //商店抵用券總額
  mysql_select_db($database_lp, $lp);
  $query_store = "SELECT * FROM coupon WHERE p_number ='$number' && effective_date >= '$date' && complete ='0' && s_nick like '%$store%'";
  $Restore = mysql_query($query_store, $lp) or die(mysql_error());
  $row_store = mysql_fetch_assoc($Restore);
  $store_num = mysql_num_rows($Restore);
  $s_nick = $row_store['s_nick'];
  
}else if($_GET['store'] == '' && $_GET['sort'] == 1){
  mysql_select_db($database_lp, $lp);
  $query_str = sprintf("SELECT * FROM coupon WHERE p_user ='$m_username' && p_number ='$number' && effective_date >= '$date' order by complete ASC,effective_date ASC,s_nick ASC");
  $Restr = mysql_query($query_str, $lp) or die(mysql_error());
  $row_str = mysql_fetch_assoc($Restr);
  $row_num = mysql_num_rows($Restr);
}else{
  mysql_select_db($database_lp, $lp);
  $query_str = sprintf("SELECT * FROM coupon WHERE p_user ='$m_username' && p_number ='$number' && effective_date >= '$date' order by complete ASC,ID DESC");
  $Restr = mysql_query($query_str, $lp) or die(mysql_error());
  $row_str = mysql_fetch_assoc($Restr);
  $row_num = mysql_num_rows($Restr);
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 
  <script>
  function goBack() {
    window.history.back();
  }

  function pop(){
    var fm = document.getElementById('form1');
    var sort = document.getElementById('sort');
    sort.value = "1";

    fm.submit();
  }
function cel(){
  $(document).ready(function() {
      //ajax 送表單
    $.ajax({
        type: "POST",
        url: "del_coupon.php",
        dataType: "text",
        success: function(data) {
        location.reload();
          //alert("完成結帳。");

        }

      })
    })
}
  </script>
  <style>
.store_1::-webkit-input-placeholder { /* Chrome/Opera/Safari */
  color: #fff;}
@media (max-width: 500px){
 .orange  {
  margin-top: 10px
 }
 .transfer {
  padding-left: 11px
 }
}
@media (max-width: 330px){
  #touser{width: 100%}
  #cuser{width: 100%!important;margin-left: 0px!important;margin-top: 5px}
  .transfer {
  padding-left: 0px;
  padding: 5px
 }
}
</style>
</head>
<body  style="min-height: 800px;background-color: #f6f6f6">
<div class="mebr_top" align="center" style="background:#84DDCB;color: #fff;padding: 13px ">
 <div style="font-size: 22px"><span ><?php echo $m_username;?></span><span>的抵用券本子</span></div>
 <a href="person_inquire.php"><span style="position: absolute; left: 20px; top:5px;color: #fff;font-size: 30px;font-weight: 300;z-index: 999"><</span></a>
</div>

<div class="search_bt" align="center" >


<form action="person_coupon.php" method="get" id="form1">
  <div  align="center" style="margin-top: 10px">
    <div style="width: 100%" align="center">
      <input class="store_1" type="text" name="store"  placeholder="輸入店家名稱" value="<?php echo $_GET['store'];?>" style="height: 40px;border:1px solid #fff;-webkit-appearance:none;padding: 5px;line-height: 40px;border-radius: 6px;color: #fff;background-color: #A1A1A1">
      <button type="submit" style="margin-left: 8px;width: 110px;margin-right: 6px" class="date_but">查詢</button>
      
      <input type="hidden" id="u_number" name="u_number" value="">
      <button type="button" style="background: #e3bc8d;width: 140px;font-size: 15px;margin-right: 2px" class="orange date_but open_transfer">查看過期抵用券</button>
      <input type="hidden" name="sort" id="sort" value="">
      <button type="submit" style="background: #e3bc8d;width: 140px;font-size: 15px" class="orange  date_but" onClick="pop()">依有效期限排序</button>
      
    </div>
  </div> 
  <div align="center" style="margin-top: 10px" class="transfer">
    <input class="store_1" type="text" id="touser" name="touser" placeholder="轉讓抵用券對象" style="height: 40px;border:1px solid #fff;-webkit-appearance:none;padding: 5px;line-height: 40px;border-radius: 6px;color: #fff;background-color: #A1A1A1">
      <button type="button" id="cuser" style="margin-left: 8px;width: 110px;margin-right: 15px" class="date_but">確認轉讓</button>
      <div id="chu" style="font-size: 12px;color: red;"></div>
  </div>
<form>

</div>

<?php
if($_GET['store'] != ''){?>
  <!--商店抵用券總額-->
<div class="search_table table-responsive " align="center" style="overflow-y: visible;white-space: nowrap;padding:0px;padding-top: 10px">
    <table align="center" class="table coupon_table" style="background: #fff;border-radius: 10px"> 
    <tr  style="background-color: #4ab3a6;color: #fff;">
      <th >抵用券額</th>
      <th >優惠店家</th>
    </tr>
    <?php 
	$a = false;
	
    if($store_num != 0){
        do{

          $discount = $row_store['discount'];
		  $nick_nick = $row_store['s_nick'];
          $discount_sum = $discount_sum+$discount;
			if(strcmp($s_nick,$nick_nick)!=0){ //比對是否相等
					$a= true;
				}
				
        }while($row_store = mysql_fetch_assoc($Restore));
      ?>
      <tr class="search_tr"  style="text-align:center; background-color: #fff">
          <td ><?php echo $discount_sum;?></td>
          <td ><?php if($a){
		  echo '';
		  }else{
		  echo $s_nick;
		  }?></td>
      </tr>
    <?php 
    }
      ?>
    </table>
</div>
<?php 
}
?>
<script>
var cbcs = new Array();
var q = 0;
function ff(v){
  var k;
  var cb = document.getElementsByName('cb');
  for(k=0;k<cb.length;k++)
  {
    if(cb[k].checked)
    {
        var cid = $('.cb'+v).attr("data-value");
        cbcs[q] = cid;

    }
  }
  q++;
  //alert(cbcs);
}
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
            $("#chu").html('');
            $("#u_number").val(data);
            var u_number = $('#u_number').val();
            if(cbcs != ""){
              $.ajax({
                type:"POST",
                url:"transfer_c.php",
                data:{
                  cbcs:cbcs,
                  u_number:u_number
                },
                dataType:"text",
                success: function(data){
                  //alert(data);
                  alert('轉讓成功!!');
                  location.reload();
                }
              })
            }
          }else{
            $("#chu").html('沒有此帳號');
          }
          
        }
      })
    }
  })
});
</script>


<div class="search_table table-responsive " align="center" style="overflow-y: visible;white-space: nowrap;padding:0px;padding-top: 10px">

    <table align="center" class="table coupon_table" style="background: #fff;border-radius: 10px">
    
    <tr  style="background-color: #4ab3a6;color: #fff ">
      <th></th>
      <th >有效期限</th>
      <th >抵用券額</th>
      <th >優惠店家</th>
      <th >使用狀態</th>
    </tr>
    <?php if($row_num != 0){
      do{
        $cid = $row_str['ID']?>
        <tr class="search_tr"  style="text-align:center; background-color: #fff">
          <td><input type="checkbox" id='cb' name='cb' data-value="<?php echo $cid;?>" class='cb<?php echo $row_num;?>' onclick="ff(<?php echo $row_num;?>)"></td>
          <td><?php echo $row_str['effective_date'];?></td>
          <td><?php echo $row_str['discount'];?></td>
          <td><?php echo $row_str['s_nick'];?></td>
          <?php if($row_str['complete']==1){?>
            <td class="table_br"><span style="color: #22a363">已使用</span></td>
            <?php }else{?>
            <td  class="table_br"><span style="color: #ff5e59">未使用</span></td>
            <?php }?>
        </tr>
      <?php 
      $row_num = $row_num - 1;
      }while($row_str = mysql_fetch_assoc($Restr));}?>
    </table>
</div>
</body>
</html>

<?php
mysql_select_db($database_lp, $lp);
$query_costr = sprintf("SELECT * FROM coupon WHERE p_user ='$m_username' && p_number ='$number' && effective_date < '$date' order by complete ASC, effective_date ASC, s_nick ASC");
$Restrco = mysql_query($query_costr, $lp) or die(mysql_error());
$row_coustr = mysql_fetch_assoc($Restrco);
$row_counum = mysql_num_rows($Restrco);

?>
<div id="box_transfer" style="background:#f6f6f6;height:100%;width:100%;top:100%; position: fixed;z-index:1001;display: none;">
  <div class="mebr_top" align="center" style="background:#84DDCB;color: #fff;padding: 13px;height: 50px ">
<div><span style="font-size: 22px">過期的抵用券</span><span style="position: absolute; left: 33px; top:5px;color: #fff;font-size: 30px;font-weight: 300;z-index: 999" class="btn_transfer">X</span></div>
</div>

  <!-- -->
<div align="center"><button type="button" style="background: #487be5;width: 84.5%;margin-top: 12px" class="date_but" onclick="cel()">清空</button></div>

  <div class="search_table table-responsive " align="center" style="overflow-y: visible;white-space: nowrap;padding:0px;padding-top: 10px">
    <table align="center" class="table coupon_table" style="background-color: #fff;border-radius: 10px">
    
    <tr  style="background-color: #4ab3a6;color: #fff ">
      <th >有效期限</th>
      <th >抵用券額</th>
      <th >優惠店家</th>
      <th >使用狀態</th>
    </tr>
    <?php if($row_counum != 0){
      do{?>
        <tr class="search_tr"  style="text-align:center; background-color: #fff">
          <td><?php echo $row_coustr['effective_date'];?></td>
          <td><?php echo $row_coustr['discount'];?></td>
          <td><?php echo $row_coustr['s_nick'];?></td>
          <?php if($row_coustr['complete']==1){?>
            <td ><span style="color: #22a363">已使用</span></td>
            <?php }else{?>
            <td><span style="color: #ff5e59">未使用</span></td>
            <?php }?>
        </tr>
      <?php }while($row_coustr = mysql_fetch_assoc($Restrco));}?>
    </table>
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
    $("#box_transfer").animate({top:"100%"},function(){
      $("#box_transfer").css("display","none");
    });
    
  });
 
});
</script>

