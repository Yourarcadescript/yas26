<?php
function getGame($id) {
	$query = yasDB_select("SELECT `file` from `games` WHERE `id` = ".intval($id));
	$gfile = $query->fetch_row();
	return $gfile(0);
}
?>
<script type="text/javascript">
	var yasurl = "<?php echo $setting['siteurl'].'includes/ratingbar/rpc.php';?>";
</script>
<?php
require('./includes/ratingbar/_drawrating.php'); // processes game votes and displays stars
$gid = $id;
$displayed = 'no';
$file = 'meep';
if (isset($_POST['Favourites_x'])) {
	yasDB_insert("INSERT INTO favourite (userid, gameid) VALUES({$_SESSION['userid']}, ".intval($_POST['gamesave']).")") or die("Error updating favourites");
}
$query = yasDB_select("SELECT * FROM `games` WHERE id = ".$id);
$row = $query->fetch_array(MYSQLI_ASSOC);
if ($row['type'] == 'SWF' || $row['type'] == 'UNITY'){ // Or UNITY if you have changed to it 
	if ($row['height']<350 || $row['width']<350) { // games are not likely less than 350px so lets double check the actual game size
		list($width, $height, $type, $attributes) = getimagesize($setting['sitepath'].'/'.$row['file']);
		$row['height'] = $height;
		$row['width'] = $width;
	}
	$ratio = $row['height']/$row['width'];
	$new_width = 720;
	$new_height = ceil($new_width*$ratio);
}
if (file_exists($row['thumbnail'])) {
	$thumbnail = $setting['siteurl'].$row['thumbnail'];
	$thumbnail = urldecode($thumbnail);
} else {
	$thumbnail = $setting['siteurl'].'img/nopic.jpg';
}
/////////////////////////////////////////////////
//											   //
//				if light box is on			   //	
/////////////////////////////////////////////////
if ($setting['lightbox'] == 'yes') {
	if ($row['type'] == 'SWF' || $row['type'] == 'MPG' || $row['type'] == 'IMAGE' || $row['type'] == 'YOUTUBE' || $row['type'] == 'CustomCode'){ 
		$displayed = 'yes';
		
		?>
		<div id="center">  			 
            <div class="container_box1">
<!-- AJ-BreadCrumb Start -->
<div class="header">
<ul id="breadcrumb">
<!-- home-nav -->
<li><a href="<?php echo $setting['siteurl'];?>" onMouseover="showhint('<b><?php echo $setting['sitename'];?></b>',this, event, 'auto')">Home</a></li>
<!-- home-nav-end -->
<!-- start: cat-nav -->
<li><?php
$catcheck = yasDB_select("SELECT `name` FROM `categories` WHERE `id` = " . $row['category']);
$catname = $catcheck->fetch_array(MYSQLI_ASSOC);
if ($setting['seo']=='yes') { ?>
    <a href="<?php echo $setting['siteurl'];?>category/<?php echo $row['category'];?>/1.html" onMouseover="showhint('<b><?php echo ucfirst($catname['name']);?></b>',this, event, 'auto')"><?php echo ucfirst($catname['name']);?></a>
    <?php
} else { ?>
    <a href="<?php echo $setting['siteurl'];?>index.php?act=cat&id=<?php echo $row['category'];?>" onMouseover="showhint('<b><?php echo ucfirst($catname['name']);?></b>',this, event, 'auto')"><?php echo ucfirst($catname['name']);?></a>
    <?php
}
?>
</li>
<!-- end: cat-nav --><!-- start: game_nav -->
<li><?php echo $row['title'];?></li>
<!-- end: game_nav -->
</ul>
</div>
<!-- AJ-BreadCrumb-End -->							
				<?php // set light box according to type
				
				switch ($row['type']) {
					case 'CustomCode':
						$class = 'embed';
						$file = "";
						?>
						<script type="text/javascript">
							var embedcode = '<?php echo json_encode($row['code']); ?>';
							$(document).ready(function() {
								$("a.embed").fancybox({
									'padding'			: 0,
									'autoScale'			: false,
									'transitionIn'		: 'elastic',
									'transitionOut'		: 'fade',
									'width'             : 'auto',
									'height'            : 'auto',
									'hideOnContentClick': false,
									'hideOnOverlayClick': false,
									'content'           : embedcode									
								});
							});
						</script>
						<?php
						break;
					case 'SWF':
						$class = 'game';
						$file = $setting['siteurl'].$row['file'];
						?>
						<script type="text/javascript">
							$(document).ready(function() {
								$("a.game").fancybox({
									'type'              : 'swf',
		 							'padding'			: 0,
									'autoScale'			: false,
									'transitionIn'		: 'elastic',
									'transitionOut'		: 'fade',
									'width'             : <?php echo $row['width'];?>,
									'height'            : <?php echo $row['height'];?>,
									'hideOnContentClick': false,
									'hideOnOverlayClick': false,
									'swf' : {'wmode' : 'transparent','allowfullscreen' : 'true'}
								});
							});
						</script>
						<?php
						break;
					case 'YOUTUBE':
						$class = 'youtube';
						$file = str_replace("watch?v=", "v/", $row['file']). '&fs=1';
						?>
						<script type="text/javascript">
							$(document).ready(function() {
								$("a.youtube").fancybox({
									'type'              : 'swf',
									'padding'			: 0,
									'transitionIn'		: 'elastic',
									'transitionOut'		: 'fade',
									'width'             : 680,
									'height'            : 495,
									'hideOnContentClick': false,
									'hideOnOverlayClick': false
								});
							});
						</script>
						<?php
						break;
					default:
						break;
				} ?>
				<div style="width:728px;height:90px;background-color:#E3ECFD;color:#000;margin:37px 0 10px 10px;">
				<?php 
				echo ad("12");
				 ?>
				</div>
				<div class="gameplay_box">
				<div id="banner_ad">
					<?php
					if (ad("8") == "Put AD code here") {
						?>
						<img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/100x100banner.png';?>" width="100" height="100" alt="100x100 Banner Exchange" />
						<?php
					} else {
						echo ad("8");
					} ?>
				</div>
				<div id="banner_ad2">
					<?php
					if (ad("9") == "Put AD code here") {
						?>
						<img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/100x100banner.png';?>" width="100" height="100" alt="100x100 Banner Exchange" />
						<?php
					} else {
						echo ad("9");
					} ?>
				</div>
                <div class="gameplay_pic">
				<a class="<?php echo $class;?>" href="<?php echo $file;?>" title="Playing: <?php echo $row['title'];?>"> 
				  <img src="<?php echo $thumbnail;?>" alt="Playing: <?php echo $row['title'];?>" width="150" height="150"/></a>
				  </div><br/>
				 <div class="gameplay_btn"><a class="<?php echo $class;?>" href="<?php echo $file;?>" title="Playing: <?php echo $row['title'];?>"> 
				  <img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/buttons/play.png';?>" width="100" height="30" alt="Playing: <?php echo $file;?>" /></a></div>
				  </div>
				<div class="clear"></div>
				<?php
				// The following code is for a box of random games
				echo '<div class="random_box">';
				$query = yasDB_select("SELECT * FROM games order by rand() limit 7");
				while($games = $query->fetch_array(MYSQLI_ASSOC)) {
					
					$gameurl = $games['title'];
					$gameurl = str_replace(" ", "-", $gameurl);
					$gameurl = str_replace("'", "_", $gameurl);
					$gameurl = str_replace('"', "_", $gameurl);
					$gameurl = str_replace('/', "_", $gameurl);
					$gameurl = str_replace("\\", "_", $gameurl);
					$gameurl = rawurlencode($gameurl);
					if (file_exists($games['thumbnail'])) {
						$thumbnail = $games['thumbnail'];						
					} else {
						$thumbnail = 'img/nopic.jpg';
					}
					if ($setting['seo']=="yes") {
					    echo '<div class="ranbox">';
						echo'<a href="' . $setting['siteurl'] . 'game/' . $games['id'] . '/' . $gameurl . '.html' . '">';
						echo '<img src="' . $setting['siteurl'] . '' . $thumbnail . '" width="80" height="80" /></a></div>';                         
					}else {
					    echo '<div class="ranbox">';
						echo '<a href="' . $setting['siteurl'] . 'index.php?act=game&id=' . $games['id'] . '">';
						echo '<img src="' . $setting['siteurl'] . '' . $thumbnail . '" width="80" height="80" alt="'.$games['title'].'" title="'.$games['title'].'"/></a></div>';
					}
					
				}
				$query->close();
				echo '</div>';?>
				         	
		<?php	if(isset($_SESSION["user"])) { 
					$user=$_SESSION["user"];
					$userid = $_SESSION['userid'];
					echo '<div class="favorites_box">';
					echo '<form method="post" action="">';
				    echo '<input type="hidden" name="gamesave" value="' . $row['id'] .'" />';
					echo '<input type="image" name="Favourites" value="Favourites" alt="Save to Favorites" title="Save to Favorites" src="' . $setting['siteurl'] . 'templates/' . $setting['theme'] . '/skins/'.$setting['skin'].'/images/buttons/favorite.png" width="40" height="40" onclick="addFavorite(\''.$row['title'].'\',\''.$id.'\');return false">
					</form></div>';
				}
	}
	else {
		$setting['lightbox'] = "no";
	}		
}
/////////////////////////////////////////////////////////////////////
//											                       //
//		if light box is off	or unsupported light box format		   //	
/////////////////////////////////////////////////////////////////////
if ($setting['lightbox']=='no'){
    ?>
<div id="center">  
 
<div class="container_box1">
<!-- AJ-BreadCrumb Start -->
<ul id="breadcrumb">
<!-- home-nav -->
<li><a href="<?php echo $setting['siteurl'];?>" onMouseover="showhint('<b><?php echo $setting['sitename'];?></b>',this, event, 'auto')">Home</a></li>
<!-- home-nav-end -->
<!-- start: cat-nav -->
<li><?php
$catcheck = yasDB_select("SELECT `name` FROM `categories` WHERE `id` = " . $row['category']);
$catname = $catcheck->fetch_array(MYSQLI_ASSOC);
if ($id = $row['category']) {
if ($setting['seo']=='yes') { ?>
    <a href="<?php echo $setting['siteurl'];?>category/<?php echo $row['category'];?>/1.html" onMouseover="showhint('<b><?php echo ucfirst($catname['name']);?></b>',this, event, 'auto')"><?php echo ucfirst($catname['name']);?></a>
    <?php
} else { ?>
    <a href="<?php echo $setting['siteurl'];?>index.php?act=cat&id=<?php echo $row['category'];?>" onMouseover="showhint('<b><?php echo ucfirst($catname['name']);?></b>',this, event, 'auto')"><?php echo ucfirst($catname['name']);?></a>
    <?php
	}
}
?>
</li>
<!-- end: cat-nav --><!-- start: game_nav -->
<li><?php echo $row['title'];?></li>
<!-- end: game_nav -->
</ul>  
<!-- AJ-BreadCrumb-End -->
<!-- |||||||||=========== Slider Start =============== |||||| -->
<?php
// this slider is only for swf games, should work with other formats, try them

if($row['type'] != ''){ ?>
<br>
<center>
<div style=" float: left; margin: 2px 2px 4px 140px; text-align: center; width: 500px;">
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.0/themes/base/jquery-ui.css">
<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.3.js"></script>  
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.js"></script> 
<?php
// ============[][][][][] Edit Below values to fit your needs, DON'T ADD 'PX' after numeric values

$gameboxwidth = 720; // width of #gameDIV
$gameboxheight = 521; // height of #gameDIV
$maximumwidth = 1200; // maximum width you want slider to give to #gameDIV
$body_wrapper_width = 1200; // width of body_wrapper

// ============[][][][][] don't edit anything after this [][][][][][]==============
$bodywidth = $body_wrapper_width - 300;

?>
<script type="text/javascript">//<![CDATA[ 
$(function(){
    $('#slider').slider({
      min: <?php echo $gameboxwidth; ?>,
      max: <?php echo $maximumwidth; ?>,
      value: <?php echo $gameboxwidth; ?>,
      slide: handleSliderChange
    });
    function handleSliderChange(event, slider){

              if(slider.value == <?php echo $gameboxwidth; ?>){
              $('#gameDiv').css('width', slider.value + 'px');
              $('#gameDiv').css('position','');
              $('#gameDiv').css('z-index', '');
              $('#gameDiv').css('margin-left', '');
              $('#gameDiv').css('height', <?php echo $gameboxheight; ?> + 'px');
              
              }
              
              else if(slider.value > <?php echo $gameboxwidth; ?> && slider.value < <?php echo $bodywidth; ?>){
              $('#gameDiv').css('width', slider.value + 'px');
              $('#gameDiv').css('height', ((slider.value - <?php echo $gameboxwidth; ?>)+<?php echo $gameboxheight; ?>) + 'px');
              $('#gameDiv').css('position','relative');
              $('#gameDiv').css('z-index', '999999999999999');
              $('#gameDiv').css('margin-left', '');
              
              }
              else if(slider.value > <?php echo $bodywidth; ?>){
              $('#gameDiv').css('margin-left', '-' + (slider.value - <?php echo $bodywidth; ?>)/2 + 'px');
              $('#gameDiv').css('width', (slider.value - 0.5) + 'px');
              $('#gameDiv').css('height', ((slider.value - <?php echo $gameboxwidth; ?>)+<?php echo $gameboxheight; ?>) + 'px');
              $('#gameDiv').css('position','relative');
              $('#gameDiv').css('z-index', '999999999999999'); 
               }               
    }
});//]]>
</script>
<div id="slider" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left:0%; background: #008320;"></a></div>
</div><div style="Float:right;margin-right:40px;margin-top:-6px;"><input type="button" id="resetSlider" value="Reset" height="30" width="90"/></div><br></center>
<?php
}
?>
<!-- |||||||||=========== Slider End =============== |||||| --> 
				
	<div class="game_box_inline" style="margin-bottom:10px;">
      <?php
	if ($row['type']=='SWF' && $displayed == 'no') {
		echo '<div id="gameDiv" style="height: '.$new_height.'px; width: '.$new_width.'px; position:relative; top: 0; left: 0">';
		include "media/swf.php";
		echo '</div>';
    }else if($row['type']=='UNITY') {  // Or UNITY if you have changed it
        echo '<div id="gameDiv" style="height: '.$new_height.'px; width: '.$new_width.'px; position:relative; top: 0; left: 0">';         
        include "media/unity.php"; // Or unity.php if you have changed it
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
    ?> </div> <div class="game_banner"><?php echo ad("9"); ?></div>
 <div class="game_ad">
	<?php 
	if (ad("4") == 'Put AD code here') {
		?><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/468x60.png';?>" width="468" height="60" />
		<?php
	} else {
		echo ad("4");
	} ?>
 </div>
 <div class="game_banner"><?php echo ad("10"); ?></div>
<div class="game_box_inline">
<?php
		if(isset($_SESSION["user"])) { 
			$user = $_SESSION["user"]; 
			$userid = $_SESSION["userid"];
			echo '<div id="fav_box">';			
			echo '<form method="post" action="">';
			echo '<input type="hidden" name="usersave" value="' . $userid . '"  />';
			echo '<input type="hidden" name="gamesave" value="' . $row['id'] .'" />';
			echo '<input type="image" name="Favourites" value="Favourites" alt="Save to Favorites" title="Save to Favorites" src="' . $setting['siteurl'] . 'templates/' . $setting['theme'] . '/skins/'.$setting['skin'].'/images/buttons/favorite.png" width="40" height="40" onclick="addFavorite(\''.$row['title'].'\',\''.$id.'\');return false">
			</form></div>'; 
		}
?>
<div id="con-us">
<?php
if ($setting['seo']=='yes') {
$conus = $setting['siteurl'] . 'contactus.html';
} else {
$conus = $setting['siteurl'] . 'index.php?act=contactus';
}
?>
<a href="<?php echo $conus;?>" alt="Contact Us" title="Contact Us" width="40" height="40"><img src="<?php echo $setting['siteurl'];?>templates/<?php echo $setting['theme'];?>/skins/<?php echo $setting['skin'];?>/images/buttons/conus.png"/></a>
</div>
<div id="t-a-f">
<?php
if ($setting['seo']=='yes') {
$tellafriend = $setting['siteurl'] . 'tellafriend.html';
} else {
$tellafriend = $setting['siteurl'] . 'index.php?act=tellafriend';
}
?>
<a href="<?php echo $tellafriend;?>" alt="Tell a Friend" title="Tell a Friend" width="40" height="40"><img src="<?php echo $setting['siteurl'];?>templates/<?php echo $setting['theme'];?>/skins/<?php echo $setting['skin'];?>/images/buttons/t-a-f.png"/></a>
</div>
<?php if ($row['type'] != 'YOUTUBE' && $row['type'] != 'CustomCode') { ?>
<div style="cursor:pointer;" id="fullscreen"><img src="<?php echo $setting['siteurl'];?>templates/<?php echo $setting['theme'];?>/skins/<?php echo $setting['skin'];?>/images/buttons/fullscreen.png" width="40" height="40" border="0" alt="full screen"></div>&nbsp;&nbsp;
<?php } ?>
</div>
<?php
				// The following code is for a box of random games
				echo '<div class="random_box">';
				$query = yasDB_select("SELECT * FROM games order by rand() limit 7");
				while($games = $query->fetch_array(MYSQLI_ASSOC)) {
					
					$gameurl = $games['title'];
					$gameurl = str_replace(" ", "-", $gameurl);
					$gameurl = str_replace("'", "_", $gameurl);
					$gameurl = str_replace('"', "_", $gameurl);
					$gameurl = str_replace('/', "_", $gameurl);
					$gameurl = str_replace("\\", "_", $gameurl);
					$gameurl = rawurlencode($gameurl);
					if (file_exists($games['thumbnail'])) {
						$thumbnail = $setting['siteurl'].$games['thumbnail'];
						$thumbnail = urldecode($thumbnail);
					} else {
						$thumbnail = $setting['siteurl'].'img/nopic.jpg';
					}
					if ($setting['seo']=="yes") {
					    echo '<div class="ranbox">';
						echo'<a href="' . $setting['siteurl'] . 'game/' . $games['id'] . '/' . $gameurl . '.html' . '">';
						echo '<img src="' . $thumbnail . '" width="80" height="80" /></a></div>';                         
					}else {
					    echo '<div class="ranbox">';
						echo '<a href="' . $setting['siteurl'] . 'index.php?act=game&id=' . $games['id'] . '">';
						echo '<img src="' . $thumbnail . '" width="80" height="80" alt="'.$games['title'].'" title="'.$games['title'].'"/></a></div>';
					}
					
				}
				$query->close();
				echo '</div>';
}
/////////////////////////////////////////////////
//											   //
//				end light box check			   //	
/////////////////////////////////////////////////
// Begin Facebook Like button
?>		
	 <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_ENG/all.js#xfbml=1&appId=";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>		
<div class="facebook_box"><div class="fb-comments" data-href="<?php echo $setting['siteurl'] . 'game/' . $row['id'] . '/' . $row['title'] . '.html';?>" data-colorscheme="light" data-numposts="5" data-width="720"></div></div>
<?php // End Facebook code ?>
<div class="clear"></div>
</div>
<div class="clear"></div>  
<div class="container_box1" style="background-color:#E3ECFD;"><div id="headergames2">Information:</div>
<div class="game_description">
<strong>Name: </strong><?php echo $row['title'];?>
<br/><br/><strong>Description: </strong> <?php echo $row['description'];?>
<br/><br/><strong>Instuctions: </strong> <?php echo $row['instructions'];?>
<br/><br/><strong>Keywords:</strong> 
<?php
$keywords = explode(',', $row['keywords']);
foreach ($keywords as $keyword) {
	echo "<a href='".$setting['siteurl'] ."/index.php?act=search&q=".trim($keyword)."'> " .trim($keyword)."</a>";
}
?>
</p>
<br/><br/><strong>Views: </strong><?php echo $row['plays'];?>
<br/><br/><strong>Category: </strong>
<?php
$catcheck = yasDB_select("SELECT `name` FROM `categories` WHERE `id` = {$row['category']}");
$catname = $catcheck->fetch_array(MYSQLI_ASSOC);
if ($setting['seo']=='yes') { ?>
	<a href="<?php echo $setting['siteurl'];?>category/<?php echo $row['category'];?>/1.html" title="<?php echo ucfirst($catname['name']);?>"><?php echo ucfirst($catname['name']);?></a>
	<?php
} else { ?>
    <a href="<?php echo $setting['siteurl'];?>index.php?act=cat&id=<?php echo $row['category'];?>" title="<?php echo ucfirst($catname['name']);?>"><?php echo ucfirst($catname['name']);?></a>
	<?php
}
?>
</div><div class="game_description">
<strong>Rating:</strong><br />
<div id="ratingbox">
	<?php
	echo rating_bar($row['id'],'');
	?> </div>
    <br />
    <div id="gamecode">
        <strong>Game Code:</strong>    
        <?php
		$query = yasDB_select("SELECT * FROM `games` WHERE id = $id");
		$row = $query->fetch_array(MYSQLI_ASSOC);
		$query->close();
		?>
        <textarea style="width: 360px; height: 100px; border:solid 3px #999; font: normal 12px arial;" id="txtarea" onClick="SelectAll('txtarea');">
        <?php 
        if($row['type']=='SWF') {
			echo '<div id="gameDiv" style="height: '.$new_height.'px; width: '.$new_width.'px; position:relative; top: 0; left: 0">';
			include "media/swf.php";
		echo '</div>';  
	    }else if($row['type']=='UNITY') {         
			include "media/unity.php";
		}else if($row['type']=='DCR') { 
		    include "media/dcr.php";  
		}else if($row['type']=='WMV') {
			include "media/wmv.php";
		}else if($row['type']=='MPG') {
			include "media/mpg.php";
		}else if($row['type']=='AVI') {
			include "media/avi.php";
		}else if($row['type']=='MOV') {
			include "media/mov.php";
		}else if($row['type']=='IMAGE') {
			include "media/image.php";
		}else if($row['type']=='FLV') {
			include "media/flv.php";
		}else if($row['type']=='YOUTUBE') {
			include "media/youtube.php";
		}else if($row['type']=='CustomCode') {
			echo"$row[code]";
		}          
		?>
		<br/><a href="<?php echo $setting['siteurl'];?>"><?php echo $setting['slogan'];?></a>
		</textarea>
	</div>
</div>
<div class="clear"></div><br style="clear: both"/>
</div>
<div class="clear"></div>
<div class="container_box1">
<div id="headergames2">Comments:</div>  
<div class="container_box3"><div id="messages">	
	<?php
	$query = yasDB_select("SELECT count(id) AS count FROM comments WHERE gameid = $id");
	$result = $query->fetch_array(MYSQLI_ASSOC);
	$total = $result['count'];
	if($total == 0) {
		echo '<div id="comment_text">This game has no comments, be the first to add one!</div>';
	} else {
		$query = yasDB_select("SELECT * FROM comments WHERE gameid = $id");
		while($row = $query->fetch_array(MYSQLI_ASSOC)) {
			$text = $row['comment'];
			$text = str_replace(':D','<img src="' . $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/biggrin.gif" title="biggrin" alt="biggrin" />',$text);
			$text = str_replace(':?','<img src="' . $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/confused.gif" title="confused" alt="confused" />',$text);
			$text = str_replace('8)','<img src="' . $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/cool.gif" title="cool" alt="cool" />',$text);
			$text = str_replace(':cry:','<img src="' . $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/cry.gif" title="cry" alt="cry" />',$text);
			$text = str_replace(':shock:','<img src="' . $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/eek.gif" title="eek" alt="eek" />',$text);
			$text = str_replace(':evil:','<img src="' . $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/evil.gif" title="evil" alt="evil" />',$text);
			$text = str_replace(':lol:','<img src="' . $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/lol.gif" title="lol" alt="lol" />',$text);
			$text = str_replace(':x','<img src="' . $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/mad.gif" title="mad" alt="mad" />',$text);
			$text = str_replace(':P','<img src="' . $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/razz.gif" title="razz" alt="razz" />',$text);
			$text = str_replace(':oops:','<img src="' . $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/redface.gif" title="redface" alt="redface" />',$text);
			$text = str_replace(':roll:','<img src="' . $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/rolleyes.gif" title="rolleyes" alt="rolleyes" />',$text);
			$text = str_replace(':(','<img src="' . $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/sad.gif" title="sad" alt="sad" />',$text);					
			$text = str_replace(':)','<img src="' . $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/smile.gif" title="smile" alt="smile" />',$text);
			$text = str_replace(':o','<img src="' . $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/surprised.gif" title="surprised" alt="surprised" />',$text);
			$text = str_replace(':twisted:','<img src="' . $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/twisted.gif" title="twisted" alt="twisted" />',$text);
			$text = str_replace(':wink:','<img src="' . $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/wink.gif" title="wink" alt="wink" />',$text);
			echo '<div class="container_box5"><div class="comment_box1">Post by - ' . $row['name'] . '</div><div class="comment_box2">' . $text . '</div></div>';
		}
	}
	$query->close();
	if (isset($_POST['comment'])) {
		$comment = $_POST['comment'];
	} else {
		$comment = '';
	}
	?>
	</div></div>
<div class="clear"></div></div>
<div class="clear"></div>

<div class="container_box1">
<div id="headergames2">Leave a comment:</div>  
	<div class="comment_box">
	<div id="preview" style="text-align:center;"></div>
	<div id="commentBox">
	<?php if(isset($_SESSION['user'])) {
	?>
	<div class="container_box4"><center>
		<form name="comment" id="addcomment" method="post" action="<?php echo $setting['siteurl'];?>includes/comment.php">
			<strong>Message:</strong><br />
			<textarea name="comment" style="width:530px;" rows="3" cols="26" id="comment_message"><?php echo $comment; ?></textarea><br /><br/>
			<input name="gameid" id="gameid" type="hidden" value="<?php echo $id;?>" />
			<input name="title" type="hidden" value="<?php echo $gameurl;?>" />
			<input type="hidden" name="timestamp" id="timestamp" value="<?php echo time(); ?>" />
			</center></div>
	<div class="container_box4">		
	<div id="smiles"><center>
		    <a href="javascript:addsmilie(' :D ')"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/biggrin.gif';?>" border="0"  /></a>
		    <a href="javascript:addsmilie(' :? ')"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/confused.gif';?>" border="0"  /></a>
		    <a href="javascript:addsmilie(' 8) ')"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/cool.gif';?>" border="0"  /></a>
		    <a href="javascript:addsmilie(' :cry: ')"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/cry.gif';?>" border="0"  /></a>
		    <a href="javascript:addsmilie(' :shock: ')"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/eek.gif';?>" border="0"  /></a>
		    <a href="javascript:addsmilie(' :evil: ')"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/evil.gif';?>" border="0"  /></a>
		    <a href="javascript:addsmilie(' :lol: ')"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/lol.gif';?>" border="0"  /></a>
		    <a href="javascript:addsmilie(' :x ')"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/mad.gif';?>" border="0"  /></a><br />
		    <a href="javascript:addsmilie(' :P ')"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/razz.gif';?>" border="0"  /></a>
		    <a href="javascript:addsmilie(' :oops: ')"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/redface.gif';?>" border="0"  /></a>
		    <a href="javascript:addsmilie(' :roll: ')"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/rolleyes.gif';?>" border="0"  /></a>
		    <a href="javascript:addsmilie(' :( ')"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/sad.gif';?>" border="0"  /></a>
		    <a href="javascript:addsmilie(' :) ')"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/smile.gif';?>" border="0"  /></a>
		    <a href="javascript:addsmilie(' :o ')"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/surprised.gif';?>" border="0"  /></a>
		    <a href="javascript:addsmilie(' :twisted: ')"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/twisted.gif';?>" border="0"  /></a>
		    <a href="javascript:addsmilie(' :wink: ')"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/wink.gif';?>" border="0"  /></a>
		</center>
		</div>
		</div>
	<div class="container_box4"><center>
		<?php
		if (!isset($_SESSION['user'])) {
			?><strong>Name:</strong><br />
			<?php
			if (isset($_POST['name'])) {
				$commentname = $_POST['name'];
			} else if (isset($_SESSION['user'])) {
				$commentname = $_SESSION['user'];
			} else {
				$commentname = '';
			}
			?>
			<input name="name" type="text" style="border: 1px solid #000;" value="<?php echo $commentname;?>"/><br /><br />	
			<?php
			if ($setting['userecaptcha'] == "yes") {
				@session_start();
				// securimage captcha
				?>
				<div style="width: 700px; float:left;height: 90px">
				<img id="siimage" align="center" style="padding-right: 5px; border: 0" src="<?php echo $setting['siteurl']; ?>includes/securimage/securimage_show.php?sid=<?php echo md5(time()) ?>" />
				<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="19" height="19" id="SecurImage_as3" align="middle">
					<param name="allowScriptAccess" value="sameDomain" />
					<param name="allowFullScreen" value="false" />
					<param name="movie" value="<?php echo $setting['siteurl']; ?>includes/securimage/securimage_play.swf?audio=securimage_play.php&bgColor1=#fff&bgColor2=#00132f&iconColor=#000&roundedCorner=5" />
					<param name="quality" value="high" />			
					<param name="bgcolor" value="#00132f" />
					<embed src="<?php echo $setting['siteurl']; ?>includes/securimage/securimage_play.swf?audio=securimage_play.php&bgColor1=#fff&bgColor2=#00132f&iconColor=#000&roundedCorner=5" quality="high" bgcolor="#00132f" width="19" height="19" name="SecurImage_as3" align="middle" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
				 </object>
						
				<!-- pass a session id to the query string of the script to prevent ie caching -->			
				<a tabindex="-1" style="border-style: none" href="#" title="Refresh Image" onclick="document.getElementById('siimage').src = '<?php echo $setting['siteurl']; ?>includes/securimage/securimage_show.php?sid=' + Math.random(); return false"><img src="<?php echo $setting['siteurl']; ?>includes/securimage/images/refresh.gif" alt="Reload Image" border="0" onclick="this.blur()" align="middle" /></a>
				<div style="clear: both"></div>
				</div>			
				Code:<br />
				<input type="text" name="code" id="code" size="12" /><br /><br />
				<input name="recaptcha" type="hidden" value="yes" /><?php
				// end securimage captcha
			}
			else {
				?>Security Question: five + five = <br />
				<input name="security" id="security" type="text" style="border: 1px solid #000;" /><br/>
				<input name="recaptcha" type="hidden" value="no" />
				<?php
			} 
		} else {
			?>
			<input type="hidden" name="recaptcha" id="recaptcha" value="no">
			<input type="hidden" name="security" id="security" value="10">
			<input type="hidden" name="name" id="name" value="<?php echo $_SESSION['user']; ?>">
			<?php
		}
		?>
        <div style="clear:both;"></div><br/><input name="sendcomment" type="submit" value="Add Comment" style="border: 1px solid #000; margin-top: 2px;" /><br/><br/>
	</form></center></div>
	<?php
	} else {
		?><span style="display:block;text-align:center;font-size:24px;padding:10px 0 50px 0;">Sign in or Register to leave a comment</span><?php
	}
	?>
	</div>
	</div>
	<div class="clear"></div></div>
<script type="text/javascript" src="<?php echo $setting['siteurl'];?>includes/js/fullscreen.js"></script>
<div id="close" style="display:none;background-color:#000000;color:#ffffff;cursor:pointer;" onclick="cFull()">Close full screen [Esc]</div>
