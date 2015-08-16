<div id="center">
<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
?>
<script type="text/javascript">
tinyMCE.init({
    mode : "textareas",
    theme : "advanced",
    plugins : "spellchecker,pagebreak,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking",
    
    // Theme options
    theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect,forecolor,backcolor",
    theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,image,cleanup,help,|,preview",
    theme_advanced_buttons3 : "charmap,emotions,iespell,media,advhr,ltr,rtl,|,spellchecker,|,visualchars,nonbreaking,|,fullscreen",
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    theme_advanced_statusbar_location : "bottom",
    theme_advanced_resizing : true,
	theme_advanced_height : "500"
    
});
</script>
<?php
if (isset($_POST['name'])) {
    $name = $_POST['name'];
} else if (isset($_SESSION['user'])) {
    $name = $_SESSION['user'];
} else {
    $name = '';
}
$query1 = yasDB_select("SELECT name FROM forumcats WHERE id = ".intval($_GET['cat']));
$cat_data = $query1->fetch_array(MYSQLI_ASSOC);
$query1->close();
?>
<div class="container_box1"><div class="forumheader"><a href="<?php echo $setting['siteurl'].'index.php?act=forum';?>">Forum</a>&nbsp;&nbsp;>&nbsp;&nbsp;<a href="<?php echo $setting['siteurl'].'index.php?act=forumcats&id='.intval($_GET['cat']);?>"><?php echo $cat_data ['name'];?></a>&nbsp;&nbsp;>&nbsp;&nbsp;New Topic</div>
<?php
    if (isset($_POST['Submit'])) {
        $cat = yasDB_clean($_POST['cat']);
        $subject = yasDB_clean($_POST['subject']);
        $text = yasDB_clean($_POST['text']);

        if (strlen($_POST['subject'])<2 || strlen($_POST['text'])<2) {
            echo "<h3 class='align-center'>Subject or message can't be empty</h3>";
        }
        else {

            if (isset($_POST['name'])) {
                $name = yasDB_clean($_POST['name']);
            } else if (isset($_SESSION['user'])) {
                $name = $_SESSION['user'];
            } else {
                $name = '';
            }

            $date = date("F j, Y, g:i a"); //create date time

            $sql = "INSERT INTO `forumtopics` (id, subject, cat, date, name, text,lastupdate) VALUES ('', '$subject', $cat, '$date', '$name', '$text'," . time() . ")";
            $result = yasDB_insert($sql);

            if (isset($_SESSION['user'])) {
                $user = yasDB_clean($_SESSION['user']);
                yasDB_update("UPDATE `user` set topics = topics +1 WHERE username = '$user'"); // add a post to the user
                yasDB_update("UPDATE `user` set totalposts = totalposts +1 WHERE username = '$user'"); // add a post to user total
                yasDB_update("UPDATE `stats` set numbers = numbers +1 WHERE id = '3'"); // adds a post to Forum Total Posts
                yasDB_update("UPDATE `stats` set numbers = numbers +1 WHERE id = '4'"); // adds a post to Post Today
            }

            if ($result) {
                ?>
                <center>Successful<br/></center>
                <?php
                $query = yasDB_select("SELECT max(id) AS lastid FROM forumtopics");
                $answer = $query->fetch_array(MYSQLI_ASSOC);
                if ($setting['seo'] == 'yes') {
                    $answerlink = $setting['siteurl'] . 'forumtopics/' . $answer['lastid'] . '/1.html';
                } else {
                    $answerlink = $setting['siteurl'] . 'index.php?act=forumtopics&id=' . $answer['lastid'];;
                }
                ?>
                <center><a href="<?php echo $answerlink; ?>">View your topic</a></center><?php
            } else {
                echo "<center>Could not create topic.</center>";
            }
        }
    } elseif (!isset($_SESSION["user"])) {
    //the user is not signed in
    if ($setting['seo'] == 'yes') {
    $reglink = $setting['siteurl'].'register.html';
    } else {
    $reglink = $setting['siteurl'] . 'index.php?act=register';
    }
    echo '<center>Sorry, you have to be <a href="'.$reglink.'">Registered</a> or signed in to create a topic.</center>';
    } else {
    //the user is signed in
?>
    <div id="commentBox">
        <div id="msg">
        <center><form id="form1" name="form1" method="post" action="">
        <input name="name" type="hidden" value="<?php echo $name;?>"/>
        Topic:<br /><input name="subject" type="text" id="subject" size="40" /><br /><br />
        Details:<br /><textarea name="text" rows="6" cols="84" id="text"></textarea><br /><br />
        <input type="submit" class="button2" name="Submit" value="Submit" /> <input type="reset" class="button2" name="Submit2" value="Reset" />
        <input type="hidden" name="cat" value="<?php echo intval($_GET['cat']); ?>" />
        </form></center><br />
</div></div>
<?php
}
?>
<div class="clear"></div>
</div>