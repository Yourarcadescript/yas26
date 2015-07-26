<div id="center-column">
<div class="top-bar">
<h1>Cpanel - Mochiads Publisher ID</h1>
<div class="breadcrumbs"><a href="index.php?act=managemochi" title="Install Mochi Games">Install Mochi Games</a></div>
</div><br />
<div class="select-bar"></div>
<?php
$result = yasDB_select("SELECT `mochi_pub_key` FROM `settings` WHERE `id`=1",false);
$mochipubkey = $result->fetch_array(MYSQLI_NUM);
$result->close();

$result2 = yasDB_select("SELECT `mochi_secret_key` FROM `settings` WHERE `id`=1",false);
$mochisecretkey = $result2->fetch_array(MYSQLI_NUM);
$result2->close();

if(!function_exists('curl_exec') || !function_exists('json_decode')) {
	echo "<center>Make sure PHP functions cURL and JSON are enabled on your server and try again.\n";
	echo "For assistance go to our support forum at www.yourarcadescript.com/forum</center>";
	exit();
}
//$mochipubkey[0] = NULL;
if ($mochipubkey[0] == NULL) { ?>
	<center><h3>Setup</h3>
    You need a MochiAds Publisher ID to upload Mochi games through this menu.  If you need one you can get one here:<br />
    <a href="https://www.mochimedia.com/r/a40f841c60c23178" target="_blank"><span style="color:#FF7700">Free MochiAds Publisher Signup</span></a>.<br />
    <br />After you have made the account enter your Publisher and Secret IDs below.<br /></center>
<?php
} ?><center><h3>Update your MochiAds Publisher ID if necessary:</h3></center>
    <div class="table">
		    <img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
			<img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
			<form action="" method="post">
			<table class="listing form" cellpadding="0" cellspacing="0">
			<tr>
	        <th class="full" colspan="2">Mochiads Publisher ID</th>
			</tr>
			<tr>
			<td class="first"><strong>Publisher ID</strong></td>
			<td class="last"><input type="text" size = "18" name="pubid" value="<?php echo $mochipubkey[0]; ?>" /></td>
			</tr>
			<tr>
			<td class="first"><strong>Secret Key</strong></td>
			<td class="last"><input type="text" size="33" name="secretkey" value="<?php echo $mochisecretkey[0]; ?>" /></td>
			</tr>
			<tr>
			<td class="first" width="172"></td>
			<td class="last"><input type="submit" class="button" name="pubsubmit" value="Submit" />	<input type="reset" class="button" name="reset" id="reset" value=" Reset " /></td>
			</tr>
			</form>
			</table>
			</div>
			<center><h3>* The Publisher ID and Publisher Secret Key, while logged into Mochimedia, can be found <a href="https://www.mochimedia.com/pub/settings" target="_blank"><span style="color:#FF7700">here</span></a>. If your Publisher ID is wrong, the feed will download but will be empty.</h3></center>
<?php
if (isset($_POST['pubsubmit'])) {
	$id = $_POST['pubid'];
	$ids = $_POST['secretkey'];
	$mochipubkey = $id;
	$mochisecretkey = $ids;
	$sql = "UPDATE settings SET mochi_pub_key = '$id', mochi_secret_key = '$ids' WHERE `id`=1";
	$result = yasDB_update($sql,false);
	if (!$result) {
		echo 'Error updating Mochi Pubublisher IDs</div>';
		exit();
	}
	unset($_POST['pubsumit']);
	include("../includes/settings_function.inc.php");
	createConfigFile();
	echo '<font color="red">Updated!</font>';
}
?>
</div>