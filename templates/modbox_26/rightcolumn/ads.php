<div class="nav_box"><div class="nav">Advertisement</div>
<div class="nav_box2"><center>
<?php 
if (ad("7") == 'Put AD code here') {
	?><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/160x600.png';?>" width="120" height="600" />
	<?php
} else {
	echo ad("7");
}
?></center>
</div></div>