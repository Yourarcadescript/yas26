<div id="center"> 
<div class="container_box1"><div id="headergames2">Members</div> 
<div class="containbox">   
<?php
//include "includes/db_functions.inc.php";

$newpass = $_POST['newpass'];
$newpass1 = $_POST['newpass1'];
$post_username = $_POST['username'];
$code = $_GET['code'];

   if (strlen($_POST['newpass'])<4 || strlen($_POST['newpass'])>20) {
echo "<h3>Password must be between 4 and 20 characters!</h3><br />Go back to your mail and click on the link again";
}else{

if($newpass == $newpass1)
        {
	$enc_pass = md5($newpass);
	
	yasDB_update("UPDATE user SET password='$enc_pass' WHERE username='$post_username'");
	yasDB_update("UPDATE user SET repeatpassword='$enc_pass' WHERE username='$post_username'");
	yasDB_update("UPDATE user SET passreset='0' WHERE username='$post_username'");
	
	echo "Your password has been updated!";
}
else
{
	echo "Passwords must match. Go back to your mail and click on the link again";
}	
}
?>
</div>
<div class="clear"></div></div>