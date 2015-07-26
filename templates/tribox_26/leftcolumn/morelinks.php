<div class="navh3"><div align="center">All Categories</div>
</div>
<div class="links1">
<?php
$query = yasDB_select("SELECT * FROM categories WHERE active='yes' ORDER BY `order` desc",false);
while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
	if ($setting['seo']=='yes') {
        echo '<a href="' . $setting['siteurl'] . 'category/' . $row['id'] .'/1.html">' . $row['name'] . '</a>';                 
    } else {
        echo '<a href="' . $setting['siteurl'] . 'index.php?act=cat&id=' . $row['id'] .'">' . $row['name'] . '</a>';
	}
}
$query->close();
?>
</div>