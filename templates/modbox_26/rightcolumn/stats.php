<div class="nav_box"><div class="nav">Stats</div>
<div class="nav_box2"><div style="margin:5px;">
<?php
$query = yasDB_select("SELECT * FROM stats where id=1");
$totalplays = $query->fetch_array(MYSQLI_ASSOC);
$query->close();
$query = yasDB_select("SELECT * FROM stats where id=2");
$dayplays = $query->fetch_array(MYSQLI_ASSOC);
$query->close();
$query = yasDB_select("SELECT count(id) as count FROM games");
$count = $query->fetch_array(MYSQLI_ASSOC);
$query->close();
$visitors_online = new usersOnline();
?>
<b>Game Plays Today:</b> <?php echo $dayplays['numbers'];?><br/>
<b>Total Game Plays:</b> <?php echo $totalplays['numbers'];?><br/>
<b>Total Games:</b> <?php echo $count['count'];?><br/>
<b>visitors online:</b> <?php echo $visitors_online->count_users();?><br/>
<b>Bots online:</b>
<?php
$bots = array();
$bots = $visitors_online->get_bots();
if ($bots) {
    foreach ($bots as $bot) {
        echo ucfirst($bot) . ' ';
    }
}
?>
<br/></div>
</div></div>      