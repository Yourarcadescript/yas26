<div class="navh3">Login</div>
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
	echo "<h2>Welcome <b>".$user."</b></h2><br/>";
	echo '<center><img id="avatarimage" src="'.$avatarimage.'" height="100" width="100" align="top"/>
	</center><br/>';
	echo "<center><h2>Games played:</h2>
	<b>".$row['plays']."</b></center>";
		if ($setting['seo']=='yes') {
		?>
		<div class="links1">
		<a href="<?php echo ''. $setting['siteurl'] .'profile.html';?>" title="<?php echo ''. $user .'';?>'s-Profile"><span><?php echo ''. $user .'';?>'s-Profile</span></a>
		<a href="<?php echo ''. $setting['siteurl'] .'editavatar.html';?>" title="Edit Avatar">Edit Avatar</a>
		<a href="<?php echo ''. $setting['siteurl'] .'favourites.html';?>" title="Favourtie Games">Favorite Games</a>
		<a href="<?php echo ''. $setting['siteurl'] .'submitgame.html';?>" title="Submit Game">Submit Game</a>
		<a href="<?php echo ''. $setting['siteurl'] .'logout.php';?>" title="Logout">Logout</a>
		</div>
		<?php
	} else {
		?>
		<div class="links1">
		<a href="<?php echo ''. $setting['siteurl'] .'index.php?act=profile';?>" title="<?php echo ''. $user .'';?>'s-Profile"><?php echo ''. $user .'';?>'s-Profile</a>
		<a href="<?php echo ''. $setting['siteurl'] .'index.php?act=editavatar';?>" title="Edit Avatar">Edit Avatar</a>
		<a href="<?php echo ''. $setting['siteurl'] .'index.php?act=favourites';?>" title="Favourtie Games">Favorite Games</a>
		<a href="<?php echo ''. $setting['siteurl'] .'index.php?act=submitgame';?>" title="Submit Game">Submit Game</a>
		<a href="<?php echo ''. $setting['siteurl'] .'logout.php';?>" title="Logout">Logout</a>
		</div><?php
		} 
} else {
?>
	<div id="loginmessage" style="font-size:9px;color:red;text-align:center;"></div>
	<form name="myform" id="arcadelogin" action="<?php echo $setting['siteurl'];?>login.php" method="post">
	Username:<br /><input type="text" name="username" size="15" /><br />
	Password:<br /> <input type="password" name="password" size="15" /><br />
	Remember me: <input type="checkbox" name="remember" value="remember" ><br />
	<input type="submit" name="submit" value="Submit" />
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
		echo '<br><a href="'. $setting['siteurl'] .'register.html">Register</a>';
		echo '<br><a href="'. $setting['siteurl'] .'forgotpassword.html">Forgot Password</a>';
	} else {
		echo '<br><a href="'. $setting['siteurl'] .'index.php?act=register">Register</a>';
		echo '<br><a href="'. $setting['siteurl'] .'index.php?act=forgotpassword">Forgot Password</a>';
		}
	}
}
?>
<div class="clear"></div>