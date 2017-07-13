<?php 
require_once('Connections/tw.php');mysql_query("set names utf8");
require_once('Connections/lp.php');mysql_query("set names utf8");
?>
<?php 
session_start();
if ($_SESSION['MM_Username'] == ""){header(sprintf("Location: life_pay.php"));exit;
}else{
  $m_username = $_SESSION['mem'];//登入帳號
  $m_nick = $_SESSION['nick'];//登入暱稱
  $number = $_SESSION['number'];
}
/* AJAX check  */
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

	$s_va = $_POST['va'];
	mysql_select_db($database_lp, $lp);
	$sql = "SELECT * FROM complete WHERE s_number = '$s_va' and rt_status= '1' ORDER BY rt_content DESC";
	$Rstore = mysql_query($sql, $lp) or die(mysql_error());
	for($i=0;$i<mysql_numrows($Rstore);$i++){
		$detail_store = mysql_fetch_assoc($Rstore);

		$num_store = mysql_numrows($Rstore);
		$p_nick = $detail_store['p_nick'];
		$rt_date = $detail_store['rt_date'];
		$rt_content = $detail_store['rt_content'];
		$id = $detail_store['ID'];
		$rat = $detail_store['rt_level'];


		$aa = "<li><span style='font-size:18px;color:#999;'>".$p_nick."</span></li>
		       <li><span style='font-size:12px;color:#999;'>".$rt_date."</span></li>
		       <li>
		       		<fieldset id=".$id." class='rating fancy'>
					<input type='radio' id='star5".$id."' value='5' />
					<label class='full' for='star5".$id."' title='夭壽讚'></label>
					<input type='radio' id='star4.5".$id."' value='4.5' />
					<label class='half' for='star4half".$id."' title='非常好'></label>										
					<input type='radio' id='star4".$id."' value='4' />
					<label class='full' for='star4".$id."' title='很棒'></label>	
					<input type='radio' id='star3.5".$id."' value='3.5' />
					<label class='half' for='star3half".$id."' title='還行啦'></label>
					<input type='radio' id='star3".$id."' value='3' />
					<label class='full' for='star3".$id."' title='普普通通'></label>
					<input type='radio' id='star2.5".$id."' value='2.5' />
					<label class='half' for='star2half".$id." title='有點糟''></label>
					<input type='radio' id='star2".$id."'value='2' />
					<label class='full' for='star2".$id."' title='蠻糟的'></label>
					<input type='radio' id='star1.5".$id."' value='1.5' />
					<label class='half' for='star1half".$id."' title='有點悲劇''></label>
					<input type='radio' id='star1".$id."' value='1' />
					<label class='full' for='star1".$id."' title='呃....'></label>
					<input type='radio' id='star0.5".$id."' value='0.5' />
					<label class='half' for='starhalf".$id."' title='!@#$%^&*'></label>
					</fieldset></span></li>
					<script>
					$( document ).ready(function() {
						var RtLevel = ".$rat.";
						var id = ".$id.";
						if(RtLevel != 0)
						{
						document.getElementById('star'+RtLevel+id).checked=true;
						    $('#'+id).prop('disabled',true);
						}
					});
					</script>
		       <li><span style='font-size:16px;color:#999;'>".$rt_content."</span></li>";

		$user[$i]=array($aa,$num_store); //存入陣列

	}
	echo json_encode($user);  //將陣列轉成 JSON 資料格式傳回

}else{
$st_name = $_GET['st_name'];
$store = $row_str['accont'];
$time = date("h:i:sa");
//取得收藏店家名稱
mysql_select_db($database_tw, $tw);
$query_str = "SELECT yu_us, lifelink_lfpay.lf_user . * , ROUND( (
				SUM( rt_level ) / COUNT( rt_level ) ) , 1
				) AS rate, ROUND( (
				SUM( rt_level ) / COUNT( rt_level ) ) , 0
				) AS rate2, COUNT( rt_level ) AS amount
				FROM lifelink_lfpay.lf_user
				LEFT OUTER JOIN fstore ON fstore.yu_us = lifelink_lfpay.lf_user.accont
				LEFT OUTER JOIN lifelink_lfpay.complete ON lifelink_lfpay.lf_user.number = lifelink_lfpay.complete.s_number
				WHERE my_us =  '$m_username' && lifelink_lfpay.lf_user.accont = yu_us && lifelink_lfpay.lf_user.level =  'boss' && st_name LIKE  '%$st_name%'
				GROUP BY yu_us";
$Restr = mysql_query($query_str, $tw) or die(mysql_error());
$row_str = mysql_fetch_assoc($Restr);
$row_num = mysql_num_rows($Restr);
require_once("lp_user2.php");
?>

<script>
function favorite(user_id){
	var st_name = document.getElementById('st_name' + user_id).value;
	var store = document.getElementById('store' + user_id).value;
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
							document.getElementById("wanttolike" + user_id).style.display="none";
							document.getElementById("wanttounlike" + user_id).style.display="block";
						}else if(data == 0){
							alert('取消收藏!');
							document.getElementById("wanttolike" + user_id).style.display="block";
							document.getElementById("wanttounlike" + user_id).style.display="none";
						}

					}
				  })
				});

}
</script>
<style>
  .mb_features li {
    float: left;
  }
  @media (max-width: 500px){
		fieldset {
			right:16%;
		}
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
	}
	@media (min-width: 1200px){
		fieldset {
			right:4%;
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
<!--<form id="form" action="person_like.php" method="get">
    <li  class="col-lg-12 col-md-12 col-xs-12"><input id="search" name="search" type="text" placeholder="收藏搜尋" style="z-index: 999"></li>
</form>-->
<div style="margin-top: 130px"></div>
<?php
  if($row_num != 0){
    do{
?>
  
  <div class="col-lg-12 col-md-12 col-xs-12 " style="background: #fff;padding:15px; " align="center">
    <ul>
     <li class="col-lg-3 col-md-3 col-xs-3" style="padding-left:0px;text-align: left;height: 90px; ">
      <a href="person_store.php?st_name=<?php echo $row_str['st_name'];?>"><div style="background-image: url(img/<?php echo $row_str['profile']; ?>);width: 80px;height: 65px;line-height: 90px;background-size: 80px auto;background-position: 50% 50%;background-repeat: no-repeat;border:1px solid #999;border-radius: 6px;margin-top: 14px"></div></a>
      </li>
       <li class="col-lg-6 col-md-6 col-xs-6" style="padding-left:0px;"><?php echo $row_str['st_name']; ?><br>
     	<span style="color: #5e5e5e;font-size: 12px">
          <?php 
          if(strtotime($row_str['time1']) < strtotime($row_str['time2'])){ //正常營業時間
            if((strtotime($row_str['time1']) < strtotime($time)) && (strtotime($time) < strtotime($row_str['time2']))){
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
          ?></span><br>
          <div style="color: #5e5e5e;font-size: 12px">
          <?php 
		 $RtLevel =  $row_str["rate2"];
		 if($RtLevel != 0)
		 { ?>
			
			 <fieldset id="<?php echo $row_str['user_id']; ?>" class="rating">
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
            <?php } ?>(<a id="nu<?php echo $row_str['user_id'];?>" href="" data-toggle="modal" data-target="#myModal<?php echo $row_str['user_id'];?>" data-value="<?php echo $row_str['number'];?>"><?php echo $row_str['amount'];?>則評價</a>)
          </div>

			<script>
			var id = "<?php echo $row_str['user_id'];?>";
			$("#nu"+id).click(function(){
				var va = $(this).attr("data-value");
				var id2 = $(this).attr("data-value");
				var dis_num = ($('#aa tr:last').attr("data-value"));
				$.ajax({
					method: "POST",
					url: "",
					dataType: "json",
					data: {
						va: va
					},
					success: function(va){
						if(va != null){
							var num = va[0][1];
							for(var u=0;u<num; u++){
	                            var tr = "#tr"+u+id2;
	                            var rtr = "tr"+u+id2;
								var h = $(tr).html();
								if(typeof h == "undefined")
								{
									$('#aa'+id2).append("<ul style='padding:8px;margin:5px;text-align:left;border-bottom:1px solid #eee' id='"+rtr+"' data-value='"+num+"'>");
									$(tr).html(va[u][0]);
								}
	                            
								
	                        }
	                        if(dis_num > num){
	                            for(num;num<dis_num;num++){
	                              var dis_tr = "#tr"+num;
	                              $(dis_tr).remove();
	                            }
	                        }
						}else{
							var z = 0;
							if(dis_num > z){
	                            for(z;z<dis_num;z++){
	                              var dis_tr = "#tr"+z;
	                              $(dis_tr).remove();
	                            }
	                        }
						}
						
					}
				})
				
			});
			</script>
        <!-- Modal -->
		<div class="modal fade" id="myModal<?php echo $row_str['user_id'];?>" role="dialog">
			<div class="modal-dialog">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-body">
						<ul id="aa<?php echo $row_str['number'];?>">
						<li style="background-color: #84DDCB;color: #fff;font-size: 20px;padding: 7px">所有評價</li>
						</ul>
						<div class="modal-footer" style="border-top: 0px">
							<input style="border-radius: 6px;border:0px;background-color: #999;color: #fff;width: 20%;height: 35px " type="button" value="關閉" data-dismiss="modal">
							<input style="border-radius: 30px;border:2px solid #999;background-color: #eee;color: #333;position: absolute;top: -10px;right: -6px; " type="button" value="close" data-dismiss="modal">
						</div>
					</div>
				</div>
			</div>
		</div>
          
          </li>
      <li  class="col-lg-3 col-md-3 col-xs-3" style="padding-left:0px;text-align:right;height: 90px ">
        <div style="padding-top: 30px"><button id="wanttolike<?php echo $row_str['user_id'];?>" onClick="favorite(<?php echo $row_str['user_id'];?>)" style="background: #84DDCB;padding: 5px 10px;border: 1px solid #84DDCB;border-radius: 6px;color: #fff;font-size: 16px">收藏</button></div>
          <div style="margin-top: -10px"><button id="wanttounlike<?php echo $row_str['user_id'];?>" onClick="favorite(<?php echo $row_str['user_id'];?>)"style="background: #C2C2C2;padding: 5px 10px;border: 1px solid #C2C2C2;border-radius: 6px;color: #fff;font-size: 16px">取消</button></div>
          <input type="hidden" id="st_name<?php echo $row_str['user_id'];?>" name="st_name" value="<?php echo $row_str['st_name'];?>">
          <input type="hidden" id="store<?php echo $row_str['user_id'];?>" name="store" value="<?php echo $row_str['accont'];?>">
        </li>
    </ul>
    

     
          <?php
			
			mysql_select_db($database_tw, $tw);
			$query_bt = "SELECT * FROM fstore WHERE my_us = '$m_username'";
			$Rebt = mysql_query($query_bt, $tw) or die(mysql_error());
			$row_bt = mysql_fetch_assoc($Rebt);
			do{
				if($row_bt['yu_us'] == $row_str['accont']){?>
					<script>
					document.getElementById("wanttolike<?php echo $row_str['user_id'];?>").style.display='none';
					document.getElementById("wanttounlike<?php echo $row_str['user_id'];?>").style.display="block";
					</script>
					<?php
					break;
				}else{?>
				<script>
					document.getElementById("wanttolike<?php echo $row_str['user_id'];?>").style.display='block';
					document.getElementById("wanttounlike<?php echo $row_str['user_id'];?>").style.display='none';
				</script>
					<?php
				}
				
			}while($row_bt = mysql_fetch_assoc($Rebt));
		  ?>  
    </ul>
  </div>
  <hr style="background: #5e5e5e;width: 90%;margin-bottom: 0px;margin-top: 0px">
<?php 
    }while($row_str = mysql_fetch_assoc($Restr));
  }else{
  	echo '唉呀!沒有店家!!快去收藏幾間(´･ω･`) 人(´･ω･`)';
	}
?>
<div class="col-lg-12 col-md-12 col-xs-12" style="margin-bottom:50px "></div>
<?php
}?>
