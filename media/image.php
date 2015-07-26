<?php
$query = yasDB_select("select * from `games` where id = '$id'");
$image = $query->fetch_array(MYSQLI_ASSOC);
if($image['width'] > 530) {
	$image['width'] = 530;
}
if($image['height'] > 530) {
	$image['height'] = 530;
}
?>
<img src="<?php echo $setting['siteurl'].$row['file'];?>" width="<?php echo $image['width'];?>" height="<?php echo $image['height'];?>" />