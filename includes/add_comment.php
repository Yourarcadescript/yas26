<?php
session_start();
include("db_functions.inc.php");
include("config.inc.php");
if ($_POST['recaptcha'] == 'yes') {	
	include("securimage/securimage.php");
	$img = new Securimage();
	$valid = $img->check($_POST['code']);

	if (!$valid) {
  		$passed = false;
	} else {
		$passed = true;
	}
}
elseif ($_POST['recaptcha'] == 'no') {
	$answer = array('10', 'ten');
	if(!in_array(strtolower($_POST['security']),$answer)) {
		$passed = false;
	} else {
		$passed = true;
	}
}
if ($passed) {
	//if(isset($_POST['sendcomment'])) {
		if(isset($_POST['member'])) {
			if (isset($_SESSION['user'])) {
				$userid = yasDB_clean($_POST['userid']);
				$comment = yasDB_clean($_POST['comment'],true);
				$name = $_SESSION['user'];
				$ipaddress = $_SERVER['REMOTE_ADDR'];		
				yasDB_insert("INSERT INTO `memberscomments` (userid, ipaddress, comment, name) VALUES ($userid, '$ipaddress', '$comment', '$name')");
				echo "<h3>Comment added!</h3>";
			}
		}
		elseif(isset($_POST['newsid'])) {
			$id = yasDB_clean($_POST['newsid']);
			$comment = yasDB_clean($_POST['comment']);
			$ipaddress = yasDB_clean($_SERVER['REMOTE_ADDR']);
			yasDB_insert("INSERT INTO `newsblog` (username, newsid, comment, ipaddress) values ('{$_SESSION['user']}', $id, '$comment', '$ipaddress')",false);
			echo "<h3>Comment added!</h3>";
		}
		elseif(empty($_POST['gameid'])) {
			echo "<h3>Sorry, the game you were commenting seems to be invalid.</h3>";
			exit;
		}
		elseif(empty($_POST['comment']) || empty($_POST['name'])) {
			echo "<h3>All fields must filled in.</h3>";
			exit;
		}
		else {
			$gameid = yasDB_clean($_POST['gameid']);
			$comment = yasDB_clean(strip_tags($_POST['comment']),true);
			$name = yasDB_clean($_POST['name']);
			$ipaddress = yasDB_clean($_SERVER['REMOTE_ADDR']);
			yasDB_insert("INSERT INTO `comments` (gameid, comment, ipaddress, name) VALUES ('{$gameid}', '{$comment}', '{$ipaddress}', '{$name}')",false);
			echo "<h3>Comment added!</h3>";
		}
	//}
} else {
	?>
	<h3>The security question was answered incorrectly</h3>
	<?php 
}
?>