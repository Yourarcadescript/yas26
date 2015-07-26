<body>
<div id="body_wrapper">
  <div id="menu">
<?php
	if ($setting['seo']=='yes') { ?>
	      <ul id="css3menu1" class="topmenu">
	      <li class="topfirst"><a href="<?php echo $setting['siteurl'];?>index.php"><span>Home</span></a></li>
		  <li class="topmenu"><a href="<?php echo $setting['siteurl'];?>download.html">Download</a></li>
		  <li class="topmenu"><a href="<?php echo $setting['siteurl'];?>news.html">News</a></li>
		  <li class="topmenu"><a href="<?php echo $setting['siteurl'];?>members.html">Members</a></li>
		  <li class="topmenu"><a href="<?php echo $setting['siteurl'];?>forum.html" title="Forum">Forum</a></li>
		  <li class="toplast"><a href="#">More</a>
		  <ul>
		  <li><a href="#" title="About Us"><span>About Us</span></a>
          <ul>
		  <li><a href="<?php echo $setting['siteurl'];?>contactus.html">Contact-Us</a></li>
		  <li><a href="<?php echo $setting['siteurl'];?>terms.html">Terms/F.A.Q./Legal</a></li>
		  </ul>
		  </li>
		  <li><a href="#" title="Links"><span>Links</span></a>
		  <ul>
		  <li><a href="<?php echo $setting['siteurl'];?>links.html">Links</a></li>
		  <li><a href="<?php echo $setting['siteurl'];?>addlink.html">Link Exchange</a></li>
		  </ul>
		  </li>
		  </ul>
		  </ul> 
<?php } else { ?>
		<ul id="css3menu1" class="topmenu">
	      <li class="topfirst"><a href="<?php echo $setting['siteurl'];?>index.php"><span>Home</span></a></li>
		  <li class="topmenu"><a href="<?php echo $setting['siteurl'];?>index.php?act=download" title="News">Download</a></li>
		  <li class="topmenu"><a href="<?php echo $setting['siteurl'];?>index.php?act=news" title="News">News</a></li>
		  <li class="topmenu"><a href="<?php echo $setting['siteurl'];?>index.php?act=members&page=1" title="Members">Members</a></li>
		  <li class="topmenu"><a href="<?php echo $setting['siteurl'];?>index.php?act=forum" title="Forum">Forum</a></li>
		  <li class="toplast"><a href="#">More</a>
		  <ul>
		  <li><a href="#" title="About Us"><span>About Us</span></a>
          <ul>
		  <li><a href="<?php echo $setting['siteurl'];?>index.php?act=contactus">Contact-Us</a></li>
		  <li><a href="<?php echo $setting['siteurl'];?>index.php?act=terms">Terms/F.A.Q./Legal</a></li>
		  </ul>
		  </li>
		  <li><a href="#" title="Links"><span>Links</span></a>
		  <ul>
		  <li><a href="<?php echo $setting['siteurl'];?>index.php?act=links">Links</a></li>
		  <li><a href="<?php echo $setting['siteurl'];?>index.php?act=addlink">Link Exchange</a></li>
		  </ul>
		  </li>
		  </ul>
		  </ul>
<?php } ?>
		  <div id="search">
          <form name="browse" method='GET' action="<?php echo $setting['siteurl'];?>index.php">
          <input type='hidden' name='act' value='search'>
          <input type="text" style="color: #999; border:solid 1px #333;" maxlength="20" size="40" name="q" onBlur="this.value = this.value || this.defaultValue; this.style.color = '#999';" onFocus="this.value=''; this.style.color = '#000000';" value="Type game search here">
          <input type="image" src="<?php echo $setting['siteurl'];?>templates/<?php echo $setting['theme'];?>/skins/<?php echo $setting['skin']; ?>/images/search.png" width="28" height="24" align="absmiddle" value="Submit" alt="Submit" />
          </form>
          </div>
<div class="social">
<?php if (!isset($_SESSION['user'])) { 
        if ($setting['regclosed'] == 'yes') {
        echo '<div style="float:right; width:96px;height:22px;"></div>';
        } else {
?>
<a href="<?php echo $setting['siteurl'];?>login.php?site=facebook" rel="nofollow"><img src="<?php echo $setting['siteurl'];?>templates/<?php echo $setting['theme'];?>/skins/<?php echo $setting['skin'];?>/images/facebook.png" border="none" /></a>
<?php }
}?>
</div>
  </div>
  
  <div id="top">
	<div id="nametext"><?php echo $setting['sitename']; ?></div>
  </div>
    
  <div id="bar">
<?php			
$query = yasDB_select("SELECT * FROM categories WHERE active='yes' AND parent='yes' ORDER BY `order` desc LIMIT 10");						
while($row = $query->fetch_array(MYSQLI_ASSOC)) {
	if ($setting['seo']=='yes') { ?>
	<ul id="css3menu2" class="topmenu">
	<li class="topmenu"><a href="<?php echo $setting['siteurl'];?>category/<?php echo $row['id'];?>/1.html" title="<?php echo $row['name'];?>"><?php echo $row['name'];?></a></li>
	</ul> 				  
<?php	} else { ?>
    <ul id="css3menu2" class="topmenu">
	<li class="topmenu"><a href="<?php echo $setting['siteurl'];?>index.php?act=cat&id=<?php echo $row['id'];?>" title="<?php echo $row['name'];?>"><?php echo $row['name'];?></a></li>
	</ul> 
<?php	}
}
$query->close();
?>
<div class="social">
<?php if (!isset($_SESSION['user'])) {
        if ($setting['regclosed'] == 'yes') {
        echo '<div style="float:right; width:96px;height:22px;"></div>';
        } else {
?>
<a href="<?php echo $setting['siteurl'];?>login.php?site=twitter" rel="nofollow"><img src="<?php echo $setting['siteurl'];?>templates/<?php echo $setting['theme'];?>/skins/<?php echo $setting['skin'];?>/images/twitter.png" border="none" /></a> 
<?php }
}?>
</div>
  </div>
<div id="content_wrapper">