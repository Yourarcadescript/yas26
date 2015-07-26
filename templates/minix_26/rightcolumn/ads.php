<div class="nav_box"><div class="nav">Advertisement</div>
<div class="nav_box2"><center>
<?php 
if (ad("2") == 'Put AD code here') {
	?><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/160x600.png';?>" width="120" height="600" />
	<?php
} else {
	echo ad("2");
}
?></center>
</div></div>