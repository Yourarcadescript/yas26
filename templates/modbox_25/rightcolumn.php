<!-- begin right -->
<div id="right">
<?php 
if ($_GET['act'] == 'contactus') {
include ("templates/$setting[theme]/rightcolumn/exchange.php");
include ("templates/$setting[theme]/rightcolumn/sidenews.php");
include ("templates/$setting[theme]/rightcolumn/memberside.php");
include ("templates/$setting[theme]/rightcolumn/morelinks.php");
include ("templates/$setting[theme]/rightcolumn/linkmenu.php");
} elseif ($_GET['act'] == 'links') {
include ("templates/$setting[theme]/rightcolumn/exchange.php");
include ("templates/$setting[theme]/rightcolumn/membersmenu.php");
include ("templates/$setting[theme]/rightcolumn/banner.php");
include ("templates/$setting[theme]/rightcolumn/g-ad.php");
} elseif ($_GET['act'] == 'addlink') {
include ("templates/$setting[theme]/rightcolumn/exchange.php");
include ("templates/$setting[theme]/rightcolumn/membersmenu.php");
include ("templates/$setting[theme]/rightcolumn/banner.php");
include ("templates/$setting[theme]/rightcolumn/g-ad.php");
} elseif ($_GET['act'] == 'members') {
include ("templates/$setting[theme]/rightcolumn/exchange.php");
include ("templates/$setting[theme]/rightcolumn/membersmenu.php");
include ("templates/$setting[theme]/rightcolumn/newmember.php");
include ("templates/$setting[theme]/rightcolumn/ads.php");
} elseif ($_GET['act'] == 'game') {
include ("templates/$setting[theme]/rightcolumn/exchange.php");
include ("templates/$setting[theme]/rightcolumn/membersmenu.php");
include ("templates/$setting[theme]/rightcolumn/favouritegames.php");
include ("templates/$setting[theme]/rightcolumn/ads.php");
include ("templates/$setting[theme]/rightcolumn/random.php");
include ("templates/$setting[theme]/rightcolumn/linkmenu.php");
include ("templates/$setting[theme]/rightcolumn/stats.php");
include ("templates/$setting[theme]/rightcolumn/stats2.php");
include ("templates/$setting[theme]/rightcolumn/newestgames.php");
} elseif ($_GET['act'] == 'news') {
include ("templates/$setting[theme]/rightcolumn/exchange.php");
include ("templates/$setting[theme]/rightcolumn/membersmenu.php");
include ("templates/$setting[theme]/rightcolumn/latestcomment.php");
include ("templates/$setting[theme]/rightcolumn/newmember.php");
include ("templates/$setting[theme]/rightcolumn/stats.php");
} else {
include ("templates/$setting[theme]/rightcolumn/recentsearches.php");
include ("templates/$setting[theme]/rightcolumn/membersmenu.php");
include ("templates/$setting[theme]/rightcolumn/favouritegames.php");
include ("templates/$setting[theme]/rightcolumn/ads.php");
include ("templates/$setting[theme]/rightcolumn/stats.php");
include ("templates/$setting[theme]/rightcolumn/stats2.php");
}?>	
</div>
<!-- end right -->
<div class="clear"></div>
</div>
<!-- end -->
<div class="clear"></div>

    
  