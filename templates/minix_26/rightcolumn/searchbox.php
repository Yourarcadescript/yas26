<div class="nav_box"><div class="nav">Search</div>
<div class="nav_box2">     
        <form name="browse" method='get' action="<?php echo $setting['siteurl'];?>index.php">
        <input type='hidden' name='act' value='search'/>
        <input type="text" style="color:#999;width:93px;font-size:12px;" maxlength="18" size="20" name="q" onblur="this.value = this.value || this.defaultValue; this.style.color = '#999';" onfocus="this.value=''; this.style.color = '#000';" value="Type search here"/>
        <input type="submit" value="Search" />
		</form>
</div></div>
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
		<div class="nav_box">
		<div class="nav">Recent Searches</div>
		<div class="nav_box2"> 
		<?php
		foreach ($searchArray AS $key=>$value) {
            ?><a href="<?php echo $setting['siteurl'];?>index.php?act=search&q=<?php echo $value;?>"><?php echo $value;?></a>&nbsp;&nbsp;
            <?php
        }
    ?>
	</div></div>
	<?php
	}
}
/* End latest searches */
?>