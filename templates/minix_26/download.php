<div id="center">

<?php

include_once($setting['sitepath'].'/includes/pagination.class.php');

$setting['gperpage'] = ($setting['gperpage']<1?15:$setting['gperpage']);

?>

<div class="container_box1">

<div id="headergames2">Download Games</div>            

<?php

ini_set( "display_errors", 0);

if ($_POST['upload']=="1")

{

	include_once ($setting['siteurl']."/includes/db_functions.inc.php");

	$email=yasDB_clean($_POST['email']);

	$query = yasDB_insert("INSERT INTO notifydown (email) VALUES ('$email')",false);

	if (!$query){

		echo("Database Error!");

		} else {

		echo("<center>Email Submited!<br />We will send you email when we add new game.</center>");

		}

} else {

	echo '

	<center>

	<form method="post">

	<input type="hidden" name="upload" value="1" />

	Enter your email to be notified about our new addition of downloadable games:

	<br />

	<input type="text" name="email" maxlength="30" width="300" />

	<input type="submit"  value="Submit" />

	</form>

	</center>';

	$result = yasDB_select("SELECT count(id) FROM `downgames` ");

	$query_data = $result->fetch_array(MYSQLI_NUM);

	$numrows = $query_data[0];

	$result->close();

	$pageurl = new pagination($numrows, $setting['seo'], 'download', $setting['gperpage'], 3);

	$select_games = yasDB_select("SELECT * FROM downgames ORDER BY `id` DESC LIMIT " . $pageurl->start . ", " . $pageurl->limit);

	while($games = $select_games->fetch_array(MYSQLI_ASSOC)){

		$thumbpath= $setting['siteurl'].urldecode(str_replace("../", "",$games['thumbnail']));

		$filepath=str_replace("../", "",$games['file']);

		if(strlen($games['description']) > 180) {

			$games['description'] = substr($games['description'],0,180).'...';

		}

		$games['description'] = stripslashes($games['description']);

		$description = str_replace(array("\r\n", "\r", "\n", "'", '"'), ' ', $games['description']);

		$pic_settings = array('w'=>130,'h'=>100);

		echo '<div class="download">

		<ul>

		<li class="title"><div class="downloadheader">'.$games['title'].'</div></li>

		<li class="even"><div class="container_box7"><div class="gameholderimg"><img align="absmiddle" src="'.resize($thumbpath, $pic_settings,"download").'" alt="' . $thumbpath . '" width="130" height="100" /></div><div class="desc">'.$games['description'].'</div>';

		echo '<div class="clear"></div><div class="downloadgame"><center><a href="'.$setting['siteurl'].$filepath.'" onclick="return download_link('.$games['id'].')"><img align="absmiddle" src="'.$setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/buttons/download.png" width="100" height="30" /></a></center></div>';

		echo '</div><div class="clear"></div></li></ul></div>';

	}

	$select_games->close();	 

}

?>

<div id="page_box">

<?php

echo $pageurl->showPagination();

?>

</div>	

<div class="clear"></div>

</div>