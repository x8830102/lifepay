<?php 
require_once('Connections/sc.php');mysql_query("set names utf8");
require_once('Connections/lp.php');mysql_query("set names utf8");

session_start();
$mkey = $_GET['a'];

if ($_SESSION['MM_Username'] == ""){header(sprintf("Location: life_pay.php?a=$mkey"));exit;
}else{
  $m_username = $_SESSION['mem'];//登入帳號
  $m_nick = $_SESSION['nick'];//登入暱稱
  $number = $_SESSION['number'];
}
//echo $number;


mysql_select_db($database_lp, $lp); //無條件
$query_str = "SELECT * FROM lf_user WHERE level = 'boss' && profile != ''";
$Restr = mysql_query($query_str, $lp) or die(mysql_error());
$row_str = mysql_fetch_assoc($Restr);
$row_num = mysql_num_rows($Restr);

//取得登入者二級密碼
mysql_select_db($database_sc, $sc);
$query_user =sprintf("SELECT * FROM memberdata WHERE number ='$number' ");
$Reuser = mysql_query($query_user, $sc) or die(mysql_error());
$row_user = mysql_fetch_assoc($Reuser);
$pas_two = $row_user['m_passtoo'];

require_once("lp_user1.php");

?>

<style>
  
.img {
  vertical-align: middle;
  width: 100%;
  border-radius: 0.5em;
  color: #333;
  position: relative;
  
}
.img:after {
  content: '';
  display: block;
  padding-bottom: 50%;
}
.img > * {
  position: ;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  
  width: -webkit-fit-content;
  width: -moz-fit-content;
  width: fit-content;
  height: -webkit-fit-content;
  height: -moz-fit-content;
  height: fit-content;

  text-transform: uppercase;

}

.store_list {
  list-style: none;
  margin: 0;
  padding: .7em 0;
  white-space: nowrap;
  overflow-x: auto;
  -webkit-scroll-snap-type: mandatory;
  -ms-scroll-snap-type: mandatory;
      scroll-snap-type: mandatory;
  -webkit-scroll-behavior: smooth;
  scroll-behavior: smooth;
  -webkit-scroll-snap-points-x: repeat(18.88889%);
  -ms-scroll-snap-points-x: repeat(18.88889%);
      scroll-snap-points-x: repeat(18.88889%);
  font-size: 1.5vw;
}
.store_list > * {
  width: 18.88889vw;
}
.banner {
  height: 20vh
}
@media (min-width: 1024px) {
  .store_list > * {
  width: 13.88889vw;
}
  .store_list {
    font-size: 1.1vw
  }
  .banner {
    height: 35vh
  }
}
  
@media screen and (max-width: 800px) {
  .store_list {
    white-space: nowrap;
    overflow-x: auto;
    -webkit-scroll-snap-type: mandatory;
    -ms-scroll-snap-type: mandatory;
        scroll-snap-type: mandatory;
    -webkit-scroll-behavior: smooth;
    scroll-behavior: smooth;
    -webkit-scroll-snap-points-x: repeat(30%);
    -ms-scroll-snap-points-x: repeat(30%);
        scroll-snap-points-x: repeat(30%);
    font-size: 3vw;
  }
  .store_list > * {
    width: 30vw;
  }
}
@media (min-width: 768px) {
  .store_list > * {
  width: 15.88889vw;
}
  .store_list {
    font-size: 1.5vw
  }
  .banner {
  height: 30vh
}
}
.store_list li {
  display: inline-block;
  padding-right: 1em;
  vertical-align: top;
}
.store_list li:first-of-type {
  margin-left: 1em;
}
.bx-next {display: none;}
.bx-prev {display: none;}
.bx-default-pager {display: none;}
</style>
<style>
  .newsotre  {
    float: left;
  }
</style>
<div class="col-lg-12 col-md-12 col-xs-12" style="padding-right: 0px;padding-left:0px;margin-top: 130px ">
<script type="text/javascript">
  $(document).ready(function(){
    $('.bxslider').bxSlider({
     auto: true,
     autoControls: true
    });
  });


  function ok(){
    swal({
      title: '獲得 <span style="color:red;">碳佐麻里精品燒肉250點優惠券!</span><br>',
      html: '<div align="left">親愛的貴賓：<br />感謝您參與本次「串門子雲盟系統啟動大會」，本優惠券限本人使用，本優惠券限本人使用，有效期限：2017/06/30<br /><span style="line-height:40px">請至查詢優惠卷頁面查詢<a style="color:#fff;background-color:#4ab3a6;padding:5px 10px;border-radius:6px;font-size:16px;margin-left:7px" href="person_transfer.php">前往查詢</a></span><br><br>*限使用Life pay結帳與出示票券核銷為憑。<br>*本活動之碳佐麻里優惠券每次結帳限抵用250元。</div>',
      type: 'success',
      animation: "slide-from-top"
    });
  }
  function err(){
    swal({
      title: '(請至查詢優惠券確認此優惠券)',
      html: '親愛的貴賓：<br />您已經獲得過本優惠券',
      type: 'error',
      animation: "slide-from-top"
    });
  }
  function fin(){
    swal({
      title: '很抱歉',
      html: '本次優惠券已全數發放完畢',
      type: 'warning',
      animation: "slide-from-top"
    });
  }
  //有無結帳內容
  function password2(){
    var pas_two = "<?php echo $pas_two;?>";
    var mkey = "<?php echo $mkey;?>";
    //alert(pas_two);
    swal({
      title: '請輸入支付密碼',
      input: 'password',
      showCancelButton: true,
      confirmButtonText: '確認',
      cancelButtonText: '取消',
      allowOutsideClick: false,
      inputValidator: function (value) {
        return new Promise(function (resolve, reject) {
          if (value) {
            resolve()
          } else {
            reject('請輸入支付密碼!')
          }
        })
      }
    }).then(function (password) {
      if (password == pas_two) {
        window.location.href = "http://lifelink.cc/life_pay/c_ok.php?a="+mkey;
      }else{
        swal({
          type: 'error',
          html: '支付密碼錯誤'
        }).then(function () {
            password2();
        })
      }
    })

  }

  
</script>
<ul class="bxslider">
<?php

//發送活動優惠券
$c = $_GET['c'];
$st= $_GET['st'];
$li = $_GET['li'];

mysql_select_db($database_lp, $lp);
$SQL = "SELECT * FROM lf_user WHERE accont = '$st' ";
$SELECT = mysql_query($SQL, $lp) or die(mysql_error());
$row_user = mysql_fetch_assoc($SELECT);
$s_number = $row_user['number'];
$s_nick = $row_user['st_name'];

mysql_select_db($database_lp, $lp);
$SQL_se = "SELECT * FROM coupon WHERE original = '$m_username' and supply = '發布活動優惠券' ";
$SELECT_se = mysql_query($SQL_se, $lp) or die(mysql_error());
$row_se = mysql_num_rows($SELECT_se);

mysql_select_db($database_lp, $lp);
$SQL_co = "SELECT * FROM coupon WHERE s_number = '$s_number' and supply = '發布活動優惠券' group by p_number";
$SELECT_co = mysql_query($SQL_co, $lp) or die(mysql_error());
$row_co = mysql_num_rows($SELECT_co);

//判斷是否拿過活動優惠券
if($c != ""){
  if($row_co < $li){
    if($row_se == ""){
    //發優惠券
    $num = ceil($c/100);
    $remainder = $c%100;

      for($i=1 ;$i<=$num ;$i++)
      {
        if($i == $num)
        {
          if((int)$remainder != 0){
            mysql_select_db($database_lp, $lp);
            $inser_coupon = "INSERT IGNORE INTO coupon (s_user,s_number,s_nick,p_user,original,p_number,p_nick,discount,effective_date,supply,complete)value('$st','$s_number','$s_nick','$m_username','$m_username','$number','$m_nick','$remainder','2017-06-30','發布活動優惠券','0')";
            $inser = mysql_query($inser_coupon, $lp) or die(mysql_error());
          }else{
            mysql_select_db($database_lp, $lp);
            $inser_coupon = "INSERT IGNORE INTO coupon (s_user,s_number,s_nick,p_user,original,p_number,p_nick,discount,effective_date,supply,complete)value('$st','$s_number','$s_nick','$m_username','$m_username','$number','$m_nick','100','2017-06-30','發布活動優惠券','0')";
            $inser = mysql_query($inser_coupon, $lp) or die(mysql_error());
          }?>
          <script>ok();</script>
        <?php 
        }else{
          mysql_select_db($database_lp, $lp);
          $inser_coupon = "INSERT IGNORE INTO coupon (s_user,s_number,s_nick,p_user,original,p_number,p_nick,discount,effective_date,supply,complete)value('$st','$s_number','$s_nick','$m_username','$m_username','$number','$m_nick','100','2017-06-30','發布活動優惠券','0')";
          $inser = mysql_query($inser_coupon, $lp) or die(mysql_error());
        }
      }
    }else{?>
      <script>err();</script>
    <?php
    }
  }else{?>
      <script>fin();</script>
  <?php
  }
}


  if($row_num != 0){
    do{
?>

  <li class="banner"  style="align:center">
    <div class="col-lg-12 col-md-12 col-xs-12 " style="background: #fff;float:none;margin:0px auto;padding-left: 0px;padding-right: 0px" align="center">
      <a href="person_store.php?st_name=<?php echo $row_str['st_name'];?>"><img src="img/<?php echo $row_str['profile']; ?>" alt=""></a>
      <!--<img src="img/1.jpg" alt="" height="359" width="540">-->
    </div>
  </li>
<?php 
  
    }while($row_str = mysql_fetch_assoc($Restr));
  }
?>
</ul>
</div>
<div class="col-lg-12 col-md-12 col-xs-12" style="margin-top: 10px">
  <div><span style="color: #707070;font-size: 16px;margin-left: 10px">最新上架</span><span style="color: #707070;font-size: 13px;float:right;margin-right: 10px"><!--<a href="" style="color:#707070">顯示全部</a>--></span></div>
  <div style="overflow-y: hidden;overflow-x: hidden;white-space: nowrap;height: 110px;border-bottom: 1px solid #E5E5E2" >
  <ul class="store_list" >
  <?php 
  mysql_select_db($database_lp, $lp); //依照合約日
  $query_str = "SELECT * FROM lf_user WHERE level = 'boss' && profile != '' ORDER BY date DESC";
  $Restr = mysql_query($query_str, $lp) or die(mysql_error());
  $row_str = mysql_fetch_assoc($Restr);
  $row_num = mysql_num_rows($Restr);
  do{
  ?>
    
    <li>
      <div class="img">
        <a href="person_store.php?st_name=<?php echo $row_str['st_name'];?>"><div style="top: 0px;border:1px solid #999;border-radius:10px;background:#fff;background-image: url(img/<?php echo $row_str['profile'];?>);width: 100%;height: 60px;background-size:contain;background-repeat: no-repeat;background-position: 50% 50%"></div></a>
        <div ><?php echo $row_str['st_name'];?></div>
        <!--<div >營業中</div>-->
      </div>
    </li>
    <?php }while($row_str = mysql_fetch_assoc($Restr)); ?>
  </ul>
  </div>
</div>

<!--推薦店家-->
<div class="col-lg-12 col-md-12 col-xs-12" style="margin-top: 10px">
  <div><span style="color: #707070;font-size: 16px;margin-left: 10px">推薦商家</span><span style="color: #707070;font-size: 13px;float:right;margin-right: 10px"><!--<a href="" style="color:#707070">顯示全部</a>--></span></div>
  <div style="overflow-y: hidden;overflow-x: hidden;white-space: nowrap;height: 110px;border-bottom: 1px solid #E5E5E2" >
  <ul class="store_list" >
  <?php 
  mysql_select_db($database_lp, $lp); //依照評分
  $query_str = "SELECT lf_user . * , ROUND( (
          SUM( rt_level ) / COUNT( rt_level ) ) , 1
          ) AS rate
          FROM lf_user
          LEFT JOIN complete ON lf_user.number = complete.s_number
          WHERE profile !=  '' && level =  'boss' && rt_level != ''
          GROUP BY accont
          ORDER BY rate";
  $Restr = mysql_query($query_str, $lp) or die(mysql_error());
  $row_str = mysql_fetch_assoc($Restr);
  $row_num = mysql_num_rows($Restr);
  do{
  ?>
    
    <li>
      <div class="img">
        <a href="person_store.php?st_name=<?php echo $row_str['st_name'];?>"><div style="top: 0px;border:1px solid #999;border-radius:10px;background:#fff;background-image: url(img/<?php echo $row_str['profile'];?>);width: 100%;height: 60px;background-size:contain;background-repeat: no-repeat;background-position: 50% 50%"></div></a>
        <div ><?php echo $row_str['st_name'];?></div>
        <!--<div >營業中</div>-->
      </div>
    </li>
    <?php }while($row_str = mysql_fetch_assoc($Restr)); ?>
  </ul>
  </div>
</div>
<!--熱門排行-->
<div class="col-lg-12 col-md-12 col-xs-12" style="margin-top: 10px">
  <div><span style="color: #707070;font-size: 16px;margin-left: 10px">熱門排行</span><span style="color: #707070;font-size: 13px;float:right;margin-right: 10px"><!--<a href="" style="color:#707070">顯示全部</a>--></span></div>
  <div style="overflow-y: hidden;overflow-x: hidden;white-space: nowrap;height: 110px;border-bottom: 1px solid #E5E5E2" >
  <ul class="store_list" >
  <?php 
  mysql_select_db($database_lp, $lp); //依照評分
  $query_str = "SELECT lf_user . * , ROUND( (
          SUM( rt_level ) / COUNT( rt_level ) ) , 1
          ) AS rate
          FROM lf_user
          LEFT JOIN complete ON lf_user.number = complete.s_number
          WHERE profile !=  '' && level =  'boss' && rt_level != ''
          GROUP BY accont
          ORDER BY rate DESC";
  $Restr = mysql_query($query_str, $lp) or die(mysql_error());
  $row_str = mysql_fetch_assoc($Restr);
  $row_num = mysql_num_rows($Restr);
  do{
  ?>
    
    <li>
      <div class="img">
        <a href="person_store.php?st_name=<?php echo $row_str['st_name'];?>"><div style="top: 0px;border:1px solid #999;border-radius:10px;background:#fff;background-image: url(img/<?php echo $row_str['profile'];?>);width: 100%;height: 60px;background-size:contain;background-repeat: no-repeat;background-position: 50% 50%"></div></a>
        <div ><?php echo $row_str['st_name'];?></div>
        <!--<div >營業中</div>-->
      </div>
    </li>
    <?php }while($row_str = mysql_fetch_assoc($Restr)); ?>
  </ul>
  </div>
</div>
<div class="col-lg-12 col-md-12 col-xs-12" style="height: 60px"></div>


<!--有無結帳內容-->
<?php
if(isset($mkey)){?>
  <script>password2()</script>
<?php
}
