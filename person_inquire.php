<?php 
require_once('Connections/lp.php');mysql_query("set names utf8mb4");
?>
<?php 
session_start();
if ($_SESSION['MM_Username'] == ""){header(sprintf("Location: life_pay.php"));exit;
}else{
  $m_username = $_SESSION['mem'];//登入帳號
  $m_nick = $_SESSION['nick'];//登入暱稱
  $number = $_SESSION['number'];
}

require_once("lp_user5.php");
?>
<script src="js/jquery-ui.js"></script>
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
  document.getElementById('person_inquire').submit();
  }
}
function goBack() {
    window.history.back();
  }
  
function rate(id){
	
	$(function() {
		y=$(window).width(); //螢幕寬度
		x=$(window).height();//螢幕高度
		if(y>"1024"){y=y/2;}else{y=y/1;}
		$( ".dialog_rate"+id ).dialog( "open");
	});
	
	$( ".dialog_rate"+id ).dialog
		({
			show: {
				effect: "fade",
			  },
			autoOpen: false, //預設不顯示
			draggable: false, //設定拖拉
			resizable: true, //設定縮放
			//dialogClass: "no-close", //關閉標題
			height: "auto",
			width: "auto",
			modal: true, //灰色透明背景限制只能按dialog
				
		})

  $('.rate'+id).click(function(event){
      $(".dialog_rate"+id).dialog( "close" );
      $('#'+id).prop('disabled',true);
  })
}

function starttorate(id){
	var rate = document.getElementsByName('rating'+id);
	var rt_level;
	var rt_content = document.getElementById('rt_content'+id).value;
	var bt = document.getElementById('bt'+id);
	//alert(rate.length);
		for (var i = 0; i < rate.length; i++) {
			if (rate[i].type === "radio" && rate[i].checked) {
				rt_level = rate[i].value;
			}
		}
	$(document).ready(function() {
		//ajax 送表單
	$.ajax({
			type: "POST",
			url: "rate_data.php",
			dataType: "text",
			data: {
				rt_level: rt_level,
				id: id,
				rt_content: rt_content
			},
			success: function(data) {
			  value = JSON.parse(data); 
			  var id = value.id;
			  var level =value.level;
			  
			  $("#comment"+id).remove("#bt"+id);
			  var ht = $("#st"+value.id).html();

			  $("#comment"+id).html(ht);
			   
				 if(level != 0)
				 {
					document.getElementById('star'+level+id).checked=true;

				 }
			  
			}
		})
	})
}
</script>
</head>
<style>
  @media (min-width: 1200px){
    .search_bt{padding-left: 7.1%}
  }
  @media (min-width: 1024px){
    .search_bt{padding-left: 7.1%}
  }
  @media (max-width: 1024px) {
	.real {
		right: 14%
	}
  }
  @media (min-width: 1025px) {
	.real {
		right: 8%
	}
  }
   @media (min-width: 1200px) {
	.real {
		right: 11%
	}
  }
  @media (min-width: 1300px) {
	.real {
		right: 6%
	}
  }
   @media (min-width: 1366px) {
	.real {
		right: 1%
	}
  }
  @media (min-width: 1540px) {
	.real {
		right: -2%
	}
  }
  @media (min-width: 1640px) {
	.real {
		right: -5%
	}
  }
  @media (min-width: 1740px) {
	.real {
		right: -8%
	}
  }
  @media (min-width: 1840px) {
	.real {
		right: -11%
	}
  }
  @media (min-width: 1940px) {
	.real {
		right: -14%
	}
  }
  @media (min-width: 2040px) {
	.real {
		right: -16%
	}
  }
</style>
<body style="min-height: 800px">


<div style="margin-top: 60px"></div>

<form id="person_inquire" action="person_inquire.php" method="get">
	<div class="search_bt" align="center" style="display: inline-block;text-align: left;">
		<div style="width: 90%" align="center" >
    <ul class="person_search">
      <li><input type="date" name="sd1"  id="sd1" value="<?php echo $_GET['sd1'];?>" style="height: 40px;min-width: 120px; display: inline-block;background-color: #A1A1A1;border-radius: 6px;color: #fff"></li>
      <li><span style="margin: 4px;line-height: 40px">至</span></li>
      <li><input type="date" name="sd2"  id="sd2" value="<?php echo $_GET['sd2'];?>" style="height: 40px;min-width: 120px;display: inline-block;background-color: #A1A1A1;border-radius: 6px;color: #fff"></li>
      <li><span style="margin: 4px;line-height: 40px">在</span></li>
      <li> <input type="text" name="store" placeholder="輸入店家名稱"  value="<?php echo $_GET['store'];?>" style="height: 40px;border:1px solid #fff;-webkit-appearance:none;padding: 5px;background-color:#A1A1A1;border-radius: 6px "></li>
      <li><button type="button" style="margin-left: 8px;line-height: 35px" class="date_but" onClick="check()">查詢</button></li>
    </ul>
    </div>
	</div>  
</form>
  <div class="search_table table-responsive " align="center" style="overflow-y: visible;white-space: nowrap;">

    
    <table align="center" class="table" style="width: 88%;margin: 10px auto 0px auto;line-height: 45px;background-color: #fff;border-radius: 10px" >
    <tr style="background-color: #4ab3a6;color: #fff ">
	  <th style="border-radius: 10px 0px 0px 0px">個人日期</th>
	  <th>消費店家</th>
	  <th>消費金額</th>
    <th>抵用券</th>
	  <th>消費積分</th>
	  <th>串串積分</th>
    <th style="border-radius: 0px 10px 0px 0px">現金/信用卡</th>
		</tr>
    <?php 

    if ($_GET['sd1'] != "" || $_GET['sd2'] != "" && $_GET['store'] != "") { //日期&商家
        $sd1=$_GET['sd1'];
        $sd2=$_GET['sd2'];
        $store=$_GET['store'];
        mysql_select_db($database_lp, $lp);
        $query_str = "SELECT * FROM complete WHERE p_number ='$number' && date >= '$sd1' && date <= '$sd2' && s_nick like '%$store%' ORDER BY id DESC";
        $Restr = mysql_query($query_str, $lp) or die(mysql_error());
        $row_str = mysql_fetch_assoc($Restr);
        $row_num = mysql_num_rows($Restr);
        $s_nick = $row_str['s_nick'];
        } else if($_GET['store'] != "") { //商家
        $store=$_GET['store'];
        mysql_select_db($database_lp, $lp);
        $query_str = "SELECT * FROM complete WHERE p_number ='$number' && s_nick like '%$store%' ORDER BY id DESC";
        $Restr = mysql_query($query_str, $lp) or die(mysql_error());
        $row_str = mysql_fetch_assoc($Restr);
        $row_num = mysql_num_rows($Restr);
        $s_nick = $row_str['s_nick'];
        } else {
        mysql_select_db($database_lp, $lp); //無條件
        $query_str = "SELECT * FROM complete WHERE p_number ='$number'  ORDER BY id DESC";
        $Restr = mysql_query($query_str, $lp) or die(mysql_error());
        $row_str = mysql_fetch_assoc($Restr);
        $row_num = mysql_num_rows($Restr);
    }

    $a=false;

    if($row_num != 0){
      do{ 
        $tatal_cost = $row_str['total_cost'];
        $consume_sum = $consume_sum + $tatal_cost;
        //使用券總額
        $discount = $row_str['discount'];
        $discount_sum = $discount_sum + $discount;
        //消費積分總額
        $g = $row_str['g'];
        $g_sum = $g_sum + $g;
        //串串積分總額
        $c = $row_str['c'];
        $c_sum = $c_sum + $c;
        //實際消費額
        $spend = $row_str['spend'];
        $spend_sum = $spend_sum + $spend;

        $nick_nick = $row_str['s_nick'];

        if(strcmp($s_nick,$nick_nick)!=0){ //比對是否相等
            $a= true;
        }

      }while($row_str = mysql_fetch_assoc($Restr));}?>

        <tr class="search_tr"  style="background-color: #fff">
          <td style="border-radius: 0px 0px 0px 10px"><?php echo $sd1."~".$sd2;?></td>
          <?php
          if($a){
            echo '<td></td>';
          }else{
            echo '<td>'.$s_nick.'</td>';
          }
          ?>
          <td><?php echo number_format($consume_sum);?></td>
          <td><?php echo number_format($discount_sum);?></td>
          <td><?php echo number_format($g_sum);?></td>
          <td><?php echo number_format($c_sum);?></td>
          <td class="table_br" style="border-radius: 0px 0px 10px 0px"><?php echo number_format($spend_sum);?></td>
        </tr>
      
    </table>
    



<!--消費明細-->
<?php
	if ($_GET['sd1'] != "" || $_GET['sd2'] != "" && $_GET['store'] != "") { //日期&商家
$sd1=$_GET['sd1'];
$sd2=$_GET['sd2'];
$store=$_GET['store'];
mysql_select_db($database_lp, $lp);
$query_str = "SELECT * FROM complete WHERE p_number ='$number' && date >= '$sd1' && date <= '$sd2' && s_nick like '%$store%' ORDER BY id DESC";
$Restr = mysql_query($query_str, $lp) or die(mysql_error());
$row_str = mysql_fetch_assoc($Restr);
$row_num = mysql_num_rows($Restr);
} else if($_GET['store'] != "") { //商家
$store=$_GET['store'];
mysql_select_db($database_lp, $lp);
$query_str = "SELECT * FROM complete WHERE p_number ='$number' && s_nick like '%$store%' ORDER BY id DESC";
$Restr = mysql_query($query_str, $lp) or die(mysql_error());
$row_str = mysql_fetch_assoc($Restr);
$row_num = mysql_num_rows($Restr);
} else {
mysql_select_db($database_lp, $lp); //無條件
$query_str = "SELECT * FROM complete WHERE p_number ='$number'  ORDER BY id DESC";
$Restr = mysql_query($query_str, $lp) or die(mysql_error());
$row_str = mysql_fetch_assoc($Restr);
$row_num = mysql_num_rows($Restr);
}


	?>
    
        <table align="center" class="table coupon_table" style="background-color: #fff;border-radius: 10px" >
    <tr style="background-color: #4ab3a6;color: #fff ">
	  <th style="border-radius: 10px 0px 0px 0px">消費日期</th>
	  <th>消費店家</th>
	  <th>消費金額</th>
    <th>抵用券額</th>
	  <th>消費積分</th>
	  <th>串串積分</th>
    <th>付費金額</th>
    <th>付款情形</th>
    <th>評價</th>
    <th  style="border-radius: 0px 10px 0px 0px">抵用券回饋額</th>
		</tr>
    <?php if($row_num != 0){
      do{
	  	?>
        <tr class="search_tr"  style="background-color: #fff">
          <td  style="border-radius: 0px 0px 0px 10px"><?php echo $row_str['date'];?></td>
          <td><?php echo $row_str['s_nick'];?></td>
          <td><?php echo number_format($row_str['total_cost']);?></td>
          <td><?php echo number_format($row_str['discount']);?></td>
          <td><?php echo number_format($row_str['g']);?></td>
          <td><?php echo number_format($row_str['c']);?></td>
          <td><?php echo number_format($row_str['spend']);?></td>
          <?php if($row_str['confirm']==1){?>
            <td  ><span style="color: #22a363">已扣款</span></td>
            <?php }else{?>
            <td ><span style="color: #ff5e59">未扣款</span></td>
            <?php }?>
            <td id="comment<?php echo $row_str['ID']; ?>">
				
				<?php 
				 $RtLevel =  $row_str["rt_level"];
				 if($RtLevel != 0)
				 { ?>
					
					 <fieldset id="<?php echo $row_str['ID']; ?>" class="rating real">
						<input type="radio"   id="star5<?php echo $row_str['ID']; ?>" name="rate<?php echo $row_str['ID']; ?>" value="5" />
						<label class = "full" for="star5<?php echo $row_str['ID']; ?>" title="夭壽讚"></label>
						
						<input type="radio" id="star4.5<?php echo $row_str['ID']; ?>" name="rate<?php echo $row_str['ID']; ?>" value="4.5" />
						<label class="half" for="star4half<?php echo $row_str['ID']; ?>" title="非常好"></label>
						
						<input type="radio" id="star4<?php echo $row_str['ID']; ?>" name="rate<?php echo $row_str['ID']; ?>" value="4" />
						<label class = "full" for="star4<?php echo $row_str['ID']; ?>" title="很棒"></label>
						
						<input type="radio" id="star3.5<?php echo $row_str['ID']; ?>" name="rate<?php echo $row_str['ID']; ?>" value="3.5" />
						<label class="half" for="star3half<?php echo $row_str['ID']; ?>" title="還行啦"></label>
						
						<input type="radio" id="star3<?php echo $row_str['ID']; ?>" name="rate<?php echo $row_str['ID']; ?>" value="3" />
						<label class = "full" for="star3<?php echo $row_str['ID']; ?>" title="普普通通"></label>
						
						<input type="radio" id="star2.5<?php echo $row_str['ID']; ?>" name="rate<?php echo $row_str['ID']; ?>" value="2.5" />
						<label class="half" for="star2half<?php echo $row_str['ID']; ?>" title="有點糟"></label>
						
						<input type="radio" id="star2<?php echo $row_str['ID']; ?>" name="rate<?php echo $row_str['ID']; ?>" value="2" />
						<label class = "full" for="star2<?php echo $row_str['ID']; ?>" title="蠻糟的"></label>
						
						<input type="radio" id="star1.5<?php echo $row_str['ID']; ?>" name="rate<?php echo $row_str['ID']; ?>" value="1.5" />
						<label class="half" for="star1half<?php echo $row_str['ID']; ?>" title="有點悲劇"></label>
						
						<input type="radio" id="star1<?php echo $row_str['ID']; ?>" name="rate<?php echo $row_str['ID']; ?>" value="1" />
						<label class = "full" for="star1<?php echo $row_str['ID']; ?>" title="呃...."></label>
						
						<input type="radio" id="star0.5<?php echo $row_str['ID']; ?>" name="rate<?php echo $row_str['ID']; ?>" value="0.5" />
						<label class="half" for="starhalf<?php echo $row_str['ID']; ?>" title="!@#$%^&*"></label>
						
					</fieldset>
				
					<script>
					 var RtLevel = "<?php echo $RtLevel;?>";
					 var id = "<?php echo $row_str['ID'];?>";
					 if(RtLevel != 0)
					 {
						document.getElementById('star'+RtLevel+id).checked=true
					 }
					</script>
				
				<?php
				 }else{?>
				
				 <button id="bt<?php echo $row_str['ID'];?>" class="<?php echo $row_str['ID'];?> date_but" onClick="rate(<?php echo $row_str['ID'];?>);" style="width:100%;background: #487be5">評價</button>
				
				<?php 
				 }?>
			</td>
          <td class="table_br" style="border-radius: 0px 0px 10px 0px">
			<a href="person_coupon.php"><?php echo number_format($row_str['next_discount']);?></a>
		  </td>
        </tr>
        
        <div class="dialog_rate<?php echo $row_str['ID']; ?>" id="dialog_rate<?php echo $row_str['ID'];?>" style="display:none;text-align:center; line-height:112px;">
            <div class="ex_span" style="font-size:21px;line-height: 35px">
				<form id="form2" method="get" action="person_inquire.php">
				<div id="st<?php echo $row_str['ID']; ?>">
					<fieldset id="<?php echo $row_str['ID']; ?>" class="rating">
						<input type="radio" id="star5<?php echo $row_str['ID']; ?>" name="rating<?php echo $row_str['ID']; ?>" value="5" />
						<label class = "full" for="star5<?php echo $row_str['ID']; ?>" title="夭壽讚"></label>
						
						<input type="radio" id="star4.5<?php echo $row_str['ID']; ?>" name="rating<?php echo $row_str['ID']; ?>" value="4.5" />
						<label class="half" for="star4.5<?php echo $row_str['ID']; ?>" title="非常好"></label>
						
						<input type="radio" id="star4<?php echo $row_str['ID']; ?>" name="rating<?php echo $row_str['ID']; ?>" value="4" />
						<label class = "full" for="star4<?php echo $row_str['ID']; ?>" title="很棒"></label>
						
						<input type="radio" id="star3.5<?php echo $row_str['ID']; ?>" name="rating<?php echo $row_str['ID']; ?>" value="3.5" />
						<label class="half" for="star3.5<?php echo $row_str['ID']; ?>" title="還行啦"></label>
						
						<input type="radio" id="star3<?php echo $row_str['ID']; ?>" name="rating<?php echo $row_str['ID']; ?>" value="3" />
						<label class = "full" for="star3<?php echo $row_str['ID']; ?>" title="普普通通"></label>
						
						<input type="radio" id="star2.5<?php echo $row_str['ID']; ?>" name="rating<?php echo $row_str['ID']; ?>" value="2.5" />
						<label class="half" for="star2.5<?php echo $row_str['ID']; ?>" title="有點糟"></label>
						
						<input type="radio" id="star2<?php echo $row_str['ID']; ?>" name="rating<?php echo $row_str['ID']; ?>" value="2" />
						<label class = "full" for="star2<?php echo $row_str['ID']; ?>" title="蠻糟的"></label>
						
						<input type="radio" id="star1.5<?php echo $row_str['ID']; ?>" name="rating<?php echo $row_str['ID']; ?>" value="1.5" />
						<label class="half" for="star1.5<?php echo $row_str['ID']; ?>" title="有點悲劇"></label>
						
						<input type="radio" id="star1<?php echo $row_str['ID']; ?>" name="rating<?php echo $row_str['ID']; ?>" value="1" />
						<label class = "full" for="star1<?php echo $row_str['ID']; ?>" title="呃...."></label>
						
						<input type="radio" id="star0.5<?php echo $row_str['ID']; ?>" name="rating<?php echo $row_str['ID']; ?>" value="0.5" />
						<label class="half" for="star0.5<?php echo $row_str['ID']; ?>" title="!@#$%^&*"></label>
						
					</fieldset>
				</div>
        </div>
			<div class="ex_span" style="font-size:21px;line-height: 35px">評價內容</div>
            <textarea id="rt_content<?php echo $row_str['ID']; ?>" class="rt_content" style="width: 300px;text-align: left;height: 300px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;"><?php echo $row_str['rt_content']; ?></textarea>
            <input name="rt_date" class="rt_date<?php echo $row_str['ID']; ?>" type="hidden" value="<?php echo $date;?>" />
            <input	type="button" class="rate<?php echo $row_str['ID']; ?> date_but" onClick="starttorate(<?php echo $row_str['ID']; ?>)" style="width:100%;background: #487be5" value="送出">
        </div>
        </form>
      <?php 
  	  
  	  }while($row_str = mysql_fetch_assoc($Restr));

          

      }?>
        
    </table>
  </div>
<div style="height: 60px"></div>