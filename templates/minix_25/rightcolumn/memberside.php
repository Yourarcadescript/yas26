<div class="nav_box"><div class="nav">Top 5 Members</div>
<div class="nav_box2">         
<div class="links1">
<?php
$query = yasDB_select("SELECT * FROM user order by plays desc limit 5",false);
while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
	$id = $row['id'];
	$username = $row['username'];
	$plays = $row['plays'];							
	if ( $setting['seo']=='yes' ) { 
		$memberlink = $setting['siteurl'].'showmember/'.$id.'.html';
	} else {
		$memberlink = $setting['siteurl'] . 'index.php?act=showmember&id='.$id;
	}							
	echo'<a href="'.$memberlink.'">'.$username.' - '.$plays.' plays</a><br>';
}
$query->close();
?>
</div></div></div>