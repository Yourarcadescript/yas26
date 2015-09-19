<div id="center">
<div class="container_box1">
<div id="headergames2">News Blog</div>
<?php
include_once($setting['sitepath'].'/includes/pagination.class.php');
$setting['gperpage'] = ($setting['gperpage']<1?15:$setting['gperpage']);
?>
<div class="container_box3">
<div class="header2">
<div id="news_btn_text">Full News</div>
<div id="news_topic">Topic</div>
<div id="news_text"><?php echo $setting['sitename'];?> News</div>
<div id="news_date_text">Date</div>
</div>
<?php
$result = yasDB_select("SELECT count(id) FROM `news` ");
$query_data =$result->fetch_array(MYSQLI_NUM);
$numrows = $query_data[0];
$result->close();
$pageurl = new pagination($numrows, $setting['seo'], 'news', 20, 3);
$query = yasDB_select("SELECT * FROM news ORDER BY `id` asc LIMIT " . $pageurl->start . ", " . $pageurl->limit);
while($row = $query->fetch_array(MYSQLI_ASSOC)) {
$rowurl = $row['news_text'];
$rowurl = str_replace(" ", "-", $rowurl);
$rowurl = str_replace("'", "_", $rowurl);
$rowurl = str_replace('"', "_", $rowurl);
$rowurl = str_replace('/', "_", $rowurl);
$rowurl = str_replace("\\", "_", $rowurl);
$rowurl = rawurlencode($rowurl);$row['news_text'] = stripslashes($row['news_text']);
if ( strlen($row['news_text']) > 45) {
$row['news_text'] = substr($row['news_text'], 0, 75)."...";
}
$id = $row['id'];
$news = $row['date'];
$news = $row['news_text'];
if ( $setting['seo']=='yes' ) {
$newslink = $setting['siteurl'].'shownews/'.$id.'.html';
} else {
$newslink = $setting['siteurl'] . 'index.php?act=shownews&id='.$id;
}
?>
<div class="container_box5">
<div class="news_btn">
<a href="<?php echo $newslink;?>"><img src="<?php echo $setting['siteurl'];?>/templates/<?php echo $setting['theme'];?>/skins/<?php echo $setting['skin'];?>/images/news.png" height="40" width="40"></a>
</div>
<div class="news_topic"><?php echo $row['topic'];?></div>
<div class="news">
<?php echo $row['news_text'];?></div>
<div class="news_date">
<?php echo $row['date'];?></div>
</div>
<?php } ?>
<div class="clear"></div>
<?php
echo $pageurl->showPagination();
?>

</div>
<div class="clear"></div>
</div>