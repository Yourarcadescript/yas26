<?php
$skinsdir = $_GET['s'];
$skins = scandir($skinsdir);
$n = 0;
foreach ($skins as &$skin) {
	if ($skin!='.' && $skin!='..' ) {
		if ($n==0) {
			echo '<option value="'.$skin.'" selected="yes">'.$skin.'</option>';
			$n++;
		} else {
		echo '<option value="'.$skin.'">'.$skin.'</option>';
		$n++;
		}						
	}
}
?>