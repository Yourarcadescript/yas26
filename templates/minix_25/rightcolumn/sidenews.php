<div class="nav_box"><div class="nav">News</div>
<div class="nav_box2">
		<?php
			$sql = yasDB_select("SELECT * FROM `news` ORDER BY `id` DESC LIMIT 1",false);
			$news = $sql->fetch_array(MYSQLI_ASSOC);
			$text = substr($news['news_text'],0,100).'...';
			if ($setting['seo']=='yes') {
				echo '<center>Date:<br><b>'. $news['date']. '</center></b><br>
						<left>'. $text. '</left><br />
						 <center><a href="'. $setting['siteurl'] .'news.html">Read All News</a></center>';
			} else {
				echo '<center>Date:<br><b>'. $news['date']. '</center></b><br>
					<left>'. $text. '</left><br />
					<center><a href="'.$setting['siteurl'].'index.php?act=news" class="linkset">Read All News</a></center>';
			}        
			$sql->close();
			?>
</div></div>