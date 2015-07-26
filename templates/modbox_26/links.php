<div id="center">
<div class="container_box1">
<div class="header">Friends</div>
<?php
include("./includes/pagination.class.php");
$result = yasDB_select("SELECT count(id) FROM links WHERE approved='yes'",false);
$query_data = $result->fetch_array(MYSQLI_NUM);
$numrows = $query_data[0];
$result->close();
$pageurl = new pagination($numrows, $setting['seo'], 'links', 50, 3);
$select_links = yasDB_select("SELECT * FROM links WHERE approved='yes' order by `in` desc LIMIT " . $pageurl->start . ", " . $pageurl->limit);
?>
<div class="container_box3">
<div class="header2">
<div class="links_header1">Friends:</div>
<div class="links_header2">Description:</div>
<div class="links_header3">In - Out:</div>
</div>
<?php
while($links = $select_links->fetch_array(MYSQLI_ASSOC)) {
	echo '
	<div class="link_box2">
	<a href="' . $links['url'] . '" target="_blank" title="In: ' . $links['in'] . '" onClick="addHit(\''.$links['id'].'\');return true;"><strong>' . $links['text'] . '</strong></a>
	</div>
	<div class="link_box3">' . $links['description'] . '</div>
	<div class="link_box4">[In: ' . $links['in'] . '][Out: ' . $links['out'] . ']</div>';
	}
?>
<br style="clear: both"/>
<center>
<div class="link_box5">
<?php
if ($setting['seo']=='yes') {
?>
<a href="<?php echo $setting['siteurl'];?>addlink.html">Add Your Link</a>
<?php } else { ?>
<a href="<?php echo $setting['siteurl'];?>index.php?act=addlink">Add Your Link</a>
<?php } ?>
</div>
</center>
</div>
<div class="link_pages">
<?php
echo $pageurl->showPagination();
?>
</div>
<div class="clear"></div>
</div>
