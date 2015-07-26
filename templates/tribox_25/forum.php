<div id="center">
<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
?>
<div class="container_box1"><div class="forumheader">Forum</div>
<?php
//This here will check to see if 0 rows then no cats will show
$result = yasDB_select("SELECT * FROM forumcats WHERE active = 'yes'");
if ($result->num_rows == 0) {
	echo '<center><h3>No categories defined yet please try again later!</h3></center>';
} else {
?>
<div class="table">
<table class="listing" cellpadding="0" cellspacing="0">
<tr>
<th class="first">Category</th>
<th>Topics</th>
<th>Posts</th>
<th class="last">Last Post</th>
</tr>
<?php
//This should show all rows of categories
$query = yasDB_select("SELECT * FROM forumcats WHERE active='yes' ORDER BY `order`",false);
while($row = $query->fetch_array(MYSQLI_ASSOC)) {
	$id = $row['id'];
	$name = $row['name'];
	$desc = $row['desc'];
	if ($setting['seo'] == 'yes') {
		$catlink = $setting['siteurl'].'forumcats/'.$id.'/1.html';
	} else {
		$catlink = $setting['siteurl'] . 'index.php?act=forumcats&id='.$id ;
	}
	?>
	<tr>
	<td class="first style1"><h3><a href="<?php echo $catlink;?>"><?php echo $name;?></a></h3><span style="font-size:10.5px;"><?php echo $desc;?></span></td>
	<td class="style3">
	<?php
			$result = yasDB_select("SELECT count(id) AS count FROM forumtopics WHERE cat = '$id' ");
			$row = $result->fetch_array(MYSQLI_ASSOC);
			echo $row['count'];
			?>
	</td>
	<td class="style3">
			<?php
			$result = yasDB_select("SELECT COUNT(id) AS count FROM forumposts WHERE topic IN (SELECT id FROM forumtopics WHERE cat = '$id') ");
			$row = $result->fetch_array(MYSQLI_ASSOC);
			echo $row['count'];
			?>
	</td>
	<td class="last style2" style="text-align:right;">
	<?php //This should show only the last topic from each category
	$result1 = yasDB_select("SELECT `id`, `subject`, `date`, `name` FROM forumtopics WHERE cat = '$id' ORDER BY `id` DESC LIMIT 1");
	if ($result1->num_rows == 0) {
		echo 'No Posts';
	} else {
		$result = yasDB_select("SELECT forumtopics.id, forumtopics.subject, forumtopics.date AS topicdate, forumposts.topic, forumposts.date, forumposts.name FROM forumposts, forumtopics WHERE forumposts.topic IN (SELECT `id` FROM forumtopics WHERE `cat` = '$id') AND forumposts.topic = forumtopics.id ORDER BY forumposts.id DESC LIMIT 1");
		$topicrow = $result1->fetch_array(MYSQLI_ASSOC);
		$postrow = $result->fetch_array(MYSQLI_ASSOC);
		if ($result->num_rows == 0 || strtotime($topicrow['date']) > strtotime($postrow['date'])) {
			$row = $topicrow;
			unset($topicrow);
			unset($postrow);
		} else {
			$row = $postrow;
			unset($topicrow);
			unset($postrow);
		}
		if ($setting['seo'] == 'yes') {
			$topiclink = $setting['siteurl'].'forumtopics/'.$row['id'].'/1.html';
		} else {
			$topiclink = $setting['siteurl'] . 'index.php?act=forumtopics&id='.$row['id'] ;
		}
		echo '<a href="'.$topiclink.'">'.$row['subject'].'</a><br/>'.$row['date'].'<br/>by '.$row['name'].'';?>
		</td>
		<?php
	}
  }
}
?>
</tr>
</table>
</div>
<div class="clear"></div>
</div>
<div class="container_box1"><div class="forumheader">Statistics</div>
<div class="boxheader">Who's online</div>
<div class="middlewrapperbox">
<?php
$active_length = 600;  // How many seconds to count an user as active until they access another page... 600 = 10 minutes
$query = yasDB_select("SELECT count(user.id) as count FROM user ");
$row = $query->fetch_array(MYSQLI_ASSOC);
$query->close();
echo 'Members - ('.$row['count'].')&nbsp;';
$time = time()-$active_length;
$query = yasDB_select("SELECT user.username AS name, user.id AS id FROM user, membersonline WHERE membersonline.memberid = user.id AND timeactive >= '$time'");
$online = $query->num_rows;
echo '&nbsp;Members Online- ('.$online.')&nbsp;<br/>';
if ($online > 0) {
    while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
        if ($setting['seo'] == 'yes' ) {
			echo '<a href="'.$setting['siteurl'].'showmember/'.$row['id'].'.html">'.$row['name'].'</a>&nbsp;';
		}
		else {
			echo '<a href="'.$setting['siteurl'].'index.php?act=showmember&id='.$row['id'].'">'.$row['name'].'</a>&nbsp;';
		}
	}
}
$visitors_online = new usersOnline();
$bots = array();
// $bots = $visitors_online->get_bots();
if ($bots) {
    foreach ($bots as $bot) {
        echo ucfirst($bot) . ' ';
    }
}
$query->close();
unset($row);
echo '<br>Welcome our newest Member:';
$query = yasDB_select("SELECT * FROM user ORDER BY id DESC LIMIT 1");
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
		echo '&nbsp;<a href="'.$setting['siteurl'].'showmember/'.$row['id'].'.html" title="'.$username.'">'.$username.'</a>&nbsp;';
	} else {
		echo '&nbsp;<a href="'.$setting['siteurl'].'index.php?act=showmember&id='.$row['id'].'" title="'.$username.'">'.$username.'</a>&nbsp;';
	}
}
$query->close();
?>
</div>
<div class="boxheader">Forum Statistics</div>
<div class="middlewrapperbox">
<?php
$query = yasDB_select("SELECT * FROM stats where id=4");
$dayposts = $query->fetch_array(MYSQLI_ASSOC);
$query->close();
?>
<b>Members have made (<?php echo $dayposts['numbers'];?>) posts today.</b>
<?php
$query = yasDB_select("SELECT count(id) as topics FROM forumtopics");
$topics = $query->fetch_array(MYSQLI_ASSOC);
$query->close();
?>
<b> Total topics:</b> (<?php echo $topics['topics'];?>)
<?php
$query = yasDB_select("SELECT count(id) as post FROM forumposts");
$post = $query->fetch_array(MYSQLI_ASSOC);
$query->close();
?>
<b> and total posts:</b> (<?php echo $post['post'];?>)<br/><br/>
<?php
$query = yasDB_select("SELECT * FROM user order by totalposts desc limit 1",false);
while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
	$id = $row['id'];
	$username = $row['username'];
	$totalposts = $row['totalposts'];
	if ( $setting['seo']=='yes' ) {
		$memberlink = $setting['siteurl'].'showmember/'.$id.'.html';
	} else {
		$memberlink = $setting['siteurl'] . 'index.php?act=showmember&id='.$id;
	}
	echo'Top Poster: <a href="'.$memberlink.'">'.$username.'</a> with '.$totalposts.' posts<br>';
}
$query->close();
?>
<?php
$query = yasDB_select("SELECT * FROM stats where id=3");
$totalposts = $query->fetch_array(MYSQLI_ASSOC);
$query->close();
?>
<b>Total Posts:</b> <?php echo $totalposts['numbers'];?>
</div>
<div class="boxheader">Hottest Topics</div>
<div class="middlewrapperbox">
<?php
$query = yasDB_select("SELECT id,subject,views FROM forumtopics ORDER BY views DESC LIMIT 5");
while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
$id = $row['id'];
$subject = $row['subject'];
$views = $row['views'];
if ($setting['seo'] == 'yes') {
$topiclink = $setting['siteurl'].'forumtopics/'.$row['id'].'/1.html';
} else {
$topiclink = $setting['siteurl'] . 'index.php?act=forumtopics&id='.$row['id'];
}
echo'<a href="'.$topiclink.'">'.$subject.'</a> (Views:  ' . $views . ')<br>';
}
$query->close();
?>
</div>
<div class="boxheader">Newest Topics</div>
<div class="middlewrapperbox">
<?php
$query = yasDB_select("select * from forumtopics order by id desc limit 5");
while($row = $query->fetch_array(MYSQLI_ASSOC)) {
$id = $row['id'];
$subject = $row['subject'];
$date = $row['date'];
if ($setting['seo'] == 'yes') {
$topiclink = $setting['siteurl'].'forumtopics/'.$row['id'].'/1.html';
} else {
$topiclink = $setting['siteurl'] . 'index.php?act=forumtopics&id='.$row['id'];
}
echo'<a href="'.$topiclink.'">'.$subject.'</a>  posted on ' . $date . '<br>';
}
$query->close();
?>
</div>
<div class="boxheader">Member Statistics</div>
<div class="middlewrapperbox">
<table width="100%" cellpadding="4" cellspacing="1">
<tr>
<td width="25%" align="left">New Members</td>
<td width="25%" align="left">Top Posters</td>
<td width="25%" align="left">Top Authors</td>
<td width="25%" align="left">Most Replies</td>
</tr>
<tr>
<td width="25%" align="left">
<?php
$query = yasDB_select("SELECT * FROM user ORDER BY id DESC LIMIT 5");
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
		echo '&nbsp;<a href="'.$setting['siteurl'].'showmember/'.$row['id'].'.html" title="'.$username.'">'.$username.'</a><br>';
	} else {
		echo '&nbsp;<a href="'.$setting['siteurl'].'index.php?act=showmember&id='.$row['id'].'" title="'.$username.'">'.$username.'</a><br>';
	}
}
$query->close();
?>
</td>
<td width="25%" align="left">
<?php
$query = yasDB_select("SELECT * FROM user order by totalposts desc limit 5",false);
while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
	$id = $row['id'];
	$username = $row['username'];
	$totalposts = $row['totalposts'];
	if ( $setting['seo']=='yes' ) {
		$memberlink = $setting['siteurl'].'showmember/'.$id.'.html';
	} else {
		$memberlink = $setting['siteurl'] . 'index.php?act=showmember&id='.$id;
	}
	echo'<a href="'.$memberlink.'">'.$username.'</a> '.$totalposts.' posts<br>';
}
$query->close();
?>
</td>
<td width="25%" align="left">
<?php
$query = yasDB_select("SELECT * FROM user order by topics desc limit 5",false);
while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
	$id = $row['id'];
	$username = $row['username'];
	$topics = $row['topics'];
	if ( $setting['seo']=='yes' ) {
		$memberlink = $setting['siteurl'].'showmember/'.$id.'.html';
	} else {
		$memberlink = $setting['siteurl'] . 'index.php?act=showmember&id='.$id;
	}
	echo'<a href="'.$memberlink.'">'.$username.'</a> '.$topics.' topics<br>';
}
$query->close();
?>
</td>
<td width="25%" align="left">
<?php
$query = yasDB_select("SELECT * FROM user order by posts desc limit 5",false);
while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
	$id = $row['id'];
	$username = $row['username'];
	$posts = $row['posts'];
	if ( $setting['seo']=='yes' ) {
		$memberlink = $setting['siteurl'].'showmember/'.$id.'.html';
	} else {
		$memberlink = $setting['siteurl'] . 'index.php?act=showmember&id='.$id;
	}
	echo'<a href="'.$memberlink.'">'.$username.'</a> '.$posts.' posts<br>';
}
$query->close();
?>
</tr>
</td></table></div>
<div class="clear"></div>
</div>