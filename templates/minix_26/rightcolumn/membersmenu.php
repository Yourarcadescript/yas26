<div class="nav_box"><div class="nav">Login</div>
<div class="nav_box2">
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
		<form name="menuform">
		<select name="menu" onChange="top.location.href = this.form.menu.options[this.form.menu.selectedIndex].value;return false;">
		<option value="#" selected><span>Select</span></option>
		<option value="<?php echo ''. $setting['siteurl'] .'index.php';?>" title="Home"><span>Home</span></option>
		<option value="<?php echo ''. $setting['siteurl'] .'editavatar.html';?>" title="Edit Avatar">Edit Avatar</option>
		<option value="<?php echo ''. $setting['siteurl'] .'profile.html';?>" title="<?php echo ''. $user .'';?>'s-Profile"><span><?php echo ''. $user .'';?>-Profile</span></option>
		<option value="<?php echo ''. $setting['siteurl'] .'favourites.html';?>" title="Favourtie Games">Favorite Games</option>
		<option value="<?php echo ''. $setting['siteurl'] .'forum.html';?>" title="Forum">Forum</option>
		<option value="<?php echo ''. $setting['siteurl'] .'members.html';?>" title="Members">Members</option>
		<option value="<?php echo ''. $setting['siteurl'] .'submitgame.html';?>" title="Submit Game">Submit Game</option>
		<option value="<?php echo ''. $setting['siteurl'] .'logout.php';?>" title="Logout">Logout</option>
		</select>
		</form>
		<?php
	} else {
		?>
		<form name="menuform">
		<select name="menu" onChange="top.location.href = this.form.menu.options[this.form.menu.selectedIndex].value;return false;">
		<option value="#" selected><span>Select</span></option>
		<option value="<?php echo ''. $setting['siteurl'] .'index.php';?>" title="Home">Home</option>
		<option value="<?php echo ''. $setting['siteurl'] .'index.php?act=editavatar';?>" title="Edit Avatar">Edit Avatar</option>
		<option value="<?php echo ''. $setting['siteurl'] .'index.php?act=profile';?>" title="<?php echo ''. $user .'';?>'s-Profile"><span>Edit-Profile</span></option>
		<option value="<?php echo ''. $setting['siteurl'] .'index.php?act=favourites';?>" title="Favourtie Games">Favorite Games</option>
		<option value="<?php echo ''. $setting['siteurl'] .'index.php?act=forum';?>" title="Forum">Forum</option>
		<option value="<?php echo ''. $setting['siteurl'] .'index.php?act=members';?>" title="Members">Members</option>
		<option value="<?php echo ''. $setting['siteurl'] .'index.php?act=submitgame';?>" title="Submit Game">Submit Game</option>
		<option value="<?php echo ''. $setting['siteurl'] .'logout.php';?>" title="Logout">Logout</option>
		</select>
		</form>		
		<?php
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

}
?>
<div class="clear"></div>
</div></div>