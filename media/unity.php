<object classid='clsid:444785F1-DE89-4295-863A-D46C3A781394'
codebase='http://webplayer.unity3d.com/download_webplayer/UnityWebPlayer.cab#version=2,0,0,0'
id='UnityObject'
width="100%"
height="100%" >
<param name="src" value="<?php echo$setting['siteurl'];?><?php echo$row['file'];?>">

<embed type='application/vnd.unity'
pluginspage='http://www.unity3d.com/unity-web-player-2.x'
id='UnityEmbed'
width="100%"
height="100%"
src="<?php echo $setting['siteurl'].$row['file'];?>" />
</object>