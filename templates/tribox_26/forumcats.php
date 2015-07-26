<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
include_once($setting['sitepath'].'/includes/pagination.class.php');
$page = isset($_GET['page']) ? $_GET['page'] : 1;

$forumcat = intval($_GET['id']);
?>
<div id="center">
<?php
    $runOnce = false;
    function echoOnce() {
        global $runOnce;
        if(!$runOnce) {
            $runOnce = true; return "<b>Sub categories:</b><br><div class='catpages'>";
        }
    }
?>

<div class="container_box1"><div class="forumheader">
<?php if ($setting['seo'] == 'yes') {
	?><a href="<?php echo $setting['siteurl'].'forum.html';?>">Forum</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span><?php echo $cat_data ['name'];?></span></div>
<?php } else { ?>
	<a href="<?php echo $setting['siteurl'].'index.php?act=forum';?>">Forum</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span><?php echo $cat_data ['name'];?></span></div>
	<?php
}
    $query = yasDB_select("SELECT * FROM forumcats WHERE active='yes' ORDER BY `order` desc");
    while($row = $query->fetch_array(MYSQLI_ASSOC)) {
        if ($id = $row['pid']) {
           echo echoOnce();
           if ($setting['seo'] == 'yes') {
               $catlink = $setting['siteurl'].'forumcats/'.$row['id'].'/1.html';
           } else {
               $catlink = $setting['siteurl'] . 'index.php?act=forumcats&id='.$row['id'] ;
           }
            echo '<a href="' . $catlink . '">' . $subject. '</a>';
        }
    }
    $count = yasDB_select("SELECT count(id) AS count FROM `forumtopics` WHERE cat = $forumcat");
    $total = $count->fetch_array(MYSQLI_ASSOC);
    if($total['count'] < 0) {
        echo '<br/><center><span style="font-size:200%;">There are no topics in this category yet.</span></center>';
    } else {
?>
<div class="table">
<table class="listing" cellpadding="0" cellspacing="0">
<tr>
<th class="first">Thread</th>
<th>Replies</th>
<th>Views</th>
<th class="last">Last Post</th>
</tr>
<?php
$pageurl = new pagination($total['count'], $setting['seo'], '', 5, 3);
$query = yasDB_select("SELECT * FROM `forumtopics` where cat = " . $forumcat . " order by `lastupdate` desc LIMIT " . $pageurl->start . ", " . $pageurl->limit);
while($row = $query->fetch_array(MYSQLI_ASSOC)) {
    $id = $row['id'];
    $subject = $row['subject'];
    $name = $row['name'];
    $date = $row['date'];
    $views = $row['views'];
    if ($setting['seo']=='yes') {
		$postlink = $setting['siteurl'].'forumtopics/'.$id.'/1.html';
    } else {
        $postlink = $setting['siteurl'] . 'index.php?act=forumtopics&id=' . $id;
    }
	?>
	<tr>
	<td class="first style1"><h3><a href="<?php echo $postlink;?>"><?php echo $subject;?></a></h3><span style="font-size:10px;">by <?php echo $name;?></span></td>
	<td class="style3">
	<?php //This will show how many replies from each topic
	$result = yasDB_select("SELECT COUNT(forumposts.topic) AS count FROM forumposts WHERE topic = '$id' ");
	$row = $result->fetch_array(MYSQLI_ASSOC);
	echo $row['count'];
	?>
	</td>
	<td class="style3">
	<?php //This will show how many times the topic has been viewed from each topic
	echo $views;
	?>
	</td>
	<td class="last style2">
	<?php //This will show only the last date,time username from each topic
	$result = yasDB_select("SELECT id, name, date FROM forumposts WHERE topic = '$id' ORDER BY date DESC LIMIT 1");
	if ($result->num_rows == 0) {
		echo "<div style=\"text-align:right;\">no posts</div>";
	} else {
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$id = $row['id'];
		$date = $row['date'];
		$name = $row['name'];
		echo "<div style=\"text-align:right;\"><span>".$date."</span><br/>by ".$name."</div>";
	}
	?>
	</td>
	<?php
}
?>
</tr>
</table>
<br/>
<div align="center">
<div align="right">
<?php
    if ($setting['seo'] == 'yes') {
    $ctlink = $setting['siteurl'].'createtopic-'.$forumcat.'.html';
    } else {
    $ctlink = $setting['siteurl'] . 'index.php?act=createtopic&cat='.$forumcat;
    }
?>
<a href="<?php echo $ctlink;?>" class="button">New Topic</a></div>
</div>

<br style="clear: both"/>
<div id="page_box"><?php echo $pageurl->showPagination();?></div>
<?php
}
?>
</div>
<div class="clear"></div>
</div>