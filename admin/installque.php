<?php
include ("../includes/db_functions.inc.php");
$check = yasDB_select("SELECT `source`, `sourceid` FROM `gameque`");
if ($check->num_rows == 0) {
	include ("../includes/config.inc.php");
	echo "Game que empty";
	$message = "Your GameQue on ".$setting['sitename']." is empty. To continue to have games installed on schedule, please add more games to your que via the Admin game feeds' management pages.";
	$headers = 'From: '.$setting['sitename'].' GameQue<'.$setting['sitename'].'>';
	$subject = 'The GameQue on'.$setting['sitename'].' is empty';
	@mail($setting['email'], $subject, $message, $headers);
	exit;
}
while ($row = $check->fetch_array(MYSQLI_ASSOC)) {
	switch ($row['source']) {
		default:
			break;
	}
}
?>