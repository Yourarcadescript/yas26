<?php
#--------------------------------
# http://www.yourarcadescript.com
#       index.php
#		VERSION 2.4
#		CC BY-ND 3.0(http://creativecommons.org/licenses/by-nd/3.0/) Liscensed
#       
#--------------------------------
session_start();
if(isset($_GET['id']) && !is_numeric($_GET['id'])) {
	echo 'This is an invalid URL';
	exit;
}
if(isset($_POST['id']) && !is_numeric($_POST['id'])) {
	echo 'This is an invalid URL';
	exit;
}
//$_SESSION['admin'] = "logged"; //add this line to bypass login, reset your password, then remove the line
if (isset($_SESSION['admin']) && $_SESSION['admin'] == "logged") {
	include ("../includes/db_functions.inc.php");
	include ("../includes/config.inc.php");
	if (substr($setting['siteurl'], -1) != '/') $setting['siteurl'] .= '/';
	if(!isset($_GET['act'])) $_GET['act'] = '';
	if ($_GET['act'] != "mochiall" && $_GET['act'] != "logout")	include ("header.php");
	switch($_GET['act']) {
		case 'manage_users':
			include ("manage_users.php");
			break;
		case 'managegames':
			include ("manage_game.php");
			break;
		case 'managegamequeue':
			include ("managegamequeue.php");
			break;
		case 'uploadgames':
			include ("add_game.php");
			break;
		case 'brokenfiles':
			include ("broken_files.php");
			break;
		case 'cache':
			include ("cache.php");
			break;
		case 'managemochi':
			include ("manage_mochigames.php");
			break;
		case 'manageagffeed':
			include ("manageagffeed.php");
			break;
		case 'managemgffeed':
			include ("managemgffeed.php");
			break;
		case 'managefgdfeed':
			include ("managefgdfeed.php");
			break;
		case 'managefogfeed':
			include ("managefogfeed.php");
			break;
		case 'manageplaytomic':
			include ("manageplaytomic.php");
			break;
		case 'managekong':
			include ("manage_kongregate.php");
			break;
		case 'managevasco':
			include ("manage_vascogames.php");
			break;
		case 'mochifeed':
			include ("mochi_feed.php");
			break;
		case 'memberscomment':
			include ("manage_memberscomment.php");
			break;
		case 'newsblogcomments':
			include ("newsblogcomments.php");
			break;
		case 'newsblogfeed':
			include ("newsblogfeed.php");
			break;
		case 'mochiid':
			include ("id_mochigames.php");
			break;
		case 'mochiall':
			include ("mochi_all.php");
			break;
		case 'addmedia':
			include ("add_media.php");
			break;
		case 'addcode':
			include ("add_code.php");
			break;
		case 'categories':
			include ("manage_category.php");
			break;
		case 'sitemap':
			include ("sitemap.php");
			break;
		case 'rssfeed':
			include ("rssfeed.php");
			break;
		case 'links':
			include ("manage_links.php");
			break;
		case 'antispam':
			include ("antispam.php");
			break;
		case 'addads':
			include ("add_ads.php");
			break;
		case 'addlink':
			include ("add_link.php");
			break;
		case 'news':
			include ("add_news.php");
			break;
		case 'ads':
			include ("manage_ads.php");
			break;
		case 'comments':
			include ("manage_comment.php");
			break;
		case 'logout':
			include ("logout.php");
			break;
		case 'settings':
			include ("settings.php");
			break;
		case 'socialids':
			include ("socialids.php");
			break;
		case 'managedowngame':
			include ("manage_down_game.php");
			break;
		case 'adddownloadgame':
			include ("add_down_game.php");
			break;	
		case 'managejobs':
			include ("manage_jobs.php");
			break;
		case 'manageforumcats':
            include ("manage_forumcats.php");
            break;
        case 'manageposts':
            include ("manage_posts.php");
            break;
        case 'managetopics':
		    include ("manage_topics.php");
            break;		
		case 'gainfo':
			include ("gainfo.php");
			break;
		case 'manage_ga':
			include ("manage_ga.php");
			break;
		case 'managedbbackup':
			include ("manage_db_backup.php");
			break;
		case 'managelogs':
			include ("manage_logs.php");
			break;
		default:
			include ("general.php");
			break;
	}	
	if ($_GET['act'] != "mochiall" && $_GET['act'] != "logout") include ("footer.php");
} else {
	include ("../includes/db_functions.inc.php");
	include ("../includes/config.inc.php");
	if (substr($setting['siteurl'], -1) != '/') $setting['siteurl'] .= '/';
	include ("login.php");
}
?>