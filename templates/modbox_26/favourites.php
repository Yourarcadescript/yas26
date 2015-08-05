<?php
include($setting['sitepath'].'/includes/pagination.class.php');
$setting['gperpage'] = ($setting['gperpage']<1?15:$setting['gperpage']);
?>
<div id="center">
<div class="container_box1">
<div class="header">Favorite Games:</div>
<div class="container_box2"><div id="category_wrap">
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
	if ($numrows == 0) {
		echo "<center><h3>You have no favorite games.</h3></center>";
	} else {
		$pageurl = new pagination($numrows, $setting['seo'], 'favourites', $setting['gperpage'], 3);
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
            if (strlen($games['title']) > 19) {
		    $games['title'] = substr($games['title'], 0, 16) . '...';
		    } else {
		    $games['title'] = $games['title'];
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
			$pic_settings = array('w'=>130,'h'=>100);
			$pp = isset($_GET['page']) ? $_GET['page'] : '1';
			echo '<div class="inner_box"><div class="images">';
			echo '<a href="' . $gurl . '">';
			echo '<img align="absmiddle" src="' . resize($thumbnail, $pic_settings) . '" alt="' . $games['title'] . '" width="130" height="100" /></div>';
			echo '<div class="text2"><strong>' . $games['title'] . '</strong></a></div>';
			echo '<div class="remove"><form method="post" action="">';
			echo '<input type="hidden" name="userdelete" value="' . $userid . '"  />';
			echo '<input type="hidden" name="gamedelete" value="' . $games['id'] .'" />';
			echo '<input type="submit" class="submit" name="favourite" value=""  onclick="deleteFavorite(\''.$games['id'].'\', \''.addslashes($games['title']).'\', \''.$pp.'\');return false"/>';
			echo '</form></div>';
			echo '<div class="clear"></div></div>';	
		}
		$query->close();
		?>
		<br style="clear: both"/>
		<div id="page_box">
		<?php
		echo $pageurl->showPagination();
		?>
		</div>
		<?php
	}
}
?>
</div></div>
<div class="clear"></div>
</div>