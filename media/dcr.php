<object classid="clsid:166B1BCA-3F9C-11CF-8075-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/director/sw.cab#version=8,5,0,0" width="<?php echo $row['width'];?>" height="<?php echo $row['height'];?>">
	<param name=swRemote value=\"swSaveEnabled='true' swVolume='true' swRestart='true' swPausePlay='true' swFastForward='true'   swContextMenu='true' \">
	<param name="swStretchStyle" value="fill">
	<param name="bgColor" value="#000000">
	<param name="src" value="<?php echo$setting['siteurl'];?><?php echo$row['file'];?>">
	<embed src="<?php echo $setting['siteurl'].$row['file'];?>" bgcolor="#000000" width="<?php echo $row['width'];?>" height="<?php echo $row['height'];?>" swRemote=\"swSaveEnabled='true' swVolume='true' swRestart='true' swPausePlay='true' swFastForward='true' swContextMenu='true'\" swStretchStyle='fill' type='application/x-director' pluginspage='http://www.macromedia.com/shockwave/download/'></embed>
</object>