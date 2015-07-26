<div id="center">
<div class="main_header">Top 20 Players:</div>   
<div class="main_box"> 		   
	<table cellspacing="0" cellpadding="0" border="1" width="95%">
	  <tr>
		<th align="left">Member</th>
		<th align="left">Plays</th>
		<th align="left">Join date</th>
	 </tr>
	 <?php
	$query = yasDB_select("SELECT * FROM user ORDER by plays desc limit 0, 20");
	while($row = $query->fetch_array(MYSQLI_ASSOC)) {
		$date = date("M/d/Y", $row['date']);				
        echo '<tr><td>' . $row['username'] . '</td><td>' . $row['plays'] . '</td><td>' . $date . '</td></tr>';
	}
	$query->close();	                           
	?>
	</table>
    <br>
</div>