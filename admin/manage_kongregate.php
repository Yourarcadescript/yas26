<?php
include_once("kongregate_function.php");
if (isset($_GET['feed'])) {
	get_kongregate_feed();
}
?>
<div id="center-column">
<div class="top-bar">
<h1>Install Kongregate.com Feed Games</h1>
</div><br />
<div class="select-bar"></div>
<?php
$result = yasDB_select("SELECT COUNT(id) FROM kongregate"); 
$query_data = $result->fetch_array(MYSQLI_NUM);
$numrows = $query_data[0];
$result->close();
$result2 = yasDB_select("SELECT `sourceid` FROM `gameque` WHERE `source` = 'kongregate'");
$queids = array();
while ($source = $result2->fetch_array(MYSQLI_NUM)) {
	$queids[] = $source[0];
}
?>
<script type="text/javascript">
		$(document).ready(function() {
			$("#game").fancybox({
				'type'              : 'swf',
				'padding'			: 0,
				'autoScale'			: true,
				'transitionIn'		: 'elastic',
				'transitionOut'		: 'elastic'
				/*'width'             : '<?php echo $row['width'];?>',
				'height'            : '<?php echo $row['height'];?>'*/
			});
		});
</script>
                      <center>
					<form id="kongfeed" action="index.php" method="get">
						<input type="hidden" name="act" value="managekong">
						<input type="submit" class="button" name="feed" value="Get Feed" />
					</form>
					  <div id="preview"></div>
					  <h4>Note: Installing a lot of games at once is resource intensive.</h4>
                        <p align="center">  
                        <form enctype="multipart/form-data" action="index.php" method="get">
                        		<input type="hidden" name="act" value="managekong">
								<label for="category_filter">Category:</label>
                        		<select name="category" id="category">
									<option value="all" selected>All Categories</option>
									<option value="action">Action</option>
									<option value="adventure">Adventure & RPG</option>
									<option value="puzzle">Puzzle</option>
									<option value="shooter">Shooter</option>
									<option value="music">Music & More</option>
									<option value="sports">Sports & Racing</option>
									<option value="strategy">Strategy & Defense</option>
                          		</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        		<br/><br/>                        		
                                Description:<input type="text" name="description"/>
                                </p><br/>
                                <input type="submit" class="button" name="filter" value="Filter Games" />
                        </form></center>
                        <div style="position:relative;float:right;margin-bottom:5px;">
						<form action="index.php" method="get">                               	 
							 <input type="submit" class="button" name="install" value="Install Filtered List"/></center>
							 <input type="hidden" name = "category" value="<?php if (isset($_GET['category'])) {echo $_GET['category'];} else {echo '';} ?>"/>	
							 <input type="hidden" name = "description" value="<?php if (isset($_GET['description'])) {echo $_GET['description'];} else {echo '';} ?>"/>
						</form></div>
	<?php
	if (!isset($_GET['category']) || empty($_GET['category'])) {
		$cat = 'all';
	} else {
		$cat = $_GET['category'];
	}
	if (!isset($_GET['description']) || empty($_GET['description'])) {
		$des = '---';
	} else {
		$des = $_GET['description'];
	}
	if (!empty($_GET['category'])) {
		$category = $_GET['category'];
		$description = yasDB_clean($_GET['description']);
	} else {
		$category ='all';
		$description = '';
	}
	if ($category == 'all') {
		$sql_category = '';
	} else {
		$sql_category = " category LIKE '%$category%' AND";
	}
	if ($description == '') {
		$sql_description = '';
	} else {
		$sql_description = " description LIKE '%$description%' AND";
	}
	$sql = 'SELECT count(id) FROM kongregate WHERE' . $sql_category . $sql_description . ' installed = 0 && hidden = 0';
	$query = yasDB_select($sql,false);	
	$num_games = $query->fetch_array(MYSQLI_NUM);	
	$query->close();
	
	echo '<table class="listing" cellpadding="0" cellspacing="0">
	    <tr style="font-weight: bold;">
	    <td width="100px">
		Category: '.$cat.'</td>
		<td align="center">Description: '.$des.'</td>
		<td class="last" align="center">Games: '.$num_games[0].'</td>
		</tr></table>';
	if (isset($_GET['page'])) {
	   $pageno = $_GET['page'];
	} else {
	   $pageno = 1;
	} 
	
//**********************************************************************************************************************************************			
	if (isset($_GET['filter'])) {
		$category = $_GET['category'];
		$description = yasDB_clean($_GET['description']);
	} else {
		$category = "all";
		$description = '';
	}
	if ($category == 'all') {
		$sql_category = '';
	} else {
		$sql_category = " category LIKE '%$category%' AND";
	}
	if ($keywords == '') {
		$sql_keywords = '';
	} else {
		$sql_keywords = " keywords LIKE '%$keywords%' AND";
	}
	if ($description == '') {
		$sql_description = '';
	} else {
		$sql_description = " description LIKE '%$description%' AND";
	}
	$sql = 'SELECT COUNT(id) FROM kongregate WHERE' . $sql_category . $sql_description . ' installed = 0 && hidden = 0';	
	$result = yasDB_select($sql,false);
	$query_data = $result->fetch_array(MYSQLI_NUM);
	$numrows = $query_data[0];
	$result->close();
	
	$rows_per_page = 20;
	$lastpage = ceil($numrows/$rows_per_page);
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
	?>
	<div class="table">
		<table class="listing" cellpadding="0" cellspacing="0">
			<thead>
			<tr>
				<th width="90px">Title</th>
				<th>Categories</th>
				<th>Description</th>
				<th>Install</th>
			</tr>
			</thead>
			<?php
//*************************************************************************************************************************************************	
		$sql = 'SELECT * FROM kongregate WHERE' . $sql_category . $sql_description . ' installed = 0 && hidden = 0 ORDER BY id DESC '.$limit;
		$query = yasDB_select($sql,false);
	if($query->num_rows == 0) {
    	echo '<center><b style="color: #7F7F7F">No games meet your criteria.</b></center>';
	}
	else {                         
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
			if ($row['installed'] != 1) {
				$thumburl = $row['thumbnail'];
				$desc = substr($row['description'], 0, 75);   // Limit the size of description displayed
				//$instr = substr($row['instructions'], 0, 75); 
				$gameid = $row['id'];
				$gameurl = $row['file'];
				echo '<tr>
			<td class="first style1" width="80px"><img src="'.$thumburl.'" style="width: 60px; height: 60px;" /><br/>' . $row['title'] . '</td>
			<td>' . $row['category'] . '</td>
			<td>' . $desc . '</td>';
			echo '<td><form action="index.php" method="get">
			<input type="hidden" name="act" value="managekong"/>
			<input type="submit" class="button" name="install" value="Install" /><br/><br/>';
			if (!in_array($gameid, $queids)) {
				echo '<div id="dummy'.$gameid.'"><input type="button" id="qbutton'.$gameid.'" class="button" name="que" value="Queue it" onclick="return doQue('.$gameid.', \'que\', \'kongregate\', \''. $row['title'].'\', \''.$thumburl.'\')"/></div><br/><br/>';
			} else {
				echo '<div id="dummy'.$gameid.'"><input type="button" id="qbutton'.$gameid.'" class="button_remove" name="que" value="UN-queue it"  style="font-size:11px;" onclick="return doQue('.$gameid.', \'remove\', \'kongregate\', \''. $row['title'].'\', \''.$thumburl.'\')"/></div><br/>';
			}
			echo '<a id = "game'.$i.'" href="'.$gameurl.'" target="_blank" alt="'.$row['title'].'">Try it!</a>
			<input type="hidden" name="gameid" value="'.$gameid.'" />
			<input type="hidden" name="page" value="'.$pageno.'" />
			<input type="hidden" name = "category" value="'.$category.'"/>	
			<input type="hidden" name = "description" value="'.$description.'"/>	
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
			<th width="90px">Title</th>
			<th>Categories</th>
			<th>Description</th>
			<th>Install</th>
		</tr>
		</tfoot>
	</table></div><br /><div style="text-align:center;">
	<?php
	if ($pageno == 1) {
	   echo ' FIRST PREV ';
	} else {
	   echo ' <a href="index.php?act=managekong&page=1&category='.$category.'&description='.$description.'&filter=Filter games">FIRST</a>';
	   $prevpage = $pageno-1;
	   echo ' <a href="index.php?act=managekong&page=' . $prevpage . '&category='.$category.'&description='.$description.'&filter=Filter games">PREV</a>';
	} 
	echo ' ( Page ' . $pageno . ' of ' . $lastpage . ' ) ';
	if ($pageno == $lastpage) {
	   echo ' NEXT LAST ';
	} else {
	   $nextpage = $pageno+1;
	   echo ' <a href="index.php?act=managekong&page=' . $nextpage . '&category='.$category.'&description='.$description.'&filter=Filter games">NEXT</a>';
	   echo ' <a href="index.php?act=managekong&page=' . $lastpage . '&category='.$category.'&description='.$description.'&filter=Filter games">LAST</a>';
	} 
	echo '</div>';
	if (isset($_GET['install'])) {
		if ($_GET['install'] == 'Install') {
			install_konggame($_GET['gameid']) or die("Game did not install successfully");
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
			echo '<META HTTP-EQUIV="Refresh" CONTENT="0; URL=index.php?act=managekong&page='.$pageno.'&category='.$category.'&description='.$description.'&filter=Filter games">';
			exit();
		}
		
	}
?>
</div>                      