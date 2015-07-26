<div class="nav_box"><div class="nav">Banner</div>
<div class="nav_box2"><center>
<?php
if (ad("7") == "Put AD code here") {
	?>
	<img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/100x100banner.png';?>" width="100" height="100" alt="banner exchange"/>
	<?php
} else {
	echo ad("7");
}
?></center>
</div>
</div>