<div id="center-column">
<div class="top-bar">
<h1>Cpanel - Mochiad Feed</h1>
<div class="breadcrumbs"><a href="index.php?act=managemochi" title="Install Mochi Games">Install Mochi Games</a> / <a href="index.php?act=mochiid" title="Update Mochi ID">Update Mochi ID</a></div>
</div><br />
<div class="select-bar">
<label>
<h3>Manage Mochiad Feed</h3>
</label>
</div>
<br/>
<span style="text-align:center;"><h3>Feed takes a while to update and install. Please wait until Success message appears.</h3></span><br/><br/>
<span style="text-align:center;">
<?php 
if (isset($_GET['getfeed'])) {
	$category = $_GET['category'];
	$rating = $_GET['rating'];
	
	include ("mochi_functions.php");
	if (!get_mochifeed($category, $rating)) {
		echo '<center>There was a problem getting the Mochi feed.</center>';
		exit();
	}
	echo '<center>Feed successfully installed!</center>';
} ?>	
<form enctype="multipart/form-data" action="index.php" method="get">
	<input type="hidden" name="act" value="mochifeed"/>
	<label for="category_filter">Category:</label>
	<select name="category" id="category">
	<option value="all" selected>All Categories</option>
	<optgroup label="genre">
	<option value="action">Action</option>
	<option value="adventure">Adventure</option>
	<option value="board_game">Board Game</option>
	<option value="casino">Casino</option>
	<option value="dress-up">Dress up</option>
	<option value="driving">Driving</option>
	<option value="fighting">Fighting</option>
	<option value="other">Other</option>
	<option value="customize">Customize</option>
	<option value="puzzles">Puzzles</option>
	<option value="shooting">Shooting</option>
	<option value="sports">Sports</option>
	<option value="strategy">Strategy</option>
	<option value="jigsaw">Jigsaw/Slider Puzzles</option>
	</optgroup>
	<optgroup label="other">
	<option value="recommended">Featured Games</option>
	<option value="coins_enabled">Coins Enabled</option>
	<option value="leaderboard_enabled">Leaderboard Enabled</option>
	</optgroup>
	</select>
	<label for="rating_filter">Rating:</label>
	<select id="rating" name="rating">
	<option value="all" selected>All Ratings</option>
	<option value="everyone">Everyone</option>
	<option value="teen">Teen</option>
	<option value="mature">Mature</option>
	</select>
	<br/><br/>
	<input type="submit" style="font-weight: bold" name="getfeed" value="Get Feed!" />
</form>
</span>
</div>