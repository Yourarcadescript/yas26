<div id="center">
<div class="container_box1">
<div class="header"><center>Unsubscribe</center></div>             
<div class="container_box4">
<center>
<?php
ini_set( "display_errors", 0);
if (isset($_GET['id']))
{
include_once ("../includes/db_functions.inc.php");
$id=yasDB_clean($_GET['id']);
$query = yasDB_delete("DELETE FROM notifydown WHERE email ='$id'",false);
if (!$query){
	echo 'Database error!';
} else echo 'Your email is deleted from the mailing list.';
}
?>
</center>
</div>
<div class="clear"></div>
</div>