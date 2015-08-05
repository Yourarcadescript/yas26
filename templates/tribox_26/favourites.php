<?php
include($setting['sitepath'].'/includes/pagination.class.php');
?>
<div id="center">
<div id="favourite_main_header">Favorite Games:</div>
<div id="favourite_main_box"><div id="category_wrap">
<?php 
if(isset($_SESSION["user"])) {
	$user = $_SESSION["user"];
	$userid = $_SESSION['userid'];
	if (isset($_POST['favourite'])) {
		yasDB_delete("DELETE FROM favourite WHERE userid='$userid' AND gameid = '{$_POST['gamedelete']}'",false);
	}
	$result = yasDB_select("SELECT gameid FROM favourite WHERE userid=".$userid,false);
	$numrows = $result->num_rows;
	$result->close();
	$pageurl = new pagination($numrows, $setting['seo'], 'favourites', 20, 3);
	$query = yasDB_select("SELECT * FROM favourite WHERE userid= '$userid' LIMIT " . $pageurl->start . ", " . $pageurl->limit);
	while($row = $query->fetch_array(MYSQLI_ASSOC)) {
		$games = yasDB_select("SELECT * FROM games WHERE id = '{$row['gameid']}'");
		$games = $games->fetch_array(MYSQLI_ASSOC);
		$gameurl = prepGame($games['file']);
		$games['description'] = stripslashes($games['description']);
		if(strlen($games['description']) > 75){
			$games['description'] = substr($games['description'], 0, 75)."...";
		} else {
			$games['description'] = $games['description'];
		}
		if ($setting['seo']=='yes') {
			$gurl = $setting['siteurl'] . 'game/' . $games['id'] . '/' . $gameurl . '.html';
		} else {
			$gurl = $setting['siteurl'] . 'index.php?act=game&id=' . $games['id'];
		}
		if (file_exists($games['thumbnail'])) {
			$thumbnail = $games['thumbnail'];
			$thumbnail = urldecode($thumbnail);
		} else {
			$thumbnail = $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/nopic.jpg';
		}
		$pic_settings = array('w'=>80,'h'=>80);
		$pp = isset($_GET['page']) ? $_GET['page'] : '1';
		echo '<div class="fav_box"><div class="fav_gamepic">';
		echo '<a href="' . $gurl . '">';
		echo '<img align="absmiddle" src="' . resize($thumbnail, $pic_settings) . '" alt="' . $games['title'] . 'width="80" height="80"" /></div><div class="fav_game_text"><strong>' . $games['title'] . '</strong></a>';
		echo '</div>';
		echo '<div class="remove"><form method="post" action="">';
		echo '<input type="hidden" name="userdelete" value="' . $userid . '"  />';
		echo '<input type="hidden" name="gamedelete" value="' . $games['id'] .'" />';
		echo '<input type="submit" class="submit" name="favourite" value=""  onclick="deleteFavorite(\''.$games['id'].'\', \''.addslashes($games['title']).'\', \''.$pp.'\');return false"/>';
		echo '</form></div><br style="clear: both"/></div>';
	}
	$query->close();
	?>
	<br style="clear: both"/>
	<div id="fav_page_box">
	<?php
	echo $pageurl->showPagination();
	?>
	</div>
	<?php
}
?>
</div>
<div class="clear"></div>
</div>