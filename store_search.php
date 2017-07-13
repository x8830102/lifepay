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
$row_em = mysql_fetch_assoc($con_em);
$level = $row_em['level'];
$st_name =$row_em['st_name'];
$time1 = $row_em['time1'];
$time2 = $row_em['time2'];
$disexp = $row_em['disexp'];
$em_name = $row_em['e_name'];

$date = date("Y-m-d");
$plus = strtotime("+1 day");
$date2 = date('Y-m-d',$plus);
$month1 = date("Y-m-01");
$pluss = strtotime("+1 month");
$month2 = date('Y-m-01',$pluss);


?>


<!DOCTYPE html>
<html>
<head>
	<title>LIFE PAY</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" href="css/jquery-ui.css" type="text/css">
  <link rel="stylesheet" href="css/iziToast.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <!--<script src="js/main.js"></script>-->
  <style>
	.ui-widget-header
		{
			background:#ee9078 !important;
			color:#fff !important;
		}
	@media (min-width: 1200px){
		.search_bt{padding-left: 7.1%}
	}
	@media (min-width: 1024px){
		.search_bt{padding-left: 7.1%}
	}
	#custTable_wrapper{
		width: 80%;
		margin: 15px auto 0px auto;
	}
</style>
</head>
<body style="background-color:#f5c1ad;min-height: 800px ">
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

function check2(){ //日期搜尋檢定2
	var sd3 = document.getElementById('sd3');
	var sd2 = document.getElementById('sd2');


	if(sd3.value == "" && sd4.value != ""){
	document.getElementById('sd3').style.background="pink";
	}else if(sd3.value != "" && sd4.value == ""){
	document.getElementById('sd4').style.background="pink";
	}else if(sd3.value > sd4.value){
	document.getElementById('sd3').style.background="pink";
	document.getElementById('sd4').style.background="pink";
	}else{
	document.getElementById('form2').submit();
	}
}

</script>
<script type="text/JavaScript">

//按下跳回#menu1
$(function () {
    var tabName = $("[id=sd3]").val() != "" ? "menu1" : "home";
    //alert(tabName);
    $('.nav-tabs a[href="#' + tabName + '"]').tab('show');
});


$(document).ready(function() {
	num = "<?php echo $number;?>";
    $.ajax({
        type: "POST",
        url: "get_coupon.php",
        data: {num:num},
        dataType: "json",
        success: function(resultData) {//alert(resultData);
        var opt={"oLanguage":{"sUrl":"dataTables.zh-tw.txt"},
               "bJQueryUI":true,
               "bProcessing":true,//如需要一些時間處理時, 表格上會顯示"處理中 ..."
               "scrollY": 450,//卷軸
               "scrollCollapse": true,
               "destroy":true,
               "aoColumns":[{"sTitle":"持有者"},
                          {"sTitle":"折抵金額"},
                          {"sTitle":"過期日期"},
                          {"sTitle":"兌換"}],
               "aaData": resultData
               };         
         $("#custTable").dataTable(opt);
         }
    });
});




//退貨機置
/*
function resub(value){

		var val = JSON.parse(value);

		var id = ".complete_id"+value;
		var exx = ".ex"+value;

		var complete_id = $(id).val();
		var ex = $(exx).val();
		
		//alert(ex);
		$(document).ready(function() {
			//ajax 送表單
		$.ajax({
				type: "POST",
				url: "re_data.php",
				dataType: "text",
				data: {
					ex: ex,
					complete_id: complete_id,
				},
				success: function(data) {
				//alert("完成結帳。");
				location.reload();
				}

			})
		})
		
}
*/

//修改%數
/*
function cpercen(v){

		var past = ".passtwo"+v;
		var di = ".dialog_pass"+v;
		var passtwo = $(past).val();

		//alert(di);
		$(document).ready(function() {
			//ajax 送表單
		$.ajax({
				type: "POST",
				url: "checkpass.php",
				dataType: "text",
				data: {
					passtwo: passtwo,
				},
				success: function(data) {
					if (data == 0) {
	                    //alert('正確');
	                    $(di).dialog( "close" );
	                } else {
	                    window.location.reload();
	                }
				}

			})
		})
		
}
*/

/*
	$(function () {
        $('#myForm').submit(function () {

            //var active_nav_tab = $('ul.nav-tabs li.active').attr('index'); //get index
            var active_nav_content = $('.tab-content .active').attr('id'); //get id
            alert(active_nav_content);
            $(active_nav_content).attr('aria-expanded') = "true";
        });
    });
*/
  
</script>
<?php 
if($disexp<$date)
{?>
	<input type="hidden" id="warning" name="warning" value="1">
<?php }
?>
<div class="mebr_top " align="center">
  <a href="store_main.php"><img src="img/life_pay_logo-01.png" width="220px" alt=""></a>
  <a href="#" data-toggle="modal" data-target="#myModal2"><img src="img/set_up-01.svg" style="position: absolute; left: 20px; top: 67px" width="25px" alt=""></a>
</div>
<div class="navtop" >
	<div class="col-lg-6 col-md-6 col-xs-6" align="center" style="top: -10px;border-right: 2px solid #fff;line-height: 55px"><a href="store_search.php"><img src="img/long_search-01.png" alt="" width="120px" ></a></div>
	<div class="col-lg-6 col-md-6 col-xs-6" align="center" style="top: -10px;line-height: 55px"><a href="store_checkout.php"><img src="img/long_checkout-01.png" alt="" width="120px"></a></div>
</div>
<ul class="nav nav-tabs" style="padding: 0px 10px">
	<li class="active"><a data-toggle="tab" href="#home">結帳查詢</a></li>
	<?php 
	if($level == "boss")
	{
		echo "<li><a data-toggle='tab' href='#menu1'>營業額查詢</a></li>";
		echo "<li><a data-toggle='tab' href='#menu2'>查詢抵用券</a></li>";
	}else{
		echo "<li><a data-toggle='tab' href='#menu2'>查詢抵用券</a></li>";
	}
	?>
</ul>
<div class="tab-content">
	<div id="home" class="tab-pane fade in active">
		<form id="form1" action="store_search.php" method="get" name="ss">
			<div class="search_bt" align="center" style="display: inline-block;text-align: left;">
				<div style="width: 90%" align="center">
					<ul class="person_search">
						<li><input type="date" name="sd1" id="sd1" value="<?php echo $_GET['sd1'];?>" style="height: 40px;min-width: 120px; "></li>
						<li><span style="margin: 4px;line-height: 40px">至</span></li>
						<li><input type="date" name="sd2" id="sd2" value="<?php echo $_GET['sd2'];?>" style="height: 40px;min-width: 120px; "></li>
						<li><input type="button" style="background-color:#ee9078;margin-left: 8px;line-height: 35px " class="date_but" onClick="check()" value="查詢"></li>
					</ul>
				</div>
	  		</div>
			<?php //
				if ($_GET['sd1'] != "") {
					$sd1=$_GET['sd1'];$sd2=$_GET['sd2'];//echo $sd1;
					$key="SELECT * FROM complete WHERE s_number = '$number' && date >= '$sd1' && date <= '$sd2' ORDER BY id DESC";
					} else {$key="SELECT * FROM complete WHERE s_number = '$number' && date >= '$date' && date <= '$date2' ORDER BY id DESC";}
					

				mysql_select_db($database_lp, $lp);
				$query_Recoc = $key;// 
				$query_limit_Recoc = sprintf($query_Recoc);
				$Recoc = mysql_query($query_limit_Recoc, $lp) or die(mysql_error());
				$row_Recoc = mysql_fetch_assoc($Recoc);
				$total_recoc = mysql_num_rows($Recoc);
				//print_r($query_Recoc);


				mysql_select_db($database_lp, $lp);
				$query_stuser = sprintf("SELECT * FROM lf_user WHERE accont ='$m_username'");
				$query = mysql_query($query_stuser, $lp) or die(mysql_error());
				$row_stuser = mysql_fetch_assoc($query);
				$total_ruser = mysql_num_rows($query);
				

		?>
			<div class="search_table table-responsive " align="center" style="overflow-y: visible;white-space: nowrap;">
			    <table align="center" class="table coupon_table" style="">
			        <tr  style="background-color: #ee9078;color: #fff ">
						<th style="border-radius: 10px 0px 0px 0px">#</th>
						<th >消費日期</th>
						<th >消費者帳號</th>
						<th >名稱暱稱</th>
						<th >本次消費</th>
						<th >抵用券</th>
						<th >消費積分</th>
						<th >串串積分</th>
						<th >現金/信用卡</th>
	                    <th >回饋%數</th>
						<!--<th >付款情形</th>-->
						<th >身分</th>
						<th style="border-radius: 0px 10px 0px 0px"></th>
					</tr>
					<?php
					//if($total_ruser !=0 ){
					//do{
						//$pas2 = $row_stuser['password2'];
						//echo $pas2;
							//輸出資料列
							if($total_recoc != "")
							{
								//echo $row_Recoc['time'];
								$st_dis = $row_em['st_dis'];
								//echo $st_dis;
								do{

									$csdate = $row_Recoc['date'];
									$cstime = $row_Recoc['time'];

									if(($csdate <= $date && $cstime >= $time1) || ($csdate >= $date2 && $cstime <= $time2)){

									$csdate = $row_Recoc['date'];
									$p_user = $row_Recoc['p_user'];
									$p_nick = $row_Recoc['p_nick'];
									$total_cost = $row_Recoc['total_cost'];
									$discount = $row_Recoc['discount'];
									$c = $row_Recoc['c'];
									$g =  $row_Recoc['g'];
									$spend =  $row_Recoc['spend'];
									$next_discount = $row_Recoc['next_discount'];
									$complete_id =$row_Recoc['ID'];
									$e_name = $row_Recoc['e_name'];
									$dis_percent = $row_Recoc['dis_percent'];
									$confirm = $row_Recoc['confirm'];
									//echo $dis_percent;
									
									
									//計算推播人數
									unset($fmu);$fmi=0;$fmi2=1;$fmk=0;
									$fmu[0]=$p_user;$fcount = '';$fm = '';
									while ($fmk != 1) {
									  $fmj=$fmu[$fmi];//從源頭的人開始取值往下代做收尋 例:peggy -> x12345 -> test94
									  //echo $fmj;
										mysql_select_db($database_sc, $sc);
										$query_Recfmu = sprintf("SELECT * FROM memberdata WHERE fname = '$fmj'" );//
										$Recfmu = mysql_query($query_Recfmu, $sc) or die(mysql_error());
										$row_Recfmu = mysql_fetch_assoc($Recfmu);
										$totalRows_Recfmu = mysql_num_rows($Recfmu);//echo $totalRows_Recfmu;exit;
										//print_r($row_Recfmu);
										if ($totalRows_Recfmu != 0) {
										do {$fma=$row_Recfmu['m_username'];if (in_array($fma,$fmu) == false) {$fmu[$fmi2]=$fma;$fmi2++;}} while ($row_Recfmu = mysql_fetch_assoc($Recfmu));
										}
										
										//底下有多少人也關注這家商店
										mysql_select_db($database_tw, $tw);
										$query_str = sprintf("SELECT * FROM fstore WHERE my_us = '$fmj' && s_name = '$st_name'");
										$Restr = mysql_query($query_str, $tw) or die(mysql_error());
										$row_str = mysql_fetch_assoc($Restr);
										$row_num = mysql_num_rows($Restr);
										//print_r($query_str);
										//echo $row_num;
										if($row_num != 0){
										  $fcount++;
										}
										if($fcount == "")
										{
											$fcount =0;
										}

										//這個月底下有多少人也關注這家商店
										mysql_select_db($database_tw, $tw);
										$query_mon = sprintf("SELECT * FROM fstore WHERE my_us = '$fmj' && s_name = '$st_name' && date >= '$month1' && date <='$month2'");
										$Restm = mysql_query($query_mon, $tw) or die(mysql_error());
										$row_mon = mysql_fetch_assoc($Restm);
										$row_mon = mysql_num_rows($Restm);
										//print_r($query_mon);
										//echo $row_num;
										if($row_mon != 0){
										  $fm++;
										}
										if($fm == "")
										{
											$fm =0;
										}

									  $fmi++;

									  if ($fmu[$fmi] == "") {$fmk=1;}

									  }

									  //echo $fcount;

									$fmu_total=count($fmu); //資料總數
									//echo ($fmu_total-1);
									
									//echo $fcount;

									mysql_select_db($database_lp, $lp);
									$query_Recoc = sprintf("SELECT * FROM complete WHERE ID ='$complete_id' ORDER BY ID DESC"); 
									$query_complete = sprintf($query_Recoc);
									$Recom = mysql_query($query_complete, $lp) or die(mysql_error());
									$row_com = mysql_fetch_assoc($Recom);
									$total_com = mysql_num_rows($Recom);
									$dis_percent = $row_com['dis_percent'];
									$note = $row_com['note'];



									$arr =array("complete_id"=>$complete_id,"spend"=>(int)$spend,"next_discount"=>$next_discount,"st_dis"=>(int)$st_dis,"accumulation"=>$fcount,"acc"=>$fm,"p_user"=>$p_user,"c"=>(int)$c,"g"=>(int)$g,"confirm"=>$confirm,"dis_percent"=>$dis_percent);
									$arr_json = json_encode($arr); //陣列轉josn
									

									echo "<tr class='search_tr' style='text-align:center;background-color: #fff'>";
									echo "<td style='text-align:center;'> ".$total_recoc."</td>";
									echo "<td style='text-align:center;'>".$csdate."</td>";
									echo "<input type='hidden' class='date' name='date' value='$date'>"; 
									echo "<td style='text-align:center;'> ".$p_user."</td>";
									echo "<td style='text-align:center;'> ".$p_nick."</td>";
									echo "<input type='hidden' class='p_nick' name='p_nick' value='$p_nick'>";
									echo "<td style='text-align:center;'>".number_format($total_cost)."</td>";
									echo "<td style='text-align:center;'>".number_format($discount)."</td>";
									echo "<td style='text-align:center;'>".number_format($g)."</td>";
									echo "<td style='text-align:center;'>".number_format($c)."</td>";
									echo "<td style='text-align:center;'>".number_format($spend)."</td>";
									echo "<td style='text-align:center;'>".$dis_percent."</td>"; //回饋%
									echo "<input type='hidden' class='ok' name='ok' value='1'>";
									?>
									<script>
									$(function() 
									{
										y=$(window).width(); //螢幕寬度
										x=$(window).height();//螢幕高度
										if(y>"1024"){y=y/2;}else{y=y/1;}

										//if($confirm = $row_Recoc['confirm'];){

											//優惠卷開啟
											$(".dialog_open<?php echo $total_recoc;?>").click(function(event) 
											{ //按下超連結
												var y = $(".dialog_open<?php echo $total_recoc;?>").val();
												var val = JSON.parse(y);
												var g = val.g;
												var c = val.c;
												var spend = val.spend + val.g + val.c;
												var st_dis = <?php echo (int)$st_dis;?>;
												var next_discount = Math.floor(spend/100*st_dis);
												var de = val.confirm;

												//判斷有沒有確認過
												if(de!=1){
													//alert(val.p_user);
													$(".re<?php echo $total_recoc;?>").css("display", "none");
													$(".p_user<?php echo $total_recoc;?>").val(val.p_user); 
													$(".spend<?php echo $total_recoc;?>").val(spend);
													$(".next_discount<?php echo $total_recoc;?>").val(next_discount);
													$(".accumulation<?php echo $total_recoc;?>").val(val.accumulation);
													$(".acc<?php echo $total_recoc;?>").val(val.acc);
													$(".complete_id<?php echo $total_recoc;?>").val(val.complete_id);
													$(".dis_percent<?php echo $total_recoc;?>").css("display", "none");
													$(".percen_span").css("display","none");
													$(".ex_span").css("display", "none");
													$(".ex<?php echo $total_recoc;?>").css("display", "none");
													$(".dis_span").css("display", "");
													$(".lat_span").css("display", "");
													$(".acc_span").css("display", "");

													
													$( ".dialog<?php echo $total_recoc;?>" ).dialog( "open");
													event.preventDefault();  //防止上方連結打開
												}/*else{
													//退貨機置
													$(".p_user<?php echo $total_recoc;?>").val(val.p_user); 
													$(".spend<?php echo $total_recoc;?>").val(spend);
													$(".complete_id<?php echo $total_recoc;?>").val(val.complete_id);
													$(".next_discount<?php echo $total_recoc;?>").css("display", "none");
													$(".accumulation<?php echo $total_recoc;?>").css("display", "none");
													$(".acc<?php echo $total_recoc;?>").css("display", "none");
													$(".st_dis<?php echo $total_recoc;?>").css("display", "none");
													$(".hb<?php echo $total_recoc;?>").css("display", "none");
													$(".sb<?php echo $total_recoc;?>").css("display", "none");
													$(".dis_span").css("display", "none");
													$(".lat_span").css("display", "none");
													$(".acc_span").css("display", "none");
													$(".percen_span").css("display","");
													$(".ex_span").css("display", "");
													$(".dis_percent<?php echo $total_recoc;?>").val(val.dis_percent);
													$(".hid<?php echo $total_recoc;?>").val("1");
													//alert(val.confirm);

													$( ".dialog<?php echo $total_recoc;?>" ).dialog( "open");
													event.preventDefault();  //防止上方連結打開
												//}
												}
												*/
												
											});
										  
											  y = y/1.1;
											  x = x/1.5;
											  
											  $( ".dialog<?php echo $total_recoc;?>" ).dialog
											  ({
												 show: {
												  effect: "fade",
												  },
												autoOpen: false, //預設不顯示
												draggable: false, //設定拖拉
												resizable: true, //設定縮放
												//title:" ",
												//dialogClass: "no-close", //關閉標題
												height: "auto",
												width: "auto",
												modal: true //灰色透明背景限制只能按dialog
												
											  })
												$(".sb<?php echo $total_recoc;?>").click(function(event){
												  $(".dialog<?php echo $total_recoc;?>").dialog( "close" );
												})
												//退貨機置
												/*
												$(".re<?php echo $total_recoc;?>").click(function(event){
												  $(".dialog<?php echo $total_recoc;?>").dialog( "close" );
												  $(".dialog_join<?php echo $total_recoc;?>").dialog( "open" );
												})
												*/

										//}
										$( ".dialog_join<?php echo $total_recoc;?>" ).dialog
										({
											show: {
												effect: "slide",
											  },
											autoOpen: false, //預設不顯示
											draggable: false, //設定拖拉
											resizable: true, //設定縮放
											//dialogClass: "no-close", //關閉標題
											height: "auto",
											width: "auto",
											modal: true, //灰色透明背景限制只能按dialog
												
										})

										$( ".dialog_pass<?php echo $total_recoc;?>" ).dialog
										({
											show: {
												effect: "slide",
											  },
											autoOpen: false, //預設不顯示
											draggable: false, //設定拖拉
											resizable: true, //設定縮放
											//dialogClass: "no-close", //關閉標題
											height: "auto",
											width: "auto",
											modal: true, //灰色透明背景限制只能按dialog
											open: function() {//開啟
										        $('.ui-dialog-titlebar-close').hide(); //關閉標題的叉叉
										    }

										})
											  
									})
											//$('.ui-dialog-titlebar-close').hide(); //關閉標題的叉叉
											
											 function sub(value){
								
										var bt = ".dialog_open"+value;
           								var bt_value = $(bt).val();
										var val = JSON.parse(value);
										
										var ne = ".next_discount"+value;
										var id = ".complete_id"+value;
										var a = ".accumulation"+value;
										var st = ".st_dis"+value;
										var eff = ".disexp"+value;
										
										var next_discount = $(ne).val();
										var complete_id = $(id).val();
										var accumulation = $(a).val();
										var st_dis = $(st).val();
										var disexp = $(eff).val();
										
										//alert(id);
										$(document).ready(function() {
											//ajax 送表單
										$.ajax({
												type: "POST",
												url: "cf_data.php",
												dataType: "text",
												data: {
													ok: $(".ok").val(),
													complete_id: complete_id,
													next_discount: next_discount,
													accumulation: accumulation,
													st_dis:st_dis,
													disexp:disexp
												},
												success: function(data) {
												  $(bt).attr("disabled","true");
												  $(bt).css("background-color","#E5E5E5");
												  $(bt).css("color","#cccccc");;
								
												}
								
											})
										})
								
								}
									</script>
									
									<div class="dialog<?php echo $total_recoc;?>" title="抵用券發放確認" style="display:none;text-align:center;">
										<ul>
											<li><span style="font-size:20px">會員帳號:</span><br>
											<input name="p_user" class="p_user<?php echo $total_recoc;?>" type="text" readonly="readonly" style="width: 200px;text-align: center;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;;font-size: 20px;" value="" /></li>
											<li><span style="font-size:20px;margin-top: 30px">支付金額</span><br>
											<input name="spend" class="spend<?php echo $total_recoc;?>" type="text" readonly="readonly" style="width: 200px;text-align: center;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;" value="" /></li>
											<li class="dis_span"><span style="font-size:20px;margin-top: 30px">應發抵用券額</span><br>
											<input name="next_discount" id="next_discount" class="next_discount<?php echo $total_recoc;?>" type="text" readonly="readonly" style="width: 200px;text-align: center;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;" value="" /><br></li>
											<li class="lat_span"><span style="font-size:20px;margin-top: 30px">累計忠誠粉絲</span><br>
											<input name="accumulation" class="accumulation<?php echo $total_recoc;?>" type="text" readonly="readonly" style="width: 200px;text-align: center;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;" value=""/><br></li>
											<li class="acc_span"><span style="font-size:20px;margin-top: 30px">本月新推廣粉絲數</span><br>
											<input name="acc" class="acc<?php echo $total_recoc;?>" type="text" readonly="readonly" style="width: 200px;text-align: center;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;" value=""/><br></li>
											<li class="percen_span"><span style="font-size:20px;margin-top: 30px">給予折扣</span><br>
											<input name="dis_percent" class="dis_percent<?php echo $total_recoc;?>" type="text" readonly="readonly" style="width: 200px;text-align: center;height: 50px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;" value="" /><br></li>
											<li><input type="hidden" class="hid<?php echo $total_recoc;?>" value=""></li>
											<li><input name="complete_id" class="complete_id<?php echo $total_recoc;?>" type="hidden" value="" /></li>
											<li><input name="em_name" class="em_name<?php echo $total_recoc;?>" type="hidden" value="<?php echo $em_name;?>" /></li>
										</ul>
										
										 <script>
										 function h(v){
											 var s =".st_dis"+v;
											 var b =".hb"+v;
											 var g =".hg"+v;
											 var sb =".sb"+v;
											 //var di = ".dialog_pass"+v;
											 /*
											 var id = ".complete_id"+v;
											 var idd = $(id).val();
											 var cid =".cid";
											 //把complete_id的值丟進去cid裡面
											 $(cid).val(idd);
											 */

											 $(s).attr("disabled", false);
											 $(s).css("background", "#FFF");
											 $(s).css("background-image", "url(img/s_discount.svg)");
											 $(s).css("background-repeat", "no-repeat");
											 $(s).css("background-size", "16px");
											 $(s).css("background-position", "46px 11px");
											 $(s).css("border", "0px");
											 $(s).css("padding-right", "15px");
											 $(s).css("border", "1px #ccc solid");
											 $(b).css("display", "none");
											 $(g).css("display", "");
											 $(sb).css("display","none");
											 //$(di).dialog( "open" );
										 }
										function ValidateNumber(e, pnumber){
											if (!/^\d+$/.test(pnumber))
											{
											e.value = /^\d+/.exec(e.value);
											}
											return false;
										}
										 function g(v){
											 var s =".st_dis"+v;
											 var n =".next_discount"+v;
											 var sp =".spend"+v;
											 var b =".hb"+v;
											 var g =".hg"+v;
											 var sb =".sb"+v;
											 var st_dis = $(s).val();
											 var next_discount = $(n).val();
											 var spend = $(sp).val();
											 var em =".em_name"+v;
											 var em_name = $(em).val();
											 //alert(em_name);
												$("#em_user").text(em_name);
												//$('#result').html("The result is....");

											 if(st_dis<0||st_dis>100){
											 	$(sb).css("display","none");
											 }else{
											 	$(sb).css("display","");
											 }
											 $(s).attr("disabled", "disabled");
											 $(s).css("background-image", "url(img/s_discount.svg)");
											 $(s).css("background-repeat", "no-repeat");
											 $(s).css("background-size", "16px");
											 $(s).css("background-position", "46px 11px");
											 $(s).css("background-color", "#e3e3e3");
											 $(s).css("border", "0px");
											 $(s).css("padding-right", "15px");
											 $(g).css("display", "none");
											 $(b).css("display", "");
											 spend = Math.floor(Number(spend)*Number(st_dis)/100);
											 $(n).val(spend);
											 if(next_discount!=0){
											 	$(".sb<?php echo $total_recoc;?>").css("display", "none");
											 	$(".ssb<?php echo $total_recoc;?>").css("display", "");
											 }else{
											 	$(".ssb<?php echo $total_recoc;?>").css("display", "none");
											 	$(".sb<?php echo $total_recoc;?>").css("display", "");
											 }
											
										 }
										 
									
										 </script>
										<input step="5" min="0" max="100" name="st_dis" id="st_dis" onKeyUp="ValidateNumber(this,value)" class="st_dis<?php echo $total_recoc;?>" type="tel" style="width: 75px;height: 42px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;text-align: center;background-image:url(img/s_discount.svg);background-repeat:no-repeat;background-size: 16px;background-position: 46px 11px;padding-right:15px" value="<?php echo $st_dis;?>" disabled="disabled" >
		                                <input type="hidden" name="disexp" class="disexp<?php echo $total_recoc;?>" style="width: 300px;text-align: left;height: 100px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;" value="<?php echo $disexp?>"/>
										<input name="complete_id" class="complete_id<?php echo $total_recoc;?>" type="hidden" value="" />
										<?php 
										$pass = $row_stuser['password2'];
										//判斷有沒有權限修改%數
										if($pass != ''){?>
											<button class="hb<?php echo $total_recoc;?> date_but" onClick="h(<?php echo $total_recoc;?>)" style="background: #ff5454;text-align: center;width: 60%">修改</button>
										<?php }
										?>
										
										<button class="hg<?php echo $total_recoc;?> date_but" onClick="g(<?php echo $total_recoc;?>)" style="display:none;background: #2bcb3a;width: 60%">確認</button><br>
										<button class="sb<?php echo $total_recoc;?> date_but" onClick="sub(<?php echo $total_recoc;?>);" style="width:100%;background: #487be5;margin-top: 13px">送出</button>
										<button class="ssb<?php echo $total_recoc;?> date_but" onClick="sub(<?php echo $total_recoc;?>);" style="width:100%;background: #487be5;margin-top: 13px;display:none;">送出</button>
										<button class="re<?php echo $total_recoc;?> date_but" style="width:100%;background: #487be5">確認退貨</button>
										
									</div>

									
									<!--退貨機置-->
									<!--
									<div class="dialog_join<?php echo $total_recoc;?>" style="display:none;text-align:center; line-height:112px;">
										<div class="ex_span" style="font-size:21px;line-height: 35px">備註</div>
										<textarea name="ex" class="ex<?php echo $total_recoc;?>" style="width: 300px;text-align: left;height: 300px;border:0px;background: #e3e3e3;line-height: 35px;border-radius: 6px;font-size: 20px;"><?php echo $note;?></textarea>
										<input name="complete_id" class="complete_id<?php echo $total_recoc;?>" type="hidden" value="" />
										<button class="resub<?php echo $total_recoc;?> date_but" onClick="resub(<?php echo $total_recoc;?>);" style="width:100%;background: #487be5">送出</button>
									</div>
									-->

									<!--修改折扣二級密碼-->
									<!--
									<div class="dialog_pass<?php echo $total_recoc;?>" style="display:none;text-align:center; line-height:60px;">
										<input name="passtwo" class="passtwo<?php echo $total_recoc;?>" type="password" placeholder="請輸入二級密碼" value="" style="height: 40px;font-size: 16px;width: 100%">
										<input name="cid" class="cid" type="hidden" value="dd">
										<button class="cpercen date_but" onClick="cpercen(<?php echo $total_recoc;?>);" style="width:100%;background: #487be5">確認</button>
									</div>
									-->
									

									<?php
									
									//判斷是否已經確認二級密


									$confirm = $row_Recoc['confirm'];
									$ID = $row_Recoc['ID'];
									$c = $row_Recoc['c'];
									$g = $row_Recoc['g'];
									/*
									if($confirm == 0)
									{
										if($ID !=''){
											echo "<td style='text-align:center;'><button style='background:#487be5;color:#fff;border:0px;border-radius:5px;padding:0px 30px' class='dialog_open$total_recoc' name='dialog_ope' id='dialog_open$total_recoc' value='$arr_json'>確認</button></td>";
										}
									}
									else{
										echo "<td style=';text-align:center;'><button style='background:#E5E5E5;color:#cccccc;border:0px;border-radius:5px;padding:0px 30px' disabled='disabled' class='confirm' name='confirm'>已結帳</button></td>";
									}
									*/
									echo "<td id='em_user' style='text-align:center;'>".$e_name."</td><td style=''></td></tr>";
									$total_recoc =$total_recoc-1;
									}
								}while($row_Recoc = mysql_fetch_assoc($Recoc));
									
							}

							//echo $pas2;



									

						//}while($row_stuser = mysql_fetch_assoc($query));
					//}

					?>
				</table>
			</div>
		</form>
		<div class="search_bt" align="center" style="margin-top: -22px;margin-bottom: 60px">
		<div style="width: 88%" align="left" id="return_bt">
		<a href="store_main.php"><input type="button" value="< 返回" style="background: #595757; color: #fff;border: 0px ;border-radius: 5px;padding: 4px 25px"></a>
		</div>
	</div>
	</div>
	
	

	<div id="menu1" class="tab-pane fade table-responsive">
    	<form id="form2" action="store_search.php" method="get" name="ss">
			<div class="search_bt" align="center" style="display: inline-block;">
				<div style="width: 88%" align="center">
					<ul class="person_search">
						<li><input type="date" id="sd3" name="sd3" id="sd3" value="<?php echo $_GET['sd3'];?>" style="height: 40px;min-width: 120px;"></li>
						<li><span style="margin: 4px;line-height: 40px">至</span></li>
						<li><input type="date" id="sd4" name="sd4" id="sd4" value="<?php echo $_GET['sd4'];?>" style="height: 40px;min-width: 120px; "></li>
						<li><input type="button" style="background-color:#ee9078;margin-left: 8px;line-height: 35px " class="date_but" onClick="check2()" value="查詢"></li>
					</ul>
				</div>
			</div>
        <?php if ($_GET['sd3'] != "") {
				$sd3=$_GET['sd3'];$sd4=$_GET['sd4'];//echo $sd1;
				$key2="SELECT * FROM complete WHERE s_number = '$number' && date >= '$sd3' && date <= '$sd4' ORDER BY id DESC";
				} else {$key2="SELECT * FROM complete WHERE s_number = '$number' ORDER BY id DESC";}
				
			

			mysql_select_db($database_lp, $lp);
			$query_Recoc = $key2;//
			//print_r($query_Recoc);
			$query_limit_Recoc = sprintf($query_Recoc);
			$Recoc = mysql_query($query_limit_Recoc, $lp) or die(mysql_error());
			$row_stcomplete = mysql_fetch_assoc($Recoc);
			$total_ow_stcomplete = mysql_num_rows($Recoc);

			?>
            <div class="search_table table-responsive " style="overflow-y: visible;white-space: nowrap;" align="center">
				<table align="center" class="table coupon_table" style="background: #fff;border-radius: 10px">
					<tr style="background-color:#ee9078 ;color: #fff"> 
						<th  style="border-radius: 10px 0px 0px 0px">店家名稱</th>
						<th  >營業金額</th>
						<th >抵用券</th>
						<th  >串串積分</th>
						<th  >消費積分</th>
						<th  >現金/信用卡</th>
						<th  style="border-radius: 0px 10px 0px 0px">回饋額</th>
					</tr>
				<?php
				if($total_ow_stcomplete != 0)
				{
					do{
					$st_name = $row_stcomplete['s_nick'];
					//營業金額
					$total_cost = $row_stcomplete['total_cost'];
					$business_sum = $business_sum + $total_cost;
					//抵用券
					$discount = $row_stcomplete['discount'];
					$discount_sum = $discount_sum + $discount;
					//現金/信用卡
					$spend = $row_stcomplete['spend'];
					$spend_sum = $spend_sum + $spend;
					//串串積分
					$c = $row_stcomplete['c'];
					$c_sum = $c_sum + $c;
					//消費積分
					$g = $row_stcomplete['g'];
					$g_sum = $g_sum + $g;
					//$pratical_in =$spend_sum + $c_sum + $g_sum;
					//回饋額
					$fee_amount = $row_stcomplete['fee_amount'];
					$user_sum = $user_sum + $fee_amount;
					
					}while($row_stcomplete = mysql_fetch_assoc($Recoc));
					echo "<tr class='search_tr' style='background-color: #fff'>";
					echo "<td style='text-align:center;border-radius: 0px 0px 0px 10px'>".$st_name."</td>";
					echo "<td style='text-align:center;'>".number_format($business_sum)."</td>";
					echo "<td style='text-align:center;'>".number_format($discount_sum)."</td>";
					echo "<td style='text-align:center;'>".number_format($c_sum)."</td>";
					echo "<td style='text-align:center;'>".number_format($g_sum)."</td>";
					echo "<td style='text-align:center;'>".number_format($spend_sum)."</td>";
					echo "<td class='table_br' style='text-align:center'>".number_format($user_sum)."</td>";
					echo "</tr>";
				}
				?>
				</table>
			</div>
    	</form>
    	<div class="search_bt" align="center" style="margin-top: -22px;margin-bottom: 20px">
			<div style="width: 88%" align="left" id="return_bt">
				<a href="store_main.php"><input type="button" value="< 返回" style="background: #595757; color: #fff;border: 0px ;border-radius: 5px;padding: 4px 25px">
				</a>
			</div>
		</div>
    </div>

    <!--menu2-->
    <div id="menu2" class="tab-pane fade table-responsive">
    	<table id="custTable" class="display" cellspacing="0" width="100%"></table>
    </div>

	
</div>
	
<div class="col-lg-12 col-md-12 col-xs-12 st_pe_foot" style="height: 48px; background-color:#efefef; width: 100% ;position: fixed;">
   <img src="img/login_footlogo-01.png" width="320px" style="margin-top: 10px" alt="">
</div>

	<!--<div class="modal fade" id="myModal" role="dialog">
	    <div class="modal-dialog">
		    <div class="modal-content col-lg-12 col-md-12 col-xs-12" style="padding-left: 0px;padding-right: 0px">
		    	<div class="col-lg-12 col-md-12 col-xs-12 mb_features" align="center" style="padding: 10px">
			    	<a href="store_main.php">
				    	<img src="../table/img/life_pay_logo-2.png" width="50%" alt="">
			    	</a>
		    	</div>
		    	<div class="col-lg-6 col-md-6 col-xs-6 mb_features"><a href="qrcodestart.html"><img src="../table/img/my_qr-01.png" width="100%" alt=""></a></div>
		    	<div class="col-lg-6 col-md-6 col-xs-6 mb_features">
			    	<a href="store_search.php">
				    	<img src="../table/img/search-01.png" width="100%" alt="">
			    	</a>
		    	</div>
		    	<div class="col-lg-6 col-md-6 col-xs-6 mb_features"><a href="store_coupon.php"><img src="../table/img/coupon-01.png" width="100%" alt=""></a></div>
		    	<div class="col-lg-6 col-md-6 col-xs-6 mb_features"><a href="store_checkout.php"><img src="../table/img/life_pay-01.png" width="100%" alt=""></a></div>
    		</div>
    	</div>
	</div>-->
	<div class="modal fade" id="myModal2" role="dialog">
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
	    		}else{?>
	    			<li><a href="store_ur.php"><img src="img/sign-out-option.svg" width="25px" alt=""><span style="margin-left: 8px">切換使用者</span></a></li>
	    		<?php
	    		}?>

	    			<li><a href="store_login.php"><img src="img/sign-out-option.svg" width="25px" alt=""><span style="margin-left: 8px">登出</span></a></li>
	    		</ul>
	    	</div>
	    </div>
    </div>
<script src="js/iziToast.min.js" type="text/javascript"></script>
<script src="js/demo.js" type="text/javascript"></script>
</body>

</html>
