<?php 
require_once('Connections/sc.php');mysql_query("set names 'utf8'");
require_once('Connections/lp.php');mysql_query("set names 'utf8'");
?>
<?php session_start();
if ($_SESSION['MM_Username'] == ""){header(sprintf("Location: life_pay.php"));exit;
}else{
  $m_username = $_SESSION['mem'];
  $m_nick = $_SESSION['nick'];
  $number = $_SESSION['number'];
}

$mkey = $_POST['mkey'];
$ct = $_POST['ct'];
$gt = $_POST['gt'];
$cash = $_POST['cash'];
$c_re = $_POST['c'];
$g_re = $_POST['g'];
$coupon_id = $_POST['coupon_id'];
$discount = $_POST['dis'];
$coupon = $_POST['coupon'];
$s_number = $_POST['s_number'];
$s_user = $_POST['s_user'];


mysql_select_db($database_lp, $lp);
$query_str = sprintf("SELECT * FROM st_record WHERE verification ='$mkey' ");
$Restr = mysql_query($query_str, $lp) or die(mysql_error());
$row_str = mysql_fetch_assoc($Restr);
$st_nm = $row_str['s_nick'];
$c =  $row_str['sum'];

if($row_str == ""){header(sprintf("Location: person_main.php"));exit;
}


//取得登入者二級密碼
mysql_select_db($database_sc, $sc);
$query_user =sprintf("SELECT * FROM memberdata WHERE number ='$number' ");
$Reuser = mysql_query($query_user, $sc) or die(mysql_error());
$row_user = mysql_fetch_assoc($Reuser);
$pas_two = $row_user['m_passtoo'];

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>

function check(){
	var pas_two = document.getElementById('pas2');
	var pas_twoo = "<?php echo $pas_two;?>";


	if(pas_two.value != pas_twoo)
	{
    document.getElementById('pas2').style.background="pink";
	}else{
    document.getElementById('con_bt').disabled=true;
    document.getElementById('form1').submit();
	}
}


function ValidateNumber(e, pnumber){
  if (!/^\d+$/.test(pnumber))
  {
  e.value = /^\d+/.exec(e.value);
  }
  return false;
}

function goBack() {
    window.history.back();
}
</script>
  <style>
  .search_table th ,.search_table td{
  text-align: center;
  line-height: 17px!important;
  }
  </style>
</head>
<body style="background-color:#84ddcb ">


<div class="mebr_top" align="center">
  <img src="img/life_pay_logo-01.png" width="220px" alt="">
</div>
  <div class="search_table table-responsive " align="center" style="overflow-y: visible;white-space: nowrap;padding-top: 10px">
    
      <table align="center" class="table coupon_table" style="width: 100%;">
        <form method="post" id="form1" action="data_in.php?a=<?php echo $mkey;?>">
            <tr >
              <td style="float: left;">結帳商家：</td>
              <td class="" colspan="2" style="text-align: left;"><?php echo $st_nm;?></td>
              <input type="hidden" name="st_nm" value="<?php echo $st_nm;?>">
            </tr>
            <tr >
              <td style="float: left;">登入帳號：</td>
              <td class="" colspan="2" style="text-align: left;"><?php echo $m_username;?></td>
            </tr>
            <tr>
              <td style="float: left;">名稱暱稱：</td>
              <td class="" colspan="2" style="text-align: left;"><?php echo $m_nick;?></td>
            </tr>
            <tr >
              <td style="float: left;">本次消費金額：</td>
              <td class="" colspan="2" style="text-align: left;"><?php echo number_format($c);?></td>
              <input type="hidden" name="tol_cs" value="<?php echo $c;?>">
            </tr>
            <tr >
              <td style="float: left;">抵用券抵用額：</td>
              <td class="" style="text-align: left;"><?php if($discount != ''){echo number_format($discount);}else{echo 0;}?></td>
              <input type="hidden" name="discount" value="<?php if($discount !=''){echo $discount;}else{echo '0';}?>">
            </tr>
            <tr >
              <td style="float: left;">消費積分餘額：<input type="text" name="gt" value="<?php if($gt == 0){
                  echo '0';
                }else{echo number_format($gt);}?>" readonly="readonly" style="border: 0px;width: 80px;height: 28px;line-height: 28px;text-align: center;background-color: #84ddcb;overflow: auto;"></td>
              <td class="" colspan="2" style="text-align: left;"><span style="height: 50px;line-height: 27px"><?php if($g_re == null){ echo '0</span>';}else{echo number_format($g_re);};?></td>
              <input type="hidden" name="gt" value="<?php echo $gt;?>">
            </tr>
            
            <tr >
              <td style="float: left;">串串積分餘額：<input type="text" name="ct" value="<?php if($ct == 0){
                  echo '0';
                }else{echo number_format($ct);}?>" readonly="readonly" style="border: 0px;width: 80px;height: 28px;line-height: 28px;text-align: center;background: #84ddcb;overflow: auto;"></td>
              <td class="" colspan="2" style="text-align: left;"><span style="height: 50px;line-height: 27px"><?php if($c_re == null){ echo '0</span>';}else{echo number_format($c_re);}?></td>
              <input type="hidden" name="ct" value="<?php echo $ct;?>">
            </tr>
            
            <tr>
              <td style="float: left;">應付現金或信用卡：</td>
              <td class="" colspan="2" style="text-align: left;"><?php echo number_format($cash);?></td>
              <input type="hidden" name="spend" value="<?php echo $cash;?>">
            </tr>
            <tr >
              <input type="hidden" name="c" value="<?php echo $c_re;?>">
            </tr>
            <tr>
              <input type="hidden" name="g" value="<?php echo $g_re;?>">
            </tr>
            <tr >
              <input type="hidden" name="coupon_id" value="<?php echo $coupon_id;?>">
              <input type="hidden" name="s_user" value="<?php echo $s_user;?>">
            </tr>
            <tr>
              <td style="float: left;">請輸入二級密碼：</td>
              <td class="pas2" colspan="2" style="text-align: left;">
              <input type="password" name="pas2" id="pas2" value="" onKeyUp="value=value.replace(/[\W]/g,'') " style="width: 120px;height: 28px"></td>
            </tr>
            <input type="hidden" name="s_number" value="<?php echo $s_number;?>">
            <tr>
              <td colspan="3">
              <input class="sys_bt" type="button" id="con_bt" value="確認結帳" style="display: inline;margin: 5px;width: 80%; background: #fff;border:0px;border-radius: 20px;height: 38px;color: #4ab3a6;font-weight: 800"  onClick="check()"><br>
              <button class="sys_bt" style="display: inline;margin: 5px;width: 80%; background: #4ab3a6;border:0px;border-radius: 20px;height: 38px;color:#fff ;font-weight: 800"  onClick="goBack()">上一步</button>
              </td>
            </tr>
            
            
        </form>
      </table>
  </div>
</body>
</html>
