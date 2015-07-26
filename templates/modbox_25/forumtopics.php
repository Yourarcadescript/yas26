<script type="text/javascript">
tinyMCE.init({
    mode : "textareas",
    theme : "advanced",
    plugins : "spellchecker,pagebreak,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking",
    
    // Theme options
    theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect,forecolor,backcolor",
    theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,image,cleanup,help,|,preview",
    theme_advanced_buttons3 : "charmap,emotions,iespell,media,advhr,ltr,rtl,|,spellchecker,|,visualchars,nonbreaking,|,fullscreen",
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    theme_advanced_statusbar_location : "bottom",
    theme_advanced_resizing : true,
	theme_advanced_height : "500"
    
});
</script>
<div id="center">
<?php
//ini_set('display_errors', 'On');
//error_reporting(E_ALL);
include_once($setting['sitepath'].'/includes/pagination.class.php');
$topicid = intval($_GET['id']);
// $id = intval($_GET['topic']);
$query = yasDB_select("SELECT forumtopics.subject,forumtopics.date,forumtopics.name,forumtopics.text,forumtopics.views,forumtopics.cat,user.id,user.username,user.avatarfile,user.useavatar,user.totalposts FROM forumtopics LEFT JOIN user ON forumtopics.name = user.username WHERE forumtopics.id = " . intval($_GET['id']));
$row = $query->fetch_array(MYSQLI_ASSOC);
$query->close();

$query2 = yasDB_select("SELECT `name` from `forumcats` where `id`={$row['cat']}");
$cat = $query2->fetch_array(MYSQL_ASSOC);
$query2->close();
$id = $row['id'];
?>
<div class="forumcontainer">
<div class="forumheader">
<?php if ($setting['seo'] == 'yes') { ?>
<a href="<?php echo $setting['siteurl'].'forum.html';?>">Forum</a>&nbsp;&nbsp;>&nbsp;&nbsp;<a href="<?php echo $setting['siteurl'].'forumcats/'.$row['cat'].'/1.html';?>"><?php echo $cat['name'];?></a>&nbsp;&nbsp;>&nbsp;&nbsp;<span><?php echo $row ['subject'];?></span>
<?php } else { ?>
<a href="<?php echo $setting['siteurl'].'index.php?act=forum';?>">Forum</a>&nbsp;&nbsp;>&nbsp;&nbsp;<a href="<?php echo $setting['siteurl'].'index.php?act=forumcats&id='.$row['cat'];?>"><?php echo $cat['name'];?></a>&nbsp;&nbsp;>&nbsp;&nbsp;<span><?php echo $row ['subject'];?></span>
<?php } ?></div>
<div class="table">
<table class="listing" cellpadding="0" cellspacing="0">
<?php
    $query = yasDB_select("SELECT forumtopics.subject,forumtopics.date,forumtopics.name,forumtopics.text,forumtopics.views,user.id,user.username,user.avatarfile,user.useavatar,user.totalposts FROM forumtopics LEFT JOIN user ON forumtopics.name = user.username WHERE forumtopics.id = " . intval($_GET['id']));
    $row = $query->fetch_array(MYSQLI_ASSOC);
    $query->close();
    $id = $row['id'];
    $text = $row['text'];
    $views = $row['views'];
    $date = $row['date'];
    $username = $row['name'];
    $totalposts = $row['totalposts'];
    
    if ( $row['useavatar'] == '1' ) {
		$avatarimage = $setting['siteurl'] . 'avatars/' . $row['avatarfile'];
    } else {
		$avatarimage = $setting['siteurl'] . 'avatars/useruploads/noavatar.JPG';
    }
    if ($setting['seo'] == 'yes') {
		$memberlink = $setting['siteurl'].'showmember/'.$id.'.html';
    } else {
		$memberlink = $setting['siteurl'] . 'index.php?act=showmember&id='.$id ;
    }
?>
<tr>
<th class="second">Views: <?php echo $views;?>
</th>
<th class="third">Date: <?php echo $date;?>
</th>
</tr>
<tr>
<td class="first style4">
<a href="<?php echo $memberlink;?>"><img src="<?php echo $avatarimage;?>" width="100" height="100" align="center" title="<?php echo $username;?>"></a><br/><a href="<?php echo $memberlink;?>"><?php echo $username;?></a><br/>
Posts: <?php echo $totalposts;?></td>
<td class="last style5"><?php echo $text;?><br/></td>
</tr>
</table>
<br/>
<table class="listing" cellpadding="0" cellspacing="0">
<?php
    $count = yasDB_select("SELECT count(id) AS count FROM `forumposts` WHERE `topic` = ".intval($_GET['id']));
    $total = $count->fetch_array(MYSQLI_ASSOC);
	$pageurl = new pagination($total['count'], $setting['seo'], '', 5, 3);
	$query = yasDB_select("SELECT forumposts.id as pid,forumposts.topic,forumposts.text,forumposts.date,forumposts.name,user.id,user.username,user.avatarfile,user.useavatar,user.totalposts FROM forumposts LEFT JOIN user ON forumposts.name = user.username WHERE forumposts.topic = " . intval($_GET['id'])." ORDER BY forumposts.id ASC LIMIT " . $pageurl->start . ", " . $pageurl->limit);
    while($row = $query->fetch_array(MYSQLI_ASSOC)) {
		$id = $row['id'];
		$username = $row['name'];
		$date = $row['date'];
		$text = $row['text'];
		$totalposts = $row['totalposts'];
		if ( $row['useavatar'] == '1' ) {
			$avatarimage = $setting['siteurl'] . 'avatars/' . $row['avatarfile'];
		} else {
			$avatarimage = $setting['siteurl'] . 'avatars/useruploads/noavatar.JPG';
		}
		if ($setting['seo'] == 'yes') {
			$memberlink = $setting['siteurl'].'showmember/'.$id.'.html';
		} else {
			$memberlink = $setting['siteurl'] . 'index.php?act=showmember&id='.$id ;
		}
		?>
		<div id="pid<?php echo $row['pid']; ?>">
		<tr>
		<th class="forth"></th>
		<th class="fifth">RE: <?php echo $date;?></th>
		</tr>
		<tr>
		<td class="first style4"><a href="<?php echo $memberlink;?>"><?php echo $username;?></a><br/><a href="<?php echo $memberlink;?>"><img src="<?php echo $avatarimage;?>" width="100" height="100" align="center" title="<?php echo $username;?>"></a>
		<br/>
		Posts: <?php echo $totalposts;?>
		<br/>
		</td>
		<td class="last style5"><?php echo $text;?></td>
		</tr>
		</div>
		<?php
	} ?>
</table>
<br style="clear: both"/>
<div id="page_box"><?php echo $pageurl->showPagination();?></div>
</div>

<div class="clear"></div>
</div>

<div class="forumcontainer">
<div class="forumheader">Quick Reply</div>
<?php
if (isset($_SESSION['user'])) {
	if (isset($_POST['name'])) {
		$name = $_POST['name'];
	} else if (isset($_SESSION['user'])) {
		$name = $_SESSION['user'];
	} else {
		$name = '';
	} 
	if (isset($_POST['Submit'])) {
		$topic = intval($_POST['id']);
		$text = yasDB_clean($_POST['text']);
		$date = yasDB_clean($_POST['date']);

		if (isset($_POST['name'])) {
			$name = yasDB_clean($_POST['name']);
		} else if (isset($_SESSION['user'])) {
			$name = $_SESSION['user'];
		} else {
			$name = '';
		}

		$date = date("F j, Y, g:i a"); //create date time

		$sql = "INSERT INTO `forumposts` (id, text, date, topic, name) VALUES ('', '$text', '$date', '$topic', '$name')";
		$result = yasDB_insert($sql);		
		if (isset($_SESSION['user'])) {
			$user = yasDB_clean($_SESSION['user']);
			yasDB_update("UPDATE `forumtopics` set `lastupdate` = ".time()." WHERE `id`=$topic");
			yasDB_update("UPDATE `user` set posts = posts +1 WHERE username = '$user'"); // add a post to the user
			yasDB_update("UPDATE `user` set totalposts = totalposts +1 WHERE username = '$user'"); // add a post to the user Total
			yasDB_update("UPDATE `stats` set numbers = numbers +1 WHERE id = '3'"); // adds a post to Forum Post Totals
			yasDB_update("UPDATE `stats` set numbers = numbers +1 WHERE id = '4'"); // adds a post to Post Today
		}

		if($result){
			?><center>Successful<br/></center>
			<?php
			$query = yasDB_select("SELECT max(id) AS lastid FROM forumposts");
			$answer = $query->fetch_array(MYSQLI_ASSOC);
			if ($setting['seo']=='yes') {
				$answerlink = $setting['siteurl'].'forumtopics/'.$topic.'/1.html';
			} else {
				$answerlink = $setting['siteurl'].'index.php?act=forumtopics&id='.$topic.'#pid'.$answer['lastid'];
			}
			?>
			<center><a href="<?php echo $answerlink;?>">View your Reply</a></center><?php
		} else {
			echo "Could not Reply to Topic.";
		}
	} else {
		?>
		<br/>
		<div id="commentBox">
		<div id="msg">
		<div class="container_box4">
		<center>
		<form id="form1" name="form1" method="post" action="">
		<input name="name" type="hidden" value="<?php echo $name;?>"/>
		<input name="date" type="hidden" value="<?php echo $date;?>"/>
		<textarea rows="6" cols="88" id="text" name="text"></textarea>
		</div>
		<center><br/>	
		<br/>
		<div style="float:left; width:740px;">
		<input type="submit" class="button" name="Submit" value="Submit" /> <input type="reset" class="button" name="Submit2" value="Reset" /><input type="hidden" name="id" value="<?php echo intval($_GET['id']); ?>"/><br/><br/>
		</div>
		</center>
		</form>
		</center>
		</div></div>
	<?php
		}
} else {
    if ( $setting['seo']=='yes' ) {
		$reglink = $setting['siteurl'].'register.html';
    } else {
		$reglink = $setting['siteurl'].'index.php?act=register';
    }
	?>
	<br/><br/><br/><center>You must be signed in to reply. You can also <a href="<?php echo $reglink;?>">Sign up</a> for an account.</center>
	<?php
}
?>
<div class="clear"></div>
</div>