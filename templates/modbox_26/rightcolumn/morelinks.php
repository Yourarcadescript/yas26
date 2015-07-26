<div class="nav_box"><div class="nav">More Categories</div>
<div class="nav_box2">
<div class="links1">
<?php
$query = yasDB_select("SELECT * FROM categories WHERE active='yes' AND home='no' ORDER BY `order` desc",false);
while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
	if ($setting['seo']=='yes') {
        echo '<a href="' . $setting['siteurl'] . 'category/' . $row['id'] .'/1.html">' . $row['name'] . '</a> | ';                 
    } else {
        echo '<a href="' . $setting['siteurl'] . 'index.php?act=cat&id=' . $row['id'] .'">' . $row['name'] . '</a> | ';
	}
}
$query->close();
?>
</div>
</div></div>