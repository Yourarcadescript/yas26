<div class="nav_box"><div class="nav">Recent Visitor Searches</div>
<div class="nav_box2"><div style="margin:5px;">
<?php
/* The following code displays the latest visitor searches */
	$filename = $setting['sitepath']. "/searches.txt";
	if(file_exists($filename)) {
		if (filesize($filename) > 0) {
			if (!isset($searchArray)) {
				$filename = $setting['sitepath']. "/searches.txt";
				$fh = fopen($filename, "r");
				$searchArray = array();
				$searches = fread($fh, filesize($filename));
				fclose($fh);
				$searchArray = unserialize($searches);
			}
			if (count($searchArray) < 1 ) {
				echo '--empty--';
			} else {
				foreach ($searchArray AS $key=>$value) {
					?><a href="<?php echo $setting['siteurl'];?>index.php?act=search&q=<?php echo $value;?>"><?php echo $value;?></a>&nbsp;&nbsp;
					<?php
				}
			}
		}
	}
	/* End latest searches */
?></div>
<div class="clear"></div>
</div></div>