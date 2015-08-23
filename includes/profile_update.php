<?php
session_start();
include ("db_functions.inc.php");
if($_SERVER["REQUEST_METHOD"] == "POST") {
	$userid = $_SESSION['userid'];
	$website = yasDB_clean($_POST['website']);
	$name = yasDB_clean($_POST['name']);
	$email = yasDB_clean($_POST['email']);
	$location = yasDB_clean($_POST['location']);
	$aboutme = yasDB_clean($_POST['aboutme']);
	$hobbies = yasDB_clean($_POST['hobbies']);
	$shhobs = yasDB_clean($_POST['shhobs']);
	$shloc = yasDB_clean($_POST['shloc']);
	$sheml = yasDB_clean($_POST['sheml']);
	$shname = yasDB_clean($_POST['shname']);
	$shabout = yasDB_clean($_POST['shabout']);
	$deact = yasDB_clean($_POST['deact']);
	$cmtsdisabled = yasDB_clean($_POST['cmtsdisabled']);

	if (isset($_SESSION['userid'])) {
		yasDB_update("UPDATE `user` SET website = '$website', name = '$name', email = '$email', location='$location', aboutme='$aboutme', hobbies='$hobbies', shhobs = '$shhobs', cmtsdisabled = '$cmtsdisabled', shloc = '$shloc',  sheml = '$sheml', shname = '$shname', shabout = '$shabout', deact = '$deact' WHERE id = '$userid'");
		echo '<h2>Your profile has been updated.</h2>';
	}
	else {
		echo '<h2>Invalid user detected.</h2>';
	}
}
?>