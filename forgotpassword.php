<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style>
    *
    {
		margin:0px; padding:0px;
    }
    body
    {
		background-repeat: repeat-x;background-position: top left;background-attachment: fixed;text-align: center;padding: 0 0 0 0;margin: 0 0 0 0;font-weight: normal;font-size: 12px;font-family: Tahoma, Arial, sans-serif;
		background-image: linear-gradient(bottom, #FFFFFF 0%, #D9D9D9 60%);
		background-image: -o-linear-gradient(bottom, #FFFFFF 0%, #D9D9D9 60%);
		background-image: -moz-linear-gradient(bottom, #FFFFFF 0%, #D9D9D9 60%);
		background-image: -webkit-linear-gradient(bottom, #FFFFFF 0%, #D9D9D9 60%);
		background-image: -ms-linear-gradient(bottom, #FFFFFF 0%, #D9D9D9 60%);
		background-image: -webkit-gradient(linear,left bottom,left top,color-stop(60, #D9D9D9),color-stop(60.0, #D9D9D9));
    }
    img
    {
		border: none;
    }
    .clear
    {
		clear:both;
    }
    #body_wrapper
    {
		margin: 0 auto;   width: 951px; text-align: left;
    }
    #top
    {
		background-image: url('images/logo.png'); background-repeat:no-repeat;
		width: 950px; height: 140px; position: relative; text-align: center;background-position:center;
    }
    #menu
    {
		color: #ffffff; background:transparent; background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAAoCAYAAAA/tpB3AAAAAXNSR0IArs4c6QAAAAZiS0dEAP8A/wD/oL2nkwAAAAlwSFlzAAALEgAACxIB0t1+/AAAAAd0SU1FB9sDBRYILVH+xREAAAAkSURBVAjXY2BgYPjPZGScxsD0l4GBgekfAwMtWUwkE8zEqQMAdPwWDNXfT1kAAAAASUVORK5CYII=);
		background-repeat: repeat-x; background-position: top-left;
		width: 950px; line-height: 16px; padding-top: 8px; padding-bottom: 8px; text-align: center;
    }
    #wrapper
    {
		width: 934px; color: #000; padding-top: 8px; padding-bottom: 8px; padding-left: 10px; text-align: center; margin-top: 20px;
	}
	#nametext
	{
		width:100%;text-align:center;position:relative;top:50px;font-size:35px;color:#00811E;
		font-family: Margarine,Tahoma, Arial, sans-serif;text-shadow: -2px -2px 2px rgba(94, 0, 0, 0.91);
	}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Forgot Password</title>
</head>
<body>
<div id="body_wrapper">
	<div id="top"></div>
    <div id="wrapper">
<b>Reset password</b><br /><br />
<?php

include "includes/db_functions.inc.php";


if($_GET['code'])
{	
	$get_username = yasDB_clean($_GET['username']);
	$get_code = yasDB_clean($_GET['code']);
	
	$query = yasDB_select("SELECT * FROM user WHERE username='$get_username'");

	while($row = mysqli_fetch_assoc($query))
	{
		$db_code = yasDB_clean($row['passreset']);
		$db_username = yasDB_clean($row['username']);
		
	}
	if($get_username == $db_username && $get_code == $db_code)
	{
		echo "
		<form action='pass_reset_complete.php?code=$get_code' method='POST'>
			Enter a new password between 4 and 20 characters!<br><input type='password' name='newpass'><br>
			Re-enter your password<br><input type='password' name='newpass1'><p>
			<input type='hidden' name='username' value='$db_username'>
			<input type='submit' value='Update Password!'>
		</form>
		
		";
	}
	
}

if(!$_GET['code'])
{
echo "

<form action='forgotpassword.php' method='POST'>
	Enter your username <input type='text' name='username'><p><br>
	Enter your email &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='text' name='email'><p></br>
	<input type='submit' value='Submit' name='submit'></br><br>
</form>

";

if(isset($_POST['submit']))
{
	$username = yasDB_clean($_POST['username']);
	$email = yasDB_clean($_POST['email']);
	
	$query = yasDB_select("SELECT * FROM user WHERE username='$username'");
	$numrow = $query->num_rows;

	if($numrow!=0)
	{
		while($row = mysqli_fetch_assoc($query))
		{
			$db_email = $row['email'];
		}
		if($email == $db_email)
		{
			$code = rand(10000,1000000);
			
			$to = $db_email;
			$subject = $setting['sitename']." Password Reset";
			$body = "
			You are receiving this e-mail because a request was made to reset your password			
			If you did not make this request, you can ignore this e-mail. Click this link to reset your password.
			".$setting['siteurl']."forgotpassword.php?code=$code&username=$username";
			
			yasDB_update("UPDATE user SET passreset='$code' WHERE username='$username'");
			mail($to,$subject,$body);
			
			echo "Check your email for password reset";
		}
		else
		{
			echo "Email is incorrect";	
		}
	}
	else
	{
		echo "That username doesnt exist";	
	}
}
}
?>
</div>
<div class="clear"></div>
</div>
</body>
</html> 