<?php 
require_once('Connections/lp.php');mysql_query("set names utf8");
?>

<!DOCTYPE html>
<html>
<head>
  <title>LIFE PAY</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="css/manag_style.css">
  <link rel="stylesheet" href="css/jquery-ui.css" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 
</head>
 <script>
  
    function userlogin(){
      //先取得欄位值
      var e = "#user_name";
      var p = "#user_password";
      var error = "#error_msg";
      var loading_div = "#loading_div";

      var user_name = $(e).val();
      var user_password = $(p).val();

      //alert(user_password);
      //判斷有無正確填寫
      if(user_password==""){
          $(error).text('請輸入密碼');
          $(p).focus();
          return false;
      };
        $(document).ready(function() {
            $.ajax({
              type : "POST",
              url:"login_man.php",
              dataType: "text",
              data: {
                "user_name": user_name,
                "user_password": user_password,
              },
              beforeSend:function(){
              $(loading_div).show(); 
              //beforeSend 發送請求之前會執行的函式
              },
              success:function(msg){
                //alert(msg);
                  if(msg =="success"){
                      window.location.href="manage_main.php";
                      //如果成功登入，就不需要再出現登入表單，而出現store_search    
                  }else
                  {    
                      $(error).show();
                      $(error).html('請重新登入,<br/>密碼不正確');
                  }
              },
              complete:function(){
              $(loading_div).hide();        
              //complete請求完成實執行的函式，不管是success或是error
              }
            });
        });

      };

  </script>
<body style="background-color: #6d4ede">
<div align="center" style="margin-top: 30px">
  <img src="img/life_pay_logo-01.png" width="220px" alt="">
</div>

<div align="center" style="padding: 10px; background: #9c90de;margin: 15px 25px;border-radius: 10px;font-size: 18px;box-shadow: 2px 2px 10px #28083d ">

  <form id="chk" action="POST">
    <ul style="margin-top: 40px" class="login_input">
      <li style="margin-bottom: 20px"><input type="text" name="user_name" id="user_name" AutoComplete="Off" style="background-color: #e9e6ff;border: 0px;border-radius: 6px;height: 40px;padding: 9px;margin-left: 0px;background-image: none" placeholder="請輸入帳號"></li>
      <li style="margin-bottom: 20px"><input type="password" name="user_password" id="user_password" AutoComplete="Off" style="background-color: #e9e6ff;border: 0px;border-radius: 6px;height: 40px;padding: 9px" placeholder="請輸入密碼"></li>
      <li >
        <input type="button" id='submit' value="登入" style="background-color: #3e6ded;color: #fff;border: 0px; border-radius: 6px;height: 40px;margin-bottom: 40px;" onclick='userlogin();'/>
      </li>
    </ul>
    
    <div id="loading_div<?php echo $total_em;?>" style="display:none" align="center">
        <img src="img/ajax-loader.gif"><br/>登入中...
    </div>
    <div id="error_msg<?php echo $total_em;?>"></div>
  </form>
</div>
<div class="mebr_info " align="center" style="padding: 20px">
</div>


</body>