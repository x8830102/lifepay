<?php
session_start();
session_unset();
session_destroy();
header('Location: store_login.php');
exit;

?>