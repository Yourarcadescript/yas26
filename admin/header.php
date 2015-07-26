<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>Admin</title>
	<meta http-equiv="content-type" content="text/html; charset=utf8" />	
	<link rel="stylesheet" type="text/css" href="<?php echo $setting['siteurl'];?>includes/notice/jquery_notification.css" />
	<link rel="stylesheet" type="text/css" href="css/all.css" />
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo $setting['siteurl'];?>includes/tiny_mce/tiny_mce.js"></script>
	
	<?php 
		switch ($_GET['act']) {
			case 'settings':
				?>
				<script type="text/javascript">		
					function changeSkins(templateUrl) {
						$.get("<?php echo $setting['siteurl'].'includes/updateskins.php';?>", { s: templateUrl }, function(data) {
							$('#skin').html(data);
						});
					}
				</script>
				<?php
				break;
			
			case 'managegames':
				?><script type="text/javascript" src="<?php echo $setting['siteurl'];?>includes/js/hint.js"></script><?php
			case 'managevasco':	
			case 'managekong':
			case 'managefgdfeed':
			case 'manageplaytomic':
			case 'managefogfeed':
			case 'managemochi':
				?><link rel="stylesheet" type="text/css" media="screen" href="<?php echo $setting['siteurl'];?>includes/fancybox/jquery.fancybox-1.3.4.css" />
				<script type="text/javascript" src="<?php echo $setting['siteurl'];?>includes/notice/jquery_notification.js"></script>
				<script>!window.jQuery && document.write('<script src="<?php echo $setting['siteurl'];?>includes/fancybox/jquery-1.4.3.min.js"><\/script>');</script>
				<script type="text/javascript" src="<?php echo $setting['siteurl'];?>includes/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
				<script type="text/javascript" src="<?php echo $setting['siteurl'];?>includes/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
				<script type="text/javascript" src="<?php echo $setting['siteurl'];?>includes/js/jquery.activity-indicator-1.0.0.min.js"></script>
				
				<?php
				break;
		} ?>
	
	<script type="text/javascript" src="<?php echo $setting['siteurl'];?>admin/admin.js"></script>
</head>
<body>
<div id="screenoverlay"></div>
<div id="loading"></div>
<div id="main">
	<div id="header">
		<div style="width:100%;text-align:center;"><a href="index.php" class="nametext"><?php echo $setting['sitename']; ?> Cpanel</a></div>
		<ul id="top-navigation">
			<li<?php if (!isset($_GET['act']) || $_GET['act'] == 'general') echo ' class="active"';?>><span><span><a href="index.php?act=general" title="Home">Home</a></span></span></li>
			<li<?php if ($_GET['act'] == 'settings') echo ' class="active"';?>><span><span><a href="index.php?act=settings" title="Settings">Settings</a></span></span></li>
			<li<?php if ($_GET['act'] == 'categories') echo ' class="active"';?>><span><span><a href="index.php?act=categories" title="Categories">Categories</a></span></span></li>
			<li<?php if ($_GET['act'] == 'manage_users') echo ' class="active"';?>><span><span><a href="index.php?act=manage_users" title="Users">Users</a></span></span></li>
			<li<?php if ($_GET['act'] == 'news') echo ' class="active"';?>><span><span><a href="index.php?act=news" title="News">News</a></span></span></li>
			<li<?php if ($_GET['act'] == 'managegames') echo ' class="active"';?>><span><span><a href="index.php?act=managegames" title="Games">Games</a></span></span></li>
			<li<?php if ($_GET['act'] == 'links') echo ' class="active"';?>><span><span><a href="index.php?act=links" title="Links">Links</a></span></span></li>
			<li<?php if ($_GET['act'] == 'addlink') echo ' class="active"';?>><span><span><a href="index.php?act=addlink" title="Add Link">Add link</a></span></span></li>
			<li<?php if ($_GET['act'] == 'managejobs') echo ' class="active"';?>><span><span><a href="index.php?act=managejobs" title="Add Link">Scheduled Jobs</a></span></span></li>
			<li<?php if ($_GET['act'] == 'logout') echo ' class="active"';?>><span><span><a href="index.php?act=logout" title="Logout">Logout</a></span></span></li>
		</ul>
	</div>
	<div id="middle">
		<div id="left-column">
			<?php include ("left_column.php");?>
		</div>
		