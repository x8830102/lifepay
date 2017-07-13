<?php
session_start();
session_unset();
session_destroy();
header('Location: manage_login.php');
exit;

?>