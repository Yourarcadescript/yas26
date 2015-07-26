<div id="center">
<div class="container_box1">
<div id="headergames2">Add Link:</div> 
<?php
include ($setting['sitepath'] . "/includes/checklinks.class.php");
if (isset($_POST['url'])) {
	$post_text = yasDB_clean($_POST['text'],true);
	$post_desc = yasDB_clean($_POST['description'],true);
	$post_url = yasDB_clean($_POST['url']);
	$post_reciprocal = yasDB_clean($_POST['reciprocal']);
	$post_linkemail = yasDB_clean($_POST['linkemail']);
} else {
	$post_text = '';
	$post_desc = '';
	$post_url = '';
	$post_reciprocal = '';
	$post_linkemail = '';
}
if(isset($_GET['add']) && $_GET['add'] == 'link') {
	$post_text = yasDB_clean($_POST['text'],true);
	$post_desc = yasDB_clean($_POST['description'],true);
	$post_url = yasDB_clean($_POST['url']);
	$post_reciprocal = yasDB_clean($_POST['reciprocal']);
	if(empty($post_text) OR empty($post_url) OR empty($post_reciprocal)){
		echo"<center>You didn't fill everything out for your link!</center>";
	}
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
    
	if(strlen($post_text) > 30) {
		echo '<center>Your text cant be greater then 30 characters.</center><br/>';
	}
	elseif(strlen($post_url) > 65) {
		echo '<center>Your url cant be greater then 65 characters.</center><br/>';
	}
    elseif(strlen($post_reciprocal) > 65) {
		echo '<center>Your Reciprocal link cant be greater then 65 characters.<br/></center>';
	}
	elseif(!$passed) {
		echo "You have failed the security code.  Please try again.<br/>";  
	}
	elseif($passed) {
	    $checklink = new checkLink;
		$response = $checklink->validateLink($post_reciprocal,$setting['siteurl']);
		switch ($response) {
			case LINKFOUND:		
				if ($setting['approvelinks']=='no') {
					yasDB_insert("INSERT INTO links(`url`, `text`, `description`, `reciprocal`, `approved`, `email`)
								VALUES('$post_url', '$post_text', '$post_desc', '$post_reciprocal', 'no', '$post_linkemail')");
					$successmsg = "Your link has been added. It will not show up until an admin approves the link. </br>An email will be sent on approval.";
				} else {
					yasDB_insert("INSERT INTO links(`url`, `text`, `description`, `reciprocal`, `approved`, `email`)
								VALUES('$post_url', '$post_text', '$post_desc', '$post_reciprocal', 'yes', '$post_linkemail')");
					$successmsg =  'Your link has been added and approved pending a manual review by an admin.';
				}
				$post_text = '';
				$post_desc = '';
				$post_url = '';
				$post_reciprocal = '';
				$post_linkemail = '';
				break;
			case LINKNOTFOUND:
				$errormsg = "We did not find our link on your web page. </br>Please make sure you have added it and it is a hard link and then resubmit the request.";
				break;
			case LINKFOUNDNOFOLLOW:
				$errormsg = 'Our link was found but you have added a rel="nofollow" attribute. Please remove this and resubmit the request. </br>We use a scheduled back link checker. Please respect our link exchange.';
				break;
			case LINKDATAERROR:
				$errormsg = 'We received an error while checking for our link on your web page. </br>Please check that you typed the reciprocal link correctly and resubmit the request';
				break;
		}
		echo "</div>";
		// send email to admin notifying of the link request
		$message = 'Link exchange request through '.$setting['siteurl']."\r\n";
		$message .= 'Url: '.$post_url."\r\n";
		$message .= 'Reciprocal link: '.$post_reciprocal."\r\n";
		$message .= 'Text: '.$post_text."\r\n";
		$message .= 'Description: '.$post_desc."\r\n";
		$message .= 'Email: '.$post_linkemail."\r\n";
		if (isset($errormsg)) $message .= 'Error message: ' . str_replace("</br>", "\r\n", $errormsg); 
		$headers = 'From: '.$setting['sitename'].' <'.$setting['sitename'].'>';
		$subject = 'Link exchange request from '.$post_url.' through '.$setting['sitename'];
		
		@mail($setting['email'], $subject, $message, $headers); 
		
	}
}
if(empty($post_url) OR empty($post_reciprocal)) {
	$post_url= "http://";
    $post_reciprocal= "http://";
}
?>
<div id="preview" style="width:auto; color:red; background-color:#ffffff;">
	<?php
	if (isset($successmsg)) {
		echo $successmsg;
	}
	elseif (isset($errormsg)) {
		echo $errormsg;
	}
	?>
</div>
<div class="containbox2">
<b>Please add our link to your site first or your site will not be approved!</b><br>
<b>Please add the following information to your site:</b><br><br>
<b>Text:</b> <?php echo $setting['sitename'];?> <br>
<b>Url:</b> <?php echo $setting['siteurl'];?><br>
<b>Reciprocal:</b> <?php echo $setting['siteurl'];?>links.html<br>
<b>Description:</b> <?php echo $setting['slogan']; ?>
<br><br>
<b>Our system automatically checks for our link at the reciprocal url on submission.</b><br>
<form id="addlink" action="index.php?act=addlink&add=link" method="post">
Text:<br><input type="text" name="text" value="<?php echo $post_text;?>"><br>
Description:<br><input type="text" name="description" value="<?php echo $post_desc;?>"><br>
Url:<br><input type="text" name="url" value="<?php echo $post_url;?>"><br>
Email:<br><input type="text" name="linkemail" value="<?php echo $post_linkemail;?>"><br>
Reciprocal link:<br><input type="text" name="reciprocal" value="<?php echo $post_reciprocal;?>"><br><br>
<?php
				
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
	<input type="text" name="code" size="12" /><br /><br />
	<input name="recaptcha" type="hidden" value="yes" /><?php
	// end securimage captcha
}
else {
	?>Security Question: five + five = <br />
	<input name="security" type="text" style="border: 1px solid #000;" /><br/>
	<input name="recaptcha" type="hidden" value="no" /><?php
}
?><br>
<input type='hidden' name='act' value='addlink'>
<input type="submit" value="Add Link">
</form>
</div>
<div class="clear"></div>
</div>