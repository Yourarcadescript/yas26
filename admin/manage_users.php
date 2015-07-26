<div id="center-column">
<div class="top-bar">
<a href="index.php?act=news" class="button">ADD NEWS</a>
<h1>Cpanel - Users</h1>
<div class="breadcrumbs"><a href="index.php?act=general" title="Settings">Settings</a> / <a href="index.php?act=categories">Categories</a> / <a href="index.php?act=links">Links</a> / <a href="index.php?act=memberscomment" title="Members Comments">Members Comments</a></div>
</div><br />
<div class="select-bar">
<label>
<h3>Manage Users</h3>
</label>
</div>
<?php

if(!empty($_GET['edit'])) {

   $query = yasDB_select("select * from user where id = '{$_GET['edit']}'");
   
   if($query->num_rows == 0) {
      echo 'You cannot edit a user that doesnt exist.';
   } else {
      $row = $query->fetch_array(MYSQLI_ASSOC);
      $date = date("M/d/Y", $row['date']);?>
	        <div class="table">
		    <img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
			<img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
			<form name="edit" method="post" action="index.php?act=manage_users">
			<table class="listing form" cellpadding="0" cellspacing="0">
	        <tr>
	        <th class="full" colspan="2">Edit Member - <?php echo $row['username'];?></th>
			</tr>
			<tr>
			<td class="first" width="172"><strong>Date joined</strong></td>
			<td class="last"><?php echo $date;?></td>
			</tr>
			<tr class="bg">
			<td class="first"><strong>Username</strong></td>
			<td class="last"><input type="text" name="username" value="<?php echo $row['username'];?>" /></td>
		    </tr>
			<tr>
			<td class="first"><strong>Email</strong></td>
			<td class="last"><input type="text" name="email" value="<?php echo $row['email'];?>" /></td>
			</tr>
			<tr class="bg">
			<td class="first"><strong>Website</strong></td>
			<td class="last"><input type="text" name="website" value="<?php echo $row['website'];?>" /></td>
			</tr>
            <tr>
			<td class="first"><strong>Ban for x days</strong></td>
			<td class="last"><input type="text" name="endban" value="" /></td>
			</tr>
			<tr class="bg">
			<td class="first"></td>
			<td class="last"><input type="hidden" name="id" value="<?php echo $row['id'];?>" /><input type="submit" class="button" name="edit" value="Edit!" /></td>
			</tr>
			</form>
			</table>
			</div>
<?php
   }
} 
elseif(isset($_POST['edit'])) {

   if(empty($_POST['id']) || empty($_POST['username'])) {
      echo '<center>One or more fields was left empty.<br />';
      echo '<a href="index.php?act=manage_users&edit=' . $_POST['id'] . '">Click here to go back</a></center>';
   } 
   else {
	   	if(isset($_POST["endban"]) && $_POST["endban"] != '' ) {
			$endban = yasDB_clean($_POST["endban"]);
			if ($endban != 0) {
				$endban = $endban * 24 * 60 * 60;
				$endban = time() + $endban;
			}
		}
		else {
			$endban = 0;
		}
        yasDB_update("UPDATE user SET username = '{$_POST['username']}', email = '{$_POST['email']}', website = '{$_POST['website']}', endban = $endban 
			WHERE id = '{$_POST['id']}'");
        echo '<center>user Successfully edited!<br />';
        echo '<a href="index.php?act=manage_users">Click here to proceed</a></center>';
   }
}
elseif(!empty($_GET['reset'])) {
	$query = yasDB_select("SELECT avatar FROM user WHERE id='{$_GET['reset']}'");
	$exists = $query->fetch_array(MYSQLI_ASSOC);
	if($exists['avatar']!="") {
		unlink("../avatars/".$exists['avatar']);
		yasDB_update("UPDATE user SET avatar='' WHERE id='{$_GET['reset']}'");
		echo "<center>Users avatar has been deleted and reset.<br />";
		echo "<a href=\"index.php?act=manage_users\">Click here to proceed</a></center>";
	}
}
elseif(!empty($_GET['delete'])) {
   $query = yasDB_select("select username FROM user WHERE id = '{$_GET['delete']}'");
   if($query->num_rows == 0) {
      echo '<center>You cannot delete a user that does not exist!<br />';
      echo '<a href="index.php?act=manage_users">Click here to go back</a></center>';
   }
   else {
      yasDB_delete("DELETE FROM user WHERE id = '{$_GET['delete']}'");
      echo '<center>user successfully deleted.<br />';
      echo '<a href="index.php?act=manage_users">Click here to proceed</a></center>';
       
   }
} elseif(isset($_POST['deletechecked'])) {
    $count = count($_POST['checkbox']);
    ?>
    <script type = "text/javascript">
        var response = confirm("Are you sure you want to delete <?php echo $count;?> user(s)?");
        if (!response) {
            window.location.href = '<?php echo $setting['siteurl'];?>admin/index.php?act=manage_users&remove=notta';
            
        }
    </script>
    <?php
    foreach ($_POST['checkbox'] as $box) {
        $query = yasDB_select("SELECT username FROM user WHERE id = '$box'",false);
        if($query->num_rows == 0) {
            echo '<center>You cannot delete a user that does not exist!<br />';
            echo '<a href="index.php?act=manage_users">Click here to go back</a></center>';
        } else {
            if ($_GET['remove'] != 'notta') {
                $row = $query->fetch_array(MYSQLI_ASSOC);				
                $query->close();            
                yasDB_delete("delete from user where id = '$box'",false);
                ?>
                <meta http-equiv="refresh" content="0;URL=<?php echo $setting['siteurl'];?>admin/index.php?act=manage_users" />
                <?php
            }
        }
   }
} else {
   if (isset($_GET['page'])) {
      $pageno = $_GET['page'];
   }
   else {
      $pageno = 1;
   }
   $result = yasDB_select("SELECT count(id) FROM user");
   $query_data = $result->fetch_array(MYSQLI_NUM);
   $numrows = $query_data[0];
   $rows_per_page = 10;
   $lastpage = ceil($numrows/$rows_per_page);
   $lastpage = ($lastpage < 1) ? 1:$lastpage;
   $pageno = (int)$pageno;
   if ($pageno < 1) {
      $pageno = 1;
   } elseif ($pageno > $lastpage) {
      $pageno = $lastpage;
   }
   $limit = 'LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;
   $query = yasDB_select("select * from user ORDER BY `id` DESC $limit");
   if($query->num_rows == 0) {
		echo '<center>There are no registered users.</center>';
   } else {
		?>
        <div class="table">
		<img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
		<img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
		<table class="listing" cellpadding="0" cellspacing="0">
		<tr>
			<th class="first" style="width:35px;">ID</th>
			<th style="width:100px;">Username</th>
			<th style="width:100px;">Date Joined</th>
			<th>Email</th>
			<th style="width:45px;">Banned</th>
			<th style="width:35px;">Edit</th>
			<th class="last" style="width:50px;">Delete</th>
		</tr>
		<form name="deleteform" method="post" action=""><?php
     while($row = $query->fetch_array(MYSQLI_ASSOC)) {
	   $date = date("F j, Y", $row['date']);
	   $banned = $row['endban'];
	   if($banned>0) {
		   $banned = date("F j, Y g:i a", $row['endban']);
	   }
	   else {
		   $banned = "No";
	   }
	   if($row["avatarfile"]!=""){
		  $avatarfile="<a href=\"index.php?act=manage_users&reset={$row['id']}\">Reset</a>";
	   }  else {
		  $avatarfile = "No";
	   } 
	   ?>
       <tr>
	    <td class="first style1"><?php echo $row['id'];?></th>
		<td style="overflow:hidden;"><?php echo $row['username'];?></td>
		<td><?php echo $date;?></td>
		<td style="overflow:hidden;"><?php echo $row['email'];?></td>
		<td><?php echo $banned;?></td>
		<td><a href="index.php?act=manage_users&edit=<?php echo $row['id'];?>"><font color="#cc0000">Edit</font></a></td>
		<td class="last"><a href="index.php?act=manage_users&delete=<?php echo $row['id'];?>" onclick="return confirm(\'Are you sure you want to delete this user?\')"><font color="#cc0000">Delete</font></a><br/><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $row['id'];?>"></td>
	   </tr>
		<?php
	 } 
		$query->close();?>
		</table>
		</div>
		<div style="text-align:right;">
			<input name="deletechecked" class="button" type="submit" id="deletechecked" value="Delete Checked">
			</form>
		</div>
		<div style="text-align:center;padding-bottom:10px;">	
		<?php
		if ($pageno == 1) {
		  echo ' FIRST PREV ';
		} else {
		  echo ' <a href="index.php?act=manage_users&page=1">FIRST</a> ';
		  $prevpage = $pageno-1;
		  echo ' <a href="index.php?act=manage_users&page=' . $prevpage . '">PREV</a> ';
		}
		echo ' ( Page ' . $pageno . ' of ' . $lastpage . ' ) ';
		if ($pageno == $lastpage) {
		  echo ' NEXT LAST ';
		} else {
		  $nextpage = $pageno+1;
		  echo ' <a href="index.php?act=manage_users&page=' . $nextpage . '">NEXT</a> ';
		  echo ' <a href="index.php?act=manage_users&page=' . $lastpage . '">LAST</a> ';
		}
		?>
		</div>
		<?php
	}
}
?>
</div>