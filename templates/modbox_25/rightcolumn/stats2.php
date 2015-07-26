<div class="nav_box"><div class="nav">Member Stats</div>
<div class="nav_box2"><div style="margin:5px;">
<?php 
$active_length = 600;  // How many seconds to count an user as active until they access another page... 600 = 10 minutes
$query = yasDB_select("SELECT count(user.id) as count FROM user ");
$row = $query->fetch_array(MYSQLI_ASSOC);
$query->close();
echo ' <b>Members - </b>'.$row['count'].'<br>'; 
$time = time()-$active_length;
$query = yasDB_select("SELECT user.username AS name, user.id AS id FROM user, membersonline WHERE membersonline.memberid = user.id AND timeactive >= '$time'");
$online = $query->num_rows;
echo '<b>Members Online- </b>'.$online.'<br>';
if ($online > 0) {
    while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
        if ($setting['seo'] == 'yes' ) {
			echo '<a href="'.$setting['siteurl'].'showmember/'.$row['id'].'.html">'.$row['name'].'</a>&nbsp;&nbsp;';
		}
		else {
			echo '<a href="'.$setting['siteurl'].'index.php?act=showmember&id='.$row['id'].'">'.$row['name'].'</a>&nbsp;&nbsp;';
		}
	}
}
$query->close();
unset($row);
echo '<b><br/>New Members: </b><br/>';
$query = yasDB_select("SELECT * FROM user ORDER BY id DESC LIMIT 9");
while($row = $query->fetch_array(MYSQLI_ASSOC)) {
	$id = $row['id'];
	$username = $row['username'];
	if ( $row['useavatar'] == '1' ) {
		$avatarimage = $setting['siteurl'] . 'avatars/' . $row['avatarfile'];
	}
	else {
		$avatarimage = $setting['siteurl'] . 'avatars/useruploads/noavatar.JPG';
	}
    if ($setting['seo'] == 'yes' ) {
		echo '<a href="'.$setting['siteurl'].'showmember/'.$row['id'].'.html" title="'.$username.'"><img src="'.$avatarimage.'" height="40" width="40"></a>&nbsp;';
	} else {
		echo '<a href="'.$setting['siteurl'].'index.php?act=showmember&id='.$row['id'].'" title="'.$username.'"><img src="'.$avatarimage.'" height="40" width="40"></a>&nbsp;';
	}
}
$query->close();
?>
</div></div></div>