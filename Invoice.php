<?php 
require_once('Connections/lp.php');mysql_query("set names utf8");
require_once('Connections/sc.php');mysql_query("set names utf8");
require_once('Connections/tw.php');mysql_query("set names utf8");
?>
<?php 
session_start();
if ($_SESSION['MM_Username'] == ""){header(sprintf("Location: store_login.php"));exit;
}else{
  $m_username = $_SESSION['mem'];//登入帳號
  $m_nick = $_SESSION['nick'];//登入暱稱
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
$date = date("Y-m-d");
$date2 = date("Y-m-01");
/*
$date = date("Y-m-d");
$plus = strtotime("-7 day");
$date2 = date('Y-m-d',$plus);
*/


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
  <link rel="stylesheet" href="/wp-content/themes/the-rex/css/jquery-ui.css" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 <!-- <script src="js/main.js"></script>-->
</head>
<body style="background-color:#f5c1ad;min-height: 860px">


<div class="mebr_top" align="center">
  <a href="store_main.php"><img src="img/life_pay_logo-01.png" width="220px" alt=""></a>
  <a href="#"  data-toggle="modal" data-target="#myModal2"><img src="img/set_up-01.svg" style="position: absolute; left: 20px; top: 67px" width="25px" alt=""></a>
 
</div>
<div class="navtop" >
<div class="col-lg-6 col-md-6 col-xs-6" align="center" style="top: -2px;border-right: 2px solid #fff"><a href="store_search.php"><img src="img/long_search-01.png" alt="" width="120px" ></a></div>
  <div class="col-lg-6 col-md-6 col-xs-6" align="center" style="top: -2px"><a href="store_checkout.php"><img src="img/long_checkout-01.png" alt="" width="120px"></a></div>
</div>

<form id="form1" action="Invoice.php" method="get">
	<div class="search_bt" align="center" style="display: inline-block;margin-left: 20px">
		<div style="width: 88%" align="center">
      <ul  class="person_search">
        <li><input type="date" name="sd1" id="sd1" value="<?php echo $_GET['sd1'];?>" style="height: 40px;min-width: 120px; "></li>
        <li><span style="margin: 4px;line-height: 40px">至</span></li>
        <li><input type="date" name="sd2" id="sd2" value="<?php echo $_GET['sd2'];?>" style="height: 40px;min-width: 120px;"></li>
        <li><input type="button" style="background-color:#ee9078;margin-left: 8px;line-height: 35px " class="date_but" onClick="check()" value="查詢"></li>
      </ul>
			
			
			
		</div>
	</div>  
</form>
<?php //
      if ($_GET['sd1'] != "" && $_GET['sd2'] != "") {//sd1 sd2 皆有值
        $sd1=$_GET['sd1'];$sd2=$_GET['sd2'];
        $key="SELECT * FROM complete WHERE s_number = '$number' && date >= '$sd1' && date <= '$sd2' && confirm = '1' ORDER BY id DESC";
      }else {//無日期顯示當月資料
        $key="SELECT * FROM complete WHERE s_number = '$number' && date >= '$date2' && date <= '$date' && confirm = '1' ORDER BY id DESC";
      }
        
      //取得交易資料
      mysql_select_db($database_lp, $lp);
      $query_Recoc = $key;// 
      $query_limit_Recoc = sprintf($query_Recoc);
      $Recoc = mysql_query($query_limit_Recoc, $lp) or die(mysql_error());
      $row_Recoc = mysql_fetch_assoc($Recoc);
      $total_recoc = mysql_num_rows($Recoc);

      //取得回饋%數
      mysql_select_db($database_lp, $lp);
      $query_user = "SELECT * FROM lf_user WHERE number = '$number' && level ='boss'";
      $query_limit_user = sprintf($query_user);
      $User = mysql_query($query_limit_user, $lp) or die(mysql_error());
      $row_User = mysql_fetch_assoc($User);
      $fee = $row_User['usage_fee'];

      //取得請款日期(總送出)
      mysql_select_db($database_lp, $lp);
      $query_user = "SELECT * FROM Invoice WHERE number = '$number'";
      $query_lv = sprintf($query_user);
      $User_lv = mysql_query($query_lv, $lp) or die(mysql_error());
      $row_lv = mysql_fetch_assoc($User_lv);


?>
<!--<div class="container" align="center" style="margin-top: 50px;margin-bottom: 20px;">
  <img class="img" src="img/banner.jpg" alt="banner">
</div>-->               
  <div align="center" style="padding: 10px;font-size: 20px;padding-bottom: 0px">
  	   
      	<span  style="text-align: center;"><?php echo $row_User['st_name'];?></span>
        <span  style="text-align: center;">請款明細表</span>
  </div>
 
  <div class="table-responsive search_table " style="overflow-y: visible;white-space: nowrap;height: 400px" align="center">          
  <table align="center" class="table coupon_table" style="background: #fff;border-radius: 10px">
      <tr style="background-color: #ee9078;color: #fff ">
        <th style="border-radius: 10px 0px 0px 0px">序號</th>
        <th >消費日期</th>
        <th >登入帳號</th>
        <th >名稱/暱稱</th>
        <th>消費積分</th>
        <th>串串積分</th>	
        <th>現金/刷卡</th>
        <th >合計</th>
        <th >回饋%數</th>
        <th>回饋金額</th>
        <th>請款金額</th>
        <th>提出申請</th>
        <th >申請日期</th>
        <th style="border-radius: 0px 10px 0px 0px">狀態</th>
      </tr>
      <?php 

            if($total_recoc != 0){

              //取得請款日期(總送出)
              mysql_select_db($database_lp, $lp);
              $query_al = "SELECT * FROM Invoice WHERE number = '$number'";
              $query_al_lv = sprintf($query_al);
              $all_lv = mysql_query($query_al_lv, $lp) or die(mysql_error());
              $row_all = mysql_fetch_assoc($all_lv);


                do{
                  //取得交易資料
                  $ID = $row_Recoc['ID'];
                  $s_nick = $row_Recoc['s_nick'];
                  $dateRe = $row_Recoc['date'];
                  $g = $row_Recoc['g'];
                  $c = $row_Recoc['c'];
                  $spend = $row_Recoc['spend'];
                  $invoice = $row_Recoc['invoice'];
                  //取得回饋
                  $fee = $row_User['usage_fee'];
                  //運算
                  $count = (int)$g+$c+$spend;//合計
                  $q = floor($count/100*$fee);
                  $p = $g+$c;
                  $usage_fee = ($p-$q);
                  //抵扣金額
                  $discount = $row_Recoc['discount'];
                  //營業金額
                  $total = $row_Recoc['total_cost'];
                  //取得請款日期
                  $datelv = $row_lv['date'];


                    $arr =array("ID"=>$ID,"c"=>(int)$c,"g"=>(int)$g,"total"=>$total,"spend"=>$spend,"count"=>$count,"q"=>(int)$q,"usage_fee"=>(int)$usage_fee,"number"=>$number,"m_username"=>$m_username,"m_nick"=>$m_nick,"discount"=>$discount);
                    $arr_json = json_encode($arr); //陣列轉josn


            ?>
            <script>

            function check(){ //日期搜尋檢定
            var sd1 = document.getElementById('sd1');
            var sd2 = document.getElementById('sd2');


            if(sd1.value == "" && sd2.value != ""){
              document.getElementById('sd1').style.background="pink";
            }else if(sd1.value != "" && sd2.value == ""){
              document.getElementById('sd2').style.background="pink";
            }else if(sd1.value > sd2.value){
              document.getElementById('sd1').style.background="pink";
              document.getElementById('sd2').style.background="pink";
            }else{
              document.getElementById('form1').submit();
              }
            }
            
            function sub(value){


              var bt = ".gg"+value;
              var bt_value = $(bt).val();
              var val = JSON.parse(bt_value);

              var ID = val.ID;
              var c = val.c;
              var g = val.g;
              var total = val.total;
              var spend = val.spend;
              var count = val.count;
              var q = val.q;
              var usage_fee = val.usage_fee;
              var number = val.number;
              var m_username = val.m_username;
              var m_nick = val.m_nick;
              var discount = val.discount;

              
              //alert(ID);
              $(document).ready(function() {
                //ajax 送表單
                $.ajax({
                    type: "POST",
                    url: "submit_invoice.php",
                    dataType: "text",
                    data: {
                      ID: ID,
                      accont: m_username,
                      nick: m_nick,
                      number: number,
                      usage_fee: usage_fee,
                      spend: spend,
                      count: count,
                      discount: discount,
                      total: total,
                      c: c,
                      g: g,
                      q: q
                    },
                    success: function(data) {
            		  $(bt).attr("disabled","true");
                      $(bt).css("background-color","#E5E5E5");
                      $(bt).css("color","#cccccc");

                    }

                  })
                })

              }

              </script>
                          <tr class="search_tr" style="text-align:center;background-color: #fff">
                            <td style="text-align:center;border-radius: 0px 0px 0px 10px"><?php echo $total_recoc;?></td>
                            <td style="text-align:center;"><?php echo $dateRe;?></td>
                            <td style="text-align:center;"><?php echo $m_username;?></td>
                            <td style="text-align:center;"><?php echo $m_nick;?></td>
                            <td style="text-align:center;"><?php echo number_format($g);?></td>
                            <td style="text-align:center;"><?php echo number_format($c);?></td>
                            <td style="text-align:center;"><?php echo number_format($spend);?></td>
                            <td style="text-align:center;"><?php echo number_format($count);?></td>
                            <td style="text-align:center;"><?php echo number_format($fee);?></td>
                            <td style="text-align:center;"><?php echo number_format($q);?></td>
                            <td style="text-align:center;"><?php echo number_format($usage_fee);?></td>
                            <?php if($invoice != 0){?>
                              <td><button class="gg<?php echo $total_recoc;?>" value='<?php echo $arr_json;?>' disabled="disabled">處理中</button></td>
                            <?php }else{?>
                              <td><button style='background:#487be5;color:#fff;' class="gg<?php echo $total_recoc;?>" onClick="sub(<?php echo $total_recoc;?>)" value='<?php echo $arr_json;?>'>申請</button></td>        
                            <?php }
                            if($invoice != 0){

                              //取得申請的日期
                              $cdate = $row_Recoc['date'];
                              $invoiceID = $row_Recoc['invoice_note'];

                              //取得請款日期
                              mysql_select_db($database_lp, $lp);
                              $query_al = "SELECT * FROM Invoice WHERE number = '$number' && idd = '$ID'";
                              $all_lv = mysql_query($query_al, $lp) or die(mysql_error());
                              $queryall = mysql_fetch_assoc($all_lv);
                              $row_all = mysql_num_rows($all_lv);

                              //以申請的日期取得交易日期
                              mysql_select_db($database_lp, $lp);
                              $query_al2 = "SELECT * FROM Invoice WHERE number = '$number' && idd = '0' && sd1 <= '$cdate' && sd2 >= '$cdate' && ID = '$invoiceID' ";
                              $all_lv2 = mysql_query($query_al2, $lp) or die(mysql_error());
                              $queryall2 = mysql_fetch_assoc($all_lv2);
                              $row_all2 = mysql_num_rows($all_lv2);

                              //以invoiceID去對應invoice的ID
                              mysql_select_db($database_lp, $lp);
                              $query_al3 = "SELECT * FROM Invoice WHERE number = '$number' && ID = '$invoiceID'";
                              $all_lv3 = mysql_query($query_al3, $lp) or die(mysql_error());
                              $queryall3 = mysql_fetch_assoc($all_lv3);
                              $row_all3 = mysql_num_rows($all_lv3);

                              if($row_all != 0){
                                echo '<td>'.$queryall['date'].'</td>';
                                if($queryall['confirm']!= 0){
                                  echo '<td style="border-radius: 0px 0px 10px 0px;color: #22a363">已撥款</td>';
                                }else{
                                  echo '<td style="border-radius: 0px 0px 10px 0px">撥款中</td>';
                                }
                              }else if($row_all2 != 0){
                                echo '<td>'.$queryall2['date'].'</td>';
                                if($queryall2['confirm']!= 0){
                                  echo '<td style="border-radius: 0px 0px 10px 0px;color: #22a363">已撥款</td>';
                                }else{
                                  echo '<td style="border-radius: 0px 0px 10px 0px">撥款中</td>';
                                }
                              }else if($row_all3 != 0){
                                echo '<td>'.$queryall3['date'].'</td>';
                                if($queryall3['confirm']!= 0){
                                  echo '<td style="border-radius: 0px 0px 10px 0px;color: #22a363">已撥款</td>';
                                }else{
                                  echo '<td style="border-radius: 0px 0px 10px 0px">撥款中</td>';
                                }
                              }

                            }else{
                              echo '<td></td>
                              <td style="border-radius: 0px 0px 10px 0px;color: #ff5e59">未請款</td>';
                            }?>

                          </tr>
            <?php 

            	  $total_recoc = $total_recoc - 1;

            	  }while($row_Recoc = mysql_fetch_assoc($Recoc));

            }

      ?>

    
  </table>
  </div>


  <?php

      if ($_GET['sd1'] != "" && $_GET['sd2'] != "") {//sd1 sd2 皆有值
        $sd1=$_GET['sd1'];$sd2=$_GET['sd2'];
        $key2="SELECT * FROM complete WHERE s_number = '$number' && date >= '$sd1' && date <= '$sd2' && confirm = '1' && invoice != '1' ORDER BY id DESC";
      }else if ($_GET['sd1'] != "") {//sd1有值
        $sd1=$_GET['sd1'];
        $key2="SELECT * FROM complete WHERE s_number = '$number' && date >= '$sd1' && date <= '$date' && confirm = '1' && invoice != '1' ORDER BY id DESC";
      }else{$key2="SELECT * FROM complete WHERE s_number = '$number' && confirm = '1' && invoice != '1' ORDER BY id DESC";}

      mysql_select_db($database_lp, $lp);
      $query_Recoc = $key2;//
      $query_limit_Recoc = sprintf($query_Recoc);
      $Recoc = mysql_query($query_limit_Recoc, $lp) or die(mysql_error());
      $row_stcomplete = mysql_fetch_assoc($Recoc);
      $total_ow_stcomplete = mysql_num_rows($Recoc);


      //取得請款日期
      mysql_select_db($database_lp, $lp);
      $query_user = "SELECT * FROM Invoice WHERE number = '$number'";
      $User_lv = mysql_query($query_lv, $lp) or die(mysql_error());
      $row_lv = mysql_fetch_assoc($User_lv);


      ?>
      <!--剩餘未申請-->
  <div align="center" style="padding: 10px;font-size: 20px;padding-bottom: 0px">
  	<span  style="text-align: center;">剩餘未申請</span>
  </div>
      <div class="search_table table-responsive " style="overflow-y: visible;white-space: nowrap;" align="center">
      <table align="center" class="table coupon_table">
      <form action="all_invoice.php" method="POST">
        <tr style="background-color: #ee9078;color: #fff "> 
          <th style="border-radius: 10px 0px 0px 0px">店家名稱</th>
          <th>營業金額</th>
          <th>抵扣金額</th>
          <th>實收消費積分</th>
          <th>實收串串積分</th>
          <th>實收現金/刷卡</th>
          <th>合計</th>
          <th>回饋金額</th>
          <th>未請款金額</th>
          <th style="border-radius: 0px 10px 0px 0px">提出申請</th>
          </tr>
        <?php
        if($total_ow_stcomplete != 0)
        {

          do{

            //取得回饋
            $fee = $row_User['usage_fee'];
            //
            $st_name = $row_stcomplete['s_nick'];
            //營業金額
            $total_cost = $row_stcomplete['total_cost'];
            $business_sum = $business_sum + $total_cost;
            //實收金額
            $spend = $row_stcomplete['spend'];
            $spend_sum = $spend_sum + $spend;
            $c = $row_stcomplete['c'];
            $g = $row_stcomplete['g'];
            $c_sum = $c_sum + $c;
            $g_sum = $g_sum + $g;
            //合計
            $count_sum = $spend_sum+$c_sum+$g_sum;
            //運算
            $count = (int)$g+$c+$spend;//合計
            $q = floor($count/100*$fee);
            //回饋金額
            $que = $que + $q;
            //請款金額
            $p = $g+$c;
            $usage = $p-$q;
            $usage_feee = $usage_feee + $usage;
            //抵扣金額
            $dis = $row_stcomplete['discount'];
            $diss = $diss+$dis;
          
          }while($row_stcomplete = mysql_fetch_assoc($Recoc));

          echo "<tr class='search_tr' style='background-color: #fff'>";
          echo "<td style='text-align:center;border-radius: 0px 0px 0px 10px'>".$st_name."</td>";
          echo "<td style='text-align:center;'>".number_format($business_sum)."</td>";
          echo "<td style='text-align:center;'>".number_format($diss)."</td>";
          echo "<td style='text-align:center;'>".number_format($g_sum)."</td>";
          echo "<td style='text-align:center;'>".number_format($c_sum)."</td>";
          echo "<td style='text-align:center;'>".number_format($spend_sum)."</td>";
          echo "<td style='text-align:center;'>".number_format($count_sum)."</td>";
          echo "<td style='text-align:center;'>".number_format($que)."</td>";
          echo "<td style='text-align:center;'>".number_format($usage_feee)."</td>";
          echo "<td style='border-radius: 0px 0px 10px 0px'><input type='submit' style='background:#487be5;color:#fff;'></td>";
          echo "</tr>";
        }
        ?>
        <input type="hidden" name="sd1" value="<?php echo $sd1;?>">
        <input type="hidden" name="sd2" value="<?php echo $sd2;?>">
        <input type="hidden" name="m_username" value="<?php echo $m_username;?>">
        <input type="hidden" name="diss" value="<?php echo $diss;?>">
        <input type="hidden" name="m_nick" value="<?php echo $m_nick;?>">
        <input type="hidden" name="number" value="<?php echo $number;?>">
        <input type="hidden" name="business_sum" value="<?php echo $business_sum;?>">
        <input type="hidden" name="g_sum" value="<?php echo $g_sum;?>">
        <input type="hidden" name="c_sum" value="<?php echo $c_sum;?>">
        <input type="hidden" name="spend_sum" value="<?php echo $spend_sum;?>">
        <input type="hidden" name="count_sum" value="<?php echo $count_sum;?>">
        <input type="hidden" name="que" value="<?php echo $que;?>">
        <input type="hidden" name="usage_feee" value="<?php echo $usage_feee;?>">
      </form>
    </table>
  </div>



<?php

      $key3="SELECT * FROM Invoice WHERE number = '$number'";

      mysql_select_db($database_lp, $lp);
      $query_Recoc = $key3;//
      $query_lv = sprintf($query_Recoc);
      $Recoc_l = mysql_query($query_lv, $lp) or die(mysql_error());
      $row_lv = mysql_fetch_assoc($Recoc_l);
      $total_ow_lv = mysql_num_rows($Recoc_l);


      ?>
  <!--已申請總額-->
    <div align="center" style="padding: 10px;font-size: 20px;padding-bottom: 0px">
        <span  style="text-align: center;">已申請總額</span>
  </div>
  <div class="search_table table-responsive " style="overflow-y: visible;white-space: nowrap;" align="center">
      <table align="center" class="table coupon_table">
        <tr style="background-color: #ee9078;color: #fff ">
          <th style="border-radius: 10px 0px 0px 0px">店家名稱</th>
          <th>營業金額</th>
          <th>抵扣金額</th>
          <th>實收消費積分</th>
          <th>實收串串積分</th>
          <th>實收現金/刷卡</th>
          <th>合計</th>
          <th>回饋金額</th>
          <th>已請款金額</th>
          </tr>
        <?php
        if($total_ow_lv != 0)
        {

          do{
            //
            $nick = $row_lv['nick'];
            //營業金額
            $total = $row_lv['total'];
            $total_sum = $total_sum+$total;
            //抵扣金額
            $discount = $row_lv['discount'];
            $discount_sum = $discount_sum+$discount;
            //已請款金額
            $fee = $row_lv['usage_fee'];
            $lv_fee = $lv_fee+$fee;
            //實收消費積分
            $lv_g = $row_lv['g'];
            $lv_gs = $lv_gs+$lv_g;
            //實收串串積分
            $lv_c = $row_lv['c'];
            $lv_cs = $lv_cs+$lv_c;
            //實收現金/刷卡
            $spendd = $row_lv['spend'];
            $spendd_count = $spendd_count+$spendd;
            //合計
            $count = $row_lv['count'];
            $count_sum = $count_sum+$count;
            //回饋金額
            $lv_q = $row_lv['q'];
            $lv_qs = $lv_qs+$lv_q;

          
          }while($row_lv = mysql_fetch_assoc($Recoc_l));

          echo "<tr class='search_tr' style='background-color: #fff'>";
          echo "<td style='text-align:center;'>".$nick."</td>";
          echo "<td style='text-align:center;'>".number_format($total_sum)."</td>";
          echo "<td style='text-align:center;'>".number_format($discount_sum)."</td>";
          echo "<td style='text-align:center;'>".number_format($lv_gs)."</td>";
          echo "<td style='text-align:center;'>".number_format($lv_cs)."</td>";
          echo "<td style='text-align:center;'>".number_format($spendd_count)."</td>";
          echo "<td style='text-align:center;'>".number_format($count_sum)."</td>";
          echo "<td style='text-align:center;'>".number_format($lv_qs)."</td>";
          echo "<td style='text-align:center;'>".number_format($lv_fee)."</td>";
          echo "</tr>";
        }
        ?>
    </table>
  </div>


<div class="col-lg-12 col-md-12 col-xs-12 st_pe_foot" style="height: 48px; background-color:#efefef; width: 100% ;position: relative;bottom: 0px">
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
          <li><a href="store_modify.php"><img src="img/user_setup.png" width="25px" alt=""><span style="margin-left: 8px">修改員工帳號</span></a></li>
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
