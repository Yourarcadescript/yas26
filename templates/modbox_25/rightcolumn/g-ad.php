<div class="nav_box"><div class="nav">Advertisement</div>
<div class="nav_box2" id="sideAd" ><center>
<?php
if (ad("6") == "Put AD code here") {
	?>
	<img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/100x100banner.png';?>" width="100" height="100" alt="banner exchange"/><br/>
	<?php
} else {
	echo ad("6");
}
?></center>
</div>
</div>