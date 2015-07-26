<?php
@session_start();
switch ($_GET['pic']) {
	case 1:
		$amounts = unserialize($_SESSION['amounts']);
		$cfg['width'] = 600;
		$cfg['height'] = 700;
		$cfg['title'] = "Past Month Visitor Count";
		$cfg['column-color'] = "E23636";
		unset($_SESSION['amounts']);
		break;
	case 2:
		$amounts = unserialize($_SESSION['amounts2']);
		$cfg['width'] = 600;
		$cfg['height'] = 300;
		$cfg['title'] = "Past Month Total Visitors / Pageviews";
		unset($_SESSION['amounts2']);
		break;
	case 3:
		$amounts = unserialize($_SESSION['amounts3']);
		$cfg['width'] = 600;
		$cfg['height'] = 400;
		$cfg['title'] = "Past Month Top 10 Viewed Pages";
		unset($_SESSION['amounts3']);
		break;
	default:
		$amounts = unserialize($_SESSION['amounts']);
		$cfg['width'] = 600;
		$cfg['height'] = 600;
		$cfg['title'] = $_SESSION['url'];
		unset($_SESSION['amounts']);
}
$cfg['column-color'] = "E23636";
header("Content-type: image/png");
include_once('phpMyGraph5.0.php');
$graph = new phpMyGraph();
$graph->parseHorizontalColumnGraph($amounts, $cfg);
?>