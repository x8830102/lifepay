<?php 
require_once('Connections/lp.php');mysql_query("set names utf8");
require_once('Connections/tw.php');mysql_query("set names utf8");

?>
<?php 
session_start();
if ($_SESSION['MM_Username'] == ""){header(sprintf("Location: life_pay.php"));exit;
}else{
  $m_username = $_SESSION['mem'];//登入帳號
  $m_nick = $_SESSION['nick'];//登入暱稱
  $number = $_SESSION['number'];
}

//$store = $_GET['accont'];
//$st_name = $_GET['st_name'];

$st_name = $_GET['st_name'];
mysql_select_db($database_lp, $lp);
$query_str = "SELECT lf_user.*,ROUND((SUM(rt_level)/COUNT(rt_level)),1) AS rate,ROUND((SUM(rt_level)/COUNT(rt_level)),0) AS rate2, COUNT(rt_level) AS amount FROM lf_user LEFT JOIN complete ON lf_user.number = complete.s_number WHERE st_name ='$st_name' && level = 'boss'";
$Restr = mysql_query($query_str, $lp) or die(mysql_error());
$row_str = mysql_fetch_assoc($Restr);
$row_num = mysql_num_rows($Restr);
$store = $row_str['accont'];
$s_number = $row_str['number'];//商店號碼
$time = date("h:i:sa");
$date = date("Y-m-d");

require_once("lp_user.php");
?>

<!DOCTYPE html>
<html>
<head>
<title>LIFE PAY</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/norate.css">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 
  <script>

  </script>
  
  <script>

  
function favorite(){
	var st_name = "<?php echo $st_name;?>";
	var store = "<?php echo $store;?>";
	var m_username = "<?php echo $m_username;?>";
	$(document).ready(function() {
				//ajax 送表單
				$.ajax
				  ({
					type: "POST",
					url: "favor.php",
					dataType: "text",
					data: {
						st_name : st_name,
						store : store,
						m_username : m_username
					},
					success: function(data) {
						if(data == 1){
							alert('收藏成功!');
							document.getElementById("wanttolike").style.display='none';
							document.getElementById("wanttounlike").style.display="block";
						}else if(data == 0){
							alert('取消收藏!');
							document.getElementById("wanttolike").style.display='block';
							document.getElementById("wanttounlike").style.display="none";
						}

					}
				  })
				});

}
function goBack() {
    window.history.back()
}
</script>
</head>
<style>
	@media (max-width: 500px){
		fieldset {
			right:16%;
		}
		.store_img{width: 80%}
	}
	@media (max-width: 375px){
		fieldset {
			right:23%;
		}
	}
	@media (max-width: 320px){
		fieldset {
			right:40%;
		}
	}
	@media (min-width: 510px){
		fieldset {
			right:8%;
		}

	}
	@media (min-width: 1024px){
		fieldset {
			right:6%;
		}
		.store_img{max-height:40vh}
	}
	@media (min-width: 1200px){
		fieldset {
			right:7%;
		}
	}
	.fancy > label:before {
		font-size: 1em
	}
	@media (max-width: 500px){
		.fancy {
			right:21%;
		}
	}
	@media (max-width: 375px){
		.fancy {
			right:23%;
		}
	}
	@media (max-width: 320px){
		.fancy {
			right:29%;
		}
	}
	@media (min-width: 510px){
		.fancy {
			right:13%;
		}
	}
</style>
<body style="background: #fff">


<div class="mebr_top" align="center">
   <div><?php echo $row_str['st_name'];?></div>
  <a href="#" onClick="window.open(document.referrer,'_self')" ><span style="position: absolute; left: 20px; top:15px;color: #999;font-size: 22px;font-weight: 300;z-index: 999">X</span></a>

</div>
        <div style="width: 100%;max-height: 45vh;overflow: hidden;text-align: center;"><img class="store_img" src="img/<?php echo $row_str['profile'];?>"  alt="尚未設定封面" ></div>
       <div align="left" style="border-bottom: 1px solid #eee;line-height: 40px;padding: 5px;width: 90%;margin: 0px auto"><div align="left" style="font-size: 16px;color: #999;display: inline-block;width: 30%;padding-left: 10px;vertical-align: top;">地址</div><div align="left" style="color: #65A89A;font-size: 14px;display: inline-block;width: 70%;padding-left: 20px;line-height: 20px;margin-top: 10px"><a href="https://www.google.com.tw/maps/place/<?php echo $row_str['m_address'];?>"><?php echo $row_str['m_address'];?></a></div></div>
        <div align="left" style="border-bottom: 1px solid #eee;line-height: 40px;padding: 5px;width: 90%;margin: 0px auto"><div align="left" style="font-size: 16px;color: #999;display: inline-block;width: 30%;padding-left: 10px">電話</div><div align="left" style="color: #65A89A;font-size: 14px;display: inline-block;width: 70%;padding-left: 20px"><a href="tel:<?php echo $row_str['m_phone'];?>"><?php echo $row_str['m_phone'];?></a></div></div>
        <div align="left" style="border-bottom: 1px solid #eee;line-height: 40px;padding: 5px;width: 90%;margin: 0px auto"><div align="left" style="font-size: 16px;color: #999;display: inline-block;width: 30%;padding-left: 10px;vertical-align: top">評價</div><div align="left" style="color: #65A89A;font-size: 14px;display: inline-block;width: 70%;padding-left: 20px">
		<?php 
		 $RtLevel =  $row_str["rate2"];
		 if($RtLevel != 0)
		 { ?>
			
			 <fieldset id="<?php echo $row_str['user_id']; ?>" class="rating" style="height: 30px;top: -10px">
				<input type="radio"   id="star5<?php echo $row_str['user_id']; ?>" name="rate<?php echo $row_str['user_id']; ?>" value="5" />
				<label class = "full" for="star5<?php echo $row_str['user_id']; ?>" title="夭壽讚"></label>
				
				<input type="radio" id="star4.5<?php echo $row_str['user_id']; ?>" name="rate<?php echo $row_str['user_id']; ?>" value="4.5" />
				<label class="half" for="star4half<?php echo $row_str['user_id']; ?>" title="非常好"></label>
				
				<input type="radio" id="star4<?php echo $row_str['user_id']; ?>" name="rate<?php echo $row_str['user_id']; ?>" value="4" />
				<label class = "full" for="star4<?php echo $row_str['user_id']; ?>" title="很棒"></label>
				
				<input type="radio" id="star3.5<?php echo $row_str['user_id']; ?>" name="rate<?php echo $row_str['user_id']; ?>" value="3.5" />
				<label class="half" for="star3half<?php echo $row_str['user_id']; ?>" title="還行啦"></label>
				
				<input type="radio" id="star3<?php echo $row_str['user_id']; ?>" name="rate<?php echo $row_str['user_id']; ?>" value="3" />
				<label class = "full" for="star3<?php echo $row_str['user_id']; ?>" title="普普通通"></label>
				
				<input type="radio" id="star2.5<?php echo $row_str['user_id']; ?>" name="rate<?php echo $row_str['user_id']; ?>" value="2.5" />
				<label class="half" for="star2half<?php echo $row_str['user_id']; ?>" title="有點糟"></label>
				
				<input type="radio" id="star2<?php echo $row_str['user_id']; ?>" name="rate<?php echo $row_str['user_id']; ?>" value="2" />
				<label class = "full" for="star2<?php echo $row_str['user_id']; ?>" title="蠻糟的"></label>
				
				<input type="radio" id="star1.5<?php echo $row_str['user_id']; ?>" name="rate<?php echo $row_str['user_id']; ?>" value="1.5" />
				<label class="half" for="star1half<?php echo $row_str['user_id']; ?>" title="有點悲劇"></label>
				
				<input type="radio" id="star1<?php echo $row_str['user_id']; ?>" name="rate<?php echo $row_str['user_id']; ?>" value="1" />
				<label class = "full" for="star1<?php echo $row_str['user_id']; ?>" title="呃...."></label>
				
				<input type="radio" id="star0.5<?php echo $row_str['user_id']; ?>" name="rate<?php echo $row_str['user_id']; ?>" value="0.5" />
				<label class="half" for="starhalf<?php echo $row_str['user_id']; ?>" title="!@#$%^&*"></label>
				
			</fieldset>
		
			<script>
			 var RtLevel = "<?php echo $RtLevel;?>";
			 var id = "<?php echo $row_str['user_id'];?>";
			 if(RtLevel != 0)
			 {
				document.getElementById('star'+RtLevel+id).checked=true;
  			    $('#'+id).prop('disabled',true);
			 }
			</script>
            
		
		<?php } echo '<span>'.$row_str['rate'].'/5 (<a href="#" data-toggle="modal" data-target="#myModal">'.$row_str['amount'].'則評價</a>)</span>'?></div></div>
        <div align="left" style="border-bottom: 1px solid #eee;line-height: 40px;padding: 5px;width: 90%;margin: 0px auto"><div align="left" style="font-size: 16px;color: #999;display: inline-block;width: 30%;padding-left: 10px">營業時間</div><div align="left" style="color: #65A89A;font-size: 14px;display: inline-block;width: 70%;padding-left: 20px"><?php echo $row_str['time1'],"~",$row_str['time2'];?></div></div>

        <!-- Modal -->
		<div class="modal fade" id="myModal" role="dialog">
			<div class="modal-dialog">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-body">
							<ul>
								<li style="background-color: #84DDCB;color: #fff;font-size: 20px;padding: 7px">所有評價</li>
							</ul>
							
							
							<?php
							mysql_select_db($database_lp, $lp);
							$sql = "SELECT * FROM complete WHERE s_number = '$s_number' and rt_status= '1' ORDER BY rt_content DESC";
							$Rstore = mysql_query($sql, $lp) or die(mysql_error());
							for($i=0;$i<mysql_numrows($Rstore);$i++){
								$detail_store = mysql_fetch_assoc($Rstore);
								$id = $detail_store['ID'];
								$rat = $detail_store['rt_level'];
								?>
								<ul style="padding:8px;margin:5px;text-align:left;border-bottom:1px solid #eee">
									<li style="font-size:18px;color:#999;"><?php echo $detail_store['p_nick'];?></li>
									<li style="font-size:12px;color:#999;"><?php echo $detail_store['rt_date'];?></li>
									<li>
									<fieldset id="<?php echo $id; ?>" class="rating fancy">
										<input type="radio"   id="star5<?php echo $id; ?>" value="5" />
										<label class = "full" for="star5<?php echo $id; ?>" title="夭壽讚"></label>
										
										<input type="radio" id="star4.5<?php echo $id; ?>" value="4.5" />
										<label class="half" for="star4half<?php echo $id; ?>" title="非常好"></label>
										
										<input type="radio" id="star4<?php echo $id; ?>" value="4" />
										<label class = "full" for="star4<?php echo $id; ?>" title="很棒"></label>
										
										<input type="radio" id="star3.5<?php echo $id; ?>" value="3.5" />
										<label class="half" for="star3half<?php echo $id; ?>" title="還行啦"></label>
										
										<input type="radio" id="star3<?php echo $id; ?>" value="3" />
										<label class = "full" for="star3<?php echo $id; ?>" title="普普通通"></label>
										
										<input type="radio" id="star2.5<?php echo $id; ?>" value="2.5" />
										<label class="half" for="star2half<?php echo $id; ?>" title="有點糟"></label>
										
										<input type="radio" id="star2<?php echo $id; ?>" value="2" />
										<label class = "full" for="star2<?php echo $id; ?>" title="蠻糟的"></label>
										
										<input type="radio" id="star1.5<?php echo $id; ?>" value="1.5" />
										<label class="half" for="star1half<?php echo $id; ?>" title="有點悲劇"></label>
										
										<input type="radio" id="star1<?php echo $id; ?>" value="1" />
										<label class = "full" for="star1<?php echo $id; ?>" title="呃...."></label>
										
										<input type="radio" id="star0.5<?php echo $id; ?>" value="0.5" />
										<label class="half" for="starhalf<?php echo $id; ?>" title="!@#$%^&*"></label>
									</fieldset>
									<script>
									$( document ).ready(function() {
										var RtLevel = "<?php echo $rat;?>";
										var id = "<?php echo $id;?>";
										if(RtLevel != 0)
										{
										document.getElementById('star'+RtLevel+id).checked=true;
										    $('#'+id).prop('disabled',true);
										}
									});
									</script>
									<span  style="font-size:16px;color:#999;"><?php echo $detail_store['rt_content'];?></span></li>
									
								</ul>
							<?php
							}
							?>
						
						
						<div class="modal-footer" style="border-top: 0px">
							<input style="border-radius: 6px;border:0px;background-color: #999;color: #fff;width: 20%;height: 35px " type="button" value="關閉" data-dismiss="modal">
							<input style="border-radius: 30px;border:2px solid #999;background-color: #eee;color: #333;position: absolute;top: -10px;right: -6px; " type="button" value="close" data-dismiss="modal">
						</div>
					</div>
				</div>
			</div>
		</div>



        <!--<li>評價</li>
        <li>網站</li>
        <li>
        <?php 
			  if(strtotime($row_str['time1']) <= strtotime($row_str['time2'])){
				  if((strtotime($row_str['time1']) <= strtotime($time)) && (strtotime($time) <= strtotime($row_str['time2']))){
					echo '營業中';
					}else{
					echo '休息中';
					}
				}else if(strtotime($row_str['time1']) == strtotime($row_str['time2'])){ //24H
					echo '營業中';
				  }else if((strtotime($row_str['time1']) > strtotime($time)) && (strtotime($time) > strtotime($row_str['time2']))){ //跨日營業時間
					echo '休息中';
					}else{
					echo '營業中';
					}
			  ?>
        </li>
        <li>活動內容</li>-->

        <div style="margin-top: 20px"><div align="center" style="width: 100%;"><button id="wanttolike" onClick="favorite()" style="width:60px;border: 0px;border-radius: 6px;background: #84DDCB;color: #fff;padding: 5px 10px;font-size: 16px;width: 85%">收藏</button></div>
        <div align="center" style="width: 100%;"><button id="wanttounlike" onClick="favorite()" style="width: 100px;border: 0px;border-radius: 6px;background:rgb(194, 194, 194);color: #fff;padding: 5px 10px;font-size: 16px;width: 85%">取消收藏</button></div></div>
        <div style="margin-bottom: 80px"></div>
          <?php
      mysql_select_db($database_tw, $tw);
      $query_bt = "SELECT *  FROM fstore WHERE my_us = '$m_username'";
      $Restr = mysql_query($query_bt, $tw) or die(mysql_error());
      $row_bt = mysql_fetch_assoc($Restr);
    
      do{
        if($row_bt['yu_us'] == $store){?>
          <script>
            document.getElementById("wanttolike").style.display='none';
            document.getElementById("wanttounlike").style.display="block";
          </script>
          <?php
          break;
        }else{?>
        <script>
          document.getElementById("wanttolike").style.display='block';
          document.getElementById("wanttounlike").style.display='none';
        </script>
          <?php
        }
        
      }while($row_bt = mysql_fetch_assoc($Restr));
      ?>

