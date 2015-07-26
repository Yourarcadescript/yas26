<div id="center-column">
<div class="top-bar">
<a href="index.php?act=managejobs" title="Scheduled Jobs" class="button">ADD GAME</a>
<h1>Cpanel - Jobs</h1>
<div class="breadcrumbs"><a href="index.php?act=addmedia" title="Ad Media">Add Media</a> / <a href="index.php?act=addcode" title="Ad Code">Add Code</a> / <a href="index.php?act=uploadgames" title="Upload Games">Upload Games</a> / <a href="index.php?act=brokenfiles" title="Broken Files">Broken Files</a></div>
</div><br />
<div class="select-bar">
<label>
<h3>Manage Scheduled Jobs</h3>
</label>
</div>
<?php
$qry = yasDB_select("SELECT jobs FROM settings");
$result = $qry->fetch_array(MYSQLI_ASSOC);
$jobs = unserialize($result['jobs']);
$jobNames = array_keys($jobs);
if (isset($_POST['jobsSubmit'])) {
	$i=0;
	$new_jobs = array();
	$status = 0;
	$midnight_today = mktime(23,59,59);
	$plus_month = strtotime('+30 days', $midnight_today);
	$plus_week = strtotime('+7 days', $midnight_today);
	foreach ($jobs AS $job) {
		if ($jobNames[$i] == 'jobstate') {$i++; continue;}
		$new_jobs[$jobNames[$i]][0] = intval($_POST['jobFrequency'.$i]);
		if (isset($_POST['jobStatus'.$i])) {
			$new_jobs[$jobNames[$i]][2] = 1;
			$status = 1;
		} else {
			$new_jobs[$jobNames[$i]][2] = 0;
		}
		if ($job[0] != $new_jobs[$jobNames[$i]][0]) {
			if ($new_jobs[$jobNames[$i]][0] == 7) {
				$new_jobs[$jobNames[$i]][1] = $plus_week;
			}
			else if ($new_jobs[$jobNames[$i]][0] == 30) {
				$new_jobs[$jobNames[$i]][1] = $plus_month;				
			}
			else {
				$new_jobs[$jobNames[$i]][1] = $midnight_today;
			}
		} else {
			$new_jobs[$jobNames[$i]][1] = $job[1];
		}
		if ($jobNames[$i] == 'gameque') {
			$new_jobs[$jobNames[$i]][3] = intval($_POST['gameCount']);
		}
		$i++;
	}
	$new_jobs['jobstate'] = $status;
	$finished_jobs = serialize($new_jobs);
	yasDB_update("UPDATE `settings` SET `jobs` = '$finished_jobs' WHERE `id` = 1");
	include("../includes/settings_function.inc.php");
	createConfigFile();
	?><center>Scheduled Jobs updated!<br/><a href="index.php?act=managejobs" />Continue</a></center><?php
} else if(isset($_GET['run'])) {
	$_SESSION['runjobs'] = 'good-to-go';
	$runjob = $_GET['run'];
	class usersOnline {
		public function ipCheck() {
			if (getenv('HTTP_CLIENT_IP')) {
				$ip = getenv('HTTP_CLIENT_IP');
			}
			elseif (getenv('HTTP_X_FORWARDED_FOR')) {
				$ip = getenv('HTTP_X_FORWARDED_FOR');
			}
			elseif (getenv('HTTP_X_FORWARDED')) {
				$ip = getenv('HTTP_X_FORWARDED');
			}
			elseif (getenv('HTTP_FORWARDED_FOR')) {
				$ip = getenv('HTTP_FORWARDED_FOR');
			}
			elseif (getenv('HTTP_FORWARDED')) {
				$ip = getenv('HTTP_FORWARDED');
			}
			else {
				$ip = $_SERVER['REMOTE_ADDR'];
			}
			return $ip;
		}
    }
	include("../includes/jobs2.php");
	?><center>Job run successfully!<br/><a href="index.php?act=managejobs" />Continue</a></center><?php
} else {
	?>
	<div class="table">
	<img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
	<img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
	<form name="deleteform" method="post" action="index.php?act=managejobs">
	<table class="listing" cellpadding="0" cellspacing="0">
	<tr>
	<th class="first" width="186px">Job</th>
	<th width="104px">Enabled</th>
	<th>Schedule</th>
	<th class="last"></th>
	</tr>
	<tr>
	<?php

	$i=0;
	foreach ($jobs AS $job) {
		if ($jobNames[$i] == 'jobstate') {$i++; continue;}
		?> 
		<td class="first style1">
		<?php
		switch ($jobNames[$i]) {
			case 'dayplays':
				echo 'Clear Daily Game Plays';
				break;
			case 'feeds':
				echo 'Update RSS Feeds';
				break;
			case 'database':
				echo 'Optimize database';
				break;
			case 'sitemap':
				echo 'Update Sitemap';
				break;
			case 'gameque':
				echo 'GameQue Installs';
				break;
			case 'backup':
				echo 'Create database backup';
				break;	
		}
		?>
		</td>
		<td><input type="checkbox" name="jobStatus<?php echo $i; ?>" <?php if($job[2] > 0) {echo 'checked="checked"';}?>></td>
		<td>
		<?php
		if ($jobNames[$i] == 'gameque') {
			?>
			<select name="gameCount" size="1">
			<?php
			for ($x=1;$x<=5;$x++) { // Edit 5 to desired max number of games per GameQue install...here ex. 5/day
				?><option value="<?php echo $x;?>"<?php echo ($job[3] == $x?' selected="selected"':null); ?>><?php echo $x;?></option><?php
			}
			?>
			</select>&nbsp;&nbsp;per&nbsp;
			<?php
		}
		?>
		<select name="jobFrequency<?php echo $i; ?>" size="1">
			<option value="1"<?php echo($job[0] == 1?' selected="selected"':null); ?>>Daily</option>
			<option value="7"<?php echo($job[0] == 7?' selected="selected"':null); ?>>Weekly</option>
			<option value="30"<?php echo($job[0] == 30?' selected="selected"':null); ?>>Monthly</option>
		</select>
		</td>
		<td ><a href="index.php?act=managejobs&run=<?php echo $jobNames[$i];?>"><font color="#cc0000">Run Job Now</font></a></td>
		</tr><?php
		$i++;
	} ?>
	</table>
	<br/>
	<center><input type="submit" class="button" name="jobsSubmit" value="Submit">&nbsp;<input type="reset" class="button" value="Reset"></center>
	</form>
	</div>
	<?php
}
?>
</div>