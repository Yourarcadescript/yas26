<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style>
    *
    {
		margin:0px; padding:0px;
    }
    body
    {
		background:#284062 url('images/background.png') repeat-x top left fixed; margin: 0; padding: 0;
		text-align: center; padding: 0 0 0 0; margin: 0 0 0 0; font-weight: normal; font-size: 12px;
		font-family: Tahoma, Arial, sans-serif;
    }
    img
    {
		border: none;
    }
    .clear
    {
		clear:both;
    }
    #body_wrapper
    {
		margin: 0 auto;   width: 951px; text-align: left;
    }
    #top
    {
		background-image: url('images/workmaintenance.png'); background-repeat:no-repeat;
		width: 950px; height: 60px; position: relative; text-align: center;background-position:center;
    }
    #menu
    {
		color: #ffffff; background:transparent; background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAAoCAYAAAA/tpB3AAAAAXNSR0IArs4c6QAAAAZiS0dEAP8A/wD/oL2nkwAAAAlwSFlzAAALEgAACxIB0t1+/AAAAAd0SU1FB9sDBRYILVH+xREAAAAkSURBVAjXY2BgYPjPZGScxsD0l4GBgekfAwMtWUwkE8zEqQMAdPwWDNXfT1kAAAAASUVORK5CYII=);
		background-repeat: repeat-x; background-position: top-left;
		width: 950px; line-height: 16px; padding-top: 8px; padding-bottom: 8px; text-align: center;
    }
    #wrapper
    {
		width: 934px; color: #fff; background-color: #00132F;
		padding-top: 8px; padding-bottom: 8px; padding-left: 10px; border:solid 3px #245A7A; text-align: center;
	}
	#game 
	{
	width: 600px; color: #fff; background-color: #00132F; border:solid 3px #245A7A; text-align: center;
	margin-left:160px; margin-top:6px;padding: 2px;
	}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Disable</title>
</head>
<body>
<div id="body_wrapper">
	<div id="top"></div>
    <div id="menu">Site Disabled:</div>
    <div id="wrapper">
	
<?php 
if ($setting['disabled'] == 'yes') {
    echo "This site is closed as work maintenance is in progress and will open shortly!</br>
	While you wait you can still enjoy our free games";
}
$displayed = 'no';
$query = yasDB_select("SELECT * FROM games order by rand() LIMIT 1");
$row = $query->fetch_array(MYSQLI_ASSOC);
if ($row['type'] == 'SWF') {
	if ($row['height']<350 || $row['width']<350) { // games are not likely less than 350px so lets double check the actual game size
		list($width, $height, $type, $attributes) = getimagesize($setting['sitepath'].'/'.$row['file']);
		$row['height'] = $height;
		$row['width'] = $width;
	}
	$ratio = $row['height']/$row['width'];
	$new_width = 600;
	$new_height = ceil($new_width*$ratio);
}

?>
<div id="game"><?php echo $row['title'].'<'.$setting['lightbox'].'>';?></div> 
<div id="game">
      <?php
	if ($row['type']=='SWF' && $displayed == 'no') {
		echo '<div id="gameDiv" style="height: '.$new_height.'px; width: '.$new_width.'px; position:relative; top: 0; left: 0">';
		include "media/swf.php";
		echo '</div>';
    }else if($row['type']=='DCR') {              
		include "media/dcr.php"; 
	}else if($row['type']=='WMV') {
		include "media/wmv.php";
	}else if($row['type']=='MPG' && $displayed == 'no') {
		include "media/mpg.php";
	}else if($row['type']=='AVI') {
		include "media/avi.php";
	}else if($row['type']=='MOV') {
		include "media/mov.php";
	}else if($row['type']=='IMAGE' && $displayed == 'no') {
		include "media/image.php";
	}else if($row['type']=='FLV') {
		include "media/flv.php";
	}else if($row['type']=='YOUTUBE') {
		include "media/youtube.php";
	}else if($row['type']=='CustomCode') {
		echo"$row[code]";
	}	
    ?>
</div>
<div id="game">
<?php
		echo '
		    <iframe src="http://www.facebook.com/plugins/like.php?href='.urlencode($setting['siteurl']).'&amp;layout=standard&amp;
			show_faces=true&amp;width=400&amp;action=like&amp;font=lucida+grande&amp;colorscheme=light&amp;height=28" scrolling="no" frameborder="0" 
			style="border:none; overflow:hidden; width:260px; height:28px;" allowTransparency="true"></iframe>
			';?></div>
<div class="clear"></div>
	</div></div>
	</body>
</html> 