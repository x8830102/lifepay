<?php require_once('Connections/lp.php');mysql_query("set names utf8");require_once('Connections/sc.php');mysql_query("set names utf8");?>
<?php 
session_start();
if ($_SESSION['MM_Username'] == ""){header(sprintf("Location: store_login.php"));exit;
}else{
  $mem = $_SESSION['mem'];
  $user_name = $_SESSION['nick'];
  $s_number = $_SESSION['number'];//驗證帳號
}

//如果沒有列數就表示不是商店端的帳號
mysql_select_db($database_lp, $lp);
$SQL = sprintf("SELECT * FROM lf_user WHERE number ='$s_number' ");
$conn = mysql_query($SQL, $lp) or die(mysql_error());
$row = mysql_fetch_assoc($conn);
$total = mysql_num_rows($conn);
if($total == 0){
    //表示是使用者連過來的
    mysql_select_db($database_sc, $sc);
    $query_user = sprintf("SELECT * FROM memberdata WHERE number ='$s_number' ");
    $Reuser = mysql_query($query_user, $sc) or die(mysql_error());
    $row_user = mysql_fetch_assoc($Reuser);
    $total_user = mysql_num_rows($Reuser);
    if($total_user != ''){
      header(sprintf("Location: logout.php"));exit;
    }
}

$moneeey = $_POST['moneeey'];
$key = $_POST['key'];
$discount =$_POST['diss'];
//echo $key;exit;

if($key !='')
{
$_SESSION['kk'] =$key;
}
$key =  $_SESSION['kk'];

$mkey = md5($key);

if($key)
{

	mysql_select_db($database_lp, $lp);
	$inser_strecord = "INSERT IGNORE INTO st_record (ID,s_user,s_nick,s_number,sum,discount,verification,data,confirm)value(NULL,'$mem','$user_name','$s_number','$moneeey','$discount','$mkey','$key','0')";
	$inser = mysql_query($inser_strecord, $lp) or die(mysql_error());

}
//echo $moneeey;echo $m_username;
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
  <link rel="stylesheet" type="text/css" href="dist/sweetalert.css">
  <link rel="stylesheet" href="css/iziToast.min.css">
  <script src="dist/sweetalert.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="js/jquery.api.google.js"></script>
  <script>
  function check(){

      var mkey = "<?php echo $mkey;?>";
      $(document).ready(function() {
      //ajax 送表單
      $.ajax({
          type: "POST",
          url: "refresh.php",
          dataType: "text",
          data: {
            mkey:mkey
          },
          success: function(data) {
            if(data != 2){
              setTimeout(function(){check();}, 1000);
            }else if(data == 2){
              window.location.href='checkout.php?a='+mkey;
            }
            
          }

        })
      })
  }

  
  check();

  </script>
</head>

<body style="background-color:#f5c1ad ">

<div class="mebr_top" align="center">
  <img src="img/life_pay_logo-01.png" width="220px" alt="">
</div>
<div class="navtop" >
<div class="col-lg-6 col-md-6 col-xs-6" align="center" style="top: -2px;border-right: 2px solid #fff"><a href="store_search.php"><img src="img/long_search-01.png" alt="" width="120px" ></a></div>
  <div class="col-lg-6 col-md-6 col-xs-6" align="center" style="top: -2px"><a href="store_checkout.php"><img src="img/long_checkout-01.png" alt="" width="120px"></a></div>
</div>
    <div  style="padding: 15px;transform: translateY(10%);">
      <div align="center" style="width: 220px;margin: auto;padding: 10px">
        <img id='qrcode' src='#' style="width: 100%;border:4px solid #666" />
        <script>
          content = encodeURIComponent('http://lifelink.cc/life_pay/person_main.php?a=<?php echo $mkey;?>');

          $("#qrcode").attr("src","http://chart.apis.google.com/chart?cht=qr&chl="+ content +"&chs=512x512");
        </script>
        <div class="search_bt" align="center" style="width:200px;margin-top: 20px">
		  <div  >
			<a href="http://lifelink.cc/life_pay/person_main.php?a=<?php echo $mkey;?>"><input type="button" value="life_pay07" style="background: #fff; color: #ee9078;border: 0px ;border-radius: 50px;padding: 4px 50px"></input></a>
		  </div>
		  <div style="margin-top:15px;">
			<a href="store_checkout.php"><input type="button" value="繼續交易" style="background: #ee9078; color: #fff;border: 0px ;border-radius: 50px;padding: 4px 50px"></a>
		  </div>
        </div> 
      </div>
	</div>
<div class="col-lg-12 col-md-12 col-xs-12 st_pe_foot" style="height: 48px; background-color:#efefef; width: 100% ;">
   <img src="img/login_footlogo-01.png" width="320px" style="margin-top: 10px" alt="">
 </div>

</body>
</html>
