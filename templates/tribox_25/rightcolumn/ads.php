<div class="navh3">Advertisement</div>
<center>
<?php 
if (ad("2") == 'Put AD code here') {
	?><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/160x600.png';?>" width="160" height="600" />
	<?php
} else {
	echo ad("2");
}
?></center>