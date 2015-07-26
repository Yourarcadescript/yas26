<?php
require_once("../includes/config.inc.php");
require_once("mochi_functions.php");
@ini_set("max_execution_time", 1000);
?>
<style>
body
{
	color: #000;
	padding: 0px;
	text-align:center;
	margin:0 auto; 
	background-color: #C8C8C8;
}
#container {
	position:relative;
	width:552px;
	height:540px;
	top: 25px;
	margin: 0px auto -1px auto;
}
#mochi {
	position:relative;
	width:300;
	height:240;
	background: transparent url('mochidude.png');
	top: 70px;
	margin-left: auto ;
	margin-right: auto ;
	z-index:1;
}	
#master {
	position:relative;
	top: 40px;
	margin-left: auto ;
	margin-right: auto ;
	width:550px;
	height:300px;
	background-color:#fff;
	border:solid 3px #970D0D;
}
#message {
	position: relative;
	top: 60px;
	margin-left: auto ;
	margin-right: auto ;
	font-size: 18px;
	text-align: center;	
}
#continue {
	position: relative;
	top: 75px;
	margin-left: auto ;
	margin-right: auto ;
	font-size: 18px;
	text-align: center;	
}
#stop {
	position: relative;
	top: 40px;
	margin-left: auto ;
	margin-right: auto ;
	font-size: 18px;
	text-align: center;	
}
</style>
<?php
if (!isset($_POST['stop_x'])) {
	if (!empty($_GET['category'])) {
		$category = $_GET['category'];
		$rating = $_GET['rating'];
		$description = $_GET['description'];
		$keywords = $_GET['keywords'];
	} else {
		$category ='all';
		$rating = 'all';
		$description = '';
		$keywords = '';
	}
	if ($rating == 'all') {
		$sql_rating = '';
	} else {
		$sql_rating = " rating = '".$rating."' AND";
	}
	if ($category == 'coins') {
		$sql_category = ' coinsenabled = 1 AND';
	} elseif ($category == 'leaderboard'){
		$sql_category = ' leaderboard = 1 AND';
	} elseif ($category == 'recommended') {
		$sql_category = ' recommended = 1 AND';
	} elseif ($category == 'all') {
		$sql_category = '';
	} else {
		$sql_category = " categories LIKE '%".$category."%' AND";
	}
	if ($keywords == '') {
		$sql_keywords = '';
	} else {
		$sql_keywords = " keywords LIKE '%".yasDB_clean($keywords)."%' AND";
	}
	if ($_GET['description'] == '') {
		$sql_description = '';
	} else {
		$sql_description = " description LIKE '%".yasDB_clean($description)."%' AND";
	}
	$sql = 'SELECT * FROM mochigames WHERE' . $sql_rating . $sql_category . $sql_keywords . $sql_description . ' isinstalled = 0 ORDER BY id DESC';
	$query = yasDB_select($sql,false);
	$GLOBALS['numgames'] = $query->num_rows;
	if (!isset($_SESSION['count'])) {$_SESSION['count'] = 0;}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head></head>
<body>
<div id="container">
<div id="mochi"></div>
<div id="master">
<div id="stop">
<form action="index.php" method="get">
    <input type="hidden" name="act" value="mochiall"/>
	<input type="image" src="stop.jpg" width="100" height="100" name="stop" alt="Stop!"/>
</form>
</div>
<div ID="message">
Installing Game 0 of <?php echo $GLOBALS['numgames']; ?>
</div>
<?php flush2();
if (isset($_GET['stop_x'])) {
		?>
		<SCRIPT>
			d = document.getElementById("message");
			d.innerHTML = "<?php echo 'Stopped. '.($_SESSION['count']-1) .' games installed.';?>";
		</SCRIPT>
		<?php $_SESSION['count']=0; ?>
		<div ID="continue">
			<?php echo '<p><center>|&nbsp;<a href="index.php?act=managemochi">Continue</a>&nbsp;|</p></center>'; ?>
		</div>
		</div>
		</div>
		</body>
		</html>
		<?php 
		flush2();
		exit;				
}
while($_SESSION['count'] < $GLOBALS['numgames']) {
	sleep(1);
	$row = $query->fetch_array(MYSQLI_ASSOC);
	$_SESSION['count']++;
	?>
	<SCRIPT>
	d = document.getElementById("message");
	d.innerHTML = "<?php echo 'Installing Game '. $_SESSION['count']. ' of '.$GLOBALS['numgames'];?>";
	</SCRIPT>
<?php flush2(); 
	$success = install_mochigame($row['id']);
	if (!$success) {
		?><SCRIPT>
			d = document.getElementById("message");
			d.innerHTML = "<?php echo 'Error installing '.$row['name'];?>";
		</SCRIPT>
		<div ID="continue">
			<?php echo '<p><center>|&nbsp;<a href="index.php?act=managemochi">Continue</a>&nbsp;|</p></center>'; ?>
		</div>
		</div>
		</div>
		</body>
		</html>
		<?php
		exit();
	}
}
$query->close();
$_SESSION['count'] = 0;
?>
<SCRIPT>
	d = document.getElementById("message");
	d.innerHTML = "<?php echo 'Installation of '.$SESSION['count'].' games finished!';?>";
</SCRIPT>
<div ID="continue">
<?php echo '<p><center>|&nbsp;<a href="index.php?act=managemochi">Continue</a>&nbsp;|</p></center>'; ?>
</div>
</div>
</div>
</body>
</html>