<!DOCTYPE html>
<html lang="zh-Hant">
<head>
	<meta charset=UTF-8>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title>lifepay 管理平台</title>
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/manag_style.css">
	<link rel="stylesheet" href="/wp-content/themes/the-rex/css/jquery-ui.css" type="text/css">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="js/jquery.scrollTo-1.4.2-min.js"></script>
	<script type="text/javascript" src="http://masonry.desandro.com/masonry.pkgd.min.js"></script>


</head>
	<div class="wrapper">
		<nav id="leftNav" class="hidden-xs">
			<ul >
				<a href="manage_main.php"  ><li><img src="img/home.png" width="73px" alt=""></li></a>
				<a href="manage_Invoice.php" ><li ><img src="img/account.png" width="73px" alt=""></li></a>
				<a href="manage_inquire_store.php"><li  class="active"><img src="img/shop_info.png" width="73px" alt=""></li></a>
				<a href="manage_inquire_person.php"><li  class=""><img src="img/user_infor.png" width="73px" alt=""></li></a>
				<a href="manage_analyze_store.php"><li  class=""><img src="img/analytics-01.png" width="73px" alt=""></li></a>
			</ul>
		</nav>
		<nav id="bottomNav" class="visible-xs" style="z-index: 999">
			<ul >
				<a href="manage_main.php"  ><li  style="width: 20%"><img src="img/home.png" width="50px" alt=""></li></a>
				<a href="manage_Invoice.php" ><li style="width: 20%"><img src="img/account.png" width="50px" alt=""></li></a>
				<a href="manage_inquire_store.php"><li class="active " style="width: 20%"><img src="img/shop_info.png" width="50px" alt=""></li></a>
				<a href="manage_inquire_person.php"><li style="width: 20%"><img src="img/user_infor.png" width="50px" alt=""></li></a>
				<a href="manage_analyze_store.php"><li  style="width: 20%"><img src="img/analytics-01.png" width="50px" alt=""></li></a>
			</ul>
		</nav>
		<header class="template_head">
			<div class="template_object">
				<ul>
					<!--<li  class="col-lg-12 col-md-12 col-xs-12"><input type="text" placeholder="全站搜尋" style="z-index: 999"></li>-->
					<li  ><img src="img/life_pay_logo.png"  alt="" style="z-index: 999"></li>
					<a href="mlogout.php"><li><img src="img/signout.png" alt="" style="width: 20px;left: 88%;top: 30px" style="z-index: 999"></li></a>
				</ul>
			</div>
		</header>
	</div>
