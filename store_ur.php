<?php 
require_once('Connections/lp.php');mysql_query("set names utf8");
require_once('Connections/sc.php');mysql_query("set names utf8");
?>
<?php 
session_start();
if ($_SESSION['MM_Username'] == ""){header(sprintf("Location: store_login.php"));exit;
}else{
  $m_username = $_SESSION['mem'];
  $m_nick = $_SESSION['nick'];
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
$row_emp = mysql_fetch_assoc($con_em);
$level = $row_emp['level'];
$number = $row_emp['number'];

//選擇使用者帳號
mysql_select_db($database_lp, $lp);
$query_em = sprintf("SELECT * FROM lf_user WHERE number ='$number' ");
$Reusem = mysql_query($query_em, $lp) or die(mysql_error());
$row_em = mysql_fetch_assoc($Reusem);
$total_em = mysql_num_rows($Reusem);


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
  <link rel="stylesheet" href="css/jquery-ui.css" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 
</head>
 <script>
  
    function userlogin(v){
      //先取得欄位值
      var e = "#user_name"+v;
      var p = "#user_password"+v;
      var error = "#error_msg"+v;
      var loading_div = "#loading_div"+v;
      //var user_password = $('#user_password<?php echo $total_em;?>').val();

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
              url:"login_chk.php",
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
                      window.location.href="store_search.php";
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
  <style>
  .employee {
    float: left;
    margin: 10px;
    background-color:#4faeff;
    padding: 20px;
    border-radius: 10px;
    color: #fff;
    -webkit-transition: 0.15s ease-in-out;
    -moz-transition: 0.15s ease-in-out;
    transition: 0.15s ease-in-out;
    cursor: pointer;
    z-index: 1
  }
  .employee:hover{
   /* -webkit-transform: perspective(500px) rotateX(35deg);*/
    box-shadow: 0px 12px 0px #479ce4
  }
  .ui-widget.ui-widget-content{z-index: 999; }
  .member_photo {transform: rotate(90deg);}
  </style>
<body class="store">
<div class="mebr_top" align="center">
  <a href="store_main.php"><img src="img/life_pay_logo-01.png" width="220px" alt=""></a>
  <a href="#" data-toggle="modal" data-target="#myModal2"><img src="img/set_up-01.svg" style="position: absolute; left: 20px; top: 67px" width="25px" alt=""></a>
</div>
<div align="center" style="padding: 10px;color: #fff; background: #ee9078;margin: 15px 25px;border-radius: 10px;font-size: 18px">
  現在登入:<?php echo $m_username;?>
</div>
<div class="mebr_info " align="center" style="padding: 20px">
  <ul>
  <?php 
  if($total_em != 0){
      do{
          $accont = $row_em['e_name'];
          $em_level = $row_em['level'];
          $em_accont = $row_em['accont'];
          if($em_level != "boss"){ //階級等於boss不顯示
              if($m_username != $em_accont){ //當前登入不顯示

                $arr =array("accont"=>$accont,"em_accont"=>$em_accont);
                $arr_json = json_encode($arr); //陣列轉josn

          ?>
              <script>
              $(function() 
              {               
                
                  //按下修改密碼
                  $( ".opener<?php echo $total_em;?>" ).on( "click", function()
                  { //按下超連結
                    var y = $(".opener<?php echo $total_em;?>").html();
                    var val = JSON.parse(y);
                    
                    var em_accont = val.em_accont;
                    //alert(em_accont);
   
                    $("#user_name<?php echo $total_em;?>").val(em_accont); 


                    $( "#dialog<?php echo $total_em;?>" ).dialog( "open" );
                    event.preventDefault();  //防止上方連結打開
                    $("#submit<?php echo $total_em;?>").click(function(event){
                      
                    })
                    
                  });


                  //開啟dialog
                  $( "#dialog<?php echo $total_em;?>" ).dialog({
                    autoOpen: false,//預設不顯示
                    draggable: false, //設定拖拉
                    resizable: true, //設定縮放
                    height: "auto",
                    width: "auto",
                    modal: false, //灰色透明背景限制只能按dialog
                    show: {
                      effect: "fade",
                    }

                  });

              });


              </script>
              <li style="" class="employee" style="z-index: 8">
                <div class="member_photo opener<?php echo $total_em;?>"><?php echo $arr_json;?></div>
                <div  style="margin-top: 5px;"><span>名稱：</span> <span style="font-size: 18px"> <?php echo $accont;?></span> </div>  
              </li>
              
              <div id="dialog<?php echo $total_em;?>" title="登入帳號密碼" style="z-index:999">
                  <form id="chk" action="POST">
                    <ul>
                      <li>帳號：</li><input type="text" name="user_name" id="user_name<?php echo $total_em;?>" readonly="readyonly" value="">
                      <li>密碼：</li><input type="password" name="user_password" id="user_password<?php echo $total_em;?>">
                    </ul>
                    <input type="button" id='submit<?php echo $total_em;?>' value="登入" onclick='userlogin(<?php echo $total_em?>);'/>
                    <div id="loading_div<?php echo $total_em;?>" style="display:none" align="center">
                        <img src="img/ajax-loader.gif"><br/>登入中...
                    </div>
                    <div id="error_msg<?php echo $total_em;?>"></div>
                  </form>
                </div>
              <?php
              $total_em = $total_em - 1;

              }
          }
      }while($row_em = mysql_fetch_assoc($Reusem));
    
  }

  ?>
  </ul>
</div>
<div class="col-lg-12 col-md-12 col-xs-12 st_pe_foot" style="height: 48px; background-color:#efefef; width: 100% ;position: fixed;bottom: 0px">
   <img src="img/login_footlogo-01.png" width="320px" style="margin-top: 10px" alt="">
 </div>

  <div class="modal fade" id="myModal2" role="dialog" >
    <div class="modal-dialog">
      <div class="modal-content " style="padding: 5px 20px;margin-top: 100px">
        <ul class="setting">
		  <?php if($level =="boss")
		  {?>
		  <li><a href="store_update.php" ><img src="img/sign-out-option.svg" width="25px" alt=""><span style="margin-left: 8px">更改資料</span></a></li>
		  <li><a href="store_establish.php"><img src="img/user_ch.png" width="25px" alt=""><span style="margin-left: 8px">建立員工帳號</span></a></li>
      <li><a href="store_modify.php"><img src="img/user_setup.png" width="25px" alt=""><span style="margin-left: 8px">修改員工帳號</span></a></li>
      <li><a href="Invoice.php"><img src="img/user_setup.png" width="25px" alt=""><span style="margin-left: 8px">請款</span></a></li>
		  <?php 
		  }?>
          <li><a href="slogout.php" ><img src="img/sign-out-option.svg" width="25px" alt=""><span style="margin-left: 8px">登出</span></a></li>
        </ul>
      </div>
    </div>
</div>
</body>
</html>
