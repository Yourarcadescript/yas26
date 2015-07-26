<div id="center-column">
<div class="top-bar">
<h1>Cpanel - Manage Site Cache</h1>
<div class="select-bar"></div>
<div class="table">
	<img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
	<img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
	<form action="" method="post" action="index.php?act=cache">
	<table class="listing form" cellpadding="0" cellspacing="0">
		<tr>
			<th class="full" colspan="2">Clear Cache</th>
		</tr>
		<tr>
			<td class="first"><strong>Clear Page Cache</strong></td>
			<td class="last"><input type="checkbox" name="pagecache" value="pagecache" /></td>
		</tr>
		<tr>
			<td class="first"><strong>Clear Image Cache</strong></td>
			<td class="last"><input type="checkbox" name="imagecache" value="imagecache" /></td>
		</tr>
		<tr>
			<td class="first" width="172"></td>
			<td class="last"><input type="submit" class="button" name="clearbtn" value="Clear" /></td>
		</tr>
	</form>
	</table>
</div>
<?php
if (isset($_POST['clearbtn'])) {
	if (isset($_POST['pagecache']) && $_POST['pagecache'] == 'pagecache') {
		$msg = true;
		$dir = $setting['sitepath'].'/cache/';
		$files = glob($dir.'*');
		if(is_array($files) && count($files) > 0) {
			foreach($files as $v){
				if (is_file($v)) {
					@chmod($v, 0777);
					if(!unlink($v)) {
						$msg = $false;
						break;
					} 
				}
			}
			if ($msg === true) {
				echo '<br/>Page cache successfully deleted.';
			} else {
				echo '<br/>Error deleting page cache.';
			}
		} else {
			echo '<br/>Cache was already empty.';
		}
	}
	if (isset($_POST['imagecache']) && $_POST['imagecache'] == 'imagecache') {
		$msg = true;
		$dir = $setting['sitepath'].'/cache/img/';
		$files = glob($dir.'*.*');
		if(is_array($files) && count($files) > 0) {
			foreach($files as $v){
				if(!unlink($v)) {
					$msg = $false;
						break;
				} else {
					echo '<br/>Image cache successfully deleted.';
				}
			}
			if ($msg === true) {
				echo '<br/>Image cache successfully deleted.';
			} else {
				echo '<br/>Error deleting image cache.';
			}
		} else {
			echo '<br/>Image cache was already empty.';
		}
	}
}
?>
</div></div>