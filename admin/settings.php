<div id="center-column">
<div class="top-bar">
<a href="index.php?act=news" class="button">ADD NEWS</a>
<h1>Cpanel - Settings</h1>
<div class="breadcrumbs"><a href="index.php?act=categories">Categories</a> / <a href="index.php?act=links">Links</a></div>
</div><br />
<div class="select-bar">
<label>
<h3>General Site Settings</h3>
</label>
</div>
<?php
$query1 = yasDB_select("SELECT * FROM links WHERE approved = 'no'",false);
$links = $query1->num_rows;
$query1->close();

$query2 = yasDB_select("SELECT id FROM games");
$tgames = $query2->num_rows;
$query2->close();

$query3 = yasDB_select("SELECT * FROM stats WHERE id = '1'",false);
$totalplays = $query3->fetch_array(MYSQLI_ASSOC);
$totalplays = $totalplays['numbers'];
$query3->close();

$query4 = yasDB_select("SELECT * FROM stats WHERE id = '2'",false);
$todayplays = $query4->fetch_array(MYSQLI_ASSOC);
$todayplays = $todayplays['numbers'];
$query5 = yasDB_select("SELECT * FROM comments");
$comments = $query5->fetch_array(MYSQLI_ASSOC);
$comments = $query5->num_rows;
$query5->close();

$query6 = yasDB_select("SELECT id FROM user");
$user = $query6->fetch_array(MYSQLI_ASSOC);
$user = $query6->num_rows;
$query6->close();
if(isset($_POST['settings'])) {
    if(empty($_POST['gperpage']) || empty($_POST['gamesort']) || empty($_POST['numbgames']) || empty($_POST['lightbox']) || empty($_POST['approvelinks']) || empty($_POST['disabled'])|| empty($_POST['regclosed']) || empty($_POST['numblinks'])) {
        echo '<center>One or more fields that were required were left empty.<br />';
        echo '<a href="index.php?act=settings">Click here to go back.</a></center>';
    } else {
        if(!ctype_digit($_POST['gperpage'])) {
            echo '<center>Files per page must be a number.</center>';
        } else {            
            if ($_POST['use'] == 1) {
                $captcha = 'yes';
            } else {
                $captcha = 'no';
            }
            if(!empty($_POST['password']) && $_POST['password'] == $_POST['passwordcheck']) {
                $id = '1';
                yasDB_update("UPDATE settings SET gperpage = ".intval($_POST['gperpage']).", numbgames = ".intval($_POST['numbgames']).", gamesort = '{$_POST['gamesort']}', approvelinks = '{$_POST['approvelinks']}', numblinks = ".intval($_POST['numblinks']).", seo = '{$_POST['seo']}' , lightbox = '{$_POST['lightbox']}', theme = '{$_POST['theme']}', skin = '{$_POST['skin']}', disabled = '{$_POST['disabled']}', regclosed = '{$_POST['regclosed']}', email = '{$_POST['email']}', sitename = '".yasDB_clean($_POST['sitename'])."', slogan = '".yasDB_clean($_POST['slogan'])."', metades = '".yasDB_clean($_POST['metades'])."', metakeywords = '".yasDB_clean($_POST['metakeywords'])."',`userecaptcha` = '$captcha', `cachelife` = ".intval($_POST['pagecache']).", `password` = '".md5($_POST['password'])."' where id = '1'");
                include("../includes/settings_function.inc.php");
				createConfigFile();
				echo '<center>Site settings updated!<br />';
                echo '<a href="index.php?act=settings">Click here to proceed.</a></center>';
            } elseif (empty($_POST['password'])){
                yasDB_update("UPDATE settings SET gperpage = ".intval($_POST['gperpage']).", numbgames = ".intval($_POST['numbgames']).", gamesort = '{$_POST['gamesort']}', approvelinks = '{$_POST['approvelinks']}', numblinks = ".intval($_POST['numblinks']).", seo = '{$_POST['seo']}' , lightbox = '{$_POST['lightbox']}', theme = '{$_POST['theme']}', skin = '{$_POST['skin']}', disabled = '{$_POST['disabled']}', regclosed = '{$_POST['regclosed']}', email = '{$_POST['email']}', sitename = '".yasDB_clean($_POST['sitename'])."', slogan = '".yasDB_clean($_POST['slogan'])."', metades = '".yasDB_clean($_POST['metades'])."', metakeywords = '".yasDB_clean($_POST['metakeywords'])."',`userecaptcha` = '$captcha', `cachelife` = ".intval($_POST['pagecache'])."  where id = '1'");
                include("../includes/settings_function.inc.php");
				createConfigFile();				
				echo '<center>Site settings updated!<br />';
                echo '<a href="index.php?act=settings"><span style="color:green;">Click here to proceed.</span></a></center>';
            } else {
                echo '<center>Passwords did not match!<br />';
                echo '<a href="index.php?act=settings">Click here to proceed.</a></center>';
            }        
        }
    }
} else {
    $query = yasDB_select("SELECT * FROM settings");
    $row = $query->fetch_array(MYSQLI_ASSOC);
    $dir = $setting['sitepath'].'/templates/';
    $files = scandir($dir);
    ?>
 <div class="table">
                <img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
                <img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
                <table class="listing form" cellpadding="0" cellspacing="0">
                    <tr>
                    <th class="full" colspan="2">General Settings</th>
                    </tr>
                    <tr>
                    <td class="first" width="172"><strong>Version</strong></td>
                    <td class="last"><b><?php echo $row['version'];?></b></td>
                    </tr>
                    <tr class="bg">
                    <td class="first"><strong>Unapproved links</strong></td>
                    <td class="last"><?php if ($links != 0) { echo '<font color="red">'.$links.'</font>';} else { echo '0'; } ?></td>
                    </tr>
                    <tr>
                    <td class="first"><strong>Comments</strong></td>
                    <td class="last"><?php echo $comments; ?></td>
                    </tr>
                    <tr class="bg">
                    <td class="first"><strong>Total Games</strong></td>
                    <td class="last"><?php echo $tgames; ?></td>
                    </tr>
                    <tr>
                    <td class="first"><strong>Total Plays</strong></td>
                    <td class="last"><?php echo $totalplays; ?></td>
                    </tr>
                    <tr class="bg">
                    <td class="first"><strong>Plays today</strong></td>
                    <td class="last"><?php echo $todayplays; ?></td>
                    </tr>
                    <tr>
                    <td class="first"><strong>Members</strong></td>
                    <td class="last"><?php echo $user; ?></td>
                    </tr>
                    <form name="settings" method="post" action="index.php?act=settings">
                    <tr class="bg">                    
                    <td class="first"><strong>Template</strong></td>
                    <td class="last">
                    <select name="theme" onchange="changeSkins('<?php echo $setting['sitepath'].'/templates/';?>' + this.options[this.selectedIndex].value + '<?php echo '/skins/';?>')">
                    <option value ="<?php echo $row['theme']; ?>" /><?php echo $row['theme']; ?></option>
                    <?php
					foreach ($files as &$file) {
						if ($file!='.' && $file!='..' && substr($file, -3) == '_25' || substr($file, -3) == '_26') { ?>
							<option value ="<?php echo $file;?>"><?php echo $file;?></option>
						<?php }
					}
					?>
					</select><br/>
                    </td>
                    </tr>
                    <tr>                    
                    <td class="first"><strong>Skin</strong></td>
                    <td class="last"><?php
                    $dir = $setting['sitepath'].'/templates/';
					$files = scandir($setting['sitepath'].'/templates/'.$row['theme'].'/skins/');
					?><select name="skin" id="skin">
                    <option value ="<?php echo $row['skin']; ?>" /><?php echo $row['skin'];?></option>
                    <?php
					foreach ($files as &$file) {
						if ($file!='.' && $file!='..' )	{ ?>
							<option value ="<?php echo $file;?>"><?php echo $file;?></option>
						<?php }						
                    }
                    ?>
					</select><br/>
                    </td>
                    </tr>
					<tr class="bg">
                    <td class="first"><strong>Anti-Spam</strong></td>
                    <td class="last"><input type="checkbox" name="use" value="1" <?php if ($row['userecaptcha'] == "yes") echo ' checked="checked"';?>/>  Use captcha System? <br /></td>
                    </tr>
					<tr>
						<td class="first"><strong>Page Cache Life</strong></td>
						<td class="last"><input type="text" name="pagecache" value="<?php echo $row['cachelife'];?>" size="4" /> in seconds (300 = 5 minutes)<br /></td>
					</tr>
				</table>
          </div> 
           <div class="table">
                <img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
                <img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
                <table class="listing form" cellpadding="0" cellspacing="0">
                    <tr>
                    <th class="full" colspan="2">Game Settings</th>
                    </tr>
                    <tr>
                    <td class="first" width="172"><strong>Lightbox Game play</strong></td>
                    <td class="last"><select name="lightbox">
                    <option value ="<?php echo $row['lightbox'];?>" selected><?php echo $row['lightbox'];?></option>
                    <option value ="yes">yes</option>  
                    <option value ="no">no</option>
                    </select> Depends on availability in template<br/></td>
                    </tr>
                    <tr class="bg">
                    <td class="first"><strong>Games per category</strong></td>
                    <td class="last"><input type="text" name="numbgames" value="<?php echo $row['numbgames'];?>" size="2" /><br /></td>
                    </tr>
                    <tr>
                    <td class="first"><strong>Games sort</strong></td>
                    <td class="last"><select name="gamesort">
                    <option value ="<?php echo $row['gamesort'];?>" /><?php echo $row['gamesort'];?></option>
                    <option value ="popular">popular</option>
                    <option value ="newest">newest</option>
                    <option value ="random">random</option>
                    </select></td>
                    </tr>
                    <tr class="bg">
                    <td class="first"><strong>Games per page</strong></td>
                    <td class="last"><input type="text" name="gperpage" value="<?php echo $row['gperpage'];?>" size="2" /> (also for category pages)</td>
                    </tr>
                </table>
          </div>
          <div class="table">
                <img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
                <img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
                <table class="listing form" cellpadding="0" cellspacing="0">
                    <tr>
                    <th class="full" colspan="2">Other</th>
                    </tr>
                    <tr>
                    <td class="first" width="172"><strong>Disable Site</strong></td>
                    <td class="last"><select name="disabled">
                    <option value ="<?php echo $row['disabled'];?>" /><?php echo $row['disabled'];?></option>
                    <option value ="yes">yes</option>  
                    <option value ="no">no</option>
                    </select></td>
                    </tr>
                    <tr>
                    <td class="first"><strong>Register Closed</strong></td>
                    <td class="last"><select name="regclosed">
                    <option value="<?php echo $row['regclosed'];?>" /><?php echo $row['regclosed'];?></option>
                    <option value="yes">yes</option>
                    <option value="no">no</option>
                    </select></td>
                    </tr>
                    <tr class="bg">
                    <td class="first"><strong>Auto Approve Exchange links</strong></td>
                    <td class="last"><select name="approvelinks">
                    <option value ="<?php echo $row['approvelinks'];?>" /><?php echo $row['approvelinks'];?></option>
                    <option value ="yes">yes</option>
                    <option value ="no">no</option>
                    </select></td>
                    </tr>
                    <tr class="bg">
                    <td class="first"><strong>Exchange Links on Home page</strong></td>
                    <td class="last"><input type="text" name="numblinks" value="<?php echo $row['numblinks'];?>" size="2" /><br /></td>
                    </tr>
					<tr>
                    <td class="first"><strong>SEO Friendly URLs</strong></td>
                    <td class="last"><select name="seo">
                    <option value ="<?php echo $row['seo'];?>" /><?php echo $row['seo'];?></option>
                    <option value ="yes">yes</option>
                    <option value ="no">no</option>
                    </select></td>
                    </tr>
                    </table>
                </div>
                <div class="table">
                <img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
                <img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
                <table class="listing form" cellpadding="0" cellspacing="0">
                    <tr>
                    <th class="full" colspan="2">Update</th>
                    </tr>
                    <tr>
                    <td class="first" width="172"><strong>Email</strong></td>
                    <td class="last"><input type="text" name="email" value="<?php echo $row['email'];?>" size="28"/></td>
                    </tr>
                    <tr>
                    <td class="first" width="172"><strong>Site name</strong></td>
                    <td class="last"><input type="text" name="sitename" value="<?php echo $row['sitename'];?>" size="28"/></td>
                    </tr>
					<tr>
                    <td class="first" width="172"><strong>Slogan</strong></td>
                    <td class="last"><input type="text" name="slogan" value="<?php echo $row['slogan'];?>" size="28"/></td>
                    </tr>
					<tr>
                    <td class="first" width="172"><strong>Meta Description (Seen in search engines)</strong></td>
                    <td class="last"><input type="text" name="metades" value="<?php echo $row['metades'];?>" size="28"/></td>
                    </tr>
					<tr>
                    <td class="first" width="172"><strong>Meta Keywords</strong></td>
                    <td class="last"><input type="text" name="metakeywords" value="<?php echo $row['metakeywords'];?>" size="28"/></td>
                    </tr>
					<tr class="bg">
                    <td class="first"><strong>Admin password</strong></td>
                    <td class="last"><input type="text" name="password" /> New Pass</td>
                    </tr>
                    <tr>
                    <td class="first"><strong>Re-enter</strong></td>
                    <td class="last"><input type="text" name="passwordcheck" /> Re-enter Pass</td>
                    </tr>
                    <tr class="bg">
                    <td class="first"><strong>Update Settings</strong></td>
                    <td class="last"><input type="submit" class="button" name="settings" value="Update Settings" /></td>
                    </tr>
                    </form>
                </table>                
          </div>         
<?php
}
?>
</div>