<!--<div class="clear"></div>-->
</div>
<!-- end center -->
<?php include 'rightcolumn.php';?>
<!-- begin footer -->
<div id="footer">
<?php if ($setting['seo']=='yes') {
	?>
	<div class="footer">
	<ul>
	<li class="title"><a href="<?php echo $setting['siteurl'];?>index.php"><span>Home</span></a></li>
	</ul>
	<ul>
	<li class="title"><a href="<?php echo $setting['siteurl'];?>contactus.html">Contact-Us</a></li>
	</ul>
	<ul>
	<li class="title"><a href="<?php echo $setting['siteurl'];?>links.html">Links</a></li>
	</ul>
	<ul>
	<li class="title"><a href="<?php echo $setting['siteurl'];?>addlink.html" title="Link Exchange">Link Exchange</a></li>
	</ul>
	<ul>
	<li class="title"><a href="<?php echo $setting['siteurl'];?>rss-arcade.xml" title="Rss-Arcade">Rss-Arcade</a></li>
	</ul>
	<ul>
	<li class="title"><a href="<?php echo $setting['siteurl'];?>Sitemap.xml" title="Sitemap">Sitemap</a></li>
	</ul>
	<ul>
	<li class="title"><a href="<?php echo $setting['siteurl'];?>tellafriend.html" title="Tell A Friend">Tell A Friend</a></li>
	</ul>
	<ul>
	<li class="title"><a href="<?php echo $setting['siteurl'];?>terms.html" title="Terms">Terms</a></li>
	</ul>
	</div>
	<br/>
<?php } else { ?>
	<div class="footer">
	<ul>
	<li class="title"><a href="<?php echo $setting['siteurl'];?>index.php"><span>Home</span></a></li>
	</ul>
	<ul>
	<li class="title"><a href="<?php echo $setting['siteurl'];?>index.php?act=contactus">Contact-Us</a></li>
	</ul>
	<ul>
	<li class="title"><a href="<?php echo $setting['siteurl'];?>index.php?act=links">Links</a></li>
	</ul>
	<ul>
	<li class="title"><a href="<?php echo $setting['siteurl'];?>index.php?act=addlink">Link Exchange</a></li>
	</ul>
	<ul>
	<li class="title"><a href="<?php echo $setting['siteurl'];?>rss-arcade.xml" title="Rss-Arcade">Rss-Arcade</a></li>
	</ul>
	<ul>
	<li class="title"><a href="<?php echo $setting['siteurl'];?>Sitemap.xml" title="Sitemap">Sitemap</a></li>
	</ul>
	<ul>
	<li class="title"><a href="<?php echo $setting['siteurl'];?>index.php?act=tellafriend" title="Tell A Friend">Tell A Friend</a></li>
	</ul>
	<ul>
	<li class="title"><a href="<?php echo $setting['siteurl'];?>index.php?act=terms" title="Terms">Terms</a></li>
	</ul>
	</div>
	<br/>
<?php } ?>
<br />
&nbsp;Copyright &copy; 2012<?php if (date("Y") > "2012") echo '-'.date("Y"); ?>&nbsp;<b><?php echo $setting['sitename'];?></b>
<br/>
<span class="footerlink">&nbsp;Powered by <a href="http://www.yourarcadescript.com">YourArcadeScript</a>&nbsp;<?php echo $setting['version']; ?></span>
</div>
<!-- end footer -->
</div>
<!-- end content_wrapper -->
</div>
<!--end body_wrapper -->
<?php echo ad("11"); // Google Analytics ad spot?>
</body>
</html>