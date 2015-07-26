<?php
$query1 = yasDB_select("SELECT * FROM links WHERE approved = 'no'",false);
$links = $query1->num_rows;
$query1->close();

$query2 = yasDB_select("SELECT id FROM games");
$tgames = $query2->num_rows;
$query2->close();

$query3 = yasDB_select("SELECT * FROM stats WHERE id = '1'",false);
$totalplays = $query3->fetch_array(MYSQLI_ASSOC);
$totalplays = $totalplays['numbers'];
$query3->close();

$query4 = yasDB_select("SELECT * FROM stats WHERE id = '2'",false);
$todayplays = $query4->fetch_array(MYSQLI_ASSOC);
$todayplays = $todayplays['numbers'];
$query5 = yasDB_select("SELECT * FROM comments");
$comments = $query5->fetch_array(MYSQLI_ASSOC);
$comments = $query5->num_rows;
$query5->close();

$query6 = yasDB_select("SELECT * FROM user");
$user = $query6->fetch_array(MYSQLI_ASSOC);
$user = $query6->num_rows;
$query6->close();
?>
<div class="box">
Version: <b><?php echo $setting['version'];?></b> <br>
Unapproved links : <?php if ($links != 0) { echo '<font color="red">'.$links.'</font>';} else { echo '0'; } ?><br>
Comments : <?php echo $comments; ?><br/>
Total Games : <?php echo $tgames; ?><br/>
Total Plays : <?php echo $totalplays; ?><br/>
Plays today : <?php echo $todayplays; ?><br/>
Members : <?php echo $user; ?><br/><br/>
</div>