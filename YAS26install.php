<?php
#--------------------------------
# http://www.yourarcadescript.com
#       YAS26install.php
#		VERSION 2.6
#		CC BY-ND 3.0 Licensed(http://creativecommons.org/licenses/by-nd/3.0/)
#       
#--------------------------------

if (file_exists("includes/db_functions.inc.php") || file_exists("includes/config.inc.php")) {
	echo "Installation created files detected. Website is already installed";
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style>
    *
    {
		margin:0px; padding:0px;
    }
    body
    {
		color: #fff; background-color: #284062; background-image: url(images/installlogo.png);
		background-repeat: no-repeat; background-position: top;
		text-align: center; padding: 0 0 0 0; margin: 0 0 0 0;   font-weight: normal; font-size: 12px;
		font-family: Tahoma, Arial, sans-serif;
    }
    img
    {
		border: none;
    }
    .clear
    {
		clear:both;
    }
    #body_wrapper
    {
		margin: 0 auto;   width: 951px; text-align: left;
    }
    #top
    {
		color: #000000;   background: transparent;
		width: 950px; height: 124px; text-align: left; position: relative;
    }
    #menu
    {
		color: #ffffff;   background:transparent url(images/installmenuimage.png);
		background-repeat: repeat-x; background-position: left;
		width: 950px; line-height: 16px; padding-top: 8px; padding-bottom: 8px; text-align: center;
    }
    #wrapper
    {
		width: 934px; color: #fff; background-color: #00132F;
		padding-top: 8px; padding-bottom: 8px; padding-left: 10px; border:solid 3px #245A7A; text-align: center;
	}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Install YourArcadeScript Version 2.6</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script type="text/javascript">		
	function changeSkins(templateUrl) {
		$.get("<?php echo $setting['siteurl'].'includes/updateskins.php';?>", { s: templateUrl }, function(data) {
			$('#skin').html(data);
		});
	}
</script>
</head>
<body>
<div id="body_wrapper">
	<div id="top"></div>
    <div id="menu">Install Settings</div>
    <div id="wrapper">
    <?php
    
function PageURL() {
	$pageURL = 'http';

	if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
		$pageURL .= "s";
	}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	$new = substr(strrchr($pageURL, '/'), 1);
	$strlen_str = strlen($new);
	$url = substr($pageURL, 0, -$strlen_str);
	return $url;
}

	if(isset($_POST['dbcheck'])){
		$mysqli = mysqli_init();
		if (!$mysqli->real_connect($_POST['host'], $_POST['username'], $_POST['password'], $_POST['database'])) {
			echo '<font color="red">Unable to connect to the MySQL server with the given details. Please edit and try again.</font><br/>';
		}		
		else {
			$_POST['form'] = 'set';
			?>			
			<form action="YAS26install.php" method="post">
		Site Name:<br/><input type="text" name="sitename" size="30" value=''><br/>
		Site Url:<br/><input type="text" name="siteurl" size="30" value="<?php echo PageURL();?>"><br/>
		Site Path:<br/><input type="text" name="sitepath" size="30" value="<?php echo str_replace('\\', '/', realpath(dirname(__FILE__)));?>"><br/>
		SEO On or OFF:<br/><select type="dropdown" name="seo"><option value="yes">On</option><option value="no">Off</option></select><br/>
		Theme:<br/>    
		<select name="theme" onchange="changeSkins('<?php echo str_replace('\\', '/', realpath(dirname(__FILE__))).'/templates/';?>' + this.options[this.selectedIndex].value + '<?php echo '/skins/';?>')">
		<?php
		if (!is_dir("templates")) {
			echo "Templates folder not found. Please upload all files and folders before installing.";
			echo'</div>
				</div>
				</body>
				</html>';
			exit;
		}
		$files = scandir("templates/");
		?><option value="minix_26" selected="yes">minix_26</option><?php
		foreach ($files as &$file) {
			if ($file!='.' && $file!='..' && substr($file, -3) == '_25' || substr($file, -3) == '_26')	{
				?>				
				<option value ="<?php echo $file;?>"><?php echo $file;?></option>
				<?php
			}
		}?>
		</select><br/>
		Skin:<br/>
		<select name="skin" id="skin">
		<?php
		$skins = scandir(str_replace('\\', '/', realpath(dirname(__FILE__))).'/templates/minix_26/skins/');
		$n = 0;
		foreach ($skins as &$skin) {
			if ($skin!='.' && $skin!='..' ) {
				if ($n==0) {
					echo '<option value="'.$skin.'" selected="yes">'.$skin.'</option>';
					$n++;
				} else {
				echo '<option value="'.$skin.'">'.$skin.'</option>';
				$n++;
				}						
			}
		}
		?>
		</select><br/>
		Contact form email:<br/><input type="text" name="supportemail" size="30"><br/>
		Slogan:<br/><input type="text" name="slogan" size="30"><br/>
		Site Meta data:<br/><input type="text" name="metades" size="30"><br/>
		Site Keywords:<br/><input type="text" name="keywords" size="30"><br/>
		<br/>
		Social App IDs for Facebook and Twitter logins (*)<br/>
		Facebook App ID:<br/><input type="text" name="fbAppId" size="30"><br/>
		Facebook App Secret:<br/><input type="text" name="fbAppSecret" size="30"><br/>
		Twitter Consumer key:<br/><input type="text" name="twAppId" size="30"><br/>
		Twitter Consumer secret:<br/><input type="text" name="twAppSecret" size="30"><br/><br/>
		<input type="submit" name="config" value="Install"><br/><br/>
		* Social App IDS can be entered in the Admin Cpanel at any time (optional)
		<input type="hidden" name="host" value="<?php echo $_POST['host'];?>"/>
		<input type="hidden" name="username" value="<?php echo $_POST['username'];?>"/>
		<input type="hidden" name="password" value="<?php echo $_POST['password'];?>"/>
		<input type="hidden" name="database" value="<?php echo $_POST['database'];?>"/>
		</form><?php
		}
		$mysqli->close();
	}
	if(isset($_POST['config'])){
		/*  Create the database tables and insert default and user data */    		
		$mysqli = new mysqli($_POST['host'], $_POST['username'], $_POST['password'], $_POST['database']);
		foreach($_POST as $pk => $pv) $_POST[$pk] = $mysqli->real_escape_string($pv);  //clean POST array for database insert
		if (!is_dir("includes")) {
			echo "Includes folder not found. Please upload all files and folders before installing.";
			echo'</div>
				</div>
				</body>
				</html>';
			exit;
		}
		$folders = array('includes', 'swf', 'img', 'media/files', 'media/img', 'avatars', 'avatars/useruploads', 'cache', 'cache/img', 'admin/backup');
		$mode = 755;
		foreach($folders as $folder) {
			@chmod($folder, octdec($mode));
			if (!is_writeable($folder)) {
				if ($folder == 'includes') {
					echo 'The "includes" folder is not writeable. The script has attempted to change the folder permissions but was unsuccessful.
					The install cannot continue. Please maually chmod the following folders to \'755\' and try the installation again.<br/>
					includes<br/>
					swf<br/> 
					img<br/> 
					media/files<br/>
					media/img<br/>
					avatars<br/>
					avatars/useruploads<br/>
					cache
					cache/img';
					exit;
				} else {
					echo $folder . " folder is not writeable, please chmod to 755 or greater after installation. Ask in the forums if you need assistance.<br/>";
				}
			}
		}	
	   $file = "includes/db_functions.inc.php";
	   $h = fopen($file, 'w');
	   $data = '<?php
define("DB_HOST", "'.$_POST['host'].'");
define("DB_DATABASE", "'.$_POST['database'].'");
define("DB_USERNAME", "'.$_POST['username'].'");
define("DB_PASSWORD","'.$_POST['password'].'");
function errorLog($text) {
    $filename = "dberrors_log.txt";
    $fh = fopen($filename, "a");
    fwrite($fh, "\n".date("m-d-Y, H:i")."\n\t$text");
    fclose($fh);
}
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

function yasDB_insert($sql, $escape = false) {
    global $mysqli;
    if ($escape) {
        $sql = $mysqli->real_escape_string($sql);
    }
    $result = $mysqli->query($sql);
    if ($mysqli->error) {
		try {   
			throw new Exception("MySQL error $mysqli->error \n\tQuery: \"$sql\"", $msqli->errno);   
		} catch(Exception $e ) {
			$text =  "Error No: ".$e->getCode(). " - ". $e->getMessage() . "\n";
			$text .= "\t".str_replace("\n", "\n\t",$e->getTraceAsString());
			errorLog($text);
			return false;
		}
	}
	return $result;
}
function yasDB_select($sql, $escape = false) {
    global $mysqli;
    if ($escape) {
        $sql = $mysqli->real_escape_string($sql);
    }
    $result = $mysqli->query($sql);
    if ($mysqli->error) {
		try {   
			throw new Exception("MySQL error $mysqli->error \n\tQuery: \"$sql\"", $msqli->errno);   
		} catch(Exception $e ) {
			$text =  "Error No: ".$e->getCode(). " - ". $e->getMessage() . "\n";
			$text .= "\t".str_replace("\n", "\n\t",$e->getTraceAsString());
			errorLog($text);
			return false;
		}
	}
	return $result;
}
function yasDB_update($sql, $escape = false) {
    global $mysqli;
    if ($escape) {
        $sql = stripslashes($sql);
        $sql = $mysqli->real_escape_string($sql);
    }
    $result = $mysqli->query($sql);
    if ($mysqli->error) {
		try {   
			throw new Exception("MySQL error $mysqli->error \n\tQuery: \"$sql\"", $msqli->errno);   
		} catch(Exception $e ) {
			$text =  "Error No: ".$e->getCode(). " - ". $e->getMessage() . "\n";
			$text .= "\t".str_replace("\n", "\n\t",$e->getTraceAsString());
			errorLog($text);
			return false;
		}
	}
	return $result;
}
function yasDB_delete($sql, $escape = false) {
    global $mysqli;
    if ($escape) {
        $sql = $mysqli->real_escape_string($sql);
    }
    $result = $mysqli->query($sql);
    if ($mysqli->error) {
		try {   
			throw new Exception("MySQL error $mysqli->error \n\tQuery: \"$sql\"", $msqli->errno);   
		} catch(Exception $e ) {
			$text =  "Error No: ".$e->getCode(). " - ". $e->getMessage() . "\n";
			$text .= "\t".str_replace("\n", "\n\t",$e->getTraceAsString());
			errorLog($text);
			return false;
		}
	}
	return $result;
}
function yasDB_clean($dirty, $encode_ent = false, $strip_tags = true) {
    global $mysqli;
    $dirty = @trim($dirty);
	if ($strip_tags)
		$dirty = strip_tags($dirty);
	$dirty = htmlspecialchars($dirty);
    if ($encode_ent) {
        $dirty = htmlentities($dirty);
    }
    if(version_compare(phpversion(),\'4.3.0\') >= 0) {
        if(get_magic_quotes_gpc()) {
            $dirty = stripslashes($dirty);
        }
        $clean = $mysqli->real_escape_string($dirty);
    }
    else {
        if(!get_magic_quotes_gpc()) {
            $clean = addslashes($dirty);
        }
    }
    return $clean;
}
?>';
	   fwrite($h,$data);
	   fclose($h);
	   /*  Create the database tables and insert default and user data */    		
		
		$mysqli->query("CREATE TABLE IF NOT EXISTS `ads` (
		  `id` int(10) NOT NULL AUTO_INCREMENT,
		  `name` varchar(255) NOT NULL DEFAULT '',
		  `code` text COLLATE utf8_unicode_ci NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  CHARACTER SET = utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=12 ;");

		$mysqli->query("INSERT INTO `ads` (`id`, `name`, `code`) VALUES
		(1, 'Header 468x60', 'Put AD code here'),
		(2, 'Right Column 160x600 (or 120x600)', 'Put AD code here'),
		(3, 'Game 300x250', 'Put AD code here'),
		(4, 'Game 468x60', 'Put AD code here'),
		(5, 'Float Ad Left 120x600', 'Put AD code here'),
		(6, 'Float Ad Right 120x600', 'Put AD code here'),
		(7, 'Left Column 160x600 (or 120x600)', 'Put AD code here'),
		(8, 'Banner Exchange 1 100x100', 'Put AD code here'),
		(9, 'Banner Exchange 2 100x100', 'Put AD code here'),
		(10, 'Banner Exchange 3 100x100', 'Put AD code here'),
		(11, 'Google Analytics', '');");

        $mysqli->query("CREATE TABLE IF NOT EXISTS `avatars` (
		`userid` int(11) NOT NULL,
		`avatar` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		KEY `userid` (`userid`,`avatar`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");         		

		$mysqli->query("CREATE TABLE IF NOT EXISTS `categories` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
		  `active` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
		  `order` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
		  `parent` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
		  `home` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
		  `desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
		  `pid` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  CHARACTER SET = utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=11 ;");

		$mysqli->query("INSERT INTO `categories` (`id`, `name`, `active`, `order`, `parent`, `home`, `desc`, `pid`) VALUES
		(1, 'Puzzle', 'yes', '1', 'yes', 'yes', '', ''),
		(2, 'Action', 'yes', '2', 'yes', 'yes', '', ''),
		(3, 'Adventure', 'yes', '3', 'yes', 'yes', '', ''),
		(4, 'Sports', 'yes', '4', 'yes', 'yes', '', ''),
		(5, 'Shooter', 'yes', '5', 'yes', 'yes', '', ''),
		(6, 'Casino', 'yes', '6', 'yes', 'yes', '', ''),
		(7, 'Other', 'yes', '7', 'yes', 'yes', '', ''),
		(8, 'Dressup', 'yes', '8', 'yes', 'yes', '', ''),
		(9, 'Arcade', 'yes', '9', 'yes', 'yes', '', ''),
		(10, 'Strategy', 'yes', '10', 'yes', 'yes', '', '');");

		$mysqli->query("CREATE TABLE IF NOT EXISTS `comments` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `gameid` int(11) NOT NULL DEFAULT '0',
		  `ipaddress` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
		  `comment` text COLLATE utf8_unicode_ci NOT NULL,
		  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM CHARACTER SET = utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1 ;");
		
		$mysqli->query("CREATE TABLE IF NOT EXISTS `contact` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
		  `email` varchar(100) COLLATE utf8_bin DEFAULT NULL,
		  `message` text COLLATE utf8_bin,
		  `created_date` int(11) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");
		
		$mysqli->query("CREATE TABLE IF NOT EXISTS `tellafriend` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
        `friendsname` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
        `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
        `friendsemail` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
        `message` text CHARACTER SET utf8 COLLATE utf8_bin,
        `created_date` int(11) DEFAULT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;");		
		
		$mysqli->query("CREATE TABLE IF NOT EXISTS `downgames` (
		`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		`title` text NOT NULL,
		`description` text NOT NULL,
		`thumbnail` text NOT NULL,
		`file` text NOT NULL,
		`downloadtimes` int(10) NOT NULL,
		PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1");

		$mysqli->query("CREATE TABLE IF NOT EXISTS `memberscomments` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `userid` int(11) NOT NULL,
        `ipaddress` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
        `comment` text COLLATE utf8_unicode_ci NOT NULL,
        `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
        PRIMARY KEY (`id`),
        KEY `name` (`name`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;");
		
		$mysqli->query("CREATE TABLE IF NOT EXISTS `featuredgames` (
		`gameid` int(11) unsigned NOT NULL,
		`add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`gameid`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
		
		$mysqli->query("CREATE TABLE IF NOT EXISTS `fgdfeed` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `uuid` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
		  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `description` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
		  `tags` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `gamefile` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `thumbfile` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `width` int(11) unsigned NOT NULL,
		  `height` int(11) unsigned NOT NULL,
		  `categories` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `mature` tinyint(3) unsigned NOT NULL DEFAULT '0',
		  `hasads` tinyint(3) unsigned NOT NULL DEFAULT '0',
		  `multiplayer` tinyint(3) unsigned NOT NULL DEFAULT '0',
		  `featured` tinyint(3) unsigned NOT NULL DEFAULT '0',
		  `highscores` tinyint(3) unsigned NOT NULL DEFAULT '0',
		  `mobileready` tinyint(3) unsigned NOT NULL DEFAULT '0',
		  `installed` tinyint(3) unsigned NOT NULL DEFAULT '0',
		  `hidden` tinyint(1) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`id`),
		  KEY `uuid` (`uuid`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=28824 ;");
		
		$mysqli->query("CREATE TABLE IF NOT EXISTS `fogfeed` (
		  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `uid` int(11) unsigned NOT NULL,
		  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `controls` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `updated` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
		  `game_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `category` int(11) NOT NULL DEFAULT '7',
		  `small_thumbnail_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `med_thumbnail_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `large_thumbnail_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `created` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
		  `swf_file` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `width` int(11) NOT NULL,
		  `height` int(11) NOT NULL,
		  `installed` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
		  `hidden` tinyint(1) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`id`),
		  KEY `uid` (`uid`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=496 ;");

		$mysqli->query("CREATE TABLE IF NOT EXISTS `forumcats` (
	        `id` int(11) NOT NULL AUTO_INCREMENT,
	        `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
	        `active` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
	        `order` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
	        `parent` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
	        `home` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
	        `desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
	        `pid` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
	        PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7;");
		
		$mysqli->query("INSERT INTO `forumcats` (`id`, `name`, `active`, `order`, `parent`, `home`, `desc`, `pid`) VALUES
		(1, 'Announcements', 'yes', '1', 'yes', 'yes', 'New things we may add to site or site is disabled we will let all know', ''),
		(2, 'Support', 'yes', '2', 'yes', 'yes', 'Need help ? ask and we will get back to you asap.', ''),
		(3, 'General', 'yes', '3', 'yes', 'yes', 'General chat', ''),
		(4, 'Known Issues', 'yes', '4', 'yes', 'yes', 'Known issues about our site we will post here.', ''),
		(5, 'Link exchange', 'yes', '5', 'yes', 'yes', 'Exchange links with our site to earn more traffic!', ''),
		(6, 'Games', 'yes', '6', 'yes', 'yes', 'Chat about the best flash games you have ever played', '');");
		
		$mysqli->query("CREATE TABLE IF NOT EXISTS `forumposts` (
        `id` int(8) NOT NULL AUTO_INCREMENT,
        `text` text COLLATE utf8_unicode_ci NOT NULL,
        `date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `topic` int(8) NOT NULL,
        `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");
		
		$mysqli->query("CREATE TABLE IF NOT EXISTS `forumtopics` (
        `id` int(8) NOT NULL AUTO_INCREMENT,
        `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `cat` int(8) NOT NULL,
        `date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
        `text` text COLLATE utf8_unicode_ci NOT NULL,
        `views` int(11) NOT NULL DEFAULT '0',
        `lastupdate` int(11) unsigned NOT NULL,
        PRIMARY KEY (`id`),
        KEY `cat` (`cat`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;");

		$mysqli->query("CREATE TABLE IF NOT EXISTS `favourite` (
		`userid` int(10) NOT NULL DEFAULT '0',
		`gameid` int(11) NOT NULL DEFAULT '0',
		KEY `userid` (`userid`),
		KEY `gameid` (`gameid`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1;");
		
		$mysqli->query("CREATE TABLE IF NOT EXISTS `gameque` (
        `source` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
        `sourceid` int(11) NOT NULL,
        `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `thumb` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `order` int(11) unsigned NOT NULL DEFAULT '0',
        KEY `source` (`source`),
        KEY `sourceid` (`sourceid`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

		$mysqli->query("CREATE TABLE IF NOT EXISTS `games` (
		  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		  `title` text COLLATE utf8_unicode_ci NOT NULL,
		  `description` text COLLATE utf8_unicode_ci NOT NULL,
		  `instructions` text COLLATE utf8_unicode_ci NOT NULL,
		  `keywords` text COLLATE utf8_unicode_ci NOT NULL,
		  `file` text COLLATE utf8_unicode_ci NOT NULL,
		  `height` int(11) NOT NULL DEFAULT '0',
		  `width` int(11) NOT NULL DEFAULT '0',
		  `category` int(11) NOT NULL DEFAULT '0',
		  `plays` int(11) NOT NULL DEFAULT '0',
		  `code` text COLLATE utf8_unicode_ci NOT NULL,
		  `type` enum('IMAGE','MOV','MPG','AVI','FLV','WMV','SWF','DCR','UNITY','YOUTUBE','CustomCode') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'SWF',
		  `source` enum('OTHER','FGD','FOG','KONGREGATE','AGF','MGF') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'OTHER',
		  `sourceid` int(11) unsigned NOT NULL,
		  `thumbnail` text COLLATE utf8_unicode_ci NOT NULL,
		  `active` tinyint(4) NOT NULL DEFAULT '1',
		  PRIMARY KEY (`id`),
		  KEY `source` (`source`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12;");

		$mysqli->query("CREATE TABLE IF NOT EXISTS `kongregate` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `kong_id` int(11) NOT NULL,
        `title` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
        `file` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
        `thumbnail` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
        `width` int(11) NOT NULL,
        `height` int(11) NOT NULL,
        `category` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
        `description` text COLLATE utf8_unicode_ci NOT NULL,
        `rating` float NOT NULL,
        `developer` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
        `installed` tinyint(4) NOT NULL DEFAULT '0',
        `hidden` tinyint(1) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=436 ;");
		
		$mysqli->query("CREATE TABLE IF NOT EXISTS `links` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
        `text` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
        `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
        `in` int(11) NOT NULL DEFAULT '0',
        `out` int(11) NOT NULL DEFAULT '0',
        `approved` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
        `reciprocal` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5");

		$mysqli->query("INSERT INTO `links` (`id`, `url`, `text`, `description`, `in`, `out`, `approved`, `reciprocal`, `email`) VALUES
		(1, 'http://www.arcadehangout.com', 'Cool Arcade Games', 'Cool Free Arcade Games!', 0, 0, 'yes', '', 'admin@arcadehangout.com'),
		(2, 'http://www.games-flash.co.uk/', 'Games-Flash', 'Play Free Online Games!', 0, 0, 'yes', '', 'admin@games-flash.co.uk'),
		(3, 'http://www.jasminrocks.com/', 'Jasminrocks', 'Girls Play Bloons Tower Defense 4!', 0, 0, 'yes', '', 'admin@arcadehangout.com'),
		(4, 'http://www.flashpilot.net/', 'Flashpilot', 'Play free online games', 0, 0, 'yes', '', '');");

		$mysqli->query("CREATE TABLE IF NOT EXISTS `membersonline` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `memberid` int(11) NOT NULL,
        `timeactive` int(11) NOT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `memberid` (`memberid`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6;");

		$mysqli->query("CREATE TABLE IF NOT EXISTS `mgffeed` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `uid` int(11) unsigned NOT NULL,
        `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `controls` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `instructions` text COLLATE utf8_unicode_ci NOT NULL,
        `description` text COLLATE utf8_unicode_ci NOT NULL,
        `category` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `thumbnail` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `medthumb` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `lgthumb` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `installdate` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
        `file` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `zip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `game_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `screen1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `screen2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `review` text COLLATE utf8_unicode_ci NOT NULL,
        `width` int(11) NOT NULL,
        `height` int(11) NOT NULL,
        `rating` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
        `ads` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
        `hsapi` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
        `keywords` text COLLATE utf8_unicode_ci NOT NULL,
        `installed` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
        `hidden` tinyint(1) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`),
        KEY `uid` (`uid`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;");

		$mysqli->query("CREATE TABLE IF NOT EXISTS `news` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `news_text` text COLLATE utf8_unicode_ci NOT NULL,
        `edit` int(11) DEFAULT '0',
        `date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `topic` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2;");

		$mysqli->query("INSERT INTO `news` (`id`, `news_text`, `edit`, `date`, `topic`) VALUES (1, 'We are excited to bring you the best in browser Flash games!', 0, '".date("D, j F Y")."','Welcome to ".$_POST['sitename']."')");
		
		$mysqli->query("CREATE TABLE IF NOT EXISTS `newsblog` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
        `newsid` int(11) unsigned NOT NULL,
        `ipaddress` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
        `comment` text COLLATE utf8_unicode_ci NOT NULL,
        `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `username` (`username`),
        KEY `newsid` (`newsid`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;");
		
		$mysqli->query("CREATE TABLE IF NOT EXISTS `notifydown` (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `email` text,
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
		
		$mysqli->query("CREATE TABLE IF NOT EXISTS `agffeed` (
		  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `uid` int(11) unsigned NOT NULL,
		  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `controls` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `instructions` text COLLATE utf8_unicode_ci NOT NULL,
		  `description` text COLLATE utf8_unicode_ci NOT NULL,
		  `category` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `thumbnail` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `medthumb` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `lgthumb` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `installdate` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
		  `file` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `zip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `game_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `screen1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `screen2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `review` text COLLATE utf8_unicode_ci NOT NULL,
		  `width` int(11) NOT NULL,
		  `height` int(11) NOT NULL,
		  `rating` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
		  `ads` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
		  `hsapi` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
		  `keywords` text COLLATE utf8_unicode_ci NOT NULL,
		  `installed` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
		  `hidden` tinyint(1) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`id`),
		  KEY `uid` (`uid`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1747 ;");
		
		$mysqli->query("CREATE TABLE IF NOT EXISTS `ratingsbar` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `total_votes` int(11) NOT NULL DEFAULT '0',
        `total_value` int(11) NOT NULL DEFAULT '0',
        `used_ips` longtext,
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;");

        $mysqli->query("CREATE TABLE IF NOT EXISTS `settings` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `main` int(11) NOT NULL DEFAULT '1',
        `gperpage` int(11) NOT NULL DEFAULT '15',
        `numbgames` int(11) NOT NULL DEFAULT '0',
        `gamesort` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'newest',
        `seolink` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
        `seo` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
        `approvelinks` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
        `numblinks` int(11) NOT NULL DEFAULT '10',
        `version` varchar(12) COLLATE utf8_unicode_ci NOT NULL DEFAULT '2.6',
        `theme` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT 'minix_26',
        `skin` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'arcadegames',
        `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
        `userecaptcha` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
        `lightbox` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
        `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `disabled` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
        `regclosed` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
        `fb_app_id` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
        `fb_app_secret` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
        `tw_app_id` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
        `tw_app_secret` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
        `cachelife` int(11) NOT NULL DEFAULT '60',
        `siteurl` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `sitepath` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `sitename` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
        `slogan` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `metades` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `metakeywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `jobs` text COLLATE utf8_unicode_ci NOT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;");
        
		$midnight_today = mktime(23,59,59);
		$plus_week = strtotime('+7 days', $midnight_today);
        $plus_month = strtotime('+30 days', $midnight_today);
		$jobs = array();
		$jobs['jobstate'] = 1;
		$jobs['dayplays'][0] = 1;
		$jobs['dayplays'][1] = $midnight_today;
		$jobs['dayplays'][2] = 1;
		$jobs['feeds'][0] = 1;
		$jobs['feeds'][1] = $midnight_today;
		$jobs['feeds'][2] = 1;
		$jobs['database'][0] = 7;
		$jobs['database'][1] = $plus_week;
		$jobs['database'][2] = 1;
		$jobs['gameque'][0] = 1;
		$jobs['gameque'][1] = $midnight_today;
		$jobs['gameque'][2] = 1;
		$jobs['gameque'][3] = 2;
		$jobs['sitemap'][0] = 1;
		$jobs['sitemap'][1] = $midnight_today;
		$jobs['sitemap'][2] = 1;
		$jobs['backup'][0] = 30;
		$jobs['backup'][1] = $plus_month;
		$jobs['backup'][2] = 1;
		$jobstring = $mysqli->real_escape_string(serialize($jobs));
				
		$mysqli->query("INSERT INTO `settings` (`id`, `main`, `gperpage`, `numbgames`, `gamesort`, `seolink`, `seo`, `approvelinks`, `numblinks`, `version`, `theme`, `skin`, `password`, `userecaptcha`, `lightbox`, `email`, `disabled`, `regclosed`, `fb_app_id`, `fb_app_secret`, `tw_app_id`, `tw_app_secret`, `cachelife`, `siteurl`, `sitepath`, `sitename`, `slogan`, `metades`, `metakeywords`, `jobs`) VALUES
        (1, 1, 15, 3, 'newest', 'yes', 'no', 'no', 10, '2.6', '".$mysqli->real_escape_string($_POST['theme'])."', '".$mysqli->real_escape_string($_POST['skin'])."', '".md5('admin')."', 'yes', 'no', '".$mysqli->real_escape_string($_POST['supportemail'])."', 'no', 'no', '".$mysqli->real_escape_string($_POST['fbAppId'])."', '".$mysqli->real_escape_string($_POST['fbAppSecret'])."', '".$mysqli->real_escape_string($_POST['twAppId'])."', '".$mysqli->real_escape_string($_POST['twAppSecret'])."', 60, '".$mysqli->real_escape_string($_POST['siteurl'])."', '".$mysqli->real_escape_string($_POST['sitepath'])."', '".$mysqli->real_escape_string($_POST['sitename'])."', '".$mysqli->real_escape_string($_POST['slogan'])."', '".$mysqli->real_escape_string($_POST['metades'])."', '".$mysqli->real_escape_string($_POST['keywords'])."', '$jobstring')");

		$mysqli->query("CREATE TABLE IF NOT EXISTS `stats` (
        `id` int(10) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL DEFAULT '',
        `numbers` int(10) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5;");

		$mysqli->query("INSERT INTO `stats` (`id`, `name`, `numbers`) VALUES
		(1, 'totalplays', 0),
		(2, 'dayplays', 0),
		(3, 'dayposts', 0),
		(4, 'totalposts', 0);");

		$mysqli->query("CREATE TABLE IF NOT EXISTS `user` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `username` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
        `password` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
        `repeatpassword` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
        `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
        `useavatar` tinyint(1) NOT NULL DEFAULT '0',
        `avatarfile` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
        `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
        `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
        `plays` int(11) NOT NULL DEFAULT '0',
        `date` int(10) NOT NULL DEFAULT '0',
        `location` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
        `aboutme` text COLLATE utf8_unicode_ci NOT NULL,
        -- `job` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
        `points` int(11) NOT NULL DEFAULT '0',
        `endban` int(10) DEFAULT '0',
        `oauth_uid` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
        `oauth_provider` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
        `twitter_oauth_token` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
        `twitter_oauth_token_secret` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
        `randomkey` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
        `activated` tinyint(1) NOT NULL,
        --`gender` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
        --`birthday` int(11) DEFAULT NULL,
        `hobbies` text COLLATE utf8_unicode_ci NOT NULL,
        `posts` int(11) NOT NULL DEFAULT '0',
        `topics` int(11) NOT NULL DEFAULT '0',
        `totalposts` int(11) NOT NULL DEFAULT '0',
        `shloc` char(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'show',
        `sheml` char(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'show',
        `shname` char(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'show',
        `shhobs` char(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'show',
        `shabout` char(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'show',
        `deact` char(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'show',
        `cmtsdisabled` char(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'show',
        `passreset` int(11) NOT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2;");

		$mysqli->query("CREATE TABLE IF NOT EXISTS `useronline` (
        `id` int(10) NOT NULL AUTO_INCREMENT,
        `ip` varchar(15) NOT NULL DEFAULT '',
        `timestamp` varchar(15) NOT NULL DEFAULT '',
        `agent` varchar(256) NOT NULL DEFAULT 'human',
        PRIMARY KEY (`id`),
        UNIQUE KEY `id` (`id`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=126;");
$data = '';
$name = array();
$value = array();
$type = array();
$setting = array();
$result = $mysqli->query("SELECT * FROM settings WHERE id = 1");
while ($meta = $result->fetch_field()) { 
	$name[] = $meta->name;
	$type[] = $meta->type;
}
$i = 0;
while ($row = $result->fetch_row()) { 
	$count = count($row);
	$y = 0;
	while ($y < $count) {
		$value[] = current($row);
		next($row);
		$y++;
	}
	$i++;
}
$result->free_result();
for ($i=0;$i<count($name);$i++) {
	$setting[$name[$i]][0] = $value[$i];
	$setting[$name[$i]][1] = $type[$i];
}
ksort($setting);
$keys = array_keys($setting);
$values = array_values($setting);
$data = "<?php"."\n";
$data .= "#--------------------------------
# http://www.yourarcadescript.com
#       VERSION 2.6
#		config.inc.php
#		CC BY-ND 3.0 Licensed(http://creativecommons.org/licenses/by-nd/3.0/)
#       
#       Do not manually edit
#       Use Admin Cpanel
#--------------------------------"."\n";
for ($i=0;$i<count($setting);$i++) {
	if ($keys[$i] != 'id') { 
		if ($values[$i][1] == 3) {
			$data .= "\$setting['".$keys[$i]."'] = ".$values[$i][0].";" . "\n";
		} else {
			$data .= "\$setting['".$keys[$i]."'] = '".addslashes($values[$i][0])."';" . "\n";
		}
	}
}
$data .=  "if (substr(\$setting['siteurl'], -1) != '/') \$setting['siteurl'] .= '/';\n?>";
unset($name);
unset($setting);
unset($value);
$file = 'includes/config.inc.php';
$h = fopen($file, 'w');
fwrite($h,$data);
fclose($h);
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, "http://www.yourarcadescript.com/handshake.php");
curl_setopt($ch,CURLOPT_POST, 3);
curl_setopt($ch,CURLOPT_POSTFIELDS, 'or=' . urlencode(PageURL()) . '&fo=install&ver=2.6');
$result = curl_exec($ch);
curl_close($ch);	   
echo "<br/>Site data set. YAS26install.php must be renamed or deleted before you can use your site.<br/><br/>";
echo '</div>
	</div>
	</body>
	</html>';
exit;
    }
    if(!isset($_POST['form'])){
		if (!is_dir("includes") || !is_dir("templates") || !is_dir("admin")) {
			echo "Script folders are missing. Please upload all files and folders before installing.";
			echo'</div>
				</div>
				</body>
				</html>';
			exit;
		}
		?>
		Please provide your MySQL database information.<br/><br/>
		<form action="YAS26install.php" method="post">
		Host:<br/><input type="text" name="host" size="30" value="localhost"><br/>		
		Database:<br/><input type="text" name="database" size="30" value=''><br/>
		Username:<br/><input type="text" name="username" size="30" value=''><br/>
		Password:<br/><input type="text" name="password" size="30" value=''><br/>
		<br/><input type="submit" name="dbcheck" value="Next">
		</form>
		<?php
	}
	?>
	</div>
</div>
</body>
</html>