<?php 
require_once('Connections/lp.php');require_once('Connections/sc.php');mysql_query("set names 'utf8'");

session_start();
if ($_SESSION['MM_Username'] == ""){header(sprintf("Location: life_pay.php"));exit;
}else{
  $m_username = $_SESSION['mem'];//登入帳號
  $m_nick = $_SESSION['nick'];//登入暱稱
  $number = $_SESSION['number'];
  $date = $_SESSION['date'];
  $time = $_SESSION['time'];
}

mysql_select_db($database_lp, $lp);
$query_str = sprintf("SELECT * FROM complete WHERE p_user ='$m_username' && p_nick = '$m_nick' && date = '$date' && time = '$time' ");
$Restr = mysql_query($query_str, $lp) or die(mysql_error());
$row_str = mysql_fetch_assoc($Restr);


$mkey = $_GET['a'];
$data = date("Y-m-d");
//$time2 = date("H:i:s");

//結帳執行
$confirm_st = "UPDATE st_record SET confirm='2',u_number='$number' WHERE verification ='$mkey' ";
$confirm = mysql_query($confirm_st, $lp) or die(mysql_error());
?>
<!DOCTYPE html>
<html>
<head>
<title>LIFE PAY</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css">
  <link rel="stylesheet" href="css/rate.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.1/angular.min.js"></script>
  <script src="js/syn.js"></script>
  <!--<link rel="stylesheet" href="css/jquery.bxslider.css">
  <script src="js/jquery.bxslider.min.js" type="text/javascript"></script>-->
  <script>
  function check(){
      var p_user = "<?php echo $m_username;?>";
      var p_nick = "<?php echo $m_nick;?>";
      var mkey = "<?php echo $mkey;?>";
      $(document).ready(function() {
        //ajax 送表單
          $.ajax({
            type: "POST",
            url: "refresh.php",
            dataType: "text",
            async: false, //啟用同步請求
            data: {
              p_user:p_user,
              p_nick:p_nick,
              mkey:mkey,
            },
            success: function(data) {
              var data = JSON.parse(data);
              var confirm = data.confirm;
              var next_discount = data.next_discount;
              var m_nick = data.m_nick;
              var total_cost = data.total_cost;
              var paid = data.paid;
              var ID = data.ID;
              if(confirm != 1){
                setTimeout(function(){check();}, 1000);
              }else if(confirm == 1){
                $('#load').hide();
                $('#succ').css("display","");
                $('#nick').text(m_nick);
                $('#total').text(total_cost);
                $('#paid').text(paid);
                $('#pc').text(next_discount);
                $('#ID').val(ID);
              }
              
            }

          })
      });
  }
  
  check();

  </script>
  <style>
  @media (min-width: 320px) {
      .loading {width: 100%;margin-left: 0%}
    }
    @media (min-width: 414px) {
      .loading {width: 100%;margin-left: 0%}
    }
    @media (min-width: 768px) {
      .loading {width: 80%;margin-left: 10%}
    }
    @media (min-width: 1200px) {
      .loading {width: 50%;margin-left: 25%}
    }
    .rating > label {color: #fff}
  </style>
  </head>
<body style="background-color: #84ddcb">
<div class="mebr_top" align="center">
  <img src="img/life_pay_logo-01.png" width="220px" alt="">
</div>
<div id="load">

  <div align="center" style="font-size: 25px; font-weight: 700;padding: 20px;background: #fff;margin-top: 15px;color: #666">商家輸入折扣</div>
  <div ng-app="syn" ng-controller="modulec">
    <h1>{{ second_controller }}</h1>
  </div>
  
  
  <!--<div>
    <ul class="bxslider">
      <li><img src="img/04.jpg" /></li>
      <li><img src="img/02.jpg" /></li>
    </ul>
  </div> -->
  <div class="col-lg-12 col-md-12 col-xs-12 st_pe_foot" style="height: 48px; background-color:#efefef; width: 100% ;position: fixed;">
    <img src="img/login_footlogo-01.png" width="320px" style="margin-top: 10px" alt="">
  </div>
</div>

  <div id="succ" style="display: none;">
    <div class="container_pay " align="center" style="overflow-y: visible">
    
      <table align="center" style="width: 88%;margin: 10px auto 0px auto;line-height: 45px;">
        <tr>
          <td align="center" colspan="3" class="sys_td" style="padding: 20px;font-size: 30px">完成交易</td>
        </tr>
        <tr style="text-align:center">
          <td>親愛的 : <span id="nick"></span></td>
        </tr>
        <tr style="text-align:center">
          <td>感謝您的光臨。</td>
        </tr>
        <tr  style="text-align:center">
          <td>消費金額：<span id="total"></span></td>
        </tr>
        <tr  style="text-align:center">
          <td>支付金額：<span id="paid"></span></td>
     
        </tr>
        <tr style="text-align:center">
          <td>贈送抵用券<span style="color: purple;font-size: 22px"><a href="person_coupon.php"><span id="pc"></span></a></span></td>
        </tr>
        <tr style="text-align:center">
          <td>給商店來個評價吧</td>
        </tr>
        <tr style="text-align:center">
          <td>
            <input type="hidden" id="ID" value="<?php echo $row_str['ID']; ?>">
            <fieldset id="rate" class="rating" style="float: none;margin:0px auto;height: 40px;margin-top: -15px;">
              <input type="radio" id="star5" name="rating" value="5" /><label class = "full" for="star5" title="夭壽讚"></label>
              <input type="radio" id="star4half" name="rating" value="4.5" /><label class="half" for="star4half" title="非常好"></label>
              <input type="radio" id="star4" name="rating" value="4" /><label class = "full" for="star4" title="很棒"></label>
              <input type="radio" id="star3half" name="rating" value="3.5" /><label class="half" for="star3half" title="還行啦"></label>
              <input type="radio" id="star3" name="rating" value="3" /><label class = "full" for="star3" title="普普通通"></label>
              <input type="radio" id="star2half" name="rating" value="2.5" /><label class="half" for="star2half" title="有點糟"></label>
              <input type="radio" id="star2" name="rating" value="2" /><label class = "full" for="star2" title="蠻糟的"></label>
              <input type="radio" id="star1half" name="rating" value="1.5" /><label class="half" for="star1half" title="有點悲劇"></label>
              <input type="radio" id="star1" name="rating" value="1" /><label class = "full" for="star1" title="呃...."></label>
              <input type="radio" id="starhalf" name="rating" value="0.5" /><label class="half" for="starhalf" title="!@#$%^&*"></label>
            </fieldset>
          </td>
        </tr>
        <tr style="text-align:center">
          <td>
        <div class="ex_span" style="font-size:21px;line-height: 35px">評價內容</div>
            <textarea id="rt_content" class="rt_content" style="width: 290px;text-align: left;border:0px;background: #fff;line-height: 35px;border-radius: 6px;font-size: 20px;"></textarea>
            <input id="rt_date" class="rt_date" type="hidden" value="<?php echo $date;?>" />
            </td>
        </tr>
        <tr style="text-align:center">
          <td>
            <input type="button" class="sys_bt" onClick="starttorate()" style="width:75%;background: #487be5;border: 0px;border-radius: 30px;height: 40px;line-height: 40px;margin-bottom: 15px" value="送出評價">
        </div>
          </td>
        </tr>
        <tr  style="text-align:center">
          <td colspan="3"><a href="person_main.php"><input class="sys_bt" type="button" value="回到主畫面" style="width: 75%;border: 0px;border-radius: 30px;height: 40px;line-height: 40px;color: #4AB3A6;background-color: #fff"></a></td>
        </tr>
      </table>
      <script>
     function starttorate(){
        var rate = document.getElementsByName('rating');
    		var rt_content = document.getElementById('rt_content').value;
    		var date = document.getElementById('rt_date').value;
        var id = $('#ID').val();
        var rt_level;
        for (var i = 0; i < rate.length; i++) {
          if (rate[i].type === "radio" && rate[i].checked) {
            rt_level = rate[i].value;
          }
        }
        if(rt_level){
        //ajax 送表單
          $.ajax({
              type: "POST",
              url: "rate_data.php",
              dataType: "text",
              data: {
                rt_level: rt_level,
          rt_content: rt_content,
          date: date,
                id: id,
              },
              success: function(data) {
                alert('評論成功!');
              }
            })
        }else{
          alert('請給予星級評價!');
        }
        }
      
      </script>

    </div>
  </div>
  


</body>
</html>