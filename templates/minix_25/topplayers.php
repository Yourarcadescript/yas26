<div id="center">
   	   <div class="container_box1">
	   <div id="headergames2">Top Players</div>
	   <?php
	   include_once($setting['sitepath'].'/includes/pagination.class.php');
	   $setting['gperpage'] = ($setting['gperpage']<1?15:$setting['gperpage']);
	   ?>
<div class="topplayers">	   
	<table class="list" cellspacing="0" cellpadding="0" >
	  <tr>
		<th class="first">Member</th>
		<th>Plays</th>
		<th>Points</th>
		<th>Rank</th>
		<th align="last">Join date</th>
	 </tr>
<?php
	   $result = yasDB_select("SELECT count(id) FROM `user` ");
	   $query_data = $result->fetch_array(MYSQLI_NUM);
	   $numrows = $query_data[0];
	   $rquery = yasDB_select("SELECT `username` FROM `user` ORDER BY `plays` DESC",false);
	   $ranking = array();
	   $i=0;
	   while ($cow = $rquery->fetch_array(MYSQLI_ASSOC)) {
		   $ranking[$i] = $cow['username'];
		   $i++;
	   }
	   $pageurl = new pagination($numrows, $setting['seo'], 'topplayers', $setting['gperpage'], 3);
	   $query = yasDB_select("SELECT * FROM user ORDER BY `plays` desc LIMIT " . $pageurl->start . ", " . $pageurl->limit);
	   while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
		   $rank = array_search($row['username'],$ranking)+1;
		   $id = $row['id'];
		   $username = $row['username'];
		   $plays = $row['plays'];
		   $points = $row['plays']*50;
		   $date = date("M/d/Y", $row['date']);
		   if ($setting['seo'] == 'yes') {
			   $memberlink = $setting['siteurl'].'showmember/'.$id.'.html';
		   } else {
			   $memberlink = $setting['siteurl'] . 'index.php?act=showmember&id='.$id ;
		   }
?>		   
<tr><td class="first style1"><a href="<?php echo $memberlink;?>"><?php echo $username;?></a></td><td><?php echo $plays;?></td><td><?php echo $points;?></td><td><?php echo $rank;?></td><td class="last style2"><?php echo $date;?></td></tr>
<?php
}	                           
?>
</table>
</div>
<div id="page_box">
<?php
	   echo $pageurl->showPagination();
?>
</div>	
<div class="clear"></div>
</div>