<div id="center">  
<div class="container_box1">
<div id="headergames2">Contact Us</div> 
<div class="containbox"><noscript> 
<?php
$successmsg = '';
$errormsg = '';
if(isset($_SESSION["user"])) { 
	$query = yasDB_select("SELECT * FROM `user` WHERE username = '{$_SESSION["user"]}'",false);
	$row = $query->fetch_array(MYSQLI_ASSOC);
	$query->close();
	$username = $row['name'];
} 
else {   
	$username = 'Guest' ;
}
if(isset($_POST['contactsubmit']) && isset($_POST['message']) && isset($_POST['email'])) {
	if ($_POST['recaptcha'] == 'yes') {	
		include($setting['sitepath']."/includes/securimage/securimage.php");
		$img = new Securimage();
		$valid = $img->check($_POST['code']);
		if (!$valid) {
			$passed = false;
		} else {
			$passed = true;
		}
	}
	elseif ($_POST['recaptcha'] == 'no') {
		$answer = array('10', 'ten');
		if(!in_array(strtolower($_POST['security']),$answer)) {
			$passed = false;
		} else {
			$passed = true;
		}
	}
	if ($passed) {
		$message = yasDB_clean($_POST['message']);
		$email = yasDB_clean($_POST['email']);
		$headers = 'From: '.$username.' <'.$username.'>';
		$subject = 'Contact message from '.$username.' through '.$setting['sitename'];
		if (@mail($setting['email'], $subject, $message, $headers)) {
			echo '<span style="color:red;">Message sent</span><br/><br/>';
		} else {
			echo '<span style="color:red;">Error sending message</span><br/><br/>';
		}
	} else {
		echo '<span style="color:red;">The security question was answered incorrectly. Please try again.</span><br/><br/>';
	}
}
	$name = isset($_POST['name'])?$_POST['name']:'';
	$email = isset($row['email'])?$row['email']:$email;
	$message = isset($_POST['message'])?$_POST['message']:'';
	?></noscript>
	<div id="preview">
			<?php
		if ($successmsg != '') {
			echo $successmsg;
		}
		elseif ($errormsg != '') {
			echo $errormsg;
		}
		?>
	</div>
	<div id="contactBox"><center>
	<form id="contactform" name="contactform" method="post" action="index.php?act=contactus">
	Name:<br /><input type="text" id="name" name="name" value="<?php echo $username;?>" size="50" /><br/><br/>
	Email:<br />
	<input type="text" id="email" name="email" value="<?php echo $email;?>" size="50" /><br/><br/>
	Message:<br />
	<textarea id="message" name="message" rows="5" cols="50"><?php echo $message; ?></textarea><p><br/><?php

  
	if ($setting['userecaptcha'] == "yes") {
		@session_start();
		// securimage captcha
		?>
		<div style="width: 700px; float:left;height: 90px">
		<img id="siimage" align="center" style="padding-right: 5px; border: 0" src="<?php echo $setting['siteurl']; ?>includes/securimage/securimage_show.php?sid=<?php echo md5(time()) ?>" />
		<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="19" height="19" id="SecurImage_as3" align="middle">
			<param name="allowScriptAccess" value="sameDomain" />
			<param name="allowFullScreen" value="false" />
			<param name="movie" value="<?php echo $setting['siteurl']; ?>includes/securimage/securimage_play.swf?audio=securimage_play.php&bgColor1=#fff&bgColor2=#284062&iconColor=#000&roundedCorner=5" />
			<param name="quality" value="high" />			
			<param name="bgcolor" value="#284062" />
			<embed src="<?php echo $setting['siteurl']; ?>includes/securimage/securimage_play.swf?audio=securimage_play.php&bgColor1=#fff&bgColor2=#284062&iconColor=#000&roundedCorner=5" quality="high" bgcolor="#284062" width="19" height="19" name="SecurImage_as3" align="middle" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
		 </object>
					
		<!-- pass a session id to the query string of the script to prevent ie caching -->			
		<a tabindex="-1" style="border-style: none" href="#" title="Refresh Image" onclick="document.getElementById('siimage').src = '<?php echo $setting['siteurl']; ?>includes/securimage/securimage_show.php?sid=' + Math.random(); return false"><img src="<?php echo $setting['siteurl']; ?>includes/securimage/images/refresh.gif" alt="Reload Image" border="0" onclick="this.blur()" align="middle" /></a>
		<div style="clear: both"></div>
		</div>			
		Security Code:<br />
		<input type="text" id="code" name="code" size="12" /><br /><br />
		<input name="recaptcha" type="hidden" value="yes" /><?php
		// end securimage captcha
	}
	else {
		?>Security Question: five + five = <br />
		<input name="security" type="text" style="border: 1px solid #000;" /><br/>
		<input name="recaptcha" type="hidden" value="no" /><?php
	}

?><input type="submit" name="contactsubmit" value="Send" />
</form></center></div>
</div><div class="clear"></div></div>