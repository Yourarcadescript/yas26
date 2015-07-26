<div class="nav_box"><div class="nav">Newest Member</div>
<div class="nav_box2"><center>
<?php

unset($row);
$query = yasDB_select("SELECT * FROM user ORDER BY id DESC LIMIT 1",false);
while($row = $query->fetch_array(MYSQLI_ASSOC)) {
	$id = $row['id'];
	$username = $row['username'];
	if ( $row['useavatar'] == '1' ) {
		$avatarimage = $setting['siteurl'] . 'avatars/' . $row['avatarfile'];
	}
	else {
		$avatarimage = $setting['siteurl'] . 'avatars/useruploads/noavatar.JPG';
	}
    if ($setting['seo']=='yes') {
		echo '<a href="'.$setting['siteurl'].'showmember/'.$row['id'].'.html" title="'.$username.'"><img src="'.$avatarimage.'" height="100" width="100">&nbsp;</a><br/>';
		echo '<a href="'.$setting['siteurl'].'showmember/'.$row['id'].'.html" title="'.$username.'">'.$username.'</a>';
	} else {
		echo '<a href="'.$setting['siteurl'].'index.php?act=showmember&id='.$row['id'].'" title="'.$username.'"><img src="'.$avatarimage.'" height="100" width="100">&nbsp;</a><br/>';
		echo '<a href="'.$setting['siteurl'].'index.php?act=showmember&id='.$row['id'].'" title="'.$username.'">'.$username.'</a>';
	}
}
$query->close();
?></center>
</div></div>