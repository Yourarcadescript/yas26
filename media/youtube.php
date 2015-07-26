<?php
$row['file'] = str_replace("http://www.youtube.com/watch?v=", "", $row['file']);
?>
<object width="530" height="430">
	<param name="movie" value="http://www.youtube.com/v/<?php echo$row['file'];?>&hl=en&fs=1"></param>
	<param name="allowFullScreen" value="true"></param>
	<param name="allowscriptaccess" value="always"></param>
	<embed src="http://www.youtube.com/v/<?php echo$row['file'];?>&hl=en&fs=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="530" height="430"></embed>
</object>