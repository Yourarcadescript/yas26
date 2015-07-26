<?php
@session_start();
$_SESSION['admin'] = '';
unset($_SESSION['admin']);
header("Location:".$setting['siteurl']."admin/index.php");
exit();
?>