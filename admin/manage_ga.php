<?php
require_once('analytics_api.php');

$gaquery = yasDB_select("SELECT `galogin`, `gapassword`, `gaurl` FROM `settings` WHERE id=1");
$gaLogin = $gaquery->fetch_array(MYSQLI_ASSOC);
?>
<div id="center-column">
<div class="top-bar">
<h1>Cpanel - Google Analytics</h1>
<div class="breadcrumbs"><a href="index.php?act=gainfo" title="Google Analytics Info">Google Analytics Login Info</a></div>
</div><br />
<div class="select-bar">
<label>
<h3>Google Analytics Data</h3>
</label>
</div>
<?php
$api = new analytics_api();

if ($api->login($gaLogin['galogin'], $gaLogin['gapassword'])) {
    $api->load_accounts();
    $tableId = $api->getAccountsTableIdFromName($gaLogin['gaurl']);
    $data = $api->data($tableId, 'ga:date', 'ga:visits','ga:date',date('Y-m-d', strtotime('1 month ago')),date('Y-m-d'),33);
    $amounts = array();
	$amounts2 = array();
	$amounts3 = array();
    foreach($data as $i => $value ) {
        $amounts[substr($i,4,2) .'/'.substr($i,6,2)]=$value['ga:visits'];
	}
    $_SESSION['amounts'] = serialize($amounts);
	$_SESSION['url'] = $gaLogin['gaurl'];
	unset($amounts);
	// output image
	?>
	<div style="margin 0 auto 10px auto;"><img src="ga_graph2.php?pic=1" style="border:3px solid #000;margin-bottom:10px;"/></div>
	<?php
	$data = $api->data($tableId, '', 'ga:newVisits,ga:visits,ga:bounces,ga:pageviews,ga:uniquePageviews');
    foreach($data as $metric => $count) {
         //echo "$metric: $count\n";
		$amounts2[$metric] = $count;
    }
	$_SESSION['amounts2'] = serialize($amounts2);
	unset($amounts2);
	?>
	<div style="margin 0 auto;"><img src="ga_graph2.php?pic=2" style="border:3px solid #000;margin-bottom:10px;"/></div>
	<?php
	$data = $api->data($tableId, 'ga:pagePath', 'ga:pageviews,ga:uniquePageviews');
    foreach($data as $dimension => $metrics) {
        //echo "$dimension pageviews: {$metrics['ga:pageviews']} unique pageviews: {$metrics['ga:uniquePageviews']}\n";
		$amounts3[$dimension] = $metrics['ga:pageviews'];		
	}
	$_SESSION['amounts3'] = serialize($amounts3);
	unset($amounts3);
	?>
	<div style="margin 0 auto;"><img src="ga_graph2.php?pic=3" style="border:3px solid #000;margin-bottom:10px;"/></div>
	<?php
} else {
	echo "<div style=\"text-align:center;\"><h3>Logging into Google Analytics server failed.</h3><br/>Make sure your login info is correct and try again.</div>";
}
?>
</div>