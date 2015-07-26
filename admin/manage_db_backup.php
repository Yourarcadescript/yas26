<?php
function getTime() {
	$a = explode (' ',microtime());
	return(double) $a[0] + $a[1];
}
function db_backup() {
	global $mysqli;
	global $setting;	
	$path =  $setting['sitepath'] . '/admin/backup/';
	$file = DB_DATABASE . '_' . date("Y-m-d-H-i-s") . '.sql';
	$backupFile = $path . $file;
	if (!is_dir($path)) mkdir($path, 0766);
    chmod($path, 0777);
	$fh = fopen($backupFile,'w');
	$tab_status = yasDB_select("SHOW TABLE STATUS");
	while($all = $tab_status->fetch_array(MYSQLI_ASSOC)) {
		 $tbl_stat[$all['Name']] = $all['Auto_increment'];
	}
	$backup='';
	$tables = yasDB_select("SHOW TABLES FROM `".DB_DATABASE."`");
	if ($tables->num_rows == 0) return false;
	while($tabs = $tables->fetch_row()) {
		$droptbl = "DROP TABLE IF EXISTS `".$tabs[0]."`;\n";
		$backup .= "--\n-- Table structure for `".$tabs[0]."`\n--\n\n".$droptbl."\nCREATE TABLE IF NOT EXISTS `".$tabs[0]."` (";
		$res = yasDB_select("SHOW CREATE TABLE ".$tabs[0]);
		while($all = $res->fetch_array(MYSQLI_ASSOC)) {
			$str = str_replace("CREATE TABLE `".$tabs[0]."` (", "", $all['Create Table']);
			$str = str_replace(",", ",", $str);
			$str2 = str_replace("`) ) TYPE=MyISAM ", "`)\n ) TYPE=MyISAM ", $str);
			if ($tbl_stat[$tabs[0]] > 0) {
				$ai = " AUTO_INCREMENT=".$tbl_stat[$tabs[0]];
			} else {
				$ai = "";
			}
			$backup .= $str2.$ai.";\n\n";
		}
		$backup .= "--\n-- Data to be executed for table `".$tabs[0]."`\n--\n\n";
		$limit = yasDB_select("SHOW COLUMNS FROM `".$tabs[0]."`");
		$column = $limit->fetch_row();
		$tcount = yasDB_select("SELECT COUNT(".$column[0].") FROM `".$tabs[0]."`");
		$total = $tcount->fetch_row();
		$total = $total[0];
		$running = 0;
		while ($running <= $total) {
			$data = yasDB_select("SELECT * FROM `".$tabs[0]."` LIMIT ".$running.",1000");
			$loop = 0;
			$numrows = $data->num_rows;
			while($dt = $data->fetch_row()) {
				$loop++;
				if ($loop == 1 ) {
					$backup .= "\nINSERT INTO `$tabs[0]` VALUES('".yasDB_clean($dt[0])."'";
				} else {
					$backup .= "\t('".yasDB_clean($dt[0])."'";
				}
				for($i=1; $i<sizeof($dt); $i++) {
					$backup .= ", '".yasDB_clean($dt[$i])."'";
				}
				if ($loop >= $numrows || ($loop % 1000 == 0)) {
					$loop = 0;
					$backup .= ");\n";
					fwrite($fh, $backup);
					unset($backup);
					$backup = '';
				} else {
					$backup .= "),\n";
				}
			}
			$running += 1000;
		}
		$backup .= "\n-- --------------------------------------------------------\n\n";
	}
	
	fwrite($fh, $backup);
	fclose($fh);
	if($fh) { 
		if (class_exists('ZipArchive')) {
			$zip = new ZipArchive();
			$zipfile = $backupFile . '.zip';
			$compress = $zip->open($zipfile, ZIPARCHIVE::CREATE);
			if ($compress === true)	{
				$zip->addFile($backupFile,$file);
				$zip->close();
				if (filesize($zipfile) > 0) {
					unlink($backupFile);
					return true;
				} else return false;
			} else return false;
		}
		return true;
	}
	else { return false; } 
}
$Start = getTime();
$mem_limit = substr(ini_get("memory_limit"), 0, -1);
set_time_limit(300);
//include('backup.class.php');

if (isset($_POST['create'])) {
	$db_success = db_backup();
}
?>
<div id="center-column">
<div class="top-bar">
<h1>Cpanel - Database Backup</h1>
<div class="breadcrumbs"><a href="index.php?act=gainfo" title="Google Analytics Info">Google Analytics Login Info</a></div>
</div><br />
<div class="select-bar">
<label>
<h3>Backup Arcade Database</h3>
</label>
</div>
<div style="font-size:14px;text-align:center;">
<form id="db_backup" method="post" action="index.php?act=managedbbackup">
	<label>Create a database backup</label>
	<input type="submit" name="create" id="name" class="button" value="Go!"/>
</form>
</div>
<br/><br/>
<div style="color:#ff0000;text-align:center;">
<?php
if (isset($db_success)) {
	if ($db_success) {
		echo 'Database backup created successfully.';
		echo '<br/>Available script memory: '. $mem_limit. ' MB<br/>';
		echo 'Peak memory usage: '. (memory_get_peak_usage(true)/1024)/1024 . ' MB<br/>';
		$End = getTime();
		echo "Time taken = ".number_format(($End - $Start),2)." secs<br/>";
	} else {
		echo 'Database backup creation failed.';
		echo 'Available script memory: '. $mem_limit. ' MB<br/>';
		echo 'Peak memory usage: '. (memory_get_peak_usage(true)/1024)/1024 . ' MB<br/>';
		$End = getTime();
		echo "Time taken = ".number_format(($End - $Start),2)." secs<br/>";
	}
}
?></div>
</div>