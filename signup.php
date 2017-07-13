<?php 
require_once('Connections/lp.php');mysql_query("set names utf8");require_once('Connections/sc.php');mysql_query("set names utf8");?>
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

require_once('lifepay_user.php');

$date = date("Y-m-d");
?>

<!DOCTYPE html>
<html>
<head>
  <title>LIFE PAY</title>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="css/test.css">

</head>
<body >
<script>
var checkpoint;
function check(checkpoint){
  var accont = document.getElementById('accont');
  var password = document.getElementById('password');
  var password2 = document.getElementById('password2');
  var st_name = document.getElementById('st_name');
  var usage_fee = document.getElementById('usage_fee');
  var number1 = document.getElementById('number1');
  var number2 = document.getElementById('number2');
  var dis = document.getElementById('dis');
  var disexp = document.getElementById('disexp');
  var industry = document.getElementById('industry');
  var email = document.getElementById('email');
  var contract = document.getElementById('contract');
  var address = document.getElementById('address');
  var phone = document.getElementById('phone');
  var host = document.getElementById('host');


//alert(contract.value+'contract');
	switch(checkpoint){
		case 1:
		  if(accont.value == ""){
			document.getElementById('accont').style.background="pink";
		  }else if(password.value == ""){
			document.getElementById('password').style.background="pink";
		  }else if(password2.value == ""){
			document.getElementById('password2').style.background="pink";
		  }else {
			step_process(1 ,2);
		  }
			break;
		case 2:
		  if(st_name.value == ""){
			document.getElementById('st_name').style.background="pink";
		  }else if(address.value == ""){
			document.getElementById('address').style.background="pink";
		  }else if(industry.value == ""){
			document.getElementById('industry').style.background="pink";
		  }else {
			step_process(2 ,3);
		  }break;
		case 3:
		  if(email.value == ""){
			document.getElementById('email').style.background="pink";
		  }else if(host.value == ""){
			document.getElementById('host').style.background="pink";
		  }else if(phone.value == ""){
			document.getElementById('phone').style.background="pink";
		  }else {
			step_process(3 ,4);
		  }break;
		case 4:
			  $(document).ready(function() {
				//ajax 送表單
				$.ajax
				  ({
					type: "POST",
					url: "signup_in.php",
					dataType: "text",
					data: {
					  accont: $("#accont").val(),
					  password: $("#password").val(),
					  password2:$("#password2").val(),
					  st_name: $("#st_name").val(),
					  industry: $("#industry").val(),
					  email: $("#email").val(),
					  usage_fee: $("#usage_fee").val(),
					  number1: $("#number1").val(),
					  number2: $("#number2").val(),
					  dis: $("#dis").val(),
					  disexp: $("#disexp").val(),
					  contract: $("#contract").val(),
					  address: $("#address").val(),
					  phone: $("#phone").val(),
					  host: $("#host").val()
					},
					success: function(data) {
					  if(data == 1){
						alert("帳號重複!!");
						document.getElementById('err').style.display="";
						document.getElementById('suc').style.display="none";
					  }else if(data == 2){
						document.getElementById('err').style.display="none";
						document.getElementById('suc').style.display="";
						alert("開通完成!!");
					  }
					}
				  })
				});
		  break;
	}
}

function ValidateNumber(e, pnumber){
  if (!/^\d+$/.test(pnumber))
  {
    e.value = /^\d+/.exec(e.value);
  }
  return false;

  if(e.value > 100){
    document.getElementById('bt').style.display="none";
  }
}

function step_process(from, to, dir) {
  if (typeof(dir) === 'undefined') dir = 'asc';
  var old_move = '';
  var new_start = '';

  var speed = 500;

  if (dir == 'asc') {
      old_move = '-';
      new_start = '';
  } else if (dir == 'desc') {
      old_move = '';
      new_start = '-';
  }

  $('#block'+from).animate({left: old_move+'100%'}, speed, function() {
      $(this).css({left: '100%', 'background-color':'transparent', 'z-index':'2'}); 
  });
  $('#block'+to).css({'z-index': '3', left:new_start+'100%'}).animate({left: '0%'}, speed, function() {
      $(this).css({'z-index':'2'}); 
  });

  if (Math.abs(from-to) === 1) {
      // Next Step
      if (from < to) $("#step"+from).addClass('complete').removeClass('current');
      else $("#step"+from).removeClass('complete').removeClass('current');
      var width = (parseInt(to) - 1) * 20;
      $(".progress_bar").find('.current_steps').animate({'width': width+'%'}, speed, function() {
          $("#step"+to).removeClass('complete').addClass('current');
      });
  } else {
      // Move to Step
      var steps = Math.abs(from-to);
      var step_speed = speed / steps;
      if (from < to) {
          move_to_step(from, to, 'asc', step_speed);
      } else {
          move_to_step(from, to, 'desc', step_speed);
      }
  }
}
  
function move_to_step(step, end, dir, step_speed) {
  if (dir == 'asc') {
      $("#step"+step).addClass('complete').removeClass('current');
      var width = (parseInt(step+1) - 1) * 20;
      $(".progress_bar").find('.current_steps').animate({'width': width+'%'}, step_speed, function() {
          $("#step"+(step+1)).removeClass('complete').addClass('current');
          if (step+1 < end) move_to_step((step+1), end, dir, step_speed);
      });
  } else {
      $("#step"+step).removeClass('complete').removeClass('current');
      var width = (parseInt(step-1) - 1) * 20;
      $(".progress_bar").find('.current_steps').animate({'width': width+'%'}, step_speed, function() {
          $("#step"+(step-1)).removeClass('complete').addClass('current');
          if (step-1 > end) move_to_step((step-1), end, dir, step_speed);
      });
  }
}

$(document).ready(function() {
  $("body").on("click", ".progress_bar .step.complete", function() {
      var from = $(this).parent().find('.current').data('step');
      var to = $(this).data('step');
      var dir = "desc";
      if (from < to) dir = "asc";

      step_process(from, to, dir);
  });
});
</script>

<div class="container" style="padding-top: 20px">
  <h1 style="font-weight: 300">開通帳號</h1>
    <form id="form1" name="form1" method="post" action="signup_in.php">
      <div class="progress_bar">
        <hr class="all_steps" style="margin-bottom: 5px">
        <hr class="current_steps" style="margin-bottom: 5px">
          <div class="step current" id="step1" data-step="1">
            Step 1
          </div>
          <div class="step" id="step2" data-step="2">
            Step 2
          </div>
          <div class="step" id="step3" data-step="3">
            Step 3
          </div>
          <div class="step" id="step4" data-step="4">
            Step 4
          </div>
          <div class="step" id="step5" data-step="5">
            Step 5
          </div>
      </div>
      <div class="wrapper">
        <div id="blocks">
          <div class="block" id="block1" style="left: 0%;">
            <div class="wrap">
              <div align="center">

                <input name="accont" id="accont" type="text" autocomplete="off" placeholder="帳號 : " style="width: 200px;text-align: left;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;margin-top: 20px;padding: 7px;margin-left: 0px"><br>
                <input name="password" id="password" type="password" autocomplete="off" placeholder="密碼 : " style="width: 200px;text-align: left;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;margin-top: 20px;padding: 7px"><br>
                <input name="password2" id="password2" type="password"  autocomplete="off" placeholder="二級密碼 : " style="width: 200px;text-align: left;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;margin-top: 20px;padding: 7px"><br>
                <div style="margin-top: 20px">
                <input type="button" value="下一步" style="width: 200px;margin-top: 18px;display: block;margin-bottom: 15px;height: 40px;border:0px;border-radius: 6px;background: #6d4ede;color: #fff"  onclick="check(1)">
              </div>

            </div>

            </div>
          </div>
          <div class="block" id="block2">
            <div class="wrap">
              <div align="center">
                <input name="st_name" id="st_name" type="text"  autocomplete="off" placeholder="店名 : " style="width: 200px;text-align: left;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;margin-top: 20px;padding: 7px;margin-left: 0px"><br>
                <input name="address" id="address" type="text"  autocomplete="off" placeholder="地址 : (地圖定位用)" style="width: 200px;text-align: left;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;margin-top: 20px;padding: 7px;margin-left: 0px"><br>
                <select name="industry" id="industry" placeholder="產業 : " style="width: 200px;text-align: left;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;margin-top: 20px">
                  <option value="">請選擇產業</option>
                  <option value="餐飲業">餐飲業</option>
                  <option value="服飾業">服飾業</option>
                  <option value="資訊業">資訊業</option>
                  <option value="飯店業">飯店業</option>
                  <option value="零售業">零售業</option>
                  <option value="其他">其他</option>
                </select><br>
                <div style="margin-top: 20px">
                  <input type="button" value="下一步" style="width: 200px;margin-top: 18px;display: block;margin-bottom: 15px;height: 40px;border:0px;border-radius: 6px;background: #6d4ede;color: #fff"  onclick="check(2)">
                </div>  

              </div>
            </div>
          </div>
          <div class="block" id="block3">
            <div class="wrap">
              <div align="center">
                <input name="email" id="email" type="email"  autocomplete="off" placeholder="信箱  : " style="width: 200px;text-align: left;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;margin-top: 20px;padding: 7px"><br>
                <input name="host" id="host" type="text"  autocomplete="off" placeholder="負責人 : " style="width: 200px;text-align: left;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;margin-top: 20px;padding: 7px;margin-left: 0px"><br>
                <input name="phone" id="phone" type="tel"  autocomplete="off" placeholder="店家電話 : " style="width: 200px;text-align: left;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;margin-top: 20px;padding: 7px;margin-left: 0px"><br>
                <div style="margin-top: 20px">
                  <input type="button" value="還差一點" style="width: 200px;margin-top: 18px;display: block;margin-bottom: 15px;height: 40px;border:0px;border-radius: 6px;background: #6d4ede;color: #fff"  onclick="check(3)">
                </div>

              </div>
            </div>
          </div>
          <div class="block" id="block4">
            <div class="wrap">
              <div align="center">
              <br>營業時間<br>
              <input name="number1" id="number1" type="time" style="width: 200px;text-align: left;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;margin-top: 20px"><br>至
              <input name="number2" id="number2" type="time" style="width: 200px;text-align: left;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;margin-top: 20px"><br>
              <input name="usage_fee" id="usage_fee" type="number"  autocomplete="off" min="0" max="100" placeholder="使用抽成(%) : " style="width: 200px;text-align: left;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;margin-top: 20px;padding: 7px" onKeyUp="ValidateNumber(this,value)"><br>
              <div style="margin-top: 20px">
              <input type="button" value="快完成囉" style="width: 200px;margin-top: 18px;display: block;margin-bottom: 15px;height: 40px;border:0px;border-radius: 6px;background: #6d4ede;color: #fff" onClick="step_process(4, 5)">
              </div>

              </div>	
            </div>
          </div>
          <div class="block" id="block5">
            <div class="wrap">
              <div align="center">
              <input name="dis" id="dis" type="number"  autocomplete="off" min="0" max="100" placeholder="折扣券%數 : " onKeyUp="ValidateNumber(this,value)" style="width: 200px;text-align: left;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;margin-top: 20px;padding: 7px"><br> 
              <span>折扣券期限 :</span>
              <input name="disexp" id="disexp" type="date" min="<?php echo $date;?>" style="width: 200px;text-align: left;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;margin-top: 20px"><br>
              <input name="contract" id="contract" type="hidden" min="<?php echo $date;?>" style="width: 200px;text-align: left;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;margin-top: 20px"><br>
              <div align="center" id="suc" style="display:none;color:red;">開通完成!!</div>
              <div align="center" id="err" style="display:none;color:red;">帳號重複!!</div>
              <input type="button" value="提交" style="width: 200px;margin-top: 18px;display: block;margin-bottom: 15px;height: 40px;border:0px;border-radius: 6px;background: #6d4ede;color: #fff" onClick="check(4)">
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>


</body>
</html>