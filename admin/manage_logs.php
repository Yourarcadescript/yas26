<?php
function get_content_of_url($url){
    $ohyeah = curl_init();
    curl_setopt($ohyeah, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ohyeah, CURLOPT_URL, $url);
    $data = curl_exec($ohyeah);
    curl_close($ohyeah);
    return $data;
 }
?>
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
			<th class="full" colspan="2">Log Files</th>
		</tr>
		<tr>
			<td class="first"><strong>Arcade Database Errors</strong></td>
			<td class="last"><a href="index.php?act=managelogs&f=db">View Log file</a></td>
		</tr>
		<tr>
			<td class="first"><strong>Scheduled Jobs Log</strong></td>
			<td class="last"><a href="index.php?act=managelogs&f=jobs">View Log file</a></td>
		</tr>
		<tr>
			<td class="first"><strong>Admin Database Errors</strong></td>
			<td class="last"><a href="index.php?act=managelogs&f=adb">View Log file</a></td>
		</tr>
	</form>
	</table>
</div>
<div id="logcontents">
<?php
if (isset($_GET['f'])) {
	$search = "/(\d{2})-(\d{2})-(\d{4}), (\d{2}:\d{2})/";
	$replace = "<span style=\"font-weight:bold\">"."<br/>$1-$2-$3, $4"."</span>";
	switch ($_GET['f']) {
		case 'db':
			if (file_exists('../dberrors_log.txt')) {
				if(isset($_GET['d']) && $_GET['d']=='t') {
					$f = @fopen($setting['sitepath']."/admin/dberrors_log.txt", "r+");
					if ($f !== false) {
						ftruncate($f, 0);
						fclose($f);
						echo 'Log file successfully cleared.';
					} else {
						echo "There was an error while attempting to clear the log file.";
					}
				} else {
					$data = get_content_of_url($setting['siteurl'].'dberrors_log.txt');
					$data = nl2br($data);
					?>
					<h3><a href="<?php echo $setting['siteurl'].'admin/index.php?act=managelogs&f=db&d=t';?>" onclick="return confirm('Do you really want to clear the log file?')">Clear the log file</a><h3>
					<?php
					echo preg_replace($search, $replace, $data);
				}
			} else {
				echo 'Log file was not found.';
			}
			break;
		case 'adb':
			if (file_exists('dberrors_log.txt')) {
				if(isset($_GET['d']) && $_GET['d']=='t') {
					$f = @fopen($setting['sitepath']."/dberrors_log.txt", "r+");
					if ($f !== false) {
						ftruncate($f, 0);
						fclose($f);
						echo 'Log file successfully cleared.';
					} else {
						echo "There was an error while attempting to clear the log file.";
					}
				} else {
					$data = get_content_of_url($setting['siteurl'].'admin/dberrors_log.txt');
					$data = nl2br($data);
					?>
					<h3><a href="<?php echo $setting['siteurl'].'admin/index.php?act=managelogs&f=adb&d=t';?>" onclick="return confirm('Do you really want to clear the log file?')">Clear the log file</a></h3>
					<?php
					echo preg_replace($search, $replace, $data);
				}
			} else {
				echo 'Log file was not found.';
			}
			break;
		case 'jobs':
			if (file_exists('../jobs_log.txt')) {
				if(isset($_GET['d']) && $_GET['d']=='t') {
					$f = @fopen($setting['sitepath']."/jobs_log.txt", "r+");
					if ($f !== false) {
						ftruncate($f, 0);
						fclose($f);
						echo 'Log file successfully cleared.';
					} else {
						echo "There was an error while attempting to clear the log file.";
					}
				} else {
					$data = get_content_of_url($setting['siteurl'].'jobs_log.txt');
					$data = nl2br($data);
					?>
					<h3><a href="<?php echo $setting['siteurl'].'admin/index.php?act=managelogs&f=jobs&d=t';?>" onclick="return confirm('Do you really want to clear the log file?')">Clear the log file</a></h3>
					<?php
					echo preg_replace($search, $replace, $data);
				}
			} else {
				echo 'Log file was not found.';
			}
			break;
	}
}
?>
</div>
</div></div>