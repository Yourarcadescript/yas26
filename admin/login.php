<?php
@session_start();

$msg = '';

if (isset($_POST['password']) && $_SESSION['logtries'] < 5) {
	if($setting['password'] == md5($_POST['password'])) {
		$_SESSION['admin'] = "logged";
		$_SESSION['logtries'] = 0;
		$_SESSION['timer'] = 0;
		header("Location:index.php");
		exit();
	} else {
		if (!isset($_SESSION['timer'])) { $_SESSION['timer'] = 0; }
		if (!isset($_SESSION['logtries'])) { $_SESSION['logtries'] = 0; }
		$_SESSION['logtries'] = $_SESSION['logtries'] + 1;
		if ($_SESSION['logtries'] == 4) { $_SESSION['timer'] = time(); }
		$msg = "<span style=\"text-align:center; display:block;\">Wrong password, sorry. Attempts: ".$_SESSION['logtries']."</span>";
	}
}
if ( isset($_SESSION['timer'])) {
	if ($_SESSION['timer'] == 0) {
		$tdif = 0;
	} else {
		$tdif = time() - $_SESSION['timer'];
		if ($tdif >= 900) { // 900 seconds or 15 minute timeout
			//reset the login process and allow user to login again
			$_SESSION['timer'] = 0;
			$tdif = 0;
			$_SESSION['logtries'] = 0;
		}
	}
} else {
	$_SESSION['timer'] = 0;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<link rel="stylesheet" type="text/css" href="css/loginstyle.css" media="screen" title="Style" />
</head>	
<div align="center">
<div id="admincontent"><br/>
<?php
if (isset($_SESSION['logtries']) && $_SESSION['logtries'] > 0) {
	?>
	<div id="message" style="color:red;text-align:center;"><?php echo $msg; ?></div><br/>
	<?php
} ?>
<h3>Please login to access the admin area.</h3><br />
<?php
if ($tdif == 0)  {
?>
<form name="login" id="login" method="post" action="index.php">
Admin Password:<br />
<input type="password" name="password" id="password"/><br /><br/>
<input type="submit" name="login" value="Login"/><br/><br/>
</form>
<?php
} else {
	echo 'You can try again in '. gmdate("i:s", abs(900-$tdif))."<br/><br/>";
}
?>
</div></div>
</body>
</html>