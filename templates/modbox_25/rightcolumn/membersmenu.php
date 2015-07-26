<div class="nav_box"><div class="nav">Login</div>
<div class="nav_box2"><div id="loginmessage" style="color:#A2171A;text-align:center;font-weight:bold;"></div>
<?php
if(isset($_SESSION["user"])) {
	$user=$_SESSION["user"];
	$query = yasDB_select("select `useavatar`, `avatarfile`, `plays` from `user` where `username` = '$user'");
	$row = $query->fetch_array(MYSQLI_ASSOC);
	if ( $row['useavatar'] == '1' ) {
	$avatarimage = $setting['siteurl'] . 'avatars/' . $row['avatarfile'];
	} else {
	$avatarimage = $setting['siteurl'] . 'avatars/useruploads/noavatar.JPG';
	}
	echo "<h2>Welcome ".$user."</h2><br/>";
	echo '<center><img id="avatarimage" src="'.$avatarimage.'" height="100" width="100" align="top"/>
	</center><br/>';
	echo "<center>Games played:
	<b>".$row['plays']."</b></center><br/><hr style=\"color:#003E85;width:98%;text-align:center;margin-left:2px;\"/><div style=\"margin-left:5px;\">";
		if ($setting['seo']=='yes') {
		?>
		<div class="links1">
		<a href="<?php echo ''. $setting['siteurl'] .'profile.html';?>" title="Profile">Profile</a>
		<a href="<?php echo ''. $setting['siteurl'] .'editavatar.html';?>" title="Edit Avatar">Edit Avatar</a>
		<a href="<?php echo ''. $setting['siteurl'] .'favourites.html';?>" title="Favourtie Games">Favorite Games</a>
		<a href="<?php echo ''. $setting['siteurl'] .'submitgame.html';?>" title="Submit Game">Submit Game</a>
		<a href="<?php echo ''. $setting['siteurl'] .'logout.php';?>" title="Logout">Logout</a>
		</div>
		<?php
	} else {
		?>
		<div class="links1">
		<a href="<?php echo ''. $setting['siteurl'] .'index.php?act=profile';?>" title="Edit Profile">Edit Profile</a>
		<a href="<?php echo ''. $setting['siteurl'] .'index.php?act=editavatar';?>" title="Edit Avatar">Edit Avatar</a>
		<a href="<?php echo ''. $setting['siteurl'] .'index.php?act=favourites';?>" title="Favourtie Games">Favorite Games</a>
		<a href="<?php echo ''. $setting['siteurl'] .'index.php?act=submitgame';?>" title="Submit Game">Submit Game</a>
		<a href="<?php echo ''. $setting['siteurl'] .'logout.php';?>" title="Logout">Logout</a>
		</div>
		<?php
		}
		?></div><?php
} else {
?>
	<div style="padding-left:5px;">
	<form name="myform" id="arcadelogin" action="<?php echo $setting['siteurl'];?>login.php" method="post">
	Username:<br /><p style="text-align:center;margin-top:3;"><input type="text" name="username" size="15" /></p>
	Password:<br /><p style="text-align:center;margin-top:3px;"><input type="password" name="password" size="15" /></p><br />
	Remember me: <input type="checkbox" name="remember" value="remember" ><br />
	<p style="text-align:center;margin-top:10px;"><input type="submit" class="button" name="submit" value="Submit"/></p>
	</form>
	<?php
	if ($setting['regclosed'] == 'yes') {
		if ($setting['seo']=='yes') {
		echo '<br><a href="'. $setting['siteurl'] .'forgotpassword.html">Forgot Password</a>';
		} else {
		echo '<br><a href="'. $setting['siteurl'] .'index.php?act=forgotpassword">Forgot Password</a>';
		}
	} else {
	if ($setting['seo']=='yes') {
		echo '<br/><hr style="color:#003E85;width:97%;text-align:center;"/><span class="links1"><a href="'. $setting['siteurl'] .'register.html">Register</a></span>';
		echo '<br/><span class="links1"><a href="'. $setting['siteurl'] .'forgotpassword.html">Forgot Password</a></span>';
	} else {
		echo '<br/><span class="links1"><a href="'. $setting['siteurl'] .'index.php?act=register">Register</a></span>';
		echo '<br/><span class="links1"><a href="'. $setting['siteurl'] .'index.php?act=forgotpassword">Forgot Password</a></span>';
	}
	?></div><?php
	}
}
?>
<div class="clear"></div>
</div></div>