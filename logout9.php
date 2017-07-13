<?php
session_start();
session_unset();
session_destroy();
header('Location: life_pay.php');
exit;

?>