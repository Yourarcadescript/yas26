<?php
#--------------------------------
# http://www.yourarcadescript.com
#       index.php
#		VERSION 2.5.1
#		CC BY-ND 3.0(http://creativecommons.org/licenses/by-nd/3.0/) Liscensed
#       
#--------------------------------
session_start();
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();
if (!file_exists("includes/db_functions.inc.php") || !file_exists("includes/config.inc.php")) {
	echo "Site is not installed";
	exit;
}
include("includes/db_functions.inc.php");
include("includes/config.inc.php");
if ($setting['disabled'] == 'yes') {
	include('siteoff.php');
	exit;
}
include("includes/inc.php");
include('includes/raycache.php');
include('includes/resize.php');
if (isset($_GET['act']) && $_GET['act'] == "girlgames" || $gender == 'girl') {
	include("templates/".$setting['theme']."/girl_header.php");
} else {
	include("templates/".$setting['theme']."/header.php"); 
}
switch($_GET['act']){
    case 'editavatar':
        include ("templates/".$setting['theme']."/editavatar.php");
        break;
    case 'members':
        include ("templates/".$setting['theme']."/members.php");
        break;
    case 'submitgame':
        include ("templates/".$setting['theme']."/submitgame.php");
        break;
    case 'pass_reset_complete':
        include ("templates/".$setting['theme']."/pass_reset_complete.php");
		break;
    case 'small_footer':
        include ("templates/".$setting['theme']."/small_footer.php");
        break;
    case 'tellafriend':
        include ("templates/".$setting['theme']."/tellafriend.php");		
        break;
    case 'news':
        include ("templates/".$setting['theme']."/news.php");
        break;
    case 'shownews':
        include ("templates/".$setting['theme']."/shownews.php");
        break;
    case 'favourites':
        include ("templates/".$setting['theme']."/favourites.php");
        break;
    case 'showmember':
        include ("templates/".$setting['theme']."/showmember.php");
        break;
    case 'terms':
        include ("templates/".$setting['theme']."/terms.php");
        break;
    case 'contactus':
        include ("templates/".$setting['theme']."/contactus.php");
        break;
    case 'career':
        include ("templates/".$setting['theme']."/career.php");
        break;
	case 'cat':
        include ("templates/".$setting['theme']."/category.php");
        break;
    case 'search':
        include ("templates/".$setting['theme']."/search.php");
        break;
    case 'register':
        include ("templates/".$setting['theme']."/register.php");
        break;
    case 'topplayers':
        include ("templates/".$setting['theme']."/topplayers.php");
        break;
    case 'links':
        include ("templates/".$setting['theme']."/links.php");
        break;
    case 'addlink':
        include ("templates/".$setting['theme']."/addlink.php");
        break;
    case 'forgotpassword':
        include ("templates/".$setting['theme']."/forgotpassword.php");
        break;
    case 'profile':
        include ("templates/".$setting['theme']."/profile.php");
        break;
    case 'editprofile':
        include ("templates/".$setting['theme']."/editprofile.php");
        break;
	case 'game':
        include ("templates/".$setting['theme']."/game.php");
        break;
    case 'girlgames':
        include ("templates/".$setting['theme']."/girlgames.php");
        break;
	case 'play':
        include ("templates/".$setting['theme']."/play.php");
        break;
	case 'mostplayed':
        include ("templates/".$setting['theme']."/mostplayed.php");
        break;
	case 'toprated':
        include ("templates/".$setting['theme']."/toprated.php");
        break;
	case 'newest':
        include ("templates/".$setting['theme']."/newest.php");
        break;
	case 'random':
        include ("templates/".$setting['theme']."/random.php");
        break;
	case 'privacy':
        include ("templates/".$setting['theme']."/privacy.php");
        break;
	case 'login':
        include ("templates/".$setting['theme']."/login2.php");
        break;
	case 'download':
        include ("templates/".$setting['theme']."/download.php");
        break;
    case 'unsubscribe':
        include ("templates/".$setting['theme']."/unsubscribe.php");
		break;
	case 'advertising':
        include ("templates/".$setting['theme']."/advertising.php");
		break;	
	case 'videos':
        include ("templates/".$setting['theme']."/video.php");
		break;	
	case 'faq':
        include ("templates/".$setting['theme']."/faq.php");
		break;
	case 'allcategories':
        include ("templates/".$setting['theme']."/all_categories.php");
		break;
	case 'forum':
        include ("templates/".$setting['theme']."/forum.php");
		break;
	case 'forumcats':
        include ("templates/".$setting['theme']."/forumcats.php");
		break;
	case 'forumtopics':
        include ("templates/".$setting['theme']."/forumtopics.php");
		break;
	case 'createtopic':
        include ("templates/".$setting['theme']."/createtopic.php");
		break;
	case 'uploadvideos':
		include ("templates/".$setting['theme']."/upload_video.php");
		break;
	default:
        include ("templates/".$setting['theme']."/home.php");
        break;		
}
include ("templates/".$setting['theme']."/footer.php");
$_SESSION['runjobs'] = 'good-to-go';
ob_end_flush();
?>