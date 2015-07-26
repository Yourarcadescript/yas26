<div id="center-column">
<div class="top-bar">
				<a href="index.php?act=links" title="Link" class="button">Links</a>
				<h1>Cpanel - Links Exchange</h1>
				<div class="breadcrumbs"><a href="index.php?act=links">Links</a></div>
			</div><br />
		  <div class="select-bar">
		    <label>
		    <h3>Add Link</h3>
		    </label>
		  </div>
<?php
if (isset($_POST['add_link'])) {
	$url = yasDB_clean($_POST['url']);
	$des = yasDB_clean($_POST['description']);
	$text = yasDB_clean($_POST['text']);
	$reciprocal = yasDB_clean($_POST['reciprocal']);
	$email = yasDB_clean($_POST['email']);
	yasDB_insert("INSERT INTO links ( `id` , `url` , `text`, `description`, `email`, `in`, `out`, `reciprocal`, `approved`) VALUES ('', '".$url."', '".$text."', '".$des."', '".$email."', '0', '0', '".$reciprocal."', 'yes')");
	 
		echo '<center>Link added!<br>
		<a href="index.php?act=addlink" />Continue</a></center>';?>
<?php } else { ?>
            <div class="table">
		    <img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
			<img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
			<form name="add_link" method="post" action="index.php?act=addlink">
			<table class="listing form" cellpadding="0" cellspacing="0">
			<tr>
	        <th class="full" colspan="2">Add A Link</th>
			</tr>
			<tr>
			<td class="first" width="172"><strong>Title</strong></td>
			<td class="last"><input type="text" name="text" maxlength="255" /></td>
			</tr>
			<tr class="bg">
			<td class="first"><strong>Url</strong></td>
			<td class="last"><input type="text" name="url" value="http://" maxlength="255" /></td>
		    </tr>
			<tr>
			<td class="first"><strong>Reciprocal</strong></td>
			<td class="last"><input type="text" name="reciprocal" value="http://" maxlength="255" /></td>
		    </tr>
			<tr class="bg">
			<td class="first" width="172"><strong>Email</strong></td>
			<td class="last"><input type="text" name="email" value="" maxlength="255" /></td>
			</tr>
			<tr class="bg">
			<td class="first" width="172"><strong>Description</strong></td>
			<td class="last"><textarea name="description" cols="35" rows="5"></textarea></td>
			</tr>
			<tr>
			<td class="first"><strong>Submit</strong></td>
			<td class="last"><input type="submit" class="button" name="add_link" value="Submit" /></td>
		    </tr>
			</table>
			</div>
			</form>
<?php
}
?>
</div>