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

$time = date("h:i:sa");
?>
<?php 


/* AJAX check  */
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  if(isset($_GET))
  {
        $store = $_GET['serr'];
        mysql_select_db($database_lp, $lp);
		$select_lfuser = "SELECT * FROM lf_user WHERE st_name like '%$store%' && level = 'boss'";
		$query_lfuser = mysql_query($select_lfuser, $lp) or die(mysql_error());
		$row_long = mysql_fetch_assoc($query_lfuser);
		$row_num = mysql_num_rows($query_lfuser);

		$lat = $row_long["lat"];
		$lng = $row_long["lng"];
		$arr =array("lat"=>$lat,"lng"=>$lng);
		$arr_json = json_encode($arr); //陣列轉josn

		echo $arr_json;
		


  }

}else{
require_once("lp_user3.php");
?>

<script>
  function detectBrowser() {
  var useragent = navigator.userAgent;
  var mapdiv = document.getElementById("map");

  if (useragent.indexOf('iPhone') != -1 || useragent.indexOf('Android') != -1 ) {
    mapdiv.style.width = '100%';
    mapdiv.style.height = '100%';
  } else {
    mapdiv.style.width = '600px';
    mapdiv.style.height = '800px';
  }
}

		
</script>

<style>
@media (min-width: 1200px) {
 .search_bt {
padding-left: 7.1%
}
}
 @media (min-width: 1024px) {
 .search_bt {
padding-left: 7.1%
}
}
</style>


  <div class="" align="center" style="display: inline-block;text-align:center;width: 100%;padding-left: 10%;margin-top: 90px">
    <div  align="center" >
      <ul >
        <li style="float: left;">
          <input id="search" type="text" name="search" placeholder="輸入店家名稱"  value="" style="height: 40px;border:1px solid #fff;-webkit-appearance:none;padding: 5px;line-height: 40px">
        </li>
        <li style="float: left;">
          <button style="margin-left: 8px;line-height: 35px" class="date_but" onClick="sub()">查詢</button>
        </li>
      </ul>
    </div>
  </div>
<div class=" table-responsive " align="center" style="overflow-y: visible;white-space: nowrap;padding-top:5px ">
  <div id="map" style="height:550px"></div>
  
  <?php 
      mysql_select_db($database_lp, $lp);
      $query_str = "SELECT lf_user . * , ROUND( (
					SUM( rt_level ) / COUNT( rt_level ) ) , 1
					) AS rate,COUNT(rt_level) AS amount
					FROM lf_user
					LEFT JOIN complete ON lf_user.number = complete.s_number
					WHERE level =  'boss'
					GROUP BY accont";
      $Restr = mysql_query($query_str, $lp) or die(mysql_error());
      $row_str = mysql_fetch_assoc($Restr);
      $row_num = mysql_num_rows($Restr);
	  
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
	  var jj = 0;
	  function sub(){
			var ser = $('input[name="search"]').val();
			//var aa ={webName:"不知為毛"};
			//var bb = JSON.stringify(aa);
					$(document).ready(function() {
						//ajax 送表單
						$.ajax({
							type: "GET",
							url: "person_map.php",
							dataType: "text",
							
							async: false,
							data:{
								serr : ser
							},
							success: function(data) {
								jj=1;
								var data = JSON.parse(data);
								initMap(data.lat,data.lng);
							},
							error: function(jqXHR, textStatus, errorThrown) {
								console.log(errorThrown);
							}
							
							
						});
						
					})
				
			
			}
	  
		function initMap(a,b) {
			
		
		var initialLocation;
		var tzml = new google.maps.LatLng(22.993542, 120.183576);
		var browserSupportFlag =  new Boolean();
		var map = new google.maps.Map(document.getElementById('map'), {
			zoom: 18
		  });
		 if(navigator.geolocation) {
		 	if(jj == 1){
				var positionlat = a;
				var positionlng = b;
				var position = new google.maps.LatLng(positionlat, positionlng);
				map.setCenter(position);
			}else{
			browserSupportFlag = true;
			navigator.geolocation.getCurrentPosition(function(position) {
		    initialLocation = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
			  	if(positionlat && positionlng){
					var position = new google.maps.LatLng(positionlat, positionlng);
					map.setCenter(position);
				}else{
			  	map.setCenter(initialLocation);
			  	}
			}, function() {
			  handleNoGeolocation(browserSupportFlag);
			});
			}
		  
		  // Browser doesn't support Geolocation
		  }else{
			browserSupportFlag = false;
			handleNoGeolocation(browserSupportFlag);
		  }
		  
		  function handleNoGeolocation(errorFlag) {
			if(jj == 1){//尚需新增至定位區
			var positionlat = a;
			var positionlng = b;
alert(positionlat);
				var position = new google.maps.LatLng(positionlat, positionlng);
				map.setCenter(position);
			}else{
				if (errorFlag == true) {
				  alert("無法取得定位");
				} else {
				  alert("您的瀏覽器不支援定位服務");
				}
				initialLocation = tzml;
				map.setCenter(initialLocation);
			 }
			
		  }
		
		var sContent = [];
		var markers = [];
		var i = 0;
		<?php do{ 
				mysql_select_db($database_tw, $tw);
				$query_bt = "SELECT * FROM fstore WHERE my_us = '$m_username'";
				$Rebt = mysql_query($query_bt, $tw) or die(mysql_error());
				$row_bt = mysql_fetch_assoc($Rebt);
				do{
					if($row_bt['yu_us'] == $row_str['accont']){
						$i = 0;
						break;
					}else{
						$i = 1;
					}
				}while($row_bt = mysql_fetch_assoc($Rebt));
			?>  
		
		var contentString = '<div id="content">'+
			  '<div id="siteNotice">'+
			  '</div>'+
			  '<h1 id="firstHeading" class="firstHeading"><?php echo $row_str['st_name']; ?></h1>'+
			  '<div id="bodyContent">'+
			  <?php
			  if($i == 0){?>
			  '<button id="wanttounlike<?php echo $row_str['user_id'];?>" onClick="favorite(<?php echo $row_str['user_id'];?>)">取消收藏</button>'+
			  '<button id="wanttolike<?php echo $row_str['user_id'];?>" onClick="favorite(<?php echo $row_str['user_id'];?>)" style="display:none">收藏</button>'+
			  <?php }else{?>
			  '<button id="wanttounlike<?php echo $row_str['user_id'];?>" onClick="favorite(<?php echo $row_str['user_id'];?>)" style="display:none">取消收藏</button>'+
			  '<button id="wanttolike<?php echo $row_str['user_id'];?>" onClick="favorite(<?php echo $row_str['user_id'];?>)" >收藏</button>'+
			  <?php } ?>
			'<input type="hidden" id="st_name<?php echo $row_str['user_id'];?>" name="st_name" value="<?php echo $row_str['st_name'];?>">'+
			'<input type="hidden" id="store<?php echo $row_str['user_id'];?>" name="store" value="<?php echo $row_str['accont'];?>">'+
         
			
			  '<p><b><a href="https://www.google.com.tw/maps/place/<?php echo $row_str['m_address'];?>"><?php echo $row_str['m_address'];?></a><br/></b></p>'+
			  '<p><b><?php echo $row_str['rate'];?>/5 (<?php echo $row_str['amount'];?>則評價)<br/></b></p>'+
			  '<p><b><a href="tel:<?php echo $row_str['m_phone'];?>"><?php echo $row_str['m_phone'];?></a><br/></b></p>'+
			  '<p><b>'+
			  <?php 
			  if(strtotime($row_str['time1']) < strtotime($row_str['time2'])){
				  if((strtotime($row_str['time1']) <= strtotime($time)) && (strtotime($time) <= strtotime($row_str['time2']))){
					echo '\'營業中\'+';
					}else{
					echo '\'休息中\'+';
					}
				}else  if(strtotime($row_str['time1']) == strtotime($row_str['time2'])){ //24H
					echo '\'營業中\'+';
			    }else if((strtotime($row_str['time1']) >= strtotime($time)) && (strtotime($time) >= strtotime($row_str['time2']))){
					echo '\'休息中\'+';
					}else{
					echo '\'營業中\'+';
					}
			  ?> '<br/></b></p>'+
			  '</div>'+
			  '</div>';
			  
			  sContent.push(contentString);
			  
			  
			var marker = new google.maps.Marker({
			position: {lat: <?php echo $row_str['lat'];?>, lng: <?php echo $row_str['lng'];?>},
			title: markers[i],
			map: map,
			info: sContent[i]
		  });
			 i++; 
			  
			  var infowindow = new google.maps.InfoWindow({
			content: sContent[i]
		  });
		
		
		
		  
			google.maps.event.addListener(marker, 'click', function() {		   
			infowindow.open(map,marker);		
			});
		  
		    google.maps.event.addListener(marker,'click', function() {
			infowindow.setContent(this.info);
			infowindow.open(map, this);
			});
			
		<?php }while($row_str = mysql_fetch_assoc($Restr)); ?>
		}
		
		
		
		
//		function initialize() {
//		
//		
//		var myOptions = {
//			zoom: 10,
//			mapTypeId: google.maps.MapTypeId.ROADMAP
//		  };
//		  var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
//		  
//		  // Try W3C Geolocation (Preferred)
//		 
//		}

    </script>
	
  <script async defer
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCAodiAdoK-e9GMN8ZZnJhkKWkDEHK5rTs&callback=initMap">
    </script>

</div>


<?php

}
?>