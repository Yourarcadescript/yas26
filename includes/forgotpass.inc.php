<?php
include ("db_functions.inc.php");
include ("config.inc.php");
if (isset($_POST["username"]) && isset($_POST["useremail"])) {
	$username = yasDB_clean($_POST["username"]);
	$useremail = yasDB_clean($_POST["useremail"]);
	$res = yasDB_select("SELECT * FROM user WHERE username='$username' AND email='$useremail'",false);                
	$row = $res->fetch_array(MYSQLI_ASSOC);
	$res->close();
	if (!empty($row)) {
		$email = $row["email"];
		srand((double)microtime()*1000000);  
		$random = rand(1234,2343); 
		$password = $row["name"].$random; 
		$upassword = md5($password);
		yasDB_update("UPDATE user SET password='$upassword' WHERE username='$username'",false);
		$headers ="From: $setting['sitename'] \n"; 
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1 \n";
		$subject = $setting['sitename']." Password Reset - Do Not Reply";
		$body="<div align=center><br><br>----------------------------- Password Reset--------------------------------<br><br><br><br>
		Your New Password is: $password<br/>
		Please log in with this password and create a new password in your profile.</div>";
		if(mail($email,$subject,$body,$headers)) {
			echo "Your password has been sent to your Email address.";
		} else {
			echo "There was an error sending the email.";
		}
	} else {
		echo '<span style="color: #ff0000;">Invalid user name and email combination. If you feel this is error, please contact us for assistance.</span><br/>';
	}
} else {
	echo '<span style="color: #ff0000;">Please enter both your username and email. If you need further assistance please contact us.</span><br/>';
}
?>