<div id="center">
<div class="main_header"><strong>Who's Online:</strong></div>
<div class="main_box"> 
<strong>Members:</strong>
<strong>Newest Members:</strong>
<strong>Visitors Online:</strong>
<?php  
$active_length = 600;  // How many seconds to count an user as active until they access another page... 600 = 10 minutes

$query = yasDB_select("SELECT count(user.id) as count FROM user ");
$row = $query->fetch_array(MYSQLI_ASSOC);
echo ''.$row['count'].'';  
$query = yasDB_select("SELECT * from user order by id desc limit 5");
while($row = $query->fetch_array(MYSQLI_ASSOC)) {
	echo '<a  href="'.$setting['siteurl'].'showmember/ '.$row['id'].'.html" style="color:'.get_random_color().'"> '.$row['username'].'&nbsp;&nbsp;</a>';
}
$visitors_online = new usersOnline();
echo $visitors_online->count_users();?>

<strong>Members Online:</strong>
<?php 
$time = time()-$active_length;
$query = yasDB_select("SELECT count(memberid) as count from membersonline WHERE `timeactive`>= $time");
$query2 = yasDB_select("SELECT memberid FROM membersonline");
$row = $query->fetch_array(MYSQLI_ASSOC);
$row2 = $query2->fetch_array(MYSQLI_ASSOC);
if ($row['count'] > 0) {
	foreach ($row2 as $member) {
		$result = yasDB_select("SELECT username FROM user WHERE id = '$member'"); 
		$name = $result->fetch_array(MYSQLI_NUM);
		echo '<a  href="'.$setting['siteurl'].'showmember/ '.$member.'.html" style="color:'.get_random_color().'"> '.$name[0].'</a>';
	}
}
?> 
<strong>Game Stats:</strong>
<strong>Game Plays Today:</strong>
<strong>Total Game Plays:</strong>
<strong>Total Games:</strong>
<?php
$stats = yasDB_select("SELECT * from stats where id=2");
$dayplays = $stats->fetch_array(MYSQLI_ASSOC);
echo $dayplays['numbers'];
$stats = yasDB_select("SELECT * from stats where id=1");
$totalplays = $stats->fetch_array(MYSQLI_ASSOC);
echo $totalplays['numbers'];
$query = yasDB_select("SELECT count(games.id) as count from games");
$row = $query->fetch_array(MYSQLI_ASSOC);
echo $row['count'];?>                
</div>