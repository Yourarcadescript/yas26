<div id="center-column">
<div class="top-bar">
<h1>Cpanel - Social</h1>
<div class="breadcrumbs"><a href="index.php?act=socialids" title="Social IDs">Social IDs</a></div>
</div><br />
<div class="select-bar">
<label>
<h3>Social App IDs for Login</h3>
</label>
</div>
<?php
if(isset($_POST['edit_social'])) {
	$fbid = yasDB_clean($_POST['fbAppId']);
	$fbsecret = yasDB_clean($_POST['fbAppSecret']);
	$twid = yasDB_clean($_POST['twAppId']);
	$twsecret = yasDB_clean($_POST['twAppSecret']);
	yasDB_update("UPDATE `settings` SET `fb_app_id` = '$fbid', `fb_app_secret` = '$fbsecret', `tw_app_id` = '$twid', `tw_app_secret` = '$twsecret' WHERE id = 1",false);
	include("../includes/settings_function.inc.php");
	createConfigFile();
	echo '<center>Updated!';
	echo '<br/><a href="index.php?act=socialids">Click to continue.</a></center>';
} else {
	echo '<br/>';
	$query = yasDB_select("SELECT `fb_app_id`, `fb_app_secret`, `tw_app_id`, `tw_app_secret` FROM `settings` WHERE `id` = 1");
	$social = $query->fetch_array(MYSQLI_ASSOC);
	?>
	<div class="table">
	<img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
	<img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
	<form name="app_ids" method="post" action="index.php?act=socialids">
	<table class="listing form" cellpadding="0" cellspacing="0">
	<tr>
		<th class="full" colspan="2">Social App IDs</th>
	</tr>
	<tr>
		<td class="first" style="width:100px;"><strong>Facebook App ID</strong></td>
		<td class="last"><input type="text" name="fbAppId" value="<?php echo $social['fb_app_id'];?>" size="45"></td>
	</tr>
	<tr class="bg">
		<td class="first" style="width:100px;"><strong>Facebook App Secret</strong></td>
		<td class="last"><input type="text" name="fbAppSecret" value="<?php echo $social['fb_app_secret'];?>" size="45"></td>
	</tr>
	<tr>
		<td class="first" style="width:100px;"><strong>Twitter Consumer key</strong></td>
		<td class="last"><input type="text" name="twAppId" value="<?php echo $social['tw_app_id'];?>" size="45"></td>
	</tr>
	<tr class="bg">
		<td class="first" style="width:100px;"><strong>Twitter Consumer secret</strong></td>
		<td class="last"><input type="text" name="twAppSecret" value="<?php echo $social['tw_app_secret'];?>" size="45"></td>
	</tr>
	<tr>
		<td class="first" style="width:100px;"></td>
		<td class="last"><input type="submit" class="button" name="edit_social" value="Save" /> <input type="reset" class="button" value="Reset"/>
	</tr>
	</table>
	</div>
	</form>
	<?php
}
?>
</div>