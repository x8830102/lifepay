<?php 
require_once('Connections/sc.php');mysql_query("set names 'utf8'");
require_once('Connections/lp.php');mysql_query("set names 'utf8'");
?>
<?php 
session_start();
mysql_select_db($database_lp, $lp);
$mkey = $_GET['a'];//qr內容傳值的key


//透過key取得交易資料
$query_str = sprintf("SELECT * FROM st_record WHERE verification ='$mkey' ");
$Restr = mysql_query($query_str, $lp) or die(mysql_error());
$row_str = mysql_fetch_assoc($Restr);
$totalRows_row_str = mysql_num_rows($Restr);
$st_nm = $row_str['s_nick'];
$c =  $row_str['sum'];
$s_user = $row_str['s_user'];
$discount =$row_str['discount'];
$s_number =$row_str['s_number'];
$u_number =$row_str['u_number'];
if($row_str == ""){header(sprintf("Location: store_main.php"));exit;
}

if ($_SESSION['MM_Username'] == ""){header(sprintf("Location: store_login.php"));exit;
}else{
  $mem = $_SESSION['mem'];
  $user_name = $_SESSION['nick'];
  $s_number = $_SESSION['number'];//驗證帳號
}

//交易人資料
mysql_select_db($database_sc, $sc);
$sql="SELECT * FROM memberdata WHERE number = '$u_number' ";
$conn=mysql_query($sql, $sc) or die(mysql_error());
$row = mysql_fetch_assoc($conn);
$m_username = $row['m_username'];
$m_nick = $row['m_nick'];

//串串積分
$query_Recf_c = sprintf("SELECT * FROM c_cash WHERE number = '$u_number' order by id desc");
$Recf_c = mysql_query($query_Recf_c, $sc) or die(mysql_error());
$row_Recf_c = mysql_fetch_assoc($Recf_c);
$totalRows_Recf_c = mysql_num_rows($Recf_c);
$cc = $row_Recf_c['sum'];
//消費積分
$query_Recf_g = sprintf("SELECT * FROM g_cash WHERE number = '$u_number' order by id desc");
$Recf_g = mysql_query($query_Recf_g, $sc) or die(mysql_error());
$row_Recf_g = mysql_fetch_assoc($Recf_g);
$totalRows_Recf_g = mysql_num_rows($Recf_g);
//紅利積分
$query_Recf_r = sprintf("SELECT * FROM r_cash WHERE number = '$u_number' order by id desc");
$Recf_r = mysql_query($query_Recf_r, $sc) or die(mysql_error());
$row_Recf_r = mysql_fetch_assoc($Recf_r);
$totalRows_Recf_r = mysql_num_rows($Recf_r);

//抵用券
$date = date("Y-m-d");
mysql_select_db($database_lp, $lp);
$query_coupon = sprintf("SELECT * FROM coupon WHERE p_number ='$u_number' && s_number ='$s_number' && complete ='0' && effective_date >='$date'  order by effective_date asc");
$Recoupon = mysql_query($query_coupon, $lp) or die(mysql_error());
$row_coupon = mysql_fetch_assoc($Recoupon);
$num_coupon = mysql_num_rows($Recoupon);

//抵用券%數
$sql1 = "SELECT * FROM lf_user WHERE number ='$s_number' && accont = '$mem'";
$result = mysql_query($sql1) or die(mysql_error());
$row1 = mysql_fetch_assoc($result);

$original = $row1['st_dis'];
	  

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
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.1/angular.min.js"></script>
  <script src="js/syn.js"></script>
  <style>
    ul li{
      display: inline;
      float: left;
      width: 33%;
    }
    .log{
      background: black;
      color: #fff;
    }
  </style>
  <script type="text/javascript">
	
	
	//抵用券折扣
	function amount(){
	//var val = JSON.parse(value);
	document.getElementById('dis').type='text';
	var coupon_sum = document.getElementById('coupon_sum').value;
	
	document.getElementById('dis').value=coupon_sum;
	/*document.getElementById('idd').value=val.id;*/
	
	if(document.getElementById('dis').value != ''){
	  var price = Number(document.getElementById('price').value);//消費金額
	  var disprice = document.getElementById('disprice');//折扣金額
	  var dis = Number(document.getElementById('dis').value);
	  var price_payable = document.getElementById('price_payable');//應付金額
	  var coupon = document.getElementById('coupon');
	  var discount = <?php echo $discount;?>;
	  disprice.value = price-dis;
	  price_payable.value = disprice.value;
    if(price_payable.value < 0 ){
      price_payable.value = 0;
    }
	  //coupon.value = Math.floor(disprice.value/100*discount);
	  
	}

	//document.getElementById('c').value=0;
	document.getElementById('c').style.background = "white";
	//document.getElementById('g').value=0;
	document.getElementById('g').style.background = "white";
	document.getElementById('sub').style.display= "";
	}
	//有輸入抵用金時 會先扣抵用金
	/*function dis()
	{
		var price = Number(document.getElementById('price').value);
		//alert(price);
		var discount = Number(document.getElementById('discount').value)
		//alert(discount);
		if(discount<=price)
		{
			document.getElementById('price_payable').value = price - discount;
			document.getElementById('disprice').value = price - discount;
		}else{
			document.getElementById('price_payable').value =0;
			document.getElementById('disprice').value =0;
		}
	}*/
	//檢查輸入是否正確
    function check()
	{

      var cc = Number(document.getElementById('cc').value);
      var c = Number(document.getElementById('c').value);
      var gg = Number(document.getElementById('gg').value);
      var g = Number(document.getElementById('g').value);
      var sub = document.getElementById('sub');
      var price_payable = Number(document.getElementById('price_payable').value);
      var price =  Number(document.getElementById('price').value);//消費金額
      var disprice = Number(document.getElementById('disprice').value);//折扣後金額
      var dis = Number(document.getElementById('dis').value);
      //alert(price);

	  if(price_payable.value < 0){ //應付不可為負
		document.getElementById('sub').style.display= "none";
		}else{
		document.getElementById('sub').style.display= "";
		}

	  if(c>cc || g>gg){ //不可超額使用積分
		if(c!=0){
		document.getElementById('c').style.background = "pink";
		document.getElementById('ct').value = cc;
		document.getElementById('sub').style.display= "none";
		}
		if(g!=0){
		document.getElementById('g').style.background = "pink";
		document.getElementById('gt').value = gg;
		document.getElementById('sub').style.display= "none";
		}

		
	  }else if (c<=cc || g<=gg){
  		if(c<=cc){
  		document.getElementById('c').style.background = "white";
  		document.getElementById('ct').value = cc-c;
  		document.getElementById('sub').style.display= "";
  		}
  		if(g<=gg){
  		document.getElementById('g').style.background = "white";
  		document.getElementById('gt').value = gg-g;
  		document.getElementById('sub').style.display= "";
  		}
      }

    if(disprice !=''){
        price_payable = price-dis;
        document.getElementById('price_payable').value = price_payable-c-g;
        if(Number(document.getElementById('price_payable').value)<0){
          document.getElementById('price_payable').style.background = "pink";
		  document.getElementById('sub').style.display= "none";
        }else{document.getElementById('price_payable').style.background = "white";}
      }else{
        price_payable = price-dis;
        document.getElementById('price_payable').value = price_payable-c-g;
        if(Number(document.getElementById('price_payable').value)<0){
          document.getElementById('price_payable').style.background = "pink";
		  document.getElementById('sub').style.display= "none";
        }else{
		document.getElementById('price_payable').style.background = "white";
		document.getElementById('sub').style.display= "";
		}
      }
		
  }
	//限制不能輸入文字和字母
	function ValidateNumber(e, pnumber)
	{
		if (!/^\d+$/.test(pnumber))
		{
			e.value = /^\d+/.exec(e.value);
		}
		return false;
	}
      

  //對話框
  y=$(window).width(); //螢幕寬度table coupon_table
  x=$(window).height();//螢幕高度
      if(y>"1024"){y=y/2;}else{y=y/1;}
      var cb = document.getElementsByName('cb');
      var user_login ="<?php echo $user_login;?>";
      $(function()
      {
        //優惠卷開啟
        $( "#dialog_open" ).click(function(event) 
        { //按下超連結
          $( "#dialog" ).dialog( "open");
          event.preventDefault();  //防止上方連結打開
          
          //$(cb).prop("disabled", false);
          //$(cb).prop("checked", false);//每次打開都要重新選擇優惠券
        });
      
          y = y/1.1;
          x = x/1.5;
          
          $( "#dialog" ).dialog
          ({
             show: {
              effect: "fade",
              },
            autoOpen: false, //預設不顯示
            draggable: false, //設定拖拉
            resizable: true, //設定縮放
            //title:" ",
            //dialogClass: "no-close", //關閉標題
            height: x,
            width: y,
            modal: true //灰色透明背景限制只能按dialog
            
          })
          $(".but").click(function(event){
              $("#dialog").dialog( "close" );
          })

        //$('.ui-dialog-titlebar-close').hide(); //關閉標題的叉叉
      })


	function error(){  //檢查輸入值不為0不為空
	var moneeey = document.getElementById('moneeey').value;
	if(moneeey == "" || moneeey <= 0)
	{
		document.getElementById('error').style.display="";
	}else{
		document.getElementById('form_ney').submit();
	}
}


  </script>
  <style>
.search_table th ,.search_table td{
    text-align: center;

    line-height: 17px!important;
 

}
  </style>
</head>
<body style="background-color:#84ddcb" ng-app="syn">


<div class="mebr_top" align="center" >
  <img src="img/life_pay_logo-01.png" width="220px" alt="">
</div>
  <div class="search_table table-responsive " align="center" style="overflow-y: visible;white-space: nowrap;padding-top: 10px">

    <table align="center" class="table coupon_table" style="width: 100%;">
    <p style="color: red;font-size:20px;"></p>
      <form id="checkout" name="checkout" method="post" action="data_in.php?a=<?php echo $mkey;?>">
        <tr>
         <td style="float: left;">商家：</td>
          <td class="" colspan="2" style="text-align: left;"><?php echo $st_nm;?></td>
          <input type="hidden" name="st_nm" value="<?php echo $st_nm;?>">
          <input type="hidden" name="tol_cs" value="<?php echo $c;?>">
        </tr>
        <tr>
          <td style="float: left;">消費帳號：</td>
          <td class="" colspan="2" style="text-align: left;"><?php echo $m_username?></td>
          <input type="hidden" name="m_username" value="<?php echo $m_username;?>">
          <input type="hidden" name="m_nick" value="<?php echo $m_nick;?>">
          <input type="hidden" name="u_number" value="<?php echo $u_number;?>">
        </tr>
        <tr>
          <td style="float: left;">名稱暱稱：</td>
          <td class="" colspan="2" style="text-align: left;"><?php echo $m_nick?></td>
        </tr>
        <tr >
          <td style="float: left;">本次消費金額：</td>
          <td class="" colspan="2" style="text-align: left;"><input type="hidden" id="price" name="price" readonly="readonly" value="<?php echo $c;?>"><input type="text" id="pricee" name="pricee" readonly="readonly" value="<?php echo number_format($c);?>" style="border: 0px;width: 120px;height: 28px;line-height: 26px;text-align: left;background: #84ddcb"></td>
        </tr>
		    <tr>
          <td style="float: left;">使用抵用券：</td>
          <td class="" colspan="2" style="text-align: left;"><input type="button" id="dialog_open" style="color:#fff; background-color:#4ab3a6;border:0px;height: 32px;width: 120px" value="抵用券"><br>
          <input type="hidden" readonly="readonly" id="dis" name="discount" value="" style="border:0px;width: 120px;margin-top: 5px;padding:4px ">
        </tr>
        <tr >
          <td style="float: left;"><span style="height: 50px;line-height: 27px">消費積分餘額：<?php if($totalRows_Recf_g == 0){
          echo '0</span>';
          }else{echo number_format($row_Recf_g['csum']);}?><input type="hidden" id="gg" name="gg" value="<?php if($totalRows_Recf_g == 0){
          echo '0</span>';
          }else{echo $row_Recf_g['csum'];}?>" readonly="readonly" style="border: 0px;width: 70px;height: 28px;text-align: center;background-color: #84ddcb;line-height: 28px" onKeyup="error()"></td>
          <td class="" colspan="2" style="text-align: left;"><input type="tel" id="g" name="g" autocomplete="off" onkeyup="check()"  value="" style="width: 120px;height: 28px;line-height: 28px;border:0px"onkeyup="return ValidateNumber(this,value)" >
          <input type="hidden" id="gt" name="gt" value="<?php if($totalRows_Recf_g == 0){echo '0';}else{echo $row_Recf_g['csum'];}?>"></td>
        </tr>
        <tr>
          <td style="float:left;"><span style="height: 50px;line-height: 27px">串串積分餘額：<?php if($totalRows_Recf_c == 0){
            echo '0</span>';
          }else{echo number_format($row_Recf_c['csum']);}?><input type="hidden" id="cc" name="cc" value="<?php if($totalRows_Recf_c == 0){
            echo '0</span>';
          }else{echo $row_Recf_c['csum'];}?>" readonly="readonly" style="border: 0px;width: 70px;height: 28px;text-align: center;line-height: 28px;background: #84ddcb" onKeyup="error()"></td>
          <td class="" colspan="2" style="text-align: left;"><input type="tel" id="c" name="c" autocomplete="off" onkeyup="check()" value="" style="width: 120px;height: 28px;line-height: 28px;border:0px" onkeyup="return ValidateNumber(this,value)" >
          <input type="hidden" id="ct" name="ct" value="<?php if($totalRows_Recf_c == 0){echo '0';}else{echo $row_Recf_c['csum'];}?>"></td>
        </tr>
        <tr >
          <td style="float: left;">應付現金或信用卡：</td>
          <td class="" colspan="2" style="text-align: left;"><input type="text" name="spend" id="price_payable" readonly="readonly" value="<?php echo $c;?>" style="border:0px; width: 120px;height: 28px;line-height: 28px"></td>
        </tr>
        <tr>
          <td style="float: left;">抵用券發放：</td>
          <?php if($row1['password2']){ ?>
          <td class="" colspan="2" style="text-align: left;"><input type="number" min="0" max="100" style="background-image:url(img/s_discount.svg);background-repeat:no-repeat;background-position:90px;background-size:20px;border:0px; width: 120px;height: 28px;line-height: 28px" name="st_dis" id="st_dis" value="<?php echo $original;?>"></td>
          <?php }else{ ?>
          <td class="" colspan="2" style="text-align: left;"><input type="number" min="0" max="100" style="background-image:url(img/s_discount.svg);background-repeat:no-repeat;background-position:90px;background-size:20px;border:0px; width: 120px;height: 28px;line-height: 28px" name="st_dis" id="st_dis" value="<?php echo $original;?>" disabled></td>
          <?php } ?>
        </tr>
		    <!--
        <tr>
          <td style="float: right;">下次使用之折扣卷 <?php echo $discount;?>% : </td>
      		  <script>
      		  function dis(){
      			  var discount = <?php echo $discount;?>;
      			  if(discount != 0)
      			  {
      				var coupon = document.getElementById("coupon");
      				var price =  document.getElementById("price");
      				 coupon.value = Math.floor(price.value*(discount/100));
      			  }
      		  }
      		  </script>
          <td class="sys_td" colspan="2"><input type="text" name="coupon" id="coupon" readonly="readonly" value="0" style="border:0px; width: 120px;height: 32px;line-height: 30px"/></td>
        </tr>
        -->
		    <input type="hidden" name="mkey" value="<?php echo $mkey;?>">
		    <input type="hidden" id="coupon_id" name="coupon_id" value="">
        <input type="hidden" id="s_number" name="s_number" value="<?php echo $s_number;?>">
        <input type="hidden" id="disprice" value="">
        <input type="hidden" name="s_user" value="<?php echo $mem;?>">
        <tr>
          <td colspan="3" style="text-align: center;"><input type="submit" id="sub" name="sub" class="sys_bt" style="display: inline;margin: 5px;width: 80%; background: #fff;border:0px;border-radius: 20px;height: 38px;color: #4ab3a6;font-weight: 800"   value="下一步"></td>
        </tr>
        
        <!--<script>dis();</script>-->
      </form>

  <!--按下後開啟抵用券-->
        <div id="dialog" title="使用抵用券">

          <ul style="text-align: center;">
            <li class="log" style="padding: 5px;background: #666">有效期限</li>
            <li class="log" style="padding: 5px;background: #666">金額</li>
            <li class="log" style="padding: 5px;background: #666">使用</li>
          </ul>
          <br>
          <div style="overflow-y: scroll;height: 170px;">
          <?php if($num_coupon != 0){
            $g=0;
          do{
            $discount = $row_coupon['discount'];
            $id = $row_coupon['ID'];
            $cid = "cb".$num_coupon;
              $arr = array("discount"=>(int)$discount,"id"=>(int)$id,"cid"=>$cid);
              $arr_json = json_encode($arr);
              //print_r($arr);
              //echo $arr_json;
            ?>
          			<script>

                var cbcs = new Array();
                function ff(v){
                  var k;
                  var q;
                  var cb = document.getElementsByName('cb');
                  var price_payablee = document.getElementById('price_payablee');
                  for(k=0;k<cb.length;k++)
                  {
                    if(cb[k].checked)
                    {
                        var jtt = JSON.parse(cb[k].value);
                        cbcs[k] = jtt.cid;
                        //cbb = cbcs[k];
                        //var x = [].slice.call(cbcs);
                        //alert(x);
                        for(q=0;q<cb.length;q++){
                          var cbb = document.querySelectorAll("."+cbcs[k]);
                            if(price_payablee.value <= 0){

                              $(cb).prop("disabled", true);
                              $(cbb).prop("disabled", false);
                              document.getElementById('g').readOnly = true;
                              document.getElementById('c').readOnly = true;
                            }else{

                              $(cb).prop("disabled", false);
                              document.getElementById('g').readOnly = false;
                              document.getElementById('c').readOnly = false;
                            }

                        }
                    }
                  }
                  //alert(cbcs);
                }

          			//優惠卷使用計算
          			var jttsum = new Array();
                var jttid = new Array();
          			function compute(){

          				var i;
          				var cb = document.getElementsByName('cb');
          				var totle = 0;
				          var price_payablee = document.getElementById('price_payablee');
                  var price = Number(document.getElementById('price').value);
          				for(i=0 ; i<cb.length ; i++)
          				{
          					if(cb[i].checked)
          					{
          						var jtt = JSON.parse(cb[i].value);
          						jttsum[i] = jtt.discount;
                      jttid[i] = jtt.id;
                      //alert(jttsum[i]);
                      document.getElementById('coupon_id').value = jttid;
          					}else{
          						jttsum[i] = 0;
                      jttid[i] = 0;
                      document.getElementById('coupon_id').value = jttid;
          					}
          				}
          				for(i=0 ; i<cb.length ; i++)
          				{		
          					totle = totle + jttsum[i];
          				}
          				document.getElementById('coupon_sum').value = totle;
                  
                    price_payablee.value = price-totle;
                    document.getElementById('c').value="";
                    document.getElementById('g').value="";
                    document.getElementById('c').style.background = "white";
                    document.getElementById('g').style.background = "white";
                    //document.getElementById('sub').style.display= "";

                    if(price_payablee.value <= 0){
                      document.getElementById('price_payablee').style.background = "pink";
                      document.getElementById('price_payable').style.background = "pink";
                      price_payablee.value = 0;
                      //$(cb).prop("disabled", true);
                    }else{
                      document.getElementById('price_payablee').style.background = "white";
                      document.getElementById('price_payable').style.background = "white";
                    }
          			}
          			
          			</script>

          <ul style="text-align: center;">
            <li style="padding: 5px"><?php echo $row_coupon['effective_date'];?></li>
            <li style="padding: 5px"><?php echo $discount;?></li>
            <?php 
            $g=$g+$discount;
            ?>
            <li style='padding: 5px'><input type='checkbox' id='cb' name='cb' class='cb<?php echo $num_coupon;?>' value='<?php echo $arr_json;?>' onclick='compute();ff(<?php echo $num_coupon;?>)' style='border:0px;border-radius:6px;background:#376fe4;color:#fff' <?php /*判斷折扣券有沒有超過消費金額*/ if($c-$g>=0){echo 'checked';}else{}?>></li>

          </ul><br>
          <?php 

          $num_coupon =$num_coupon-1;

          }while($row_coupon = mysql_fetch_assoc($Recoupon));}?></div>
      		<ul><li><HR color="#00FF00" size="10" ></li><li><HR color="#00FF00" size="10" ></li><li><HR color="#00FF00" size="10" ></li></ul>
        <div>
            <span style="float:right;">使用金額 : <input type="text" readonly="readonly" style="width:50px;border:0px;" id="coupon_sum" value=""/>
            <span>尚需金額：<input type="text" id="price_payablee" readonly="readonly" value="<?php echo $c; ?>" style="border:0px; width: 100px;height: 32px;line-height: 30px"></span></span>
            <div align="center"><button id='but' class='but' onclick='amount()' style="border:0px;border-radius:6px;background:#376fe4;color:#fff;width: 95%;height: 30px;margin: 15px">使用</button></div>
            <!--自動使用折扣券-->
            <script>compute();amount();</script>
        </div>
          

		
        </div>
    </table>

  </div>
  <script>
    $( "#st_dis" ).keyup(function() {
      var st_value = $( this ).val();
      if(st_value >100 || st_value<0){
        $("#sub").css("display","none");
        $( "p" ).text( "抵用折扣%數輸入錯誤" );
      }else{
        $("#sub").css("display","");
      }
    })
  </script>

</body>
</html>
