<?php
ob_start();
//include ("seourl.class.php"); // class creates seo urls - customizable - Not yet fully integrated into all templates
include ('browser_detection.php'); //library which gathers browser info
#-- Script functions and classes --#
function get_random_color()
{
    $c = '';
	for ($i = 0; $i<6; $i++)
    {
        $c .=  dechex(rand(0,15));
    }
    return "#$c";
}
function ad($id) {
    $ad = yasDB_select("SELECT `code` FROM `ads` WHERE id = '$id'");
    $ads = $ad->fetch_array(MYSQLI_ASSOC);
    $ad->close();
    return $ads['code'];
}
function prepGame($title) {
	$title = urldecode($title);
	$switch = array(" - "," ",":", "<", ">", ",");
	$title = str_replace($switch, "-", $title);
	$title = preg_replace('/[^a-zA-Z0-9\-]/', '', $title);		
	return rawurlencode($title);
}
function exchangeCheck() {
		if(isset($_SERVER['HTTP_REFERER'])){
			$referral = $_SERVER['HTTP_REFERER'];
			$array = parse_url($referral);
			$referral1 = $array['host'];
			$referral2 = str_replace("www.", "", $referral1);
			$referral3 = "http://".trim($referral2);
			$referral4 = "http://www.".$referral2;
			$select_link_in = yasDB_select("SELECT * FROM links WHERE url='$referral1' OR url='$referral2' OR url='$referral3' OR url='$referral4'");
			if($select_link_in->num_rows >0){
				$link_in = $select_link_in->fetch_array(MYSQLI_ASSOC);
				$select_link_in->close();
				yasDB_update("UPDATE links SET `in`=`in`+1 WHERE id='$link_in[id]'");
				return true;
			}
		}
		return false;
}
function strip_html_tags($text) {
    $text = preg_replace(
        array(
          // Remove invisible content
            '@<head[^>]*?>.*?</head>@siu',
            '@<style[^>]*?>.*?</style>@siu',
            '@<script[^>]*?.*?</script>@siu',
            '@<object[^>]*?.*?</object>@siu',
            '@<embed[^>]*?.*?</embed>@siu',
            '@<applet[^>]*?.*?</applet>@siu',
            '@<noframes[^>]*?.*?</noframes>@siu',
            '@<noscript[^>]*?.*?</noscript>@siu',
            '@<noembed[^>]*?.*?</noembed>@siu',
          // Add line breaks before and after blocks
            '@</?((address)|(blockquote)|(center)|(del))@iu',
            '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
            '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
            '@</?((table)|(th)|(td)|(caption))@iu',
            '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
            '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
            '@</?((frameset)|(frame)|(iframe))@iu',
        ),
        array(
            ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',"$0", "$0", "$0", "$0", "$0", "$0","$0", "$0",), $text);
  
    return strip_tags($text);
}
function closeTags($html) {
    $html = preg_replace("#]*$#", " ", $html);
	preg_match_all("##iU", $html, $result, PREG_OFFSET_CAPTURE);
    if (!isset($result[1])) {
        return $html;
	}
    $openedtags = $result[1];
    $len_opened = count($openedtags);
    if (!$len_opened) {
        return $html;
	}
    preg_match_all("##iU", $html, $result, PREG_OFFSET_CAPTURE);
    $closedtags = array();
    foreach($result[1] as $tag) {
        $closedtags[$tag[1]] = $tag[0];
	}
    $openedtags = array_reverse($openedtags);
    for($i = 0; $i < $len_opened; $i++) {
        if (preg_match('/(img|br|hr)/i', $openedtags[$i][0])) continue;
        $found = array_search($openedtags[$i][0], $closedtags);
        if (!$found || $found < $openedtags[$i][1]) {
            $html .= "";
		}
        if ($found) unset($closedtags[$found]);
    }
    return $html;
}
//change $timeout in seconds on how long to count a visitor before deleting
class usersOnline {
    var $timeout = 600;
    var $count = 0;
    
    public function usersOnline () {
        $this->timestamp = time();
        $this->ip = $this->ipCheck();
        $this->new_user();
        $this->delete_user();
        $this->count_users();
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
    
    private function new_user() {
        $browser_data = browser_detection('full_assoc'); // get browser data
        if ($browser_data['ua_type'] == 'bot' || $browser_data['ua_type'] == 'dow' || $browser_data['ua_type'] == 'lib') {
            $agent = $browser_data['browser_working'];
        } else {
            $agent = 'human';
        }
        yasDB_insert("INSERT INTO useronline(timestamp, ip, agent) VALUES ('$this->timestamp', '$this->ip', '$agent')");
    }
    
    private function delete_user() {
        yasDB_delete("DELETE FROM useronline WHERE timestamp < ($this->timestamp - $this->timeout)");
    }
    
    public function count_users() {
            $query = yasDB_select("SELECT DISTINCT ip FROM useronline");
            $count = $query->num_rows;
            $query->close();
            return $count;
    }
	public function get_bots() {
		$bots = array();
		$query = yasDB_select("SELECT DISTINCT agent FROM useronline WHERE agent != 'human'");
		if ($query->num_rows == 0) {
			return false;
		} else {
			while ($bot = $query->fetch_array(MYSQLI_ASSOC)) {
				$bots[] = $bot['agent'];
			}
		}
		return $bots;
	}
}

// Check if visitor is from our link exchange. If so update url visitor count.
exchangeCheck();

///Url id variable
if(isset($_GET['id'])) {
	if(!is_numeric($_GET['id'])) {
		header("Location: " . $setting['siteurl']);
		exit;
	}
	$id = yasDB_clean(intval(strip_tags($_GET['id'])));
} else {
	$id = '';
}
if(isset($_POST['id']) && !is_numeric($_POST['id'])) header("Location: " . $setting['siteurl']);
$rows_per_page = (($setting['gperpage']<1)?25:$setting['gperpage']);

// For member stats - Reset the active time for user in membersonline table
if (isset($_SESSION['userid'])) {
    $mtime = time();
    yasDB_update("UPDATE membersonline SET timeactive = $mtime WHERE memberid={$_SESSION['userid']}");
}
$fbUrl = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
list($iwidth, $iheight, $itype, $iattributes) = getimagesize($setting['sitepath'].'/templates/'. $setting['theme'].'/skins/'.$setting['skin'].'/images/logo.png');

///HEADER and META data
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="<?php echo $setting['metakeywords'];?>" />
<meta name="author" content="www.yourarcadescript.com" />
<meta property="og:type" content="website" />
<meta property="og:url" content="<?php echo $fbUrl;?>" />
<meta property="og:title" content="<?php echo $setting['sitename'];?>" />
<meta property="og:image" content="<?php echo $setting['siteurl'];?>templates/<?php echo $setting['theme'];?>/skins/<?php echo $setting['skin'];?>/images/logo.png" />
<meta property="og:image:width" content="<?php echo $iwidth; ?>" />
<meta property="og:image:height" content="<?php echo $iheight; ?>" />

<script type="text/javascript">
	//declare config variables for Javascript function use
	var siteurl = '<?php echo $setting['siteurl'];?>';
	var theme = '<?php echo $setting['theme'];?>';
	var skin = '<?php echo $setting['skin'];?>';
	var seoState = '<?php echo $setting['seo'];?>';
	var yasurl = "<?php echo $setting['siteurl'].'includes/ratingbar/rpc.php';?>";
</script>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo $setting['siteurl'];?>includes/notice/jquery_notification.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo $setting['siteurl'];?>includes/ratingbar/css/rating.css" />
<link href="<?php echo $setting['siteurl'];?>includes/fileuploader/fileUploader.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo $setting['siteurl'];?>templates/<?php echo $setting['theme'];?>/skins/<?php echo $setting['skin'];?>/style.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $setting['siteurl'];?>includes/js/jquery.form.js"></script>
<script type="text/javascript" src="<?php echo $setting['siteurl'];?>includes/notice/jquery_notification.js"></script>
<script type="text/javascript" src="<?php echo $setting['siteurl'];?>includes/js/yas_common.js"></script>
<script type="text/javascript" src="<?php echo $setting['siteurl'];?>includes/js/hint.js"></script>

<?php
if (isset($_SESSION['user'])) {
	?>
	<script type="text/javascript">
        // Get rid of the Facebook residue hash in the URI
        // IE and Chrome version of the hack
        if (String(window.location.hash).substring(0,1) == "#") {
                window.location.hash = "";
                window.location.href=window.location.href.slice(0, -1);
                }
        // Firefox version of the hack
        if (String(location.hash).substring(0,1) == "#") {
                location.hash = "";
                location.href=location.href.substring(0,location.href.length-3);
                }
        </script>
	<?php
}
if (!isset($_GET['act'])) $_GET['act'] = '';
switch($_GET['act']){
    case 'game':
        $query = yasDB_select("SELECT id, title, description, thumbnail FROM games WHERE id = '$id'");
        $row = $query->fetch_array(MYSQLI_ASSOC);
        $query->close();
        ?><title><?php  echo $row['title']; ?> - <?php echo $setting['sitename'];?> - <?php echo $setting['slogan'];?></title>
        <meta property="fb:admins" content="100003031501058" />
		<meta name="description" content="<?php  echo strip_html_tags($row['description']); ?>" />        
        <meta name="title" content="Play <?php echo $row['title'] . 'at ' . $setting['sitename'];?>" />
		<link rel="image_src" href="<?php echo $setting['siteurl'] . $row['thumbnail'];?>" />
		<script type="text/javascript" src="<?php echo $setting['siteurl'];?>includes/ratingbar/js/behavior.js"></script>
        <script type="text/javascript" src="<?php echo $setting['siteurl'];?>includes/ratingbar/js/rating.js"></script>
		
		<?php
		if (isset($_COOKIE['gamecount']) && $_COOKIE['gamecount'] != $id) {
			if (isset($_SESSION['user'])) {
				$user = yasDB_clean($_SESSION['user']);
				yasDB_update("UPDATE `user` set plays = plays +1 WHERE username = '$user'"); // add a play to the user
			}
			yasDB_update("UPDATE `games` set plays = plays +1 WHERE id = '$id'"); // add a play to the game
			yasDB_update("UPDATE `stats` set numbers = numbers +1 WHERE id = '2'"); // adds a play to the day's stats
            yasDB_update("UPDATE `stats` set numbers = numbers +1 WHERE id = '1'"); // adds a play to the total plays stats 
			setcookie("gamecount", $id, time()+60); // set gamecount cookie to expire in 60 seconds, or change to number of seconds you would like to limit game refresh before counting a game play
		}
		elseif (!isset($_COOKIE['gamecount'])) { // if user has browser cookies disabled the limit on page refresh is skipped
			$browser_data = browser_detection('full_assoc'); // get browser data
			if ($browser_data['ua_type'] != 'bot' && $browser_data['ua_type'] != 'dow' && $browser_data['ua_type'] != 'lib') { //don't add a game play if user agent is a bot or download agent or library
				setcookie("gamecount", $id, time()+60); // set gamecount cookie to expire in 60 seconds
				yasDB_update("UPDATE `games` set plays = plays +1 WHERE id = '$id'"); // add a play to the game
				yasDB_update("UPDATE `stats` set numbers = numbers +1 WHERE id = '2'"); // adds a play to the day's stats
                yasDB_update("UPDATE `stats` set numbers = numbers +1 WHERE id = '1'"); // adds a play to the total plays stats 
				if (isset($_SESSION['user'])) {
					$user = yasDB_clean($_SESSION['user']);
					yasDB_update("UPDATE `user` set plays = plays +1 WHERE username = '$user'"); // add a play to the user
				}
			}
		}
		if ($setting['lightbox'] == 'yes') {
			?>
			<link rel="stylesheet" type="text/css" href="<?php echo $setting['siteurl'];?>includes/fancybox/jquery.fancybox-1.3.4.css" />
			<script type="text/javascript" src="<?php echo $setting['siteurl'];?>includes/fancybox/jquery.fancybox-1.3.4.pack.js" /></script>
			<script type="text/javascript" src="<?php echo $setting['siteurl'];?>includes/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
			<?php
		}
		break;
    case 'play':
        $query = yasDB_select("SELECT id, title, description, thumbnail FROM games WHERE id = '$id'");
        $row = $query->fetch_array(MYSQLI_ASSOC);
        $query->close();
        ?><title><?php  echo $row['title']; ?> - <?php echo $setting['sitename'];?> - <?php echo $setting['slogan'];?></title>
        <script type="text/javascript" src="<?php echo $setting['siteurl'];?>includes/ratingbar/js/behavior.js"></script>
        <script type="text/javascript" src="<?php echo $setting['siteurl'];?>includes/ratingbar/js/rating.js"></script>
        <meta name="description" content="<?php  echo $row['description']; ?>" />        
        <meta name="title" content="Play <?php echo $row['title'] . 'at ' . $setting['sitename'];?>" />
		<link rel="image_src" href="<?php echo $setting['siteurl'] . $row['thumbnail'];?>" />
		<?php break;	
	case 'cat':
        $qu = yasDB_select("SELECT `id`, `name`, `desc` FROM `categories` WHERE `id` = '" . $id . "'");
        $cat_data = $qu->fetch_array(MYSQLI_ASSOC);
        ?><title><?php echo $cat_data['name']. ' - '. $setting['sitename']. ' - '. $setting['slogan'];?></title>
        <meta name="description" content="<?php echo $cat_data['desc'];?>" />
        <?php break;
    case 'search':
        if (isset($_GET['q'])) {
            $q = yasDB_clean($_GET["q"]);;
        }
        ?><title><?php echo $setting['sitename'];?> - Search Results for <?php echo $q?></title>
        <?php break;
    case 'contactus':
        ?><title><?php echo $setting['sitename'];?> - Contact Us</title>
        <?php break;
    case 'small_footer':
        ?><title><?php echo $setting['sitename'];?> - stats</title>
        <?php break;
    case 'forgotpassword':
        ?><title><?php echo $setting['sitename'];?> - Forgot Password</title>
		<?php break;
    case 'links':
        ?><title><?php echo $setting['sitename'];?> - Links</title>
        <?php
        break;
    case 'favourites':
        ?><title><?php echo $setting['sitename'];?> - Favorite Games</title>
        <?php
		break;
    case 'addlink':
        ?><title><?php echo $setting['sitename'];?> - Add Link</title>
        <?php
        break;
    case 'submitgame':
        ?><title><?php echo $setting['sitename'];?> - Submit Game</title>
        <?php
        break;
    case 'pass_reset_complete':
        ?><title><?php echo $setting['sitename'];?> - Password Reset Complete</title>
        <?php
        break;
    case 'news':
        ?><title><?php echo $setting['sitename'];?> - News</title>
        <?php
        break;
    case 'shownews':
        ?><title><?php echo $setting['sitename'];?> - News Flash</title>
        <?php
        break;
    case 'register':
        ?><title><?php echo $setting['sitename'];?> - Register</title>
        <?php
		break;
    case 'terms':
        ?><title><?php echo $setting['sitename'];?> - Terms/F.A.Q./Legal Notice</title>
        <?php
        break;
    case 'members':
        ?><title><?php echo $setting['sitename'];?> - Members</title>
        <?php
        break;
    case 'showmember':
        ?><title><?php echo $setting['sitename'];?>  - <?php echo $_SESSION['user'];?>'s Profile</title>
        <?php
        break;
    case 'profile':
        ?><title><?php echo $setting['sitename'];?> - <?php echo $_SESSION['user'];?>'s Profile</title>
        <?php
		break;
    case 'topplayers':
        ?><title><?php echo $setting['sitename'];?> - Top Players</title>
        <?php break;
    case 'editavatar':
        ?><title><?php echo $setting['sitename'];?> - Edit Avatar</title>
        <link href="<?php echo $setting['siteurl'];?>includes/fileuploader/jquery-ui-1.8.14.custom.css" rel="stylesheet" type="text/css" />
		<script src="<?php echo $setting['siteurl'];?>includes/fileuploader/js/jquery-ui-1.8.14.custom.min.js" type="text/javascript"></script>
		<script src="<?php echo $setting['siteurl'];?>includes/fileuploader/js/jquery.fileUploader.js" type="text/javascript"></script>
 		<script type="text/javascript">
			jQuery(function($){
				$('.fileUpload').fileUploader({
					autoUpload: false,
					selectFileLabel: 'Select Avatar(s)',
					limit: 102400,
					allowedExtension: 'jpg|jpeg|gif|png',
					onValidationError: function() {
						return showNotification({message: "Invalid file selected!", type: "error", autoClose: true, duration: 3});
					},
					afterUpload: function() {
						return 	loadAvatars();
					},
					afterEachUpload: function() {
						return 	loadAvatars();
					}
				});
			});
		</script>
		<?php
        break;
    case 'donation':
        ?><title><?php echo $setting['sitename'];?> - Donation</title>
        <?php
        if ($setting['theme'] == 'modbox') {
            ?><link rel="stylesheet" type="text/css" href="<?php echo $setting['siteurl'];?>templates/<?php echo $setting['theme'];?>/skins/<?php echo $setting['skin'];?>/donation.css" />
            <?php
        }
        break;
    case 'popular':
		?><title><?php echo $setting['sitename'];?> - Most Popular Games</title>
        <meta name="description" content="<?php echo $setting['metades'];?>" />
        <?php break;
	case 'toprated':
		?><title><?php echo $setting['sitename'];?> - Top Rated Games</title>
        <meta name="description" content="<?php echo $setting['metades'];?>" />
        <?php break;
	case 'latest':
		?><title><?php echo $setting['sitename'];?> - Newest Games</title>
        <meta name="description" content="<?php echo $setting['metades'];?>" />
        <?php break;
	case 'privacy':
		?><title><?php echo $setting['sitename'];?> - Privacy Statement</title>
        <meta name="description" content="<?php echo $setting['metades'];?>" />
        <?php break;
	case 'download':
        ?><title><?php echo $setting['sitename'];?> - Download Games</title>
        <?php
        break;
    case 'unsubscribe':
        ?><title><?php echo $sitename;?> - Unsubscribe</title>
		<?php break;
	case 'forum':
		?><title><?php echo $setting['sitename'];?> - Forum</title>
		<?php break;
	case 'forumcats':
		$query = yasDB_select("SELECT `id`, `name`, `desc` FROM `forumcats` WHERE `id` = '" . $id . "'");
		$cat_data = $query->fetch_array(MYSQLI_ASSOC);
		?><title><?php echo $cat_data['name']. ' - '. $setting['sitename']. ' - '. $setting['slogan'];?></title>
		<?php break;
	case 'forumtopics':
		yasDB_update("UPDATE `forumtopics` set views = views +1 WHERE id = '$id'");
		$query = yasDB_select("SELECT `id`, `subject` FROM `forumtopics` WHERE `id` = '" . $id . "'");
		$topic = $query->fetch_array(MYSQLI_ASSOC);
		$topic = $topic['subject'];
		?><title><?php echo $topic;?> - <?php echo $setting['sitename'];?></title>
		<script type="text/javascript" src="<?php echo $setting['siteurl'];?>includes/tiny_mce/tiny_mce.js"></script>
		<?php
		break;
	case 'createtopic':
		?><title><?php echo $setting['sitename'];?> - Creating Forum Topic</title>
		<script type="text/javascript" src="<?php echo $setting['siteurl'];?>includes/tiny_mce/tiny_mce.js"></script>
		<?php
		break;
	default:
        ?><title><?php echo $setting['sitename'];?> - <?php echo $setting['slogan'];?></title>
        <meta name="description" content="<?php echo $setting['metades'];?>" />
        <?php
		break;
}?>
</head>
<?php ob_end_flush(); ?>