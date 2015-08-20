<?php
include ("db_functions.inc.php");
include ("config.inc.php");
include ("resizer.php");
if(isset($_FILES['file'])) {
    if ($_FILES['file']['size'] > 144000) { //@140 kb (size is also in bytes)
        print '<div id="status">Error</div><div id="message">Images must be smaller than 140 kb';
    } else { // File within size restrictions
        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $os = array("gif", "jpg", "jpeg", "png", "GIF", "JPG", "JPEG", "PNG");
        if (!in_array($ext, $os)) {
            print '<div id="status">error</div><div id="message">File type (' . $ext . ') not allowed</div>';
            exit();
        }
        //This assigns the subdirectory you want to save into... make sure it exists!
        $path = $setting['sitepath'] . '/avatars/useruploads/';

        $filename = 'avatar' . rand(111, 999) . rand(111, 999) . rand(11, 99) . rand(11, 99) . '.' . $ext; // create 10 digit random number
        $target = $path . $filename;
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
            switch ($_FILES['file'] ['error']) {
                case 1:
                    print '<div id="status">error</div><div id="message"> The file is too big.  Keep it to 140 kb max please.</div>';
                    break;
                case 2:
                    print '<div id="status">error</div><div id="message"> The file is bigger than this form allows</div>';
                    break;
                case 3:
                    print '<div id="status">error</div><div id="message"> Only part of the file was uploaded</div>';
                    break;
                case 4:
                    print '<div id="status">error</div><div id="message"> No file was uploaded</div>';
                    break;
            }
        } else {
            print '<div id="status">success</div><div id="message">Your Avatar has been updated.';
            $avatarfile = 'useruploads/' . $filename;
            $settings = array('w' => 100, 'h' => 100);
            $image = new SimpleImage();
            $image->load($setting['sitepath'] . '/avatars/' . $avatarfile);
            if ($image->getHeight() > $image->getWidth()) {
                $image->resizeToHeight(100);
            } else {
                $image->resizeToWidth(100);
            }
            $image->save($setting['sitepath'] . '/avatars/' . $avatarfile);
            $avatar = 1;
            $userid = intval($_POST['userid']);
            yasDB_update("UPDATE `user` SET useavatar='$avatar', avatarfile='$avatarfile' WHERE id='$userid'");
            yasDB_insert("INSERT INTO `avatars` (userid, avatar) VALUES ('$userid', '$avatarfile')");
            ?>
            <script type="text/javascript">
                $('#avatarimage').attr("src", "<?php echo $setting['siteurl'];?>/avatars/" + "<?php echo $avatarfile;?>");
            </script>
            <?php
        }
    }
} else {
    print '<div id="status">error</div><div id="message">No file was detected';
}
?>