<div id="center-column">
<div class="top-bar">
<h1>Cpanel - Social</h1>
<div class="breadcrumbs"><a href="index.php?act=manage_ga" title="Google Analytics">Google Analytics Data</a></div>
</div><br />
<div class="select-bar">
<label>
<h3>Google Analytics Login Info</h3>
</label>
</div>
<?php
$replace = array('https://', 'http://', 'www.');
$serverUrl = $setting['siteurl'];
$serverUrl = str_replace($replace, "", $serverUrl);
$serverUrl = 'www.' . $serverUrl;

if(isset($_POST['edit_ga'])) {
	$galogin = yasDB_clean($_POST['gaLogin']);
	$gapassword = yasDB_clean($_POST['gaPassword']);
	$gaurl = yasDB_clean($_POST['gaUrl']);
	if ($gapassword == "") {
		$pswd = "";
	} else {
		$pswd = ", `gapassword` = '".$gapassword."'";
	}
	yasDB_update("UPDATE `settings` SET `galogin` = '$galogin'" . $pswd . ", `gaurl` = '$gaurl' WHERE id = 1",false);
	
	echo '<center>Updated!';
	echo '<br/><a href="index.php?act=gainfo">Click to continue.</a></center>';
} else {
	echo '<br/>';
	$query = yasDB_select("SELECT `galogin`, `gapassword`, `gaurl` FROM `settings` WHERE `id` = 1");
	$social = $query->fetch_array(MYSQLI_ASSOC);
	?>
	<div class="table">
	<img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
	<img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
	<form name="app_ids" method="post" action="index.php?act=gainfo">
	<table class="listing form" cellpadding="0" cellspacing="0">
	<tr>
		<th class="full" colspan="2">Google Analytics Login Info</th>
	</tr>
	<tr>
		<td class="first" style="width:100px;"><strong>Login (email)</strong></td>
		<td class="last"><input type="text" name="gaLogin" value="<?php echo $social['galogin'];?>" size="35"></td>
	</tr>
	<tr class="bg">
		<td class="first" style="width:100px;"><strong> New Password</strong></td>
		<td class="last"><input type="text" name="gaPassword" value="" size="35"></td>
	</tr>
	<tr>
		<td class="first" style="width:100px;"><strong>Profile Name</strong></td>
		<td class="last"><input type="text" name="gaUrl" value="<?php if ($social['gaurl'] == '') { echo $serverUrl; } else { echo $social['gaurl']; }?>" size="35"></td>
	</tr>
	<tr class="bg">
		<td class="first" style="width:100px;"></td>
		<td class="last"><input type="submit" class="button" name="edit_ga" value="Update" /> <input type="reset" class="button" value="Reset"/>
	</tr>
	</table>
	</div>
	</form>
	<?php
}
?>
</div>