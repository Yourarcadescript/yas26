<div id="center">
<div class="container_box1">
<div id="headergames2">Links</div>
<?php
include("./includes/pagination.class.php");
$result = yasDB_select("SELECT count(id) FROM links WHERE approved='yes'",false);
$query_data = $result->fetch_array(MYSQLI_NUM);
$numrows = $query_data[0];
$result->close();
$pageurl = new pagination($numrows, $setting['seo'], 'links', 50, 3);
$select_links = yasDB_select("SELECT * FROM links WHERE approved='yes' order by `in` desc LIMIT " . $pageurl->start . ", " . $pageurl->limit);
?>
<div class="containbox2"><div class="linkspage"><ul>
<?php
while($links = $select_links->fetch_array(MYSQLI_ASSOC)) {
	echo '	
	<li class="even"><a href="' . $links['url'] . '" target="_blank" title="' . $links['url'] . '" onClick="addHit(\''.$links['id'].'\');return true;"><strong>' . $links['text'] . '</strong></a>&nbsp;-&nbsp;' . $links['description'] . '</li>
	';
	}
?></ul></div>
<br style="clear: both"/>
<div id="page_box">
<?php
echo $pageurl->showPagination();
?>
</div>
</div>
<div class="link_box6">
<?php
if ($setting['seo']=='yes') {
?>
<a href="<?php echo $setting['siteurl'];?>addlink.html">Add Link</a>
<?php } else { ?>
<a href="<?php echo $setting['siteurl'];?>index.php?act=addlink">Add Link</a>
<?php } ?>
</div><br/><br/>
<div class="clear"></div>
</div>
