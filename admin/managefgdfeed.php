<?php
include_once("fgd_functions.php");
if (isset($_GET['feed'])) {
	get_fgdfeed();
}
?>
<div id="center-column">
<div class="top-bar">
<h1>Install FlashGameDistribution.com Games</h1></div>
<br/>
<div class="select-bar">
</div>
<?php
$result = yasDB_select("SELECT COUNT(id) FROM `fgdfeed`");
$query_data = $result->fetch_array(MYSQLI_NUM);
$numrows = $query_data[0];
$result->close();
$result2 = yasDB_select("SELECT `sourceid` FROM `gameque` WHERE `source` = 'fgd'");
$queids = array();
while ($source = $result2->fetch_array(MYSQLI_NUM)) {
	$queids[] = $source[0];
}
?>
<script type="text/javascript">
$(document).ready(function() {
    $("#game").fancybox({
        'type'  : 'swf',
        'padding'  : 0,
        'autoScale'  : true,
        'transitionIn' : 'elastic',
        'transitionOut' : 'elastic'
    });
});
</script>
<center>
<form id="fgdfeed" action="index.php" method="get">
	<input type="hidden" name="act" value="managefgdfeed">
	<input type="submit" class="button" name="feed" value="Get Feed" />
</form>
<h4>Note: Installing many games at once is resource intensive.</h4></center>
<div style="position:relative;display:block;height:125px;padding-bottom:10px;">
<div style="position:relative;float:left">
<form enctype="multipart/form-data" action="index.php" method="get">
	<input type="hidden" name="act" value="managefgdfeed">
	<label for="category_filter">Category:</label>
	<select name="category" id="category">
		<option value="all" selected>All Categories</option>
		<optgroup label="category">
		<option value="action">Action</option>
		<option value="adventure">Adventure</option>
		<option value="defense">Defense</option>
		<option value="casino">Casino</option>
		<option value="gadgets">Gadgets</option>
		<option value="driving">Driving</option>
		<option value="rpg">RPG</option>
		<option value="fighting">Fighting</option>
		<option value="multiplayer">Multiplayer</option>
		<option value="other">Other</option>
		<option value="arcade">Arcade</option>
		<option value="puzzle">Puzzle</option>
		<option value="shooter">Shooter</option>
		<option value="rhythm">Rhythm</option>
		<option value="sports">Sports</option>
		<option value="strategy">Strategy</option>
		</optgroup>
	</select>
	<label>&nbsp;&nbsp;Rating</label>
	<select id="rating" name="rating">
		<option value="all" selected>All</option>
		<option value="everyone">Everyone</option>
		<option value="mature">Mature</option>
	</select><br/><br/>
	Description:<input type="text" name="description"/>
	Keywords:<input type="text" name="keywords"/>
	<br/><br/><br/><br/>
	<input type="submit" class="button" name="filter" value="Filter Games" />
	</div>
	<div style="position:relative;float:left;margin-left:30px;">
		<input type="checkbox" name="featured" value="featured" /> Featured Games<br>
		<input type="checkbox" name="multiplayer" value="multiplayer" /> Multiplayer<br>
		<input type="checkbox" name="highscores" value="highscores" /> Highscores<br>
		<input type="checkbox" name="mobileready" value="mobileready" /> Mobile Ready<br>
		<input type="checkbox" name="hasads" value="hasads" /> No Pregame Ads
	</div>
</form>
<?php // display:none --- Install All on development To-Do list *********************************************************************************?>
<div style="position:relative;display:none;float:left;margin:5px 0 0 0;"> 
	<form action="index.php" method="get">
		<input type="hidden" name="act" value="mochiall"/>
		<input type="submit" class="button" name="install" value="Install Filtered List"/></center>
		<input type="hidden" name = "category" value="<?php echo $_GET['category']; ?>"/>
		<input type="hidden" name= "rating" value ="<?php echo $_GET['rating']; ?>"/>
		<input type="hidden" name="keywords" value ="<?php echo $_GET['keywords']; ?>"/>
		<input type="hidden" name = "description" value="<?php echo $_GET['description']; ?>"/>
		<input type="hidden" name = "featured" value="<?php echo $_GET['featured']; ?>"/>
		<input type="hidden" name = "multiplayer" value="<?php echo $_GET['multiplayer']; ?>"/>
		<input type="hidden" name = "highscores" value="<?php echo $_GET['highscores']; ?>"/>
		<input type="hidden" name = "mobileready" value="<?php echo $_GET['mobileready']; ?>"/>
		<input type="hidden" name = "hasads" value="<?php echo $_GET['hasads']; ?>"/>
	</form>
</div>
</div>
<?php
if (!isset($_GET['category']) || empty($_GET['category'])) {
    $cat = 'all';
} else {
    $cat = $_GET['category'];
}
if (!isset($_GET['rating']) || empty($_GET['rating'])) {
    $rat = 'all';
} else {
    $rat = $_GET['rating'];
}
if (!isset($_GET['description']) || empty($_GET['description'])) {
    $des = '---';
} else {
    $des = $_GET['description'];
}
if (!isset($_GET['keywords']) || empty($_GET['keywords'])) {
    $key = '---';
} else {
    $key = $_GET['keywords'];
}
if (!empty($_GET['category'])) {
    $category = $_GET['category'];
    $rating = $_GET['rating'];
    $description = yasDB_clean($_GET['description']);
    $keywords = yasDB_clean($_GET['keywords']);
} else {
    $category ='all';
    $rating = 'all';
    $description = '';
    $keywords = '';
}
if ($rating == 'all') {
    $sql_rating = '';
} elseif ($rating == 'mature'){
    $sql_rating = " mature = 1 AND";
} else {
    $sql_rating = " mature = 0 AND";
}
if ($category == 'all') {
    $sql_category = '';
} else {
    $sql_category = " categories LIKE '%$category%' AND";
}
if ($keywords == '') {
    $sql_keywords = '';
} else {
    $sql_keywords = " tags LIKE '%$keywords%' AND";
}
if ($description == '') {
    $sql_description = '';
} else {
    $sql_description = " description LIKE '%$description%' AND";
}
if (!isset($_GET['featured']) || empty($_GET['featured'])) {
    $sql_featured = '';
} else {
    $sql_featured = " featured = 1 AND";
}
if (!isset($_GET['multiplayer']) || empty($_GET['multiplayer'])) {
    $sql_multiplayer = '';
} else {
    $sql_multiplayer = " multiplayer = 1 AND";
}
if (!isset($_GET['highscores']) || empty($_GET['highscores'])) {
    $sql_highscores = '';
} else {
    $sql_highscores = " highscores = 1 AND";
}
if (!isset($_GET['mobileready']) || empty($_GET['mobileready'])) {
    $sql_mobileready = '';
} else {
    $sql_mobileready = " mobileready = 1 AND";
}
if (!isset($_GET['hasads']) || empty($_GET['hasads'])) {
    $sql_hasads = '';
} else {
    $sql_hasads = " hasads = 0 AND";
}
//  Number of games  //
$sql = 'SELECT count(id) FROM fgdfeed WHERE' . $sql_rating . $sql_category . $sql_keywords . $sql_description . $sql_featured . $sql_multiplayer . $sql_highscores . $sql_mobileready . $sql_hasads . ' installed = 0';
$query = yasDB_select($sql,false);
$num_games = $query->fetch_array(MYSQLI_NUM);
$query->close();
echo '<table class="listing" cellpadding="0" cellspacing="0">
<tr style="font-weight: bold;">
<td width="80px">Category: '.$cat.'</td>
<td align="center">Rating: '.$rat.'</td>
<td align="center">Description: '.$des.'</td>
<td align="center">Keywords: '.$key.'</td>
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
    $rating = $_GET['rating'];
    $description = yasDB_clean($_GET['description']);
    $keywords = yasDB_clean($_GET['keywords']);
    $featured = isset($_GET['featured'])?$_GET['featured']:'';
    $multiplayer = isset($_GET['multiplayer'])?$_GET['multiplayer']:'';
    $highscores = isset($_GET['highscores'])?$_GET['highscores']:'';
    $mobileready = isset($_GET['mobileready'])?$_GET['mobileready']:'';
    $hasads = isset($_GET['hasads'])?$_GET['hasads']:'';
} else {
    $category = "all";
    $rating = "all";
    $description = '';
    $keywords = '';
    $featured = '';
    $multiplayer = '';
    $highscores = '';
    $mobileready = '';
    $hasads = '';
}
if ($rating == 'all') {
    $sql_rating = '';
} elseif ($rating == 'mature'){
    $sql_rating = " mature = 1 AND";
} else {
    $sql_rating = " mature = 0 AND";
}
if ($rating == 'all') {
    $sql_rating = '';
} elseif ($rating == 'mature'){
    $sql_rating = " mature = 1 AND";
} else {
    $sql_rating = " mature = 0 AND";
}
if ($category == 'all') {
    $sql_category = '';
} else {
    $sql_category = " categories LIKE '%$category%' AND";
}
if ($keywords == '') {
    $sql_keywords = '';
} else {
    $sql_keywords = " tags LIKE '%$keywords%' AND";
}
if ($description == '') {
    $sql_description = '';
} else {
    $sql_description = " description LIKE '%$description%' AND";
}
if (!isset($_GET['featured']) || empty($_GET['featured'])) {
    $sql_featured = '';
} else {
    $sql_featured = " featured = 1 AND";
}
if (!isset($_GET['multiplayer']) || empty($_GET['multiplayer'])) {
    $sql_multiplayer = '';
} else {
    $sql_multiplayer = " multiplayer = 1 AND";
}
if (!isset($_GET['highscores']) || empty($_GET['highscores'])) {
    $sql_highscores = '';
} else {
    $sql_highscores = " highscores = 1 AND";
}
if (!isset($_GET['mobileready']) || empty($_GET['mobileready'])) {
    $sql_mobileready = '';
} else {
    $sql_mobileready = " mobileready = 1 AND";
}
if (!isset($_GET['hasads']) || empty($_GET['hasads'])) {
    $sql_hasads = '';
} else {
    $sql_hasads = " hasads = 0 AND";
}
$sql = 'SELECT COUNT(id) FROM fgdfeed WHERE' . $sql_rating . $sql_category . $sql_keywords . $sql_description . $sql_featured . $sql_multiplayer . $sql_highscores . $sql_mobileready . $sql_hasads . ' installed = 0';
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
	<th width="90px">Categories</th>
	<th>Rating</th>
	<th>Description</th>
	<th>Keywords</th>
	<th>Highscores</th>
	<th>Install</th>
</tr>
</thead>
<?php
//*************************************************************************************************************************************************
$sql = 'SELECT * FROM fgdfeed WHERE' . $sql_rating . $sql_category . $sql_keywords . $sql_description . $sql_featured . $sql_multiplayer . $sql_highscores . $sql_mobileready . $sql_hasads . ' installed = 0 ORDER BY id DESC '.$limit;
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
                'type'  : 'swf',
                'padding'  : 0,
                'autoScale'  : true,
                'transitionIn' : 'elastic',
                'transitionOut' : 'elastic'
            });
        });
        </script>
        <?php
        if ($row['installed'] != 1) {
            $thumburl = $row['thumbfile'];
            $desc = substr($row['description'], 0, 75) . "...";  // Limit the size of description displayed
            $keys = substr($row['tags'], 0, 75);
            $gameid = $row['id'];
            $rating = ($row['mature'] == 1)?'Mature':'Everyone';
            $leaderboard = ($row['highscores'] == 1)?'Yes': '';
            $gameurl = $row['gamefile'];
            echo '<tr>
            <td class="first style1" width="70px"><img src="'.$thumburl.'" style="width:60px;height:60px;" /><br>' . $row['title'] . '</td>
            <td width="80px">' . str_replace(",","<br>",$row['categories']) . '</td>
            <td width="80px">' . $rating. '</td>
            <td width="80px">' . $desc . '</td>
            <td width="80px">' . str_replace(","," ",$keys) . '</td>
            <td width="80px">';
			if ($leaderboard == 'Yes') {
				echo '<img src="img/checkmark.png" width="16" height="16" alt="Yes" />';
			} 
			echo '</td>
            <td class="last" width="80px"><form action="index.php" method="get">
            <input type="hidden" name="act" value="managefgdfeed"/>
            <input type="submit" class="button" name="install" value="Install" /><br/><br/>';
			if (!in_array($gameid, $queids)) {
				echo '<div id="dummy'.$gameid.'"><input type="button" id="qbutton'.$gameid.'" class="button" name="que" value="Queue it" onclick="return doQue('.$gameid.', \'que\', \'fgd\', \''. $row['title'].'\', \''.$thumburl.'\')"/></div><br/><br/>';
			} else {
				echo '<div id="dummy'.$gameid.'"><input type="button" id="qbutton'.$gameid.'" class="button_remove" name="que" value="UN-queue it" style="font-size:11px;" onclick="return doQue('.$gameid.', \'remove\', \'fgd\', \''. $row['title'].'\', \''.$thumburl.'\')"/></div><br/>';
			}
            echo '<a id = "game'.$i.'" href="'.$gameurl.'" target="_blank" alt="'.$row['title'].'">Try it!</a>
            <input type="hidden" name="gameid" value="'.$gameid.'" />
            <input type="hidden" name="page" value="'.$pageno.'" />
            <input type="hidden" name = "category" value="'.$category.'"/>
            <input type="hidden" name="rating" value ="'.$rating.'"/>
            <input type="hidden" name = "description" value="'.$description.'"/>
            <input type="hidden" name="keywords" value ="'.$keywords.'"/>
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
	<th width="90px">Categories</th>
	<th>Rating</th>
	<th>Description</th>
	<th>Keywords</th>
	<th>Highscores</th>
	<th>Install</th>
</tr>
</tfoot>
</table></div><br/><div style="text-align:center;">
<?php
if ($pageno == 1) {
    echo ' FIRST PREV ';
    } else {
    echo ' <a href="index.php?act=managefgdfeed&page=1&category='.$category.'&rating='.$rating.'&description='.$description.'&keywords='.$keywords.'&filter=Filter games">FIRST</a>';
    $prevpage = $pageno-1;
    echo ' <a href="index.php?act=managefgdfeed&page=' . $prevpage . '&category='.$category.'&rating='.$rating.'&description='.$description.'&keywords='.$keywords.'&filter=Filter games">PREV</a>';
}
echo ' ( Page ' . $pageno . ' of ' . $lastpage . ' ) ';
if ($pageno == $lastpage) {
    echo ' NEXT LAST ';
    } else {
    $nextpage = $pageno+1;
    echo ' <a href="index.php?act=managefgdfeed&page=' . $nextpage . '&category='.$category.'&rating='.$rating.'&description='.$description.'&keywords='.$keywords.'&filter=Filter games">NEXT</a>';
    echo ' <a href="index.php?act=managefgdfeed&page=' . $lastpage . '&category='.$category.'&rating='.$rating.'&description='.$description.'&keywords='.$keywords.'&filter=Filter games">LAST</a>';
}
echo '</div>';
if (isset($_GET['install'])) {
    if ($_GET['install'] == 'Install') {
        install_fgdgame($_GET['gameid']) or die("Game did not install successfully");
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
        if (isset($_GET['rating'])) {
            $rating = $_GET['rating'];
            } else {
            $rating = 'all';
        }
        $description = yasDB_clean($_GET['description']);
        $keywords = yasDB_clean($_GET['keywords']);
        echo '<META HTTP-EQUIV="Refresh" CONTENT="0; URL=index.php?act=managefgdfeed&page='.$pageno.'&category='.$category.'&rating='.$rating.'&description='.$description.'&keywords='.$keywords.'&filter=Filter games">';
        exit();
    }
}
?>
</div>