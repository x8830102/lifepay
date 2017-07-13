<?php require_once('Connections/lpv.php');mysql_query("set names utf8");
require_once('Connections/sc.php');mysql_query("set names utf8");

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
 function random_color(){
    mt_srand((double)microtime()*1000000);
    $c = '';
    while(strlen($c)<6){
        $c .= sprintf("%02X", mt_rand(0, 255));
    }
    return $c;
}


 //版型
require_once('lifepay_user5.php'); 
?>

<script>
  function check(){ //日期搜尋檢定
  <?php 
  $industry=$_GET['industry'];
	$store =$_GET['store'];?>
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
 function check4(){
  <?php 
  $industry=$_GET['industry'];
  $store =$_GET['store'];?>
  document.getElementById('form4').submit();
}

 $(document).ready(function() {
    $('.menu a').click(function () {
      $('#wrapper').scrollTo($(this).attr('href'), 1000);
      //alert(this);
      return false;
    });  
  });
  
  </script>
<script src="js/Chart.js"></script>
<style type="text/css">
    #canvas-holder{
        width:50%;
        margin:0 auto;
      }
      .demo-chat{width: 50%;margin: 0 auto;}
  </style>
</head>
<body>
<div class="management_desk">
  <div id="wrapper">
    <ul id="m_mask">
    <li id="section1" class="m_box">
        <div class="contant">
          <h3>分析報表</h3>
           <div class="store_block aa" align="center">
            <ul >
              <li>
               
                  <div id="c1" class="menu" >
                     <a href="#section2"><div  align="center" style="margin-bottom: 20px"><span style="line-height: 35px;background-color:#8e78de;border-radius: 30px;color: #fff;border: 0px;font-size: 17px;padding: 7px "><?php echo $_GET['store'], $year;?>交易統計分析</span></div></a>
                      <div class="htmleaf-content">
                        <div >
                            <canvas id="canvas" ></canvas> 
                        </div>
                      </div>
                  </div>
                
              </li>
              <li>
                
                  <div id="c2" class="menu" >
                    <div  align="center" style="margin-bottom: 20px">
                      <a href="#section3"><div align="center" style="margin-bottom: 20px"><span style="line-height: 35px;background-color:#8e78de;border-radius: 30px;color: #fff;border: 0px;font-size: 17px;padding: 7px ">積分消費產業分布</span></div></a>
                      <?php
                            mysql_select_db($database_lp, $lp); //產業分布
                        $query_str1 = 
                        "SELECT SUM( Invoice.c+Invoice.g) AS cg, lf_user.industry
                        FROM Invoice, lf_user
                        WHERE Invoice.accont = lf_user.accont
                        GROUP BY lf_user.industry 
                        ORDER BY cg DESC";
                            $Restr1 = mysql_query($query_str1, $lp) or die(mysql_error());
                            $row_str1 = mysql_fetch_assoc($Restr1);
                            $row_num1 = mysql_num_rows($Restr1);
                        
                        ?>
                          <div class="htmleaf-content">
                            <div id="canvas-holder">
                              <canvas id="chart-area" ></canvas>
                            </div>
                          </div>
                      </div>
                    </div>
                  
              </li>
            </ul>
            <ul class="uu">
              <li>
                
                <div id="c3" class="menu" style="">
                <a href="#section4"><div  align="center" style="margin-bottom: 20px"><span style="line-height: 35px;background-color:#8e78de;border-radius: 30px;color: #fff;border: 0px;font-size: 17px;padding: 7px ">LIFE LINK註冊種類統計</span></div></a>
        
                  <div class="htmleaf-content">
                  <div >
                  <div>
                    <canvas id="canvas2" ></canvas>
                  </div>
                </div>
                </div>
              </div>
              
            </li>
              <li>
                
                <div id="c4" class="menu" >
              <a href="#section5"><div align="center" style="margin-bottom: 20px"><span style="line-height: 35px;background-color:#8e78de;border-radius: 30px;color: #fff;border: 0px;font-size: 17px;padding: 7px "><?php echo $_GET['industry'],$_GET['store'];?>消費族群分析</span></div></a>
                <div class="htmleaf-content">
                <div class="demo-chat" style="width: 70%">
                  <canvas id="canvas3" ></canvas>
                    </div>
                  </div>
                  </div>
                
              </li>
            </ul>
           </div>
        </div>
    </li>
    <li id="section2" class="m_box">
      <div  class="contant"><h3><?php echo $_GET['store'], $year;?>交易統計分析</h3><div class="store_block">
        <form id="form1" action="manage_analyze_store.php#section2" method="get">
  <div class="search_bt" align="center" style="display: inline-block;text-align: left;">

  <ul style="float: left;margin-right: 8px;position: absolute;top: 25px;left: 25px" class="menu">
                  
                  <li style="padding-left: 5px">
                     <a href="#section1"><img src="img/next-01.png" width="22px" alt="" style="transform: rotate(180deg)"></a>
                  </li>
                </ul>
    <ul style="float: right;position: absolute;top: 25px;right: 25px" class="menu">
                  <li style="padding-left: 5px">
                     <a href="#section3"><img src="img/next-01.png" width="22px" alt=""></a>
                  </li>
                </ul>
    <div style=";margin-left: 15px" align="center" >
      <ul class="search_bar">
        <li>
          <input type="date" name="sd1"  id="sd1" value="<?php echo $_GET['sd1'];?>" style="height: 40px;min-width: 120px; display: inline-block">
        </li>
        <li><span style="margin: 4px;line-height: 40px">至</span></li>
        <li>
          <input type="date" name="sd2"  id="sd2" value="<?php echo $_GET['sd2'];?>" style="height: 40px;min-width: 120px;display: inline-block;">
        </li>
        <li> <input type="text" name="store" placeholder="輸入店家名稱"  value="<?php echo $_GET['store'];?>" style="height: 40px;-webkit-appearance:none;line-height: 40px;width: 70%;margin-left: 0px"></li>
        <li> <select name="industry" id="industry" placeholder="產業 : " style="width: 150px;text-align: left;height: 40px;line-height: 40px;border: 2px solid #8e78de">
              <option value="" style="color:#999999">選擇產業別</option>
              <option value="餐飲業">餐飲業</option>
              <option value="服飾業">服飾業</option>
              <option value="資訊業">資訊業</option>
              <option value="飯店業">飯店業</option>
              <option value="零售業">零售業</option>
              <option value="其他">其他</option>
              </select></li>
                                
        <li>
          <button type="button" style="margin-left: 8px;line-height: 35px;background-color:#8e78de;border-radius: 6px;color: #fff;border: 0px;width: 80px;font-size: 17px " class="date_but" onClick="check()">查詢</button>
        </li>
      </ul>
    </div>
  </div>
</form>

<?php
        if ($_GET['sd1'] != "") { //日期&商家
        $sd1=$_GET['sd1'];
        $sd2=$_GET['sd2'];
        mysql_select_db($database_lp, $lp);
    $query_str = 
    "SELECT Invoice.nick, SUM( Invoice.total ) AS total,SUM( Invoice.usage_fee) AS total_usage, YEAR( Invoice.date ) AS YEAR, MONTH( Invoice.date ) AS MONTH , lf_user.industry
    FROM Invoice, lf_user 
    WHERE Invoice.accont = lf_user.accont && Invoice.nick like '%$store%' && Invoice.date >= '$sd1' && Invoice.date <= '$sd2' && lf_user.industry like '%$industry%' 
    GROUP BY YEAR( DATE ) , MONTH( DATE ) 
    ORDER BY MONTH";
        $Restr = mysql_query($query_str, $lp) or die(mysql_error());
        $row_str = mysql_fetch_assoc($Restr);
        $row_num = mysql_num_rows($Restr);
    $year = $row['YEAR'];
    
        } else {
    $sd1=date("Y-01-01");
    $sd2=date("Y-m-d");
        mysql_select_db($database_lp, $lp); //無日期
    $query_str = 
    "SELECT Invoice.nick, SUM( Invoice.total ) AS total,SUM( Invoice.usage_fee) AS total_usage, YEAR( Invoice.date ) AS YEAR, MONTH( Invoice.date ) AS MONTH , lf_user.industry
    FROM Invoice, lf_user 
    WHERE Invoice.accont = lf_user.accont && Invoice.nick like '%$store%' && Invoice.date >= '$sd1' && Invoice.date <= '$sd2' && lf_user.industry like '%$industry%' 
    GROUP BY YEAR( DATE ) , MONTH( DATE ) 
    ORDER BY MONTH";
        $Restr = mysql_query($query_str, $lp) or die(mysql_error());
        $row_str = mysql_fetch_assoc($Restr);
        $row_num = mysql_num_rows($Restr);
    $year = $row_str['YEAR'];
    }
    ?>

<div class="search_bt" align="center">
  <div style="width: 88%;margin-bottom: 30px" align="center"><span><?php echo $_GET['store'], $year;?>交易統計分析</span></div>
      
      <div class="htmleaf-content">
      <div style="width:70%;margin:0 auto">
      <div>
        <canvas id="canvas-2" height="280" width="600"></canvas>
      </div>
    </div>
    </div>
        
  <script>
  var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
    var lineChartData1 = {
      labels : ["1月","2月","3月","4月","5月","6月","7月","8月","9月","10月","11月","12月"],
      
      datasets : [
        {
          label: "My First dataset",
          fillColor : "rgba(220,220,220,0.2)",
          strokeColor : "rgba(220,220,220,1)",
          pointColor : "rgba(220,220,220,1)",
          pointStrokeColor : "#fff",
          pointHighlightFill : "#fff",
          pointHighlightStroke : "rgba(220,220,220,1)",
          data : [<?php do{
          echo "\"" ,$row_str['total'], "\",";
          }while($row_str = mysql_fetch_assoc($Restr));?>]
        },
        
        <?php
        if ($_GET['sd1'] != "") { //日期&商家
        $sd1=$_GET['sd1'];
        $sd2=$_GET['sd2'];
        mysql_select_db($database_lp, $lp);
    $query_str = 
    "SELECT Invoice.nick, SUM( Invoice.total ) AS total,SUM( Invoice.usage_fee) AS total_usage, SUM( Invoice.c + Invoice.g ) AS total_cg, YEAR( Invoice.date ) AS YEAR, MONTH( Invoice.date ) AS MONTH , lf_user.industry
    FROM Invoice, lf_user 
    WHERE Invoice.accont = lf_user.accont && Invoice.nick like '%$store%' && Invoice.date >= '$sd1' && Invoice.date <= '$sd2' && lf_user.industry like '%$industry%' 
    GROUP BY YEAR( DATE ) , MONTH( DATE ) 
    ORDER BY MONTH";
        $Restr = mysql_query($query_str, $lp) or die(mysql_error());
        $row_str = mysql_fetch_assoc($Restr);
        $row_num = mysql_num_rows($Restr);
    
        } else {
        $sd1=date("Y-01-01");
    $sd2=date("Y-m-d");
        mysql_select_db($database_lp, $lp); //無日期
    $query_str = 
    "SELECT Invoice.nick, SUM( Invoice.total ) AS total,SUM( Invoice.usage_fee) AS total_usage, SUM( Invoice.c + Invoice.g ) AS total_cg, YEAR( Invoice.date ) AS YEAR, MONTH( Invoice.date ) AS MONTH , lf_user.industry
    FROM Invoice, lf_user 
    WHERE Invoice.accont = lf_user.accont && Invoice.nick like '%$store%' && Invoice.date >= '$sd1' && Invoice.date <= '$sd2' && lf_user.industry like '%$industry%' 
    GROUP BY YEAR( DATE ) , MONTH( DATE ) 
    ORDER BY MONTH";
        $Restr = mysql_query($query_str, $lp) or die(mysql_error());
        $row_str = mysql_fetch_assoc($Restr);
        $row_num = mysql_num_rows($Restr);
    }
    ?>
        {
          label: "My Second dataset",
          fillColor : "rgba(183,200,87,0.2)",
          strokeColor : "rgba(183,200,87,1)",
          pointColor : "rgba(183,200,87,1)",
          pointStrokeColor : "#fff",
          pointHighlightFill : "#fff",
          pointHighlightStroke : "rgba(220,220,220,1)",
          data : [<?php do{
          echo "\"" ,$row_str['total_cg'], "\",";
          }while($row_str = mysql_fetch_assoc($Restr));?>]
        },
        
        <?php
        if ($_GET['sd1'] != "") { //日期&商家
        $sd1=$_GET['sd1'];
        $sd2=$_GET['sd2'];
        mysql_select_db($database_lp, $lp);
    $query_str = 
    "SELECT Invoice.nick, SUM( Invoice.total ) AS total,SUM( Invoice.usage_fee) AS total_usage, YEAR( Invoice.date ) AS YEAR, MONTH( Invoice.date ) AS MONTH , lf_user.industry
    FROM Invoice, lf_user 
    WHERE Invoice.accont = lf_user.accont && Invoice.nick like '%$store%' && Invoice.date >= '$sd1' && Invoice.date <= '$sd2' && lf_user.industry like '%$industry%' 
    GROUP BY YEAR( DATE ) , MONTH( DATE ) 
    ORDER BY MONTH";
        $Restr = mysql_query($query_str, $lp) or die(mysql_error());
        $row_str = mysql_fetch_assoc($Restr);
        $row_num = mysql_num_rows($Restr);
    
        } else {
        $sd1=date("Y-01-01");
    $sd2=date("Y-m-d");
        mysql_select_db($database_lp, $lp); //無日期
    $query_str = 
    "SELECT Invoice.nick, SUM( Invoice.total ) AS total,SUM( Invoice.usage_fee) AS total_usage, YEAR( Invoice.date ) AS YEAR, MONTH( Invoice.date ) AS MONTH , lf_user.industry
    FROM Invoice, lf_user 
    WHERE Invoice.accont = lf_user.accont && Invoice.nick like '%$store%' && Invoice.date >= '$sd1' && Invoice.date <= '$sd2' && lf_user.industry like '%$industry%' 
    GROUP BY YEAR( DATE ) , MONTH( DATE ) 
    ORDER BY MONTH";
        $Restr = mysql_query($query_str, $lp) or die(mysql_error());
        $row_str = mysql_fetch_assoc($Restr);
        $row_num = mysql_num_rows($Restr);
    }
    ?>
        {
          label: "My Third dataset",
          fillColor : "rgba(151,187,205,0.2)",
          strokeColor : "rgba(151,187,205,1)",
          pointColor : "rgba(151,187,205,1)",
          pointStrokeColor : "#fff",
          pointHighlightFill : "#fff",
          pointHighlightStroke : "rgba(151,187,205,1)",
          data : [<?php do{
          echo "\"" ,$row_str['total_usage'], "\",";
          }while($row_str = mysql_fetch_assoc($Restr));?>]
        }
      ]

    }

  
    
  </script>
<div style="text-align:center;clear:both">
</div>
  
</div>
<div class="search_table table-responsive " align="center" style="overflow-y: visible;white-space: nowrap;padding-top: 10px">
  <div class="search_bt" align="center">
    <div style="width: 88%" align="center"><span>銷售紀錄</span></div>
  </div>
  <table align="center" class="table" style="width: 88%;margin: 10px auto 0px auto;line-height: 45px;background-color: #eee;border-radius: 10px" >
    <tr style="background-color: #6D4EDE;color: #fff ">
      <th style="border-radius: 10px 0px 0px 0px">日期</th>
      <th>商店名稱</th>
      <th>產業別</th>
      <th>銷售金額</th>
      <th>消費積分</th>
      <th>串串積分</th>
      <th style="border-radius: 0px 10px 0px 0px">支付店家</th>
    </tr>
    <?php
    if ($_GET['sd1'] != "") { //日期&商家
        $sd1=$_GET['sd1'];
        $sd2=$_GET['sd2'];
        mysql_select_db($database_lp, $lp);
    $query_str = 
    "SELECT Invoice.*, YEAR( Invoice.date ) AS YEAR, MONTH( Invoice.date ) AS MONTH , lf_user.industry 
    FROM Invoice, lf_user 
    WHERE Invoice.accont = lf_user.accont && Invoice.nick like '%$store%' && Invoice.date >= '$sd1' && Invoice.date <= '$sd2' && lf_user.industry like '%$industry%' 
    GROUP BY Invoice.total, YEAR( DATE ) , MONTH( DATE )  
    ORDER BY id DESC";
        $Restr = mysql_query($query_str, $lp) or die(mysql_error());
        $row_str = mysql_fetch_assoc($Restr);
        $row_num = mysql_num_rows($Restr);
    $nick = $row_str['nick'];
    $industcheck = $row_str['industry'];
    
        } else {
        mysql_select_db($database_lp, $lp); //無日期
    $query_str = 
    "SELECT Invoice.*, YEAR( Invoice.date ) AS YEAR, MONTH( Invoice.date ) AS MONTH , lf_user.industry 
    FROM Invoice, lf_user 
    WHERE Invoice.accont = lf_user.accont && Invoice.nick like '%$store%' && lf_user.industry like '%$industry%' 
    GROUP BY Invoice.total, YEAR( DATE ) , MONTH( DATE ) 
    ORDER BY id DESC";
        $Restr = mysql_query($query_str, $lp) or die(mysql_error());
        $row_str = mysql_fetch_assoc($Restr);
        $row_num = mysql_num_rows($Restr);
    $nick = $row_str['nick'];
    $industcheck = $row_str['industry'];
    }

  $a=false;
  $b=false;
  
    if($row_num != 0){
      do{ 
      //銷售金額
        $total_sell = $row_str['total'];
        $sell_sum = $sell_sum + $total_sell;
        //消費積分總額
        $g = $row_str['g'];
        $g_sum = $g_sum + $g;
        //串串積分總額
        $c = $row_str['c'];
        $c_sum = $c_sum + $c;
        //支付店家
        $usage_fee = $row_str['usage_fee'];
        $usage_fee_sum = $usage_fee_sum + $usage_fee;
    
    $nick_nick = $row_str['nick'];
    $industcheck_check = $row_str['industry'];

        if(strcmp($nick,$nick_nick)!=0){ //比對是否相等
            $a= true;
        }
    
    if(strcmp($industcheck,$industcheck_check)!=0){
      $b= true;
      }


      }while($row_str = mysql_fetch_assoc($Restr));}?>
    <tr class="search_tr"  style="background-color: #eee">
      <td style="border-radius: 0px 0px 0px 10px"><?php echo $sd1."~".$sd2;?></td>
      <?php
          if($a){
            echo '<td></td>';
          }else{
            echo '<td>'.$nick.'</td>';
          }
          ?>
      <?php
          if($b){
            echo '<td></td>';
          }else{
            echo '<td>'.$industcheck.'</td>';
          }
          ?>
      <td><?php echo number_format($sell_sum);?></td>
      <td><?php echo number_format($g_sum);?></td>
      <td><?php echo number_format($c_sum);?></td>
      <td style="border-radius: 0px 0px 10px 0px"><?php echo number_format($usage_fee_sum);?></td>
    </tr>
  </table>
</div>
      </div></div>
    </li>
    <li id="section3" class="m_box">
      <div class="contant">
                <h3>積分消費產業分布</h3> 
          <div class="store_block">
            <div class="search_bt" align="center">
            
            <ul style="float: left;margin-right: 8px;position: absolute;top: 25px;left: 45px" class="menu">
                  <li style="padding-left: 5px">
                     <a href="#section2"><img src="img/next-01.png" width="22px" alt="" style="transform: rotate(180deg)"></a>
                  </li>
                </ul>
    <ul style="float: right;position: absolute;top: 25px;right: 25px" class="menu">
                  <li style="padding-left: 5px">
                     <a href="#section4"><img src="img/next-01.png" width="22px" alt=""></a>
                  </li>
                </ul>
  <div style="width: 50%;margin-bottom: 30px" align="center"><h3>積分消費產業分布</h3></div>
  <?php
        mysql_select_db($database_lp, $lp); //產業分布
    $query_str1 = 
    "SELECT SUM( Invoice.c+Invoice.g) AS cg, lf_user.industry
    FROM Invoice, lf_user
    WHERE Invoice.accont = lf_user.accont
    GROUP BY lf_user.industry 
    ORDER BY cg DESC";
        $Restr1 = mysql_query($query_str1, $lp) or die(mysql_error());
        $row_str1 = mysql_fetch_assoc($Restr1);
        $row_num1 = mysql_num_rows($Restr1);
    
    ?>
  <div class="htmleaf-content">
    <div id="canvas-holder">
      <canvas id="chart-area-2" width="250" height="250"/>
    </div>
  </div>

<script>
            var doughnutData = [
            <?php do{?>
      {
                value: <?php echo $row_str1['cg'];?>,
                color: <?php echo "\"#",random_color(),"\"";?>,
                highlight: "#977DF5",
                label: <?php echo "\"",$row_str1['industry'],"\"";?>
            },<?php 
        
        $cg = $row_str1['cg'];
        $cg_sum = $cg_sum + $cg;
        
      } while($row_str1 = mysql_fetch_assoc($Restr1));?>
            
        
        ];
        
       

        
            </script>

<div class="search_table table-responsive " align="center" style="overflow-y: visible;white-space: nowrap;padding-top: 10px">
  <div class="search_bt" align="center">
    <div style="width: 88%" align="center"><h3>銷售紀錄</h3></div>
  </div>
  <table align="center" class="table" style="width: 70%;margin: 10px auto 0px auto;line-height: 45px;background-color: #fff;border-radius: 10px;margin-bottom: 30px" >
    <tr style="background-color: #6D4EDE;color: #fff ">
      <th style="border-radius: 10px 0px 0px 0px">產業別</th>
      <th>積分消費額</th>
      <th style="border-radius: 0px 10px 0px 0px">市佔率</th>
    </tr>
    <?php
   mysql_select_db($database_lp, $lp); //產業分布
    $query_str1 = 
    "SELECT SUM( Invoice.c+Invoice.g) AS cg, lf_user.industry
    FROM Invoice, lf_user
    WHERE Invoice.accont = lf_user.accont
    GROUP BY lf_user.industry 
    ORDER BY cg DESC";
        $Restr1 = mysql_query($query_str1, $lp) or die(mysql_error());
        $row_str1 = mysql_fetch_assoc($Restr1);
        $row_num1 = mysql_num_rows($Restr1);
    if($row_num1 != 0){
        do{?>
    <tr class="search_tr"  style="background-color: #eee">
      <td style="border-radius: 0px 0px 0px 10px"><?php echo $row_str1['industry'];?></td>
      <td><?php echo number_format($row_str1['cg']);?></td>
      <td style="border-radius: 0px 0px 10px 0px"><?php echo number_format($row_str1['cg']/$cg_sum*100),"%";?></td>
    </tr>
    <?php 
      
      }while($row_str1 = mysql_fetch_assoc($Restr1));

          

      }?>
  </table>
</div></div>
          </div>
          </div>
    </li>
    <li id="section4" class="m_box">
      <div class="contant">
                <h3>LIFE LINK註冊種類統計</h3> 
          <div class="store_block">
            <?php
        
        mysql_select_db($database_sc, $sc);
    $query_str2 = 
    "SELECT a_pud, COUNT( a_pud ) AS member FROM  `memberdata` WHERE a_pud != '0'  GROUP BY a_pud";
        $Restr2 = mysql_query($query_str2, $sc) or die(mysql_error());
        $row_str2 = mysql_fetch_assoc($Restr2);
        $row_num2 = mysql_num_rows($Restr2);
    
    ?>

<div class="search_bt" align="center">

<ul style="float: left;margin-right: 8px;position: absolute;top: 25px;left: 45px" class="menu">
                  <li style="padding-left: 5px">
                     <a href="#section3"><img src="img/next-01.png" width="22px" alt="" style="transform: rotate(180deg)"></a>
                  </li>
                </ul>
    <ul style="float: right;position: absolute;top: 25px;right: 25px" class="menu">
                  <li style="padding-left: 5px">
                     <a href="#section5"><img src="img/next-01.png" width="22px" alt=""></a>
                  </li>
                </ul>
  <div style="width: 88%;margin-bottom: 30px" align="center"><h3>LIFE LINK註冊種類統計</h3></div>
      
      <div class="htmleaf-content">
      <div style="width:70%;margin:0 auto">
      <div>
        <canvas id="canvas2-2" height="280" width="600"></canvas>
      </div>
    </div>
    </div>
        <script src="js/Chart.js"></script>
  <script>
  var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
    var lineChartData = {
      labels : ["粉絲","小資","創業","企業","致富","總裁"],
      
      datasets : [
        {
          label: "My First dataset",
          fillColor : "rgba(220,220,220,0.2)",
          strokeColor : "rgba(220,220,220,1)",
          pointColor : "rgba(220,220,220,1)",
          pointStrokeColor : "#fff",
          pointHighlightFill : "#fff",
          pointHighlightStroke : "rgba(220,220,220,1)",
          data : [<?php do{
          echo "\"" ,$row_str2['member'], "\",";
          }while($row_str2 = mysql_fetch_assoc($Restr2));?>]
        }
      ]

    }

    
  </script>
<div style="text-align:center;clear:both">
</div>
  
</div>
<div class="search_table table-responsive " align="center" style="overflow-y: visible;white-space: nowrap;padding-top: 10px;position: absolute;top: 80px;width: 35%;left: 25px">
  <div class="search_bt" >
    <div  align="left" style="margin-left: 10px"><h3>人數統計</h3></div>
  </div>
  <ul align="center" class="table" style="width: 20%;margin: 10px auto 0px auto;line-height: 30px;background-color: #fff;float: left;" >
   
      <li >粉絲</li>
      <li>小資</li>
      <li>創業</li>
      <li>企業</li>
      <li>致富</li>
      <li>總裁</li>
 
  </ul>
  <ul align="center" class="table_s" style="margin: 10px auto 0px auto;line-height: 30px;background-color: #fff;float: left;width: 23%">  
    <?php

        mysql_select_db($database_sc, $sc);
    $query_str2 = 
    "SELECT a_pud, COUNT( a_pud ) AS member FROM  `memberdata` WHERE a_pud != '0'  GROUP BY a_pud";
        $Restr2 = mysql_query($query_str2, $sc) or die(mysql_error());
        $row_str2 = mysql_fetch_assoc($Restr2);
        $row_num2 = mysql_num_rows($Restr2);
    ?>
    
      <?php do{
      echo "<li>".$row_str2['member']. "</li>";
      }while($row_str2 = mysql_fetch_assoc($Restr2));?>
   
  </ul>
</div>
          </div></div>
    </li>
    <li id="section5" class="m_box">
      <div class="contant">
                <h3>消費族群分析</h3> 
          <div class="store_block">
            <form id="form4" action="manage_analyze_store.php#section5" method="get">
  <div class="search_bt" align="center" style="display: inline-block;text-align: left;">
 
  <ul style="float: left;margin-right: 8px;position: absolute;top: 25px;left: 45px" class="menu">
                  <li style="padding-left: 5px">
                     <a href="#section4"><img src="img/next-01.png" width="22px" alt="" style="transform: rotate(180deg)"></a>
                  </li>
                </ul>
  <ul style="float: right;margin-right: 8px;position: absolute;top: 25px;right: 45px" class="menu">
                  <li style="padding-left: 5px">
                     <a href="#section1"><img src="img/next-01.png" width="22px" alt="" ></a>
                  </li>
                </ul>
    <div style="margin-left: 15px" align="center" >
      <ul class="search_bar">
        <li> <input type="text" name="store" placeholder="輸入店家名稱"  value="<?php echo $_GET['store'];?>" style="height: 40px;-webkit-appearance:none;line-height: 40px;width: 70%;margin-left: 0px"></li>
        <li> <select name="industry" id="industry" placeholder="產業 : " style="width: 150px;text-align: left;height: 40px;line-height: 40px;border: 2px solid #8e78de">
              <option value="" style="color:#999999">選擇產業別</option>
              <option value="餐飲業">餐飲業</option>
              <option value="服飾業">服飾業</option>
              <option value="資訊業">資訊業</option>
              <option value="飯店業">飯店業</option>
              <option value="零售業">零售業</option>
              <option value="其他">其他</option>
              </select></li>
                                
        <li>
          <button type="button" style="margin-left: 8px;line-height: 35px;background-color:#8e78de;border-radius: 6px;color: #fff;border: 0px;width: 80px;font-size: 17px " class="date_but" onClick="check4()">查詢</button>
        </li>
      </ul>
    </div>
  </div>
</form>



<div class="search_bt" align="center">
  <div style="width: 88%;margin-bottom: 30px" align="center"><span><?php echo $_GET['industry'],$_GET['store'];?>消費族群分析</span></div>
      
      <div class="htmleaf-content">
    <div class="demo-chat" >
        <canvas id="canvas3-2" height="450" width="600"></canvas>
      </div>
    </div>
        <script src="js/Chart.js"></script>
  <script>
  var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
    var barChartData = {
      labels : ["0~20","21~40","41~60","61~80","81~100"],
      
      <?php
      
      ?>
      datasets : [
        {
          fillColor : "rgba(220,20,20,0.5)",
          strokeColor : "rgba(220,20,20,0.8)",
          highlightFill: "rgba(220,20,20,0.75)",
          highlightStroke: "rgba(220,20,20,1)",
          <?php
          //m_sex = F
              $result = array();
              mysql_select_db($database_lp, $lp);
              if($_GET['industry'] || $_GET['store']){
              $query_str = 
              "SELECT complete.s_nick,lf_user.industry,complete.p_user,
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) AS age,
              SUM( complete.c + complete.g ) AS total, 
              twliveli_a.memberdata.m_sex
              FROM 
              twliveli_a.memberdata INNER JOIN twliveli_lfpay.complete,
              twliveli_lfpay.lf_user
              WHERE complete.p_user = twliveli_a.memberdata.m_username &&
              complete.s_nick = lf_user.st_name &&
              twliveli_a.memberdata.m_sex =  'F' && 
              lf_user.industry like '%$industry%' &&
              complete.s_nick like '%$store%' &&
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) BETWEEN 0 AND 20
              GROUP BY age BETWEEN 0 AND 20
              ";
              $Restr = mysql_query($query_str, $lp) or die(mysql_error());
              $row_str = mysql_fetch_assoc($Restr);
              $row_num = mysql_num_rows($Restr);
              if($row_num != 0){
                  array_push( $result,$row_str['total']);
                  }else{
                  array_push( $result, '0');
                }
                $query_str = 
              "SELECT complete.s_nick,lf_user.industry,complete.p_user,
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) AS age,
              SUM( complete.c + complete.g ) AS total, 
              twliveli_a.memberdata.m_sex
              FROM 
              twliveli_a.memberdata INNER JOIN twliveli_lfpay.complete,
              twliveli_lfpay.lf_user
              WHERE complete.p_user = twliveli_a.memberdata.m_username &&
              complete.s_nick = lf_user.st_name &&
              twliveli_a.memberdata.m_sex =  'F' && 
              lf_user.industry like '%$industry%' &&
              complete.s_nick like '%$store%' &&
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) BETWEEN 21 AND 40
              GROUP BY age BETWEEN 21 AND 40
              ";
              $Restr = mysql_query($query_str, $lp) or die(mysql_error());
              $row_str = mysql_fetch_assoc($Restr);
              $row_num = mysql_num_rows($Restr);
              if($row_num != 0){
                  array_push( $result,$row_str['total']);
                  }else{
                  array_push( $result, '0');
                }
              $query_str = 
              "SELECT complete.s_nick,lf_user.industry,complete.p_user,
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) AS age,
              SUM( complete.c + complete.g ) AS total, 
              twliveli_a.memberdata.m_sex
              FROM 
              twliveli_a.memberdata INNER JOIN twliveli_lfpay.complete,
              twliveli_lfpay.lf_user
              WHERE complete.p_user = twliveli_a.memberdata.m_username &&
              complete.s_nick = lf_user.st_name &&
              twliveli_a.memberdata.m_sex =  'F' && 
              lf_user.industry like '%$industry%' &&
              complete.s_nick like '%$store%' &&
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) BETWEEN 41 AND 60
              GROUP BY age BETWEEN 41 AND 60
              ";
              $Restr = mysql_query($query_str, $lp) or die(mysql_error());
              $row_str = mysql_fetch_assoc($Restr);
              $row_num = mysql_num_rows($Restr);
              if($row_num != 0){
                  array_push( $result,$row_str['total']);
                  }else{
                  array_push( $result, '0');
                }
              $query_str = 
              "SELECT complete.s_nick,lf_user.industry,complete.p_user,
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) AS age,
              SUM( complete.c + complete.g ) AS total, 
              twliveli_a.memberdata.m_sex
              FROM 
              twliveli_a.memberdata INNER JOIN twliveli_lfpay.complete,
              twliveli_lfpay.lf_user
              WHERE complete.p_user = twliveli_a.memberdata.m_username &&
              complete.s_nick = lf_user.st_name &&
              twliveli_a.memberdata.m_sex =  'F' && 
              lf_user.industry like '%$industry%' &&
              complete.s_nick like '%$store%' &&
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) BETWEEN 61 AND 80
              GROUP BY age BETWEEN 61 AND 80
              ";
              $Restr = mysql_query($query_str, $lp) or die(mysql_error());
              $row_str = mysql_fetch_assoc($Restr);
              $row_num = mysql_num_rows($Restr);
              if($row_num != 0){
                  array_push( $result,$row_str['total']);
                  }else{
                  array_push( $result, '0');
                }
              $query_str = 
              "SELECT complete.s_nick,lf_user.industry,complete.p_user,
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) AS age,
              SUM( complete.c + complete.g ) AS total, 
              twliveli_a.memberdata.m_sex
              FROM 
              twliveli_a.memberdata INNER JOIN twliveli_lfpay.complete,
              twliveli_lfpay.lf_user
              WHERE complete.p_user = twliveli_a.memberdata.m_username &&
              complete.s_nick = lf_user.st_name &&
              twliveli_a.memberdata.m_sex =  'F' && 
              lf_user.industry like '%$industry%' &&
              complete.s_nick like '%$store%' &&
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) BETWEEN 81 AND 100
              GROUP BY age BETWEEN 81 AND 100
              ";
              $Restr = mysql_query($query_str, $lp) or die(mysql_error());
              $row_str = mysql_fetch_assoc($Restr);
              $row_num = mysql_num_rows($Restr);
              if($row_num != 0){
                  array_push( $result,$row_str['total']);
                  }else{
                  array_push( $result, '0');
                }
              ?>
          data : [<?php
          foreach($result as $value){
          echo "\"" .$value. "\",";
          }?>] 
          <? }else{
              $query_str = 
              "SELECT complete.s_nick,lf_user.industry,complete.p_user,
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) AS age,
              SUM( complete.c + complete.g ) AS total, 
              twliveli_a.memberdata.m_sex
              FROM 
              twliveli_a.memberdata INNER JOIN twliveli_lfpay.complete,
              twliveli_lfpay.lf_user
              WHERE complete.p_user = twliveli_a.memberdata.m_username &&
              complete.s_nick = lf_user.st_name &&
              twliveli_a.memberdata.m_sex =  'F' &&
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) BETWEEN 0 AND 20
              GROUP BY age BETWEEN 0 AND 20
              ";
              $Restr = mysql_query($query_str, $lp) or die(mysql_error());
              $row_str = mysql_fetch_assoc($Restr);
              $row_num = mysql_num_rows($Restr);
              if($row_num != 0){
                  array_push( $result,$row_str['total']);
                  }else{
                  array_push( $result, '0');
                }
              $query_str = 
              "SELECT complete.s_nick,lf_user.industry,complete.p_user,
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) AS age,
              SUM( complete.c + complete.g ) AS total, 
              twliveli_a.memberdata.m_sex
              FROM 
              twliveli_a.memberdata INNER JOIN twliveli_lfpay.complete,
              twliveli_lfpay.lf_user
              WHERE complete.p_user = twliveli_a.memberdata.m_username &&
              complete.s_nick = lf_user.st_name &&
              twliveli_a.memberdata.m_sex =  'F' &&
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) BETWEEN 21 AND 40
              GROUP BY age BETWEEN 21 AND 40
              ";
              $Restr = mysql_query($query_str, $lp) or die(mysql_error());
              $row_str = mysql_fetch_assoc($Restr);
              $row_num = mysql_num_rows($Restr);
              if($row_num != 0){
                  array_push( $result,$row_str['total']);
                  }else{
                  array_push( $result, '0');
                }
              $query_str = 
              "SELECT complete.s_nick,lf_user.industry,complete.p_user,
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) AS age,
              SUM( complete.c + complete.g ) AS total, 
              twliveli_a.memberdata.m_sex
              FROM 
              twliveli_a.memberdata INNER JOIN twliveli_lfpay.complete,
              twliveli_lfpay.lf_user
              WHERE complete.p_user = twliveli_a.memberdata.m_username &&
              complete.s_nick = lf_user.st_name &&
              twliveli_a.memberdata.m_sex =  'F' &&
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) BETWEEN 41 AND 60
              GROUP BY age BETWEEN 41 AND 60
              ";
              $Restr = mysql_query($query_str, $lp) or die(mysql_error());
              $row_str = mysql_fetch_assoc($Restr);
              $row_num = mysql_num_rows($Restr);
              if($row_num != 0){
                  array_push( $result,$row_str['total']);
                  }else{
                  array_push( $result, '0');
                }
              $query_str = 
              "SELECT complete.s_nick,lf_user.industry,complete.p_user,
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) AS age,
              SUM( complete.c + complete.g ) AS total, 
              twliveli_a.memberdata.m_sex
              FROM 
              twliveli_a.memberdata INNER JOIN twliveli_lfpay.complete,
              twliveli_lfpay.lf_user
              WHERE complete.p_user = twliveli_a.memberdata.m_username &&
              complete.s_nick = lf_user.st_name &&
              twliveli_a.memberdata.m_sex =  'F' &&
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) BETWEEN 61 AND 80
              GROUP BY age BETWEEN 61 AND 80
              ";
              $Restr = mysql_query($query_str, $lp) or die(mysql_error());
              $row_str = mysql_fetch_assoc($Restr);
              $row_num = mysql_num_rows($Restr);
              if($row_num != 0){
                  array_push( $result,$row_str['total']);
                  }else{
                  array_push( $result, '0');
                }
              $query_str = 
              "SELECT complete.s_nick,lf_user.industry,complete.p_user,
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) AS age,
              SUM( complete.c + complete.g ) AS total, 
              twliveli_a.memberdata.m_sex
              FROM 
              twliveli_a.memberdata INNER JOIN twliveli_lfpay.complete,
              twliveli_lfpay.lf_user
              WHERE complete.p_user = twliveli_a.memberdata.m_username &&
              complete.s_nick = lf_user.st_name &&
              twliveli_a.memberdata.m_sex =  'F' &&
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) BETWEEN 81 AND 100
              GROUP BY age BETWEEN 81 AND 100
              ";
              $Restr = mysql_query($query_str, $lp) or die(mysql_error());
              $row_str = mysql_fetch_assoc($Restr);
              $row_num = mysql_num_rows($Restr);
              if($row_num != 0){
                  array_push( $result,$row_str['total']);
                  }else{
                  array_push( $result, '0');
                }
              ?>
          data : [<?php
          foreach($result as $value){
          echo "\"" .$value. "\",";
          }?>]
          <?php } ?>
          
        },
        
        {
          fillColor : "rgba(15,18,205,0.5)",
          strokeColor : "rgba(15,18,205,0.8)",
          highlightFill : "rgba(15,18,205,0.75)",
          highlightStroke : "rgba(15,18,205,1)",
          <?php
          //m_sex = M
              $result = array();
              mysql_select_db($database_lp, $lp);
              if($_GET['industry'] || $_GET['store']){
              $query_str = 
              "SELECT complete.s_nick,lf_user.industry,complete.p_user,
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) AS age,
              SUM( complete.c + complete.g ) AS total, 
              twliveli_a.memberdata.m_sex
              FROM 
              twliveli_a.memberdata INNER JOIN twliveli_lfpay.complete,
              twliveli_lfpay.lf_user
              WHERE complete.p_user = twliveli_a.memberdata.m_username &&
              complete.s_nick = lf_user.st_name &&
              twliveli_a.memberdata.m_sex =  'M' && 
              lf_user.industry like '%$industry%' &&
              complete.s_nick like '%$store%' &&
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) BETWEEN 0 AND 20
              GROUP BY age BETWEEN 0 AND 20
              ";
              $Restr = mysql_query($query_str, $lp) or die(mysql_error());
              $row_str = mysql_fetch_assoc($Restr);
              $row_num = mysql_num_rows($Restr);
              if($row_num != 0){
                  array_push( $result,$row_str['total']);
                  }else{
                  array_push( $result, '0');
                }
              $query_str = 
              "SELECT complete.s_nick,lf_user.industry,complete.p_user,
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) AS age,
              SUM( complete.c + complete.g ) AS total, 
              twliveli_a.memberdata.m_sex
              FROM 
              twliveli_a.memberdata INNER JOIN twliveli_lfpay.complete,
              twliveli_lfpay.lf_user
              WHERE complete.p_user = twliveli_a.memberdata.m_username &&
              complete.s_nick = lf_user.st_name &&
              twliveli_a.memberdata.m_sex =  'M' && 
              lf_user.industry like '%$industry%' &&
              complete.s_nick like '%$store%' &&
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) BETWEEN 21 AND 40
              GROUP BY age BETWEEN 21 AND 40
              ";
              $Restr = mysql_query($query_str, $lp) or die(mysql_error());
              $row_str = mysql_fetch_assoc($Restr);
              $row_num = mysql_num_rows($Restr);
              if($row_num != 0){
                  array_push( $result,$row_str['total']);
                  }else{
                  array_push( $result, '0');
                }
              $query_str = 
              "SELECT complete.s_nick,lf_user.industry,complete.p_user,
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) AS age,
              SUM( complete.c + complete.g ) AS total, 
              twliveli_a.memberdata.m_sex
              FROM 
              twliveli_a.memberdata INNER JOIN twliveli_lfpay.complete,
              twliveli_lfpay.lf_user
              WHERE complete.p_user = twliveli_a.memberdata.m_username &&
              complete.s_nick = lf_user.st_name &&
              twliveli_a.memberdata.m_sex =  'M' && 
              lf_user.industry like '%$industry%' &&
              complete.s_nick like '%$store%' &&
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) BETWEEN 41 AND 60
              GROUP BY age BETWEEN 41 AND 60
              ";
              $Restr = mysql_query($query_str, $lp) or die(mysql_error());
              $row_str = mysql_fetch_assoc($Restr);
              $row_num = mysql_num_rows($Restr);
              if($row_num != 0){
                  array_push( $result,$row_str['total']);
                  }else{
                  array_push( $result, '0');
                }
              $query_str = 
              "SELECT complete.s_nick,lf_user.industry,complete.p_user,
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) AS age,
              SUM( complete.c + complete.g ) AS total, 
              twliveli_a.memberdata.m_sex
              FROM 
              twliveli_a.memberdata INNER JOIN twliveli_lfpay.complete,
              twliveli_lfpay.lf_user
              WHERE complete.p_user = twliveli_a.memberdata.m_username &&
              complete.s_nick = lf_user.st_name &&
              twliveli_a.memberdata.m_sex =  'M' && 
              lf_user.industry like '%$industry%' &&
              complete.s_nick like '%$store%' &&
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) BETWEEN 61 AND 80
              GROUP BY age BETWEEN 61 AND 80
              ";
              $Restr = mysql_query($query_str, $lp) or die(mysql_error());
              $row_str = mysql_fetch_assoc($Restr);
              $row_num = mysql_num_rows($Restr);
              if($row_num != 0){
                  array_push( $result,$row_str['total']);
                  }else{
                  array_push( $result, '0');
                }
              $query_str = 
              "SELECT complete.s_nick,lf_user.industry,complete.p_user,
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) AS age,
              SUM( complete.c + complete.g ) AS total, 
              twliveli_a.memberdata.m_sex
              FROM 
              twliveli_a.memberdata INNER JOIN twliveli_lfpay.complete,
              twliveli_lfpay.lf_user
              WHERE complete.p_user = twliveli_a.memberdata.m_username &&
              complete.s_nick = lf_user.st_name &&
              twliveli_a.memberdata.m_sex =  'M' && 
              lf_user.industry like '%$industry%' &&
              complete.s_nick like '%$store%' &&
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) BETWEEN 81 AND 100
              GROUP BY age BETWEEN 81 AND 100
              ";
              $Restr = mysql_query($query_str, $lp) or die(mysql_error());
              $row_str = mysql_fetch_assoc($Restr);
              $row_num = mysql_num_rows($Restr);
              if($row_num != 0){
                  array_push( $result,$row_str['total']);
                  }else{
                  array_push( $result, '0');
                }
              ?>
          data : [<?php
          foreach($result as $value){
          echo "\"" .$value. "\",";
          }?>]
          <?php }else {
          $query_str = 
              "SELECT complete.s_nick,lf_user.industry,complete.p_user,
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) AS age,
              SUM( complete.c + complete.g ) AS total, 
              twliveli_a.memberdata.m_sex
              FROM 
              twliveli_a.memberdata INNER JOIN twliveli_lfpay.complete,
              twliveli_lfpay.lf_user
              WHERE complete.p_user = twliveli_a.memberdata.m_username &&
              complete.s_nick = lf_user.st_name &&
              twliveli_a.memberdata.m_sex =  'M' &&
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) BETWEEN 0 AND 20
              GROUP BY age BETWEEN 0 AND 20
              ";
              $Restr = mysql_query($query_str, $lp) or die(mysql_error());
              $row_str = mysql_fetch_assoc($Restr);
              $row_num = mysql_num_rows($Restr);
              if($row_num != 0){
                  array_push( $result,$row_str['total']);
                  }else{
                  array_push( $result, '0');
                }
              $query_str = 
              "SELECT complete.s_nick,lf_user.industry,complete.p_user,
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) AS age,
              SUM( complete.c + complete.g ) AS total, 
              twliveli_a.memberdata.m_sex
              FROM 
              twliveli_a.memberdata INNER JOIN twliveli_lfpay.complete,
              twliveli_lfpay.lf_user
              WHERE complete.p_user = twliveli_a.memberdata.m_username &&
              complete.s_nick = lf_user.st_name &&
              twliveli_a.memberdata.m_sex =  'M' &&
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) BETWEEN 21 AND 40
              GROUP BY age BETWEEN 21 AND 40
              ";
              $Restr = mysql_query($query_str, $lp) or die(mysql_error());
              $row_str = mysql_fetch_assoc($Restr);
              $row_num = mysql_num_rows($Restr);
              if($row_num != 0){
                  array_push( $result,$row_str['total']);
                  }else{
                  array_push( $result, '0');
                }
              $query_str = 
              "SELECT complete.s_nick,lf_user.industry,complete.p_user,
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) AS age,
              SUM( complete.c + complete.g ) AS total, 
              twliveli_a.memberdata.m_sex
              FROM 
              twliveli_a.memberdata INNER JOIN twliveli_lfpay.complete,
              twliveli_lfpay.lf_user
              WHERE complete.p_user = twliveli_a.memberdata.m_username &&
              complete.s_nick = lf_user.st_name &&
              twliveli_a.memberdata.m_sex =  'M' &&
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) BETWEEN 41 AND 60
              GROUP BY age BETWEEN 41 AND 60
              ";
              $Restr = mysql_query($query_str, $lp) or die(mysql_error());
              $row_str = mysql_fetch_assoc($Restr);
              $row_num = mysql_num_rows($Restr);
              if($row_num != 0){
                  array_push( $result,$row_str['total']);
                  }else{
                  array_push( $result, '0');
                }
              $query_str = 
              "SELECT complete.s_nick,lf_user.industry,complete.p_user,
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) AS age,
              SUM( complete.c + complete.g ) AS total, 
              twliveli_a.memberdata.m_sex
              FROM 
              twliveli_a.memberdata INNER JOIN twliveli_lfpay.complete,
              twliveli_lfpay.lf_user
              WHERE complete.p_user = twliveli_a.memberdata.m_username &&
              complete.s_nick = lf_user.st_name &&
              twliveli_a.memberdata.m_sex =  'M' &&
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) BETWEEN 61 AND 80
              GROUP BY age BETWEEN 61 AND 80
              ";
              $Restr = mysql_query($query_str, $lp) or die(mysql_error());
              $row_str = mysql_fetch_assoc($Restr);
              $row_num = mysql_num_rows($Restr);
              if($row_num != 0){
                  array_push( $result,$row_str['total']);
                  }else{
                  array_push( $result, '0');
                }
              $query_str = 
              "SELECT complete.s_nick,lf_user.industry,complete.p_user,
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) AS age,
              SUM( complete.c + complete.g ) AS total, 
              twliveli_a.memberdata.m_sex
              FROM 
              twliveli_a.memberdata INNER JOIN twliveli_lfpay.complete,
              twliveli_lfpay.lf_user
              WHERE complete.p_user = twliveli_a.memberdata.m_username &&
              complete.s_nick = lf_user.st_name &&
              twliveli_a.memberdata.m_sex =  'M' &&
              YEAR(CURRENT_DATE())-YEAR(twliveli_a.memberdata.m_birthday) BETWEEN 81 AND 100
              GROUP BY age BETWEEN 81 AND 100
              ";
              $Restr = mysql_query($query_str, $lp) or die(mysql_error());
              $row_str = mysql_fetch_assoc($Restr);
              $row_num = mysql_num_rows($Restr);
              if($row_num != 0){
                  array_push( $result,$row_str['total']);
                  }else{
                  array_push( $result, '0');
                }
              ?>
          data : [<?php
          foreach($result as $value){
          echo "\"" .$value. "\",";
          }?>]
          <?php } ?>
        }
      ]

    }

  window.onload = function(){
    var ctx = document.getElementById("chart-area").getContext("2d");
            window.myDoughnut = new Chart(ctx).Doughnut(doughnutData, {responsive : true});
      var ctx = document.getElementById("canvas").getContext("2d");
    window.myLine = new Chart(ctx).Line(lineChartData1, {
      responsive: true
    });
    var ctx = document.getElementById("canvas2").getContext("2d");
    window.myLine = new Chart(ctx).Line(lineChartData, {
      responsive: true
    });
    var ctx = document.getElementById("canvas3").getContext("2d");
    window.myBar = new Chart(ctx).Bar(barChartData, {
      responsive : false
    });
    var ctx = document.getElementById("chart-area-2").getContext("2d");
            window.myDoughnut = new Chart(ctx).Doughnut(doughnutData, {responsive : false});
      var ctx = document.getElementById("canvas-2").getContext("2d");
    window.myLine = new Chart(ctx).Line(lineChartData1, {
      responsive: true
    });
    var ctx = document.getElementById("canvas2-2").getContext("2d");
    window.myLine = new Chart(ctx).Line(lineChartData, {
      responsive: true
    });
    var ctx = document.getElementById("canvas3-2").getContext("2d");
    window.myBar = new Chart(ctx).Bar(barChartData, {
      responsive : true
    });

  }
    
    
  </script>
<div style="text-align:center;clear:both">
</div>
  
</div>
<div class="search_table table-responsive " align="center" style="overflow-y: visible;white-space: nowrap;padding-top: 10px">
  <div class="search_bt" align="center">
    <div style="width: 88%" align="center"><span>銷售紀錄</span></div>
  </div>
  <table align="center" class="table" style="width: 88%;margin: 10px auto 0px auto;line-height: 45px;background-color: #fff;border-radius: 10px" >
    <tr style="background-color: #6D4EDE;color: #fff ">
      <th style="border-radius: 10px 0px 0px 0px">商店名稱</th>
      <th>產業別</th>
      <th>銷售金額</th>
      <th>消費積分</th>
      <th>串串積分</th>
      <th style="border-radius: 0px 10px 0px 0px">支付店家</th>
    </tr>
    <?php /*?><?php
    if ($_GET['sd1'] != "") { //日期&商家
        $sd1=$_GET['sd1'];
        $sd2=$_GET['sd2'];
        mysql_select_db($database_lp, $lp);
    $query_str = 
    "SELECT Invoice.*, YEAR( Invoice.date ) AS YEAR, MONTH( Invoice.date ) AS MONTH , lf_user.industry 
    FROM Invoice, lf_user 
    WHERE Invoice.accont = lf_user.accont && Invoice.nick like '%$store%' && Invoice.date >= '$sd1' && Invoice.date <= '$sd2' && lf_user.industry like '%$industry%' 
    GROUP BY Invoice.total, YEAR( DATE ) , MONTH( DATE )  
    ORDER BY id DESC";
        $Restr = mysql_query($query_str, $lp) or die(mysql_error());
        $row_str = mysql_fetch_assoc($Restr);
        $row_num = mysql_num_rows($Restr);
    $nick = $row_str['nick'];
    $industcheck = $row_str['industry'];
    
        } else {<?php */?>
        <?php 
        mysql_select_db($database_lp, $lp); //無日期
    $query_str = 
    "SELECT Invoice.*, YEAR( Invoice.date ) AS YEAR, MONTH( Invoice.date ) AS MONTH , lf_user.industry 
    FROM Invoice, lf_user 
    WHERE Invoice.accont = lf_user.accont && Invoice.nick like '%$store%' && lf_user.industry like '%$industry%' 
    GROUP BY Invoice.total, YEAR( DATE ) , MONTH( DATE ) 
    ORDER BY id DESC";
        $Restr = mysql_query($query_str, $lp) or die(mysql_error());
        $row_str = mysql_fetch_assoc($Restr);
        $row_num = mysql_num_rows($Restr);
    $nick = $row_str['nick'];
    $industcheck = $row_str['industry'];
    

  $a=false;
  $b=false;
  
    if($row_num != 0){
      do{ 
      //銷售金額
        $total_sell = $row_str['total'];
        $sell_sum = $sell_sum + $total_sell;
        //消費積分總額
        $g = $row_str['g'];
        $g_sum = $g_sum + $g;
        //串串積分總額
        $c = $row_str['c'];
        $c_sum = $c_sum + $c;
        //支付店家
        $usage_fee = $row_str['usage_fee'];
        $usage_fee_sum = $usage_fee_sum + $usage_fee;
    
    $nick_nick = $row_str['nick'];
    $industcheck_check = $row_str['industry'];

        if(strcmp($nick,$nick_nick)!=0){ //比對是否相等
            $a= true;
        }
    
    if(strcmp($industcheck,$industcheck_check)!=0){
      $b= true;
      }


      }while($row_str = mysql_fetch_assoc($Restr));}?>
    <tr class="search_tr"  style="background-color: #eee">
      <td style="border-radius: 0px 0px 0px 10px">
      <?php
          if($a){
            echo '</td>';
          }else{
            echo ''.$nick.'</td>';
          }
          ?>
      <?php
          if($b){
            echo '<td></td>';
          }else{
            echo '<td>'.$industcheck.'</td>';
          }
          ?>
      <td><?php echo number_format($sell_sum);?></td>
      <td><?php echo number_format($g_sum);?></td>
      <td><?php echo number_format($c_sum);?></td>
      <td style="border-radius: 0px 0px 10px 0px"><?php echo number_format($usage_fee_sum);?></td>
    </tr>
  </table>
</div>
          </div></div>
    </li></ul>
  </div>
</div>


</body>
</html>
