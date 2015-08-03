<?php
#--------------------------------
# http://www.yourarcadescript.com
#       VERSION 2.5
#		jobs.php
#		Called by jQuery in includes/inc.php on Document load
#		Purpose: Run jobs scheduled from admin panel
#		CC BY-ND 3.0 Licensed (http://creativecommons.org/licenses/by-nd/3.0/) 
#       
#--------------------------------
@session_start();
include_once("db_functions.inc.php");
include_once("config.inc.php");
set_time_limit(500);
if (!isset($_SESSION['runjobs']) || $_SESSION['runjobs'] != 'good-to-go') {echo 'Unauthorized access.'; exit;}
if (!isset($runjob)) $runjob = '';
$jobs = array();
$jobs = unserialize(stripslashes($setting['jobs']));
if($jobs['jobstate'] != 1) exit;
if (!class_exists(usersOnline)) {
	class usersOnline {
		//minified version of class declared in inc.php for use in ajax call
		public function usersOnline () {
			$this->ip = $this->ipCheck();
		}
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
}
function doLog($text) {
    global $setting;
	$filename = $setting['sitepath']. "/jobs_log.txt";
    $fh = fopen($filename, "a");
    fwrite($fh, date("d-m-Y, H:i")." - $text\n");
    fclose($fh);
}
$currentIp = new usersOnline();
$now = time();
$midnight_today = mktime(23,59,59);
$plus_week = strtotime('+7 days', $midnight_today);
$plus_month = strtotime('+30 days', $midnight_today);
$update = 0;
// empty daily game plays
if (($jobs['dayplays'][2] != 0 && $jobs['dayplays'][1] <= $now) || $runjob == 'dayplays') {
	$update = 1;
	$displayIp = ($runjob == 'dayplays') ? 'Admin' : $currentIp->ipCheck();
	yasDB_update("UPDATE `stats` SET numbers = 0 WHERE id = 2");
	yasDB_update("UPDATE `stats` SET numbers = 0 WHERE id = 3");
	$jobs['dayplays'][1] = $midnight_today;
	doLog("Daily game plays emptied triggered by visitor " . $displayIp);
}
// create game feeds
if (($jobs['feeds'][2] != 0 && $jobs['feeds'][1] <= $now) || $runjob == 'feeds') {
	$update = 1;
	$displayIp = ($runjob == 'feeds') ? 'Admin' : $currentIp->ipCheck();
	
	$rrsfile = $setting['sitepath'].'/rss-arcade.xml';
	$rss_intro = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
	$rss_intro .= "<rss version=\"2.0\">\n";
	$rss_intro .= "<channel>\n";
	$rss_intro .= "<title>" . $setting['sitename'] . "</title>\n";
	$rss_intro .= "<link>" . $setting['siteurl'] . "</link>\n";
	$rss_intro .= "<ttl>600</ttl>\n";
	$rss_intro .= "<description>".$setting['slogan']."</description>\n";
	$rss_intro .= "<copyright>".date("Y")." " . $setting['sitename'] . " All rights reserved.</copyright>\n";   
	$query = yasDB_select("SELECT * FROM games order by id DESC LIMIT 40",false);
	$rss = '';
	while($row = $query->fetch_array(MYSQLI_ASSOC)) {
		$name = $row['title'];
		$ID = $row['id'];
		$description = $row['description'];
		$gameurl = $row['title'];
		$gameurl = str_replace(" ", "-", $gameurl);
		$gameurl = str_replace("'", "_", $gameurl);
		$gameurl = str_replace('"', "_", $gameurl);
		$gameurl = str_replace('/', "_", $gameurl);
		$gameurl = str_replace("\\", "_", $gameurl);
		$gameurl = rawurlencode($gameurl);
		$thumb = $row['thumbnail'];
		$gamename = preg_replace('[^A-Za-z0-9]', '-', $row['title']);
		if ($setting['seo'] == 'yes') { 
			$playlink = ''. $setting['siteurl'] . 'game/' . $ID . '/' . $gameurl . '.html' ;
		} else {
			$playlink = ''. $setting['siteurl'] . 'index.php?act=game&id=' . $ID;
		}
		$thumburl = $setting['siteurl'].$thumb ;
		
		$rss .= "<item>\n";
		$rss .= "<title><![CDATA[ $name ]]></title>\n";
		$rss .= "<link><![CDATA[ " . $playlink ." ]]></link>\n";
		$rss .= "<description><![CDATA[ <img src='$thumburl' > $description ]]></description>\n";
		$rss .= "</item>\n";
	}
	$rss_final = $rss_intro . $rss;
	$rss_final .= "</channel>\n";
	$rss_final .= "</rss>";             
	$query->close();
	$fopen = fopen($rrsfile, 'w');
	fwrite($fopen, $rss_final);
	fclose($fopen);
			
	$rrsfile = $setting['sitepath'].'/rss-arcade-huge.xml';
	$query = yasDB_select("SELECT * FROM games ORDER BY id DESC");
	$rss = '';
	while($row = $query->fetch_array(MYSQLI_ASSOC)) {
		$name = $row['title'];
		$ID = $row['id'];
		$description = $row['description'];
		$gameurl = $row['title'];
		$gameurl = str_replace(" ", "-", $gameurl);
		$gameurl = str_replace("'", "_", $gameurl);
		$gameurl = str_replace('"', "_", $gameurl);
		$gameurl = str_replace('/', "_", $gameurl);
		$gameurl = str_replace("\\", "_", $gameurl);
		$gameurl = rawurlencode($gameurl);
		$thumb = $row['thumbnail'];
		if ($setting['seo'] == 'yes') {
			$playlink = ''. $setting['siteurl'] . 'game/' . $ID . '/' . $gameurl . '.html';
		} else {
			$playlink = ''. $setting['siteurl'] . 'index.php?act=game&id=' . $ID;
		}
		$thumburl = $setting['siteurl'] . $thumb;
		
		$rss .= "<item>\n";
		$rss .= "<title><![CDATA[ $name ]]></title>\n";
		$rss .= "<link><![CDATA[ " . $playlink ." ]]></link>\n";
		$rss .= "<description><![CDATA[ <img src='$thumburl' > $description ]]></description>\n";
		$rss .= "</item>\n";
	}
	$rss_final = $rss_intro . $rss;
	$rss_final .= "</channel>\n";
	$rss_final .= "</rss>";             
	$query->close();
	$fopen = fopen($rrsfile, 'w');
	fwrite($fopen, $rss_final);
	fclose($fopen);
	if ($jobs['feeds'][0] == 7) {
		$jobs['feeds'][1] = $plus_week;
	}
	else if ($jobs['feeds'][0] == 30) {
		$jobs['feeds'][1] = $plus_month;
	}
	else {
		$jobs['feeds'][1] = $midnight_today;
	}
	doLog("Feeds updated triggered by visitor ". $displayIp);
}
//create sitemap
if (($jobs['sitemap'][2] != 0 && $jobs['sitemap'][1] <= $now) || $runjob == 'sitemap') {
	$update = 1;
	$displayIp = ($runjob == 'sitemap') ? 'Admin' : $currentIp->ipCheck();
	$sitemap = $setting['sitepath'] . '/Sitemap.xml';
	$map = '<?xml version="1.0" encoding="UTF-8"?>';
	$map .= "\n<urlset";
	$map .= "\n\t".'xmlns="http://www.google.com/schemas/sitemap/0.9"';
	$map .= "\n\t".'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"';
	$map .= "\n\t".'xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.9';
	$map .= "\n\t".'http://www.google.com/schemas/sitemap/0.9/sitemap.xsd">';
	$map .= "\n\t<url>";
	$map .= "\n\t\t".'<loc>' . $setting['siteurl'] . '</loc>';
	$map .= "\n\t\t".'<priority>0.5</priority>'."\n\t";
	$map .= '</url>'."\n\t";
	$query = yasDB_select("SELECT * FROM games ORDER BY id DESC");
	while($row = $query->fetch_array(MYSQLI_ASSOC)) {
		$ID = $row['id'];
		$description = $row['description'];
		$gameurl = $row['title'];
		$gameurl = str_replace(" ", "-", $gameurl);
		$gameurl = str_replace("'", "_", $gameurl);
		$gameurl = str_replace('"', "_", $gameurl);
		$gameurl = str_replace('/', "_", $gameurl);
		$gameurl = str_replace("\\", "_", $gameurl);
		$gameurl = rawurlencode($gameurl);
		if ($setting['seo'] == 'yes') {
			$playlink = ''. $setting['siteurl'] . 'game/' . $ID . '/' . $gameurl . '.html';
		} else {
			$playlink = ''. $setting['siteurl'] . 'index.php?act=game&amp;id=' . $ID;
		}
		$map .= '<url>'."\n\t\t";
		$map .= '<loc>' .$playlink . '</loc>'."\n\t\t";
		$map .= '<priority>0.5</priority>'."\n\t";
		$map .= '</url>'."\n\t";
	}
	$query->close();
	$query = yasDB_select("SELECT id FROM news order by id",false);
	while($row = $query->fetch_array(MYSQLI_ASSOC)) {
		if ($setting['seo'] == 'yes') {
			$url = $setting['siteurl'] . 'shownews/' . $row['id'] . '.html';
		} else {
			$url = $setting['siteurl'] . 'index.php?act=shownews&amp;id=' . $row['id'];
		}
		$map .= '<url>
				<loc>' . $url . '</loc>
				<priority>0.5</priority>
				</url>'."\n\t";
	}
	$query = yasDB_select("SELECT id FROM categories order by id",false);
	while($row = $query->fetch_array(MYSQLI_ASSOC)) {
		if ($setting['seo'] == 'yes') {
			$url = $setting['siteurl'] . 'category/' . $row['id'] . '/1.html';
		} else {
			$url = $setting['siteurl'] . 'index.php?act=category&amp;id=' . $row['id'];
		}
		$map .= '<url>
				<loc>' . $url . '</loc>
				<priority>0.5</priority>
			</url>'."\n\t";
	}	
	$map .= '</urlset>';
	$fopen = fopen($sitemap, 'w');
	fwrite($fopen, $map);
	fclose($fopen);
	if ($jobs['sitemap'][0] == 7) {
		$jobs['sitemap'][1] = $plus_week;
	}
	else if ($jobs['sitemap'][0] == 30) {
		$jobs['sitemap'][1] = $plus_month;
	}
	else {
		$jobs['sitemap'][1] = $midnight_today;
	}
	doLog("Sitemap updated triggered by visitor ". $displayIp);
}
// Install games from que
if (($jobs['gameque'][2] != 0 && $jobs['gameque'][1] <= $now) || $runjob == 'gameque') {
	$update = 1;
	$displayIp = ($runjob == 'gameque') ? 'Admin' : $currentIp->ipCheck();
	$check = yasDB_select("SELECT `source`, `sourceid` FROM `gameque` LIMIT ".$jobs['gameque'][3]);
	if ($check->num_rows == 0) {
		$message = "Your GameQue on ".$setting['sitename']." is empty. To continue to have games installed on schedule, please add more games to your queue via the Admin game feeds' management pages.";
		$headers = 'From: '.$setting['sitename'].' GameQueue<'.$setting['sitename'].'>';
		$subject = 'The GameQue on'.$setting['sitename'].' is empty';
		@mail($setting['email'], $subject, $message, $headers);
	} else {
		include_once($setting['sitepath']."/admin/gamefeed_installs.php");
		$success = false;
		while ($row = $check->fetch_array(MYSQLI_ASSOC)) {
			switch ($row['source']) {
				case 'fgd':
					$installed = yasDB_select("SELECT `id` FROM `games` WHERE `source` = 'FGD' AND `sourceid` = ".$row['sourceid']);
					if ($installed->num_rows > 0) {
						$success = true;
					} else {
						$success = install_fgdgame($row['sourceid']);
					}
					break;
				case 'fog':
					$installed = yasDB_select("SELECT `id` FROM `games` WHERE `source` = 'FOG' AND `sourceid` = ".$row['sourceid']);
					if ($installed->num_rows > 0) {
						$success = true;
					} else {
						$success = install_foggame($row['sourceid']);
					}
					break;
				case 'kongregate':
					$installed = yasDB_select("SELECT `id` FROM `games` WHERE `source` = 'KONGREGATE' AND `sourceid` = ".$row['sourceid']);
					if ($installed->num_rows > 0) {
						$success = true;
					} else {
						$success = install_konggame($row['sourceid']);
					}
					break;	
				default:
					break;
			}
			if ($success) {
				$orderresult = yasDB_select("SELECT `order` FROM `gameque` WHERE `source` = '".$row['source']."' AND `sourceid` = ".$row['sourceid']);
				$order = $orderresult->fetch_array();
				yasDB_delete("DELETE FROM `gameque` WHERE `source` = '".$row['source']."' AND `sourceid` = ".$row['sourceid']);
				yasDB_update("UPDATE `gameque` SET `order` = `order` - 1 WHERE `order` > {$order[0]}");
			}
		}	
	}	
	if ($jobs['gameque'][0] == 7) {
		$jobs['gameque'][1] = $plus_week;
	}
	else if ($jobs['gameque'][0] == 30) {
		$jobs['gameque'][1] = $plus_month;
	}
	else {
		$jobs['gameque'][1] = $midnight_today;
	}
	doLog("GameQue update triggered by visitor ". $displayIp);
}

// optimize database tables
if (($jobs['database'][2] != 0 && $jobs['database'][1] <= $now) || $runjob == 'database') {
	$update = 1;
	$displayIp = ($runjob == 'database') ? 'Admin' : $currentIp->ipCheck();
	$result	= yasDB_select("SHOW TABLE STATUS");
	while($row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$optimise_sql = "OPTIMIZE TABLE {$row['Name']}";
		$optimise_result = yasDB_select($optimise_sql);
	}
	if ($jobs['database'][0] == 7) {
		$jobs['database'][1] = $plus_week;
	}
	else if ($jobs['database'][0] == 30) {
		$jobs['database'][1] = $plus_month;
	}
	else {
		$jobs['database'][1] = $midnight_today;
	}
	doLog("Database optimized triggered by visitor ". $displayIp);
}
// Create database backup
if (($jobs['backup'][2] != 0 && $jobs['backup'][1] <= $now) || $runjob == 'backup') {
	$update = 1;
	$displayIp = ($runjob == 'backup') ? 'Admin' : $currentIp->ipCheck();
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
	$backup = db_backup();
	if ($jobs['backup'][0] == 7) {
		$jobs['backup'][1] = $plus_week;
	}
	else if ($jobs['backup'][0] == 30) {
		$jobs['backup'][1] = $plus_month;
	}
	else {
		$jobs['backup'][1] = $midnight_today;
	}
	$message = ($backup === true)?'successful':'failed';
	doLog("Database update ".$message." - triggered by visitor ". $displayIp);
}
if ($update == 1) {
	$new_jobs = serialize($jobs);
	yasDB_update("UPDATE `settings` SET `jobs` = '$new_jobs' WHERE `id` = 1");
	include($setting['sitepath']."/includes/settings_function.inc.php");
	createConfigFile();
}
$_SESSION['runjobs'] = 'no-go';
?>