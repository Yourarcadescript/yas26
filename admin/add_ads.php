<div id="center-column">
<div class="top-bar">
<h1>Cpanel - Ads</h1>
<div class="breadcrumbs"><a href="index.php?act=ads" title="Manage Ads">Manage Ads</a></div>
</div><br />
<div class="select-bar">
<label>
<h3>Add Ads</h3>
</label>
</div>
<?php
if (isset($_POST['add_ads'])) {
	$name = yasDB_clean($_POST['name']);
	$code = stripslashes($_POST['code']);
	yasDB_insert("INSERT INTO `ads` ( `id` , `name` , `code`) VALUES ('', '".$name."', '".$code."')",false);
	echo 'Ad added!';
} else {
	?>
	<div class="table">
		<img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
	    <img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
	    <form name="add_ads" method="post" action="index.php?act=addads">
	    <table class="listing form" cellpadding="0" cellspacing="0">
		<tr>
	    <th class="full" colspan="2">Ads</th>
	    </tr>
		<tr>
		<td class="first" width="172"><strong>Name</strong></td>
		<td class="last"><input type="text" name="name" maxlength="255" /></td>
		</tr>
		<tr class="bg">
		<td class="first"><strong>Ad Code</strong></td>
		<td class="last"><textarea name="code" cols="35" rows="5"></textarea></td>
		</tr>
		<tr>
		<td class="first" width="172"><strong>Submit</strong></td>
		<td class="last"><input type="submit" class="button" name="add_ads" value="Submit" /></td>
		</tr>
		</table>
		</div>
		</form>
<?php
}
?>
</div>