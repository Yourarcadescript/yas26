<body>

<div id="body_wrapper">
  <div id="top_wrapper">
  <div id="top">
	<div id="nametext"><?php echo $setting['sitename']; ?></div>
	<?php
	if (!isset($_SESSION['user'])) {
        if ($setting['regclosed'] == 'yes') {
        echo '<div style="float:right; width:96px;height:22px;"></div>';
        } else {
	?>
			  <div style="position:relative;float:right; width:90px; margin-top:5px;bottom:50px;">
			  <a href="<?php echo $setting['siteurl'];?>login.php?site=facebook"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/fb_login.png';?>" width="90px" height="36px"></a>
			  <a href="<?php echo $setting['siteurl'];?>login.php?site=twitter"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/tw_login.png';?>" width="90px" height="36px"></a>
			  </div><?php
		}
    } ?> 
  </div>
  </div>
  <div id="bar">
<?php
if ($setting['seo']=='yes') {
	echo '<a href="'. $setting['siteurl'] .'index.php">Home</a> |&nbsp; <a href="'. $setting['siteurl'] .'forum.html">Forum</a> |&nbsp; <a href="'. $setting['siteurl'] .'contactus.html">Contact-Us</a> |&nbsp; <a href="'. $setting['siteurl'] .'links.html">links</a> |&nbsp; <a href="'. $setting['siteurl'] .'addlink.html">Link Exchange</a> |&nbsp; <a href="'. $setting['siteurl'] .'news.html">News</a> |&nbsp; <a href="'. $setting['siteurl'] .'members.html">Members</a> |&nbsp; <a href="'. $setting['siteurl'] .'terms.html">Terms/F.A.Q./Legal</a>';
}
else {
	echo '<a href="'. $setting['siteurl'] .'index.php">Home</a> |&nbsp; <a href="'. $setting['siteurl'] .'index.php?act=forum">Forum</a> |&nbsp; <a href="'. $setting['siteurl'] .'index.php?act=contactus">Contact-Us</a> |&nbsp; <a href="'. $setting['siteurl'] .'index.php?act=links">Links</a> |&nbsp; <a href="'. $setting['siteurl'] .'index.php?act=addlink">Link Exchange</a> |&nbsp; <a href="'. $setting['siteurl'] .'index.php?act=news">News</a> |&nbsp; <a href="'. $setting['siteurl'] .'index.php?act=members">Members</a> |&nbsp; <a href="'. $setting['siteurl'] .'index.php?act=terms">Terms/F.A.Q./Legal</a>';
}
?>
  </div>
<div id="content_wrapper"><div id="content_wrapper_inner">
<?php include 'leftcolumn.php';?>