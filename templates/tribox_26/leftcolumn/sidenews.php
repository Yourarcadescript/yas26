<div class="navh3"><div align="center">News</div></div>
	<div class="cont">
		<div class="inner">
		<?php
			$sql = yasDB_select("SELECT * FROM `news` ORDER BY `id` DESC LIMIT 1",false);
			$news = $sql->fetch_array(MYSQLI_ASSOC);
			$text = closeTags(substr($news['news_text'],0,350).'...');
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
	</div>