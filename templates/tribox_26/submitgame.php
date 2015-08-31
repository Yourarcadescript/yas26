<!----------Left----------->
<div id="center">
<!------------Submitgame---------->
<?php
if (isset($_POST['name'])) {
  $name = $_POST['name'];
} else if (isset($_SESSION['user'])) {
  $name = $_SESSION['user'];
} else {
  $name = '';
}
if(isset($_SESSION['user'])) {
?>
<div class="container_box1">
<div class="game_header">&nbsp;Submit Game</div>
<div class="containbox">
<center><p style="font-weight:bold;">Adding Game Info</p><p>All Games are reviewed before they are approved.<br/> Please allow up to 24-48hrs ... Thanks<br/>
<span style="display:block;text-align:center;font-weight:bold;font-size:18px;">Fill out the Upload Game Form</span></p></center><br/>
<?php
function get_file_extension($filepath) {
    preg_match('/[^?]*/', $filepath, $matches);
    $string = $matches[0];
    $pattern = preg_split('/\./', $string, -1, PREG_SPLIT_OFFSET_CAPTURE);
    // check if there is any extension
    if(count($pattern) == 1) {
        return false;
    }

    if(count($pattern) > 1) {
        $filenamepart = $pattern[count($pattern)-1][0];
        preg_match('/[^?]*/', $filenamepart, $matches);
        return strtolower($matches[0]);
    }
}
$num = rand(0, pow(10, 5)) . '-'; // 5 digit random number to prefix game name
$thumb_types=array(
    'gif',
    'jpeg',
    'jpg',
    'png'
);
$file_types=array(
	'swf',
	'dcr',
	'UNITY',
	'mov',
	'mpg',
	'avi',
	'flv',
	'wmv'
);
if (isset($_POST['add'])) {
    if (!isset($_FILES['file']['name']) || !isset($_FILES['thumbnail']['name'])) {
        echo '<center>No file or thumbnail detected</center>';
        exit();
    }
    $thumb_ext = get_file_extension($_FILES["thumbnail"]["name"]) or die("<center>You must include a game pic.</center>");
    $file_ext = get_file_extension($_FILES["file"]["name"]) or die("<center>You must include a game file.</center>");
    if (in_array($thumb_ext, $thumb_types) || in_array($file_ext, $file_types)) {
        if ($_FILES["file"]["error"] > 0) {
            echo "<center>Return Code: " . $_FILES["thumbnail"]["error"] . "</center>";
        }
        else {
            move_uploaded_file($_FILES["thumbnail"]["tmp_name"],
            $setting['sitepath']."/img/" . $num . $_FILES["thumbnail"]["name"]);
            move_uploaded_file($_FILES["file"]["tmp_name"],
            $setting['sitepath']."/swf/" . $num . $_FILES["file"]["name"]);
            $img = yasDB_clean("img/" .  $num . $_FILES['thumbnail']['name']);
            $file = yasDB_clean("swf/" .  $num . $_FILES['file']['name']);
            $desc = yasDB_clean($_POST['description']);
            $title = yasDB_clean($_POST['title']);
            $instructions = yasDB_clean($_POST['instructions']);
            $keywords = yasDB_clean($_POST['keywords']);
            $height = yasDB_clean($_POST['height']);
            $width = yasDB_clean($_POST['width']);
            if ($_POST['height'] && $_POST['width'] > 0 ) {
                $query = yasDB_insert("INSERT INTO games (title, description, instructions, keywords, category, thumbnail, file, height, width, type, active) VALUES ('$title', '$desc', '$instructions', '$keywords', '{$_POST['category']}', '$img', '$file', '$height', '$width'', '{$_POST['type']}', '0')",false);
                if ($query) echo '<center><span style="display:block;text-align:center;font-size:24px;padding:10px 0 50px 0;">Game successfully added!</span></center>';
            }
            else {
                list($width, $height, $type, $attributes) = getimagesize($setting['sitepath'].'/'.$file);
                $query = yasDB_insert("INSERT INTO games (title, description, instructions, keywords, category, thumbnail, file, height, width, type, active) VALUES ('$title', '$desc', '$instructions', '$keywords', '{$_POST['category']}', '$img', '$file', '$height', '$width', '{$_POST['type']}', '0')",false);
                if ($query) echo '<center><span style="display:block;text-align:center;font-size:24px;padding:10px 0 50px 0;">Game successfully added!</span></center>';
            }
        }
    }
    else {
        echo '<center>File or thumbnail type not supported</center>';
        exit();
    }
}
?>
</div><div class="clear"></div></div>
<div class="container_box1"> 
<div class="containbox">
    <form enctype="multipart/form-data" action="index.php?act=submitgame" method="post">
    <input type="hidden" name="act" value="uploadgames"/>
    <table class="tg" colspan="4">
    <tr>
    <th class="tg-s6z2" colspan="4">Title:</th>
    </tr>
    <tr>
    <td class="tg-s6z2" colspan="4"><input type="text" id="s-a-g" name="title"/></td>
    </tr>
    <tr>
    <th class="tg-s6z2" colspan="2">Description:</th>
    <th class="tg-s6z2" colspan="2">Instructions:</th>
    </tr>
    <tr>
    <td class="tg-s6z2" colspan="2"><textarea id="d-i-k" name="description"></textarea></td>
    <td class="tg-s6z2" colspan="2"><textarea id="d-i-k" name="instructions"></textarea></td>
    </tr>
    <tr>
    <th class="tg-s6z2" colspan="2">Keywords:</th>
    <th class="tg-s6z2" colspan="2">Category:&nbsp;-&nbsp;Game File Type:</th>
    </tr>
    <tr>
    <td class="tg-s6z2" colspan="2"><textarea id="d-i-k" name="keywords"></textarea></td>
    <td class="tg-s6z2" colspan="2"><select name="category">
    <?php
    $query = yasDB_select("SELECT * FROM categories",false);
    while($row = $query->fetch_array(MYSQLI_ASSOC)) {
    echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
    }
    $query->close();
    ?>
    </select></br></br>
    <select name="type">
    <option value="SWF">swf</option>
    <option value="DCR">dcr</option>
    </select>
    </td>
    </tr>
    <tr>
    <th class="tg-s6z2" colspan="2">Game File:</th>
    <th class="tg-s6z2" colspan="2">Thumbnail:</th>
    </tr>
    <tr>
    <td class="tg-s6z2" colspan="2"><input type="file" name="file"/> (swf/dcr)</td>
    <td class="tg-s6z2" colspan="2"><input type="file" name="thumbnail"/> (gif/jpg/png)</td>
    </tr>
    <tr>
    <th class="tg-s6z2" colspan="2">Height:</th>
    <th class="tg-s6z2" colspan="2">Width:</th>
    </tr>
    <tr>
    <td class="tg-s6z2" colspan="2"><input type="text" name="height" id="s-a-g"/></td>
    <td class="tg-s6z2" colspan="2"><input type="text" id="s-a-g" name="width"/></td>
    </tr>
    <tr>
    <th class="tg-s6z2" colspan="4">Submit Game:</th>
    </tr>
    <tr>
    <td class="tg-s6z2" colspan="4"><input type="submit" class="button" name="add" value="Add Game" /></td>
    </tr>
    </table>
    </form>
    <div class="clear"></div>
    </div>
    <div class="clear"></div>
    </div>    
<?php
} else {
?>
<div class="container_box1">
<div id="headergames2">&nbsp;Submit Game</div>
<div class="containbox">
<?php
	    if ( $setting['seo']=='yes' ) {
			$reglink = $setting['siteurl'].'register.html';
	    } else {
			$reglink = $setting['siteurl'].'index.php?act=register';
    }
?>
<center><span style="display:block;text-align:center;padding:10px 0 50px 0;">Sorry, you have to be a <a href="<?php echo $reglink;?>">Registered</a> member or signed in to submit a game.</span></center>
</div>
<div class="clear"></div>
</div>
<?php }	?>