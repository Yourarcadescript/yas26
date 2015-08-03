<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
include_once("agf_functions.php");
if(isset($_GET['updatefeed'])) {
  get_agffeed();
}
?>
<div id="center-column">
<div class="top-bar">
<h1>Install ArcadeGameFeed.com - Game Feed for Your Arcade!</h1>
<div class="select-bar">
<center>
<form id="agffeed" name="agffeed" method="get" action="index.php">
<input type="hidden" name="act" value="manageagffeed">
<input type="submit" class="button" name="updatefeed" value="Update Feed" />
</form></center></div>
<?php
$catnames = array('Puzzle','Action','Adventure','Sports','Shooter','Casino','Other','Dressup','Arcade','Strategy','Cartoon','Coloring');
$result = yasDB_select("SELECT COUNT(id) FROM `agffeed`");
$query_data = $result->fetch_array(MYSQLI_NUM);
$numrows = $query_data[0];
$result->close();
if($numrows == 0){
  echo '<center><h3>';
  echo 'Feed is not installed.';
  echo '</h3></center></div>';

} else {
?>
<script type="text/javascript">
    $(document).ready(function() {
      $("#game").fancybox({
        'type'              : 'swf',
        'padding'      : 0,
        'autoScale'      : true,
        'transitionIn'    : 'elastic',
        'transitionOut'    : 'elastic'
      });
    });
</script>
<center><p align="center">
<form enctype="multipart/form-data" action="index.php" method="get">
  <input type="hidden" name="act" value="manageagffeed">
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
    <option value="11">Cartoon</option>
    <option value="12">Coloring</option>
    </optgroup>
      </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <label for="rating_filter">Rating:</label>
        <select id="rating" name="rating">
        <option value="All Ages">All Ages</option>
        <option value="Teens">Teen and Up</option>
      </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <label for="ads_filter">Game Ads:</label>
      <select id="ads" name="ads">
      <option value="None" selected>None</option>
      <option value="CPM">CPM</option>
      <option value="Star">Star</option>
      <option value="Other">Other</option>
    </select><br/><br/>
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
if (!isset($_GET['rating']) || empty($_GET['rating'])) {
  $rnum = '---';
  $rating = '';
  $sql_rating = '';
} else {
  $rnum = $_GET['rating'];
  $rating = yasDB_clean($_GET['rating']);
  $sql_rating = " rating LIKE '%$rating%' AND";
}
if (!isset($_GET['ads']) || empty($_GET['ads'])) {
  $anum = '---';
  $ads = '';
  $sql_ads = '';
} else {
  $anum = $_GET['ads'];
  $ads = yasDB_clean($_GET['ads']);
  $sql_ads = " ads LIKE '%$ads%' AND";
}

$sql = 'SELECT count(id) FROM agffeed WHERE' . $sql_category . $sql_title . $sql_description . $sql_rating . $sql_ads . ' installed = "0"';
$query = yasDB_select($sql,false);
$num_games = $query->fetch_array(MYSQLI_NUM);
$query->close();
echo '<table class="listing" cellpadding="0" cellspacing="0">
      <tr style="font-weight: bold;">
      <td align="center">Title: '.$tnum.'</td>
    <td align="center">Category: '.$cat.'</td>
    <td align="center">Description: '.$des.'</td>
    <td align="center">Rating: '.$rnum.'</td>
    <td align="center">Ads: '.$anum.'</td>
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
$sql = 'SELECT * FROM agffeed WHERE' . $sql_category .$sql_title. $sql_description . $sql_rating . $sql_ads . ' installed = "0" ORDER BY id DESC '.$limit;
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
        'padding'      : 0,
        'autoScale'      : true,
        'transitionIn'    : 'elastic',
        'transitionOut'    : 'elastic'

      });
    });
    </script>
    <?php
    if ($row['installed'] != "1") {
      $thumburl = $row['thumbnail'];
      $desc = substr($row['description'], 0, 150) . '...';   // Limit the size of description displayed
      $twords = substr($row['title'], 0, 75);
      $gameid = $row['id'];
      $gameurl = $row['file'];
      echo '<tr>
        <td class="first style1" width="80px"><img src="'.$thumburl.'" style="width: 80px; height: 80px;" /></td>
        <td>' . $twords . '</td>
        <td>' . $catnames[$row['category']-1] . '</td>
        <td>' . $desc . '</td>
        <td class="last"><form action="index.php" method="get">
        <input type="hidden" name="act" value="manageagffeed"/>
        <input type="submit" class="button" name="install" value="Install" /><br/><br/>
        <a id = "game'.$i.'" href="'.$gameurl.'" target="_blank" alt="'.$row['title'].'">Try it!</a>
        <input type="hidden" name="gameid" value="'.$gameid.'" />
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
   echo ' <a href="index.php?act=manageagffeed&page=1&category='.$category.'&description='.$description.'&title='.$title.'&filter=Filter games">FIRST</a>';
   $prevpage = $pageno-1;
   echo ' <a href="index.php?act=manageagffeed&page=' . $prevpage . '&category='.$category.'&description='.$description.'&title='.$title.'&filter=Filter games">PREV</a>';
}
echo ' ( Page ' . $pageno . ' of ' . $lastpage . ' ) ';
if ($pageno == $lastpage) {
   echo ' NEXT LAST ';
} else {
   $nextpage = $pageno+1;
   echo ' <a href="index.php?act=manageagffeed&page=' . $nextpage . '&category='.$category.'&description='.$description.'&title='.$title.'&filter=Filter games">NEXT</a>';
   echo ' <a href="index.php?act=manageagffeed&page=' . $lastpage . '&category='.$category.'&description='.$description.'&title='.$title.'&filter=Filter games">LAST</a>';
}
echo '</div>';
if (isset($_GET['install'])) {
  if ($_GET['install'] == 'Install') {
    install_agfgame($_GET['gameid']) or die("Game did not install successfully");
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
    echo '<META HTTP-EQUIV="Refresh" CONTENT="0; URL=index.php?act=manageagffeed&page='.$pageno.'&description='.$description.'&title='.$title.'&filter=Filter games">';
    exit();
  }
}
?>
<br/></div>
<?php } ?></div>