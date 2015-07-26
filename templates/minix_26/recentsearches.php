<div id="center">
<div class="container_box1">
<div id="headergames2">Recent Searches</div>
<center>
        <form name="browse" method='get' action="<?php echo $setting['siteurl'];?>index.php">
        <input type='hidden' name='act' value='search'/>
        <input type="text" style="color:#999" maxlength="18" size="20" name="q" onblur="this.value = this.value || this.defaultValue; this.style.color = '#999';" onfocus="this.value=''; this.style.color = '#000';" value="Type search here"/>
        <input type="submit" value="Search" />
		</form></center>
<div class="recentsearches">
<?php
/* The following code displays the latest visitor searches */
$filename = $setting['sitepath']. "/searches.txt";
if(file_exists($filename)) {
    if (filesize($filename) > 0) {
        if (!isset($searchArray)) {
            $filename = $setting['sitepath']. "/searches.txt";
            $fh = fopen($filename, "r");
            $searchArray = array();
            $searches = fread($fh, filesize($filename));
            fclose($fh);
            $searchArray = unserialize($searches);
        }
        echo '<center><h3>View what other guests or members have recently searched:</h3></center>'."<br/>";
        foreach ($searchArray AS $key=>$value) {
?>			
			<ul>
			<li class="title">
			<a href="<?php echo $setting['siteurl'];?>index.php?act=search&q=<?php echo $value;?>"><?php echo $value;?></a>,		
			</li>
			</ul>			
<?php
        }
    }
}
/* End latest searches */
?>
<div class="clear"></div>
</div>
<div class="clear"></div>
</div>