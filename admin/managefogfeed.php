<?php
include_once("fog_functions.php");
if(isset($_GET['getfeed'])) {
	get_fogfeed();
}
?>
<div id="center-column">
<div class="top-bar">
<h1>Install FreeGamesForYourWebsite.com Feed Games</h1>
<div class="select-bar">
<center>
<form id="fogfeed" name="fogfeed" method="get" action="index.php">
<input type="hidden" name="act" value="managefogfeed">
<input type="submit" class="button" name="getfeed" value="GetFeed" />
</form></center></div>
<?php
$catnames = array('Puzzle','Action','Adventure','Sports','Shooter','Casino','Other','Dressup','Arcade','Strategy');
$result = yasDB_select("SELECT COUNT(id) FROM `fogfeed`"); 
$query_data = $result->fetch_array(MYSQLI_NUM);
$numrows = $query_data[0];
$result->close();
$result2 = yasDB_select("SELECT `sourceid` FROM `gameque` WHERE `source` = 'fog'");
$queids = array();
while ($source = $result2->fetch_array(MYSQLI_NUM)) {
	$queids[] = $source[0];
}
if($numrows == 0){
	echo '<center><h3>Feed is not installed.</h3></center></div>';
	
} else {
?>
<script type="text/javascript">
		$(document).ready(function() {
			$("#game").fancybox({
				'type'              : 'swf',
				'padding'			: 0,
				'autoScale'			: true,
				'transitionIn'		: 'elastic',
				'transitionOut'		: 'elastic'
			});
		});
</script>
<center><p align="center">  
<form enctype="multipart/form-data" action="index.php" method="get">
	<input type="hidden" name="act" value="managefogfeed">
	<label for="category_filter">Category:</label>
    <select name="category" id="category">
        <option value="" selected>All Categories</option>
        <option value="1">Puzzle</option>
		<option value="2">Action</option>
		<option value="3">Adventure</option>
		<option value="4">Sports</option>
		<option value="5">Shooter</option>
		<option value="6">Casino</option>
		<option value="7">Other</option>
		<option value="8">Dressup</option>
		<option value="9">Arcade</option>
		<option value="10">Strategy</option>
		</optgroup>
	</select>&nbsp;&nbsp;
	Title:<input type="text" name="title"/>&nbsp;&nbsp;
	Description:<input type="text" name="description"/>
	</p><br/>
	<input type="submit" class="button" name="filter" value="Filter Games" />
</form>
</center><br/>												
<?php
if (!isset($_GET['category']) || empty($_GET['category'])) {
	$cat = '---';
	$category = '';
	$sql_category = '';
} else {
	$cat = $catnames[$_GET['category'] - 1];
	$category = yasDB_clean($_GET['category']);
	$sql_category = " category = $category AND";
}
if (!isset($_GET['description']) || empty($_GET['description'])) {
	$des = '---';
	$description = '';
	$sql_description = '';
} else {
	$des = $_GET['description'];
	$description = yasDB_clean($_GET['description']);
	$sql_description = " description LIKE '%$description%' AND";
}
if (!isset($_GET['title']) || empty($_GET['title'])) {
	$tnum = '---';
	$title = '';
	$sql_title = '';
} else {
	$tnum = $_GET['title'];
	$title = yasDB_clean($_GET['title']);
	$sql_title = " title LIKE '%$title%' AND";
}

$sql = 'SELECT count(id) FROM fogfeed WHERE' . $sql_category . $sql_title . $sql_description . ' installed = "0"';
$query = yasDB_select($sql,false);	
$num_games = $query->fetch_array(MYSQLI_NUM);	
$query->close();
echo '<table class="listing" cellpadding="0" cellspacing="0">
	    <tr style="font-weight: bold;">
	    <td align="center">Title: '.$tnum.'</td>
		<td align="center">Category: '.$cat.'</td>
		<td align="center">Description: '.$des.'</td>
		<td class="last" align="center">Games: '.$num_games[0].'</td>
		</tr></table>';
if (isset($_GET['page'])) {
   $pageno = $_GET['page'];
} else {
   $pageno = 1;
} 
	
//**********************************************************************************************************************************************			

$rows_per_page = 20;
$lastpage = ceil($num_games[0]/$rows_per_page);
if($lastpage<1) {
	$lastpage = 1;
}
$pageno = (int)$pageno;
if ($pageno < 1) {
   $pageno = 1;
} 
elseif ($pageno > $lastpage) {
   $pageno = $lastpage;
} 
$limit = 'LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;
echo '<div class="table">
	<table class="listing" cellpadding="0" cellspacing="0">
	<thead>
	<tr>
		<th width="90px"></th>
		<th>Title</th>
		<th>Category</th>		
		<th>Description</th>
		<th></th>
	</tr></thead>';
//*************************************************************************************************************************************************	
$sql = 'SELECT * FROM fogfeed WHERE' . $sql_category .$sql_title. $sql_description . ' installed = "0" ORDER BY id DESC '.$limit;
$query = yasDB_select($sql,false);
if($query->num_rows == 0) {
   	echo '<center><b style="color: #7F7F7F">No games meet your criteria.</b></center>';
} else {                         
   	$i=0;
	while($row = $query->fetch_array(MYSQLI_ASSOC)) {
		$i++;
		?>			 
		<script type="text/javascript">
		$(document).ready(function() {
			$("#game<?php echo $i;?>").fancybox({
				'type'              : 'swf',
				'padding'			: 0,
				'autoScale'			: true,
				'transitionIn'		: 'elastic',
				'transitionOut'		: 'elastic'
				
			});
		});
		</script>
		<?php
		if ($row['installed'] != "1") {
			$thumburl = $row['small_thumbnail_url'];
			$desc = substr($row['description'], 0, 150) . '...';   // Limit the size of description displayed
			$twords = substr($row['title'], 0, 75);
			$gameid = $row['id'];
			$gameurl = $row['swf_file'];
			echo '<tr>
				<td class="first style1" width="80px"><img src="showimage.php?url='.$thumburl.'" style="width: 80px; height: 80px;" /></td>
				<td>' . $twords . '</td>
				<td>' . $catnames[$row['category']-1] . '</td>
				<td>' . $desc . '</td>
				<td class="last"><form action="index.php" method="get">
				<input type="hidden" name="act" value="managefogfeed"/>
				<input type="submit" class="button" name="install" value="Install" /><br/><br/>';
				//<a id = "game'.$i.'" href="'.$gameurl.'" target="_blank" alt="'.$row['title'].'">Try it!</a>
				if (!in_array($gameid, $queids)) {
					echo '<div id="dummy'.$gameid.'"><input type="button" id="qbutton'.$gameid.'" class="button" name="que" value="Queue it" onclick="return doQue('.$gameid.', \'que\', \'fog\', \''. $row['title'].'\', \''.$thumburl.'\')"/></div><br/><br/>';
				} else {
					echo '<div id="dummy'.$gameid.'"><input type="button" id="qbutton'.$gameid.'" class="button_remove" name="que" value="UN-queue" style="font-size:11px;" onclick="return doQue('.$gameid.', \'remove\', \'fog\', \''. $row['title'].'\', \''.$thumburl.'\')"/></div><br/>';
				}
				echo '<input type="hidden" name="gameid" value="'.$gameid.'" />
				<input type="hidden" name="page" value="'.$pageno.'" />
				<input type="hidden" name = "category" value="'.$category.'"/>	
				<input type="hidden" name = "description" value="'.$description.'"/>	
				<input type="hidden" name="title" value ="'.$title.'"/>					
				</form></td>
				</tr>';
		}
	}
}
$query->close();
if (isset($_GET['gamepage'])) {
	$pageno = $_GET['gamepage'];
}
?>
<tfoot>
	<tr>
		<th width="90px"></th>
		<th>Title</th>
		<th>Category</th>		
		<th>Description</th>
		<th></th>
	</tr></tfoot>
</table></div><br /><div style="text-align:center;">
<?php
if ($pageno == 1) {
   echo ' FIRST PREV ';
} else {
   echo ' <a href="index.php?act=managefogfeed&page=1&category='.$category.'&description='.$description.'&title='.$title.'&filter=Filter games">FIRST</a>';
   $prevpage = $pageno-1;
   echo ' <a href="index.php?act=managefogfeed&page=' . $prevpage . '&category='.$category.'&description='.$description.'&title='.$title.'&filter=Filter games">PREV</a>';
} 
echo ' ( Page ' . $pageno . ' of ' . $lastpage . ' ) ';
if ($pageno == $lastpage) {
   echo ' NEXT LAST ';
} else {
   $nextpage = $pageno+1;
   echo ' <a href="index.php?act=managefogfeed&page=' . $nextpage . '&category='.$category.'&description='.$description.'&title='.$title.'&filter=Filter games">NEXT</a>';
   echo ' <a href="index.php?act=managefogfeed&page=' . $lastpage . '&category='.$category.'&description='.$description.'&title='.$title.'&filter=Filter games">LAST</a>';
} 
echo '</div>';
if (isset($_GET['install'])) {
	if ($_GET['install'] == 'Install') {
		install_foggame($_GET['gameid']) or die("Game did not install successfully");
		echo '<script>alert("Game sucessfully installed.");</script>';
		if (isset($_GET['page'])) {
			$pageno = $_GET['page'];
		}
		else {
			$pageno = 1;
		} 
		if (isset($_GET['category'])) {
			$category = $_GET['category'];
		}
		else {
			$category = 'all';
		}
		$description = yasDB_clean($_GET['description']);
		echo '<META HTTP-EQUIV="Refresh" CONTENT="0; URL=index.php?act=managefogfeed&page='.$pageno.'&description='.$description.'&title='.$title.'&filter=Filter games">';
		exit();
	}
}
?>
<br/></div>
<?php } ?></div>                      