<OBJECT ID="MediaPlayer" WIDTH="530" HEIGHT="530" CLASSID="CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95"
STANDBY="Loading Windows Media Player components..." TYPE="application/x-oleobject">
<PARAM NAME="FileName" VALUE="<?php echo$setting['siteurl'];?><?php echo$row['file'];?>">
<PARAM name="autostart" VALUE="false">
<PARAM name="ShowControls" VALUE="true">
<param name="ShowStatusBar" value="false">
<PARAM name="ShowDisplay" VALUE="false">
<EMBED TYPE="application/x-mplayer2" SRC="<?php echo $setting['siteurl'].$row['file'];?>" NAME="MediaPlayer"
WIDTH="530" HEIGHT="530" ShowControls="1" ShowStatusBar="0" ShowDisplay="0" autostart="0"> </EMBED>
</OBJECT>