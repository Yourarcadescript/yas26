<div id="center-column">
<div class="top-bar">
<h1>Cpanel - Ads</h1>
<div class="breadcrumbs"><a href="index.php?act=addads" title="Add ads">Add Ads</a></div>
</div><br />
<div class="select-bar">
<label>
<h3>Manage Ads</h3>
</label>
</div>
<?php
include ($setting['sitepath'].'/templates/'.$setting['theme'].'/adschedule.php');
$id = isset($_POST["id"])?yasDB_clean($_POST["id"]):'';
$name = isset($_POST['name'])?yasDB_clean($_POST['name']):'';
$code = isset($_POST['code'])?yasDB_clean(stripslashes($_POST['code'])):'';
if(isset($_POST['edit_ads'])) {
	if ($id > 0 && $id < 12 ) {
		yasDB_update("UPDATE `ads` SET code = '$code' WHERE id = '$id'",false);
	} else {
		yasDB_update("UPDATE `ads` SET name = '$name', code = '$code' WHERE id = '$id'",false);
	}
	echo '<center>Updated!';
	echo '<br><a href="index.php?act=ads">Click to continue.</a></center>';
}	
elseif(isset($_POST['delete_ad'])) {
	$id = $_REQUEST["id"];
	if ($id > 0 && $id < 12 ) {
		yasDB_update("UPDATE ads SET code='Put AD code here' WHERE id = '$id'",false);
	} else {
		yasDB_delete("DELETE FROM ads WHERE id = '$id'",false);
	}
	echo '<center>The ad was deleted</center>';
	echo '<center><br><a href="index.php?act=ads">Click to continue.</a></center>';
} else {
	echo '<br/>';
	$ads = yasDB_select("SELECT * FROM `ads` ORDER BY id asc",false);
	while($row = $ads->fetch_array(MYSQLI_ASSOC)) {
		$ad  = '<?php ';
		$ad .= 'echo ad("'.$row['id'].'");';
		$ad .= ' ?>';
		?>
		<div class="table">
		<img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
	    <img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
		<form name="edit_ads" method="post" action="index.php?act=ads">
		<input type="hidden" name="id" value="<?php echo $row['id'];?>"/>
		<table class="listing form" cellpadding="0" cellspacing="0">
		<tr>
	    <th class="full" colspan="2">Ad # <?php echo $row['id']; if(isset($adSchedule[$row['id']])) { echo '<span style="color:red;float:right;">'.$setting['theme'].': '.$adSchedule[$row['id']].'</span>'; } ?></th>
	    </tr>
		<tr>
		<td class="first" width="172"><strong>Code for placement</strong></td>
		<td class="last"><label><?php echo highlight_string($ad,1); ?></label></td>
		</tr>
		<tr class="bg">
		<td class="first"><strong>Name</strong></td>
		<td class="last">
		<?php
		if ($row['id'] > 0 && $row['id'] < 12) {
			echo $row['name'];
		} else {
		?>
		<textarea name="name" rows="1" cols="22"><?php echo $row['name'];?></textarea>
		<?php } ?>
		</td>
		</tr>
		<tr>
		<td class="first" width="172"><strong>Code</strong></td>
		<td class="last"><textarea name="code" rows="1" cols="22"><?php echo $row['code'];?></textarea></td>
		</tr>
		<tr class="bg">
		<td class="first"></td>
		<td class="last"><input type="submit" class="button" name="edit_ads" value="Save" /> <input type="reset" class="button" value="Reset"/> <input type="submit" class="button" onclick="return confirm(\'Are you sure you want to delete this ad?\')" name="delete_ad" value="Delete"/></td>
		</tr>
		</table>
		</div>
		</form>
		<?php
	}
}
?>
</div>