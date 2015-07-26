	<div id="center">
	<div id="member_main_header">Members</div>
	   <div id="member_main_box">
	   <?php
	   include_once($setting['sitepath'].'/includes/pagination.class.php');
	   $setting['gperpage'] = ($setting['gperpage']<1?15:$setting['gperpage']);
	   ?><div id="member_main">
	   <div id="member_avatar_header">Avatar</div>
	   <div id="member_username_header">Username</div>
	   <div id="member_comming_header">Rank</div>
	   <div id="member_plays_header">Plays</div>
	   </div><div class="member_box">
	   <div class="member_box2">
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
	   $pageurl = new pagination($numrows, $setting['seo'], 'members', 15, 3);
	   $query = yasDB_select("SELECT * FROM user ORDER BY `username` asc LIMIT " . $pageurl->start . ", " . $pageurl->limit);
	   while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
		   $rank = array_search($row['username'],$ranking)+1;
		   $id = $row['id'];
		   $username = $row['username'];
		   $plays = $row['plays'];
		   if ($setting['seo'] == 'yes') {
			   $memberlink = $setting['siteurl'].'showmember/'.$id.'.html';
		   } else {
			   $memberlink = $setting['siteurl'] . 'index.php?act=showmember&id='.$id ;
		   }
		   if ( $row['useavatar'] == '1' ) {
			   $avatarimage = $setting['siteurl'] . 'avatars/' . $row['avatarfile'];
		   } else {
			   $avatarimage = $setting['siteurl'] . 'avatars/useruploads/noavatar.jpg';
		   }
		   ?>
		   <div class="member_avatar"><img src="<?php echo $avatarimage;?>" height="40" width="40" align="left"></div>
		   <div class="member_text_box1"><a href="<?php echo $memberlink;?>"><?php echo $username;?></a></div>
		   <div class="member_text_box2"><?php echo $rank;?></div><div class="member_text_box3"><?php echo $plays;?></div><?php
	   }?>
	   <div id="pages"><?php
	   echo $pageurl->showPagination();
?>
</div></div></div>
<div class="clear"></div>
</div>