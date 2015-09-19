<body>
<div id="body_wrapper">
  <div id="menu">
<?php
	if ($setting['seo']=='yes') { ?>
	      <a href="<?php echo $setting['siteurl'];?>index.php"><span>Home</span></a>&nbsp;/&nbsp;<a href="<?php echo $setting['siteurl'];?>news.html" title="Blog">Blog</a>&nbsp;/&nbsp;<a href="<?php echo $setting['siteurl'];?>download.html" title="Download">Download</a>&nbsp;/&nbsp;<a href="<?php echo $setting['siteurl'];?>forum.html" title="Forum">Forum</a>&nbsp;/&nbsp;<a href="<?php echo $setting['siteurl'];?>mostplayed.html" title="Most Played">Most Played</a>&nbsp;/&nbsp;<a href="<?php echo $setting['siteurl'];?>newest.html" title="Newest">Newest</a>&nbsp;/&nbsp;<a href="<?php echo $setting['siteurl'];?>toprated.html" title="TopRated">Top Rated</a>
<?php } else { ?>
	      <a href="<?php echo $setting['siteurl'];?>index.php"><span>Home</span></a>&nbsp;/&nbsp;<a href="<?php echo $setting['news'];?>index.php?act=news" title="Blog">Blog</a>&nbsp;/&nbsp;<a href="<?php echo $setting['siteurl'];?>index.php?act=download" title="Download">Download</a>&nbsp;/&nbsp;<a href="<?php echo $setting['siteurl'];?>index.php?act=forum" title="Forum">Forum</a>&nbsp;/&nbsp;<a href="<?php echo $setting['siteurl'];?>index.php?act=mostplayed" title="Most Played">Most Played</a>&nbsp;/&nbsp;<a href="<?php echo $setting['siteurl'];?>index.php?act=newest" title="Newest">Newest</a>&nbsp;/&nbsp;<a href="<?php echo $setting['siteurl'];?>index.php?act=toprated" title="TopRated">Top Rated</a>
<?php } 
if (!isset($_SESSION['user'])) {
        if ($setting['regclosed'] == 'yes') {
        echo '<div style="float:right; width:96px;height:22px;">Welcome Guest</div>';
        } else {
        ?><div style="float:right; width:120px;"><a href="<?php echo $setting['siteurl'];?>login.php?site=facebook" title="Login With Facebook">Facebook</a>&nbsp;/&nbsp;<a href="<?php echo $setting['siteurl'];?>login.php?site=twitter" title="Login With Twitter">Twitter</a></div>       
  <?php }
  } ?>
</div>
<div id="top"><div id="nametext"><?php echo $setting['sitename']; ?></div></div>    
  <div id="bar">
	<?php			
	$query = yasDB_select("SELECT * FROM categories WHERE active='yes' AND parent='yes' AND home='yes' ORDER BY `name` ASC");						
	while($row = $query->fetch_array(MYSQLI_ASSOC)) {
		if ($setting['seo']=='yes') { ?>
	<a href="<?php echo $setting['siteurl'];?>category/<?php echo $row['id'];?>/1.html" title="<?php echo $row['name'];?>"><?php echo $row['name'];?></a> |&nbsp;				  
	<?php	} else { ?>
	<a href="<?php echo $setting['siteurl'];?>index.php?act=cat&id=<?php echo $row['id'];?>" title="<?php echo $row['name'];?>"><?php echo $row['name'];?></a> |&nbsp;
	<?php	}
	}
	$query->close();
	if (!isset($_SESSION['user'])) {
		if ($setting['regclosed'] == 'yes') { ?>
		<div style="float:right; width:100px;">
		<?php if ($setting['seo']=='yes') { ?>
		<a href="<?php echo $setting['siteurl'];?>forgotpassword.html" title="Forgot Password">Forgot Password</a>
		<?php } else { ?>
		<a href="<?php echo $setting['siteurl'];?>index.php?act=forgotpassword" title="Forgot Password">Forgot Password</a>
		<?php } ?>
		</div>
		<?php } else { ?>
		<div style="float:right; width:160px;">
		<?php
			if ($setting['seo']=='yes') { ?>
				  <a href="<?php echo $setting['siteurl'];?>forgotpassword.html" title="Forgot Password">Forgot Password</a>&nbsp;/&nbsp;<a href="<?php echo $setting['siteurl'];?>register.html" title="Register">Register</a>
		<?php } else { ?>
				  <a href="<?php echo $setting['siteurl'];?>index.php?act=forgotpassword" title="Forgot Password">Forgot Password</a>&nbsp;/&nbsp;<a href="<?php echo $setting['siteurl'];?>index.php?act=register" title="Register">Register</a>
		<?php } ?>
		</div>
	<?php }
	} ?>
  </div>
<div id="content_wrapper">    
<div id="loginbox"><?php include ("templates/$setting[theme]/columnone.php");?></div>