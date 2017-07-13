<?php 
require_once('Connections/sc.php');mysql_query("set names utf8");
require_once('Connections/lp.php');mysql_query("set names utf8");
?>
<?php 
session_start();
if ($_SESSION['MM_Username'] == ""){header(sprintf("Location: life_pay9.php"));exit;
}else{
  $m_username = $_SESSION['mem'];//登入帳號
  $m_nick = $_SESSION['nick'];//登入暱稱
  $number = $_SESSION['number'];
}
//echo $number;

mysql_select_db($database_lp, $lp); //無條件
$query_str = "SELECT * FROM lf_user WHERE level = 'boss'";
$Restr = mysql_query($query_str, $lp) or die(mysql_error());
$row_str = mysql_fetch_assoc($Restr);
$row_num = mysql_num_rows($Restr);

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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body class="person" style="height: 60vh">
<div class="maincontain">
<div class="mb_integral">
</div>

<?php
  if($row_num != 0){
    do{
?>
  <div class="col-lg-6 col-md-6 col-xs-6 mb_features" style="background: #fff">
<?php 
  $imgfile = "img/" . $row_str['user_id'] . '.jpg';
  if (file_exists($imgfile)) {
?>
    <a target="_blank" href="person_main9.php"><img src=<?php echo $imgfile; ?> width="100%" alt=""></a>
<?php 
  } else {
?>
    <a target="_blank" href="person_main9.php"><img src="img/life_store-01.png" width="100%" alt=""></a>
<?php 
  }
?>
    <ul>
      <li><?php echo $row_str['st_name']; ?></li>
      <li><?php echo $row_str['m_address']; ?></li>
      <li><?php echo $row_str['email']; ?></li>
      <li>
        <?php echo $row_str['time1'] . ' ~ ' . $row_str['time2']; ?>
      </li>
    </ul>
  </div>
<?php 
    }while($row_str = mysql_fetch_assoc($Restr));
  }
?>

<div class="col-lg-12 col-md-12 col-xs-12 st_pe_foot" style="height: 50px; background-color:#efefef; width: 100% ;position: fixed;">
  <a href="person_main9.php" style="color: #595757"><img src="img/login_footlogo-01.png" width="320px" style="margin-top: 10px" alt=""></a>
</div>
 </body>
</html>
