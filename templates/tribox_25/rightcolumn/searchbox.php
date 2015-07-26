<div class="navh3">
    <div align="center">Search</div>
</div>
<div id="search" style = "height:auto;">      
    <div id="div1">
        <form name="browse" method='get' action="<?php echo $setting['siteurl'];?>index.php">
        <input type='hidden' name='act' value='search'/>
        <input type="text" style="width:115px;color:#999" maxlength="30" size="22" name="q" onblur="this.value = this.value || this.defaultValue; this.style.color = '#999';" onfocus="this.value=''; this.style.color = '#000';" value="Type search here"/>
        <input type="submit" value="Search" />
        </form>
</div></div><br/>
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
        ?>
		<div class="navh3">
			<div align="center">Recent Visitor Searches</div>
		</div><br/>
		<?php
		foreach ($searchArray AS $key=>$value) {
            ?><a href="<?php echo $setting['siteurl'];?>index.php?act=search&q=<?php echo $value;?>"><?php echo $value;?></a>&nbsp;&nbsp;
            <?php
        }
    }
}
/* End latest searches */
?>
 