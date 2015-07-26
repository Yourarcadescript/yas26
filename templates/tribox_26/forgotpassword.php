<div id="center">
<div class="password_main_header">Forgot Password:</div>  
<div id="password_main_box">
<div id="forgot_password">      
<form name="myform" action="<?php echo $setting['siteurl'].'index.php?act=forgotpassword';?>" method="post">
Username: <input type="text" name="username" size="30" /><br/><br/>
Email:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="useremail" size="30" /><br/><br/>
<input type="submit" name="submit" value="Submit" /><br/><br/>
</form>
<?php
if (isset($_POST["username"]) || isset($_POST["useremail"])) {
	$username=$_POST["username"];
	$useremail=$_POST["useremail"];
	$res=yasDB_select("SELECT * FROM user WHERE username='$username' AND email='$useremail'",false);                
	$row = $res->fetch_array(MYSQLI_ASSOC);
    $res->close();
	if (!empty($row)) {
		$email=$row["email"];
		srand((double)microtime()*1000000);  
		$random=rand(1234,2343); 
		$password=$row["name"].$random; 
		$upassword=md5($password);
		yasDB_update("UPDATE user SET password='$upassword' WHERE username='$username'",false);
		$headers ="From: $setting[email] \n"; //from address
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1 \n";
		$subject = $setting['sitename']." Password Reset - Do Not Reply";
		$body="<div align=center><br><br>----------------------------- Password Reset--------------------------------<br><br><br><br>
		Your New Password is: $password<br/>
		Please log in and create a new password if you wish.</div>";
		if(mail($email,$subject,$body,$headers)) {echo "<font class=tblackb>Your password has been sent to your Email address</font>";} 
		else {echo "";}
	} else {
		echo '<span style="color: #ff0000;">Invalid user name or email. If you feel this is error, please contact us for assistance.</span><br/>';
	}
}

?>
</div>
<div class="clear"></div>
</div>