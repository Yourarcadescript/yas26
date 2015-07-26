<?php
include("db_functions.inc.php");
$userid = yasDB_clean($_GET['uid']);
$query = yasDB_select("SELECT * FROM `user` WHERE `id` = '$userid'");
$userdata = $query->fetch_array(MYSQLI_ASSOC);
?>
<br/><br/>
<form id="profile" name="profile" method="post" action="index.php?act=profile">
<?php if (!$userdata['oauth_provider']) { ?>
Name:<br />
<input type="text" name="name" value="<?php echo $userdata['name'];?>" size="50" /><p>
<?php } ?>
<?php if ($userdata['oauth_provider'] != "facebook") { ?>
Email:<br />
<input type="email" name="email" value="<?php echo $userdata['email'];?>" size="50" /><p>
<?php } ?>
Website:(Remember the http://)<br />
<input type="text" name="website" value="<?php echo $userdata['website'];?>" size="50" /><p>
Location:<br />
<input type="text" name="location" value="<?php echo $userdata['location'];?>" size="50" /><p>
Occupation:<br />
<input type="text" name="job" value="<?php echo $userdata['job'];?>" size="50" /><p>
About Me:<br />
<textarea name="aboutme" rows="5" cols="38"><?php echo $userdata['aboutme'];?></textarea><p>
AIM:<br />
<input type="text" name="aim" value="<?php echo $userdata['aim'];?>" size="50" /><p>
MSN:<br />
<input type="text" name="msn" value="<?php echo $userdata['msn'];?>" size="50" /><p>
Skype:<br />
<input type="text" name="skype" value="<?php echo $userdata['skype'];?>" size="50" /><p>
Yahoo:<br />
<input type="text" name="yahoo" value="<?php echo $userdata['yahoo'];?>" size="50" /><p>
Disable Profile Comments:<br />
<select name="cmtsdisabled">
<?php
if ($userdata['cmtsdisabled'] == '') {
	?><option value='hidden' selected="selected">Hidden</option>
	<?php
} else {
	?><option value='<?php echo $userdata['cmtsdisabled'];?>' selected="selected"><?php echo $userdata['cmtsdisabled'];?></option>
	<?php
}
?>
<option value="hidden">Hidden</option>
<option value="show">Show</option>
</select><p>
    <select name="shloc">
<?php
if ($userdata['shloc'] == '') {
	?><option value='hidden' selected="selected">Hidden</option>
	<?php
} else {
	?><option value='<?php echo $userdata['shloc'];?>' selected="selected"><?php echo $userdata['shloc'];?></option>
	<?php
}
?>
    <option value="hidden">Hidden</option>
    <option value="show">Show</option>
    </select>
  <select name="sheml">
<?php
if ($userdata['sheml'] == '') {
	?><option value='hidden' selected="selected">Hidden</option>
	<?php
} else {
	?><option value='<?php echo $userdata['sheml'];?>' selected="selected"><?php echo $userdata['sheml'];?></option>
	<?php
}
?>
  <option value="hidden">Hidden</option>
  <option value="show">Show</option>
  </select>
  <select name="shname">
<?php
if ($userdata['shname'] == '') {
	?><option value='hidden' selected="selected">Hidden</option>
	<?php
} else {
	?><option value='<?php echo $userdata['shname'];?>' selected="selected"><?php echo $userdata['shname'];?></option>
	<?php
}
?>
  <option value="hidden">Hidden</option>
  <option value="show">Show</option>
  </select>
  <select name="shhobs">
<?php
if ($userdata['shhobs'] == '') {
	?><option value='hidden' selected="selected">Hidden</option>
	<?php
} else {
	?><option value='<?php echo $userdata['shhobs'];?>' selected="selected"><?php echo $userdata['shhobs'];?></option>
	<?php
}
?>
  <option value="hidden">Hidden</option>
  <option value="show">Show</option>
  </select>
  <select name="shabout">
<?php
if ($userdata['shabout'] == '') {
	?><option value='hidden' selected="selected">Hidden</option>
	<?php
} else {
	?><option value='<?php echo $userdata['shabout'];?>' selected="selected"><?php echo $userdata['shabout'];?></option>
	<?php
}
?>
  <option value="hidden">Hidden</option>
  <option value="show">Show</option>
  </select>
  <select name="deact">
<?php
if ($userdata['deact'] == '') {
	?><option value='hidden' selected="selected">Hidden</option>
	<?php
} else {
	?><option value='<?php echo $userdata['deact'];?>' selected="selected"><?php echo $userdata['deact'];?></option>
	<?php
}
?>
  <option value="hidden">Hidden</option>
  <option value="show">Show</option>
  </select>
<?php if (!$userdata['oauth_provider']) { ?>
Password:(leave blank if no change)<br />
<input type="password" name="password" /><p>
<?php } ?>
<input type="hidden" name="uid" value="<?php echo $_SESSION['userid'];?>">
<input type="image" class="submit_button" border="0" src="<?php echo $setting['siteurl'] . 'templates/'.$setting['theme'].'/images/submit.png';?>" name="settings" value="Update" />
</form>