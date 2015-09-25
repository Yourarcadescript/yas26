<div id="center">
    <div class="container_box1">
        <noscript>
            <?php
            if (isset($_POST['submit'])) {
                if ($_POST['recaptcha'] == 'yes') {
                    include($setting['sitepath'] . "/includes/securimage/securimage.php");
                    $img = new Securimage();
                    $valid = $img->check($_POST['code']);
                    if (!$valid) {
                        $passed = false;
                    } else {
                        $passed = true;
                    }
                } elseif ($_POST['recaptcha'] == 'no') {
                    $answer = array('10', 'ten');
                    if (!in_array(strtolower($_POST['security']), $answer)) {
                        $passed = false;
                    } else {
                        $passed = true;
                    }
                }
            if ($_POST['username2'] == '' || $_POST['password'] == '' || $_POST['repeatpassword'] == '') {
                ?>
                <script>alert("Sorry username or password,repeatpassword is empty!!");</script>
            <META HTTP-EQUIV="Refresh"
                  CONTENT="0; URL=<?php echo $setting['siteurl'] . 'index.php?act=register'; ?>">
            <?php
            exit;
            }
            if ($passed) {
            $id = yasDB_clean($_POST["id"]);
            $username = yasDB_clean($_POST["username2"]);
            $password = md5(yasDB_clean($_POST["password"]));
            $repeatpassword = md5(yasDB_clean($_POST["repeatpassword"]));
            $name = yasDB_clean($_POST["name"]);
            $email = yasDB_clean($_POST["email"]);;
            $website = yasDB_clean($_POST["website"]);
            $date = time() + (0 * 24 * 60 * 60);
            $plays = 0;
            $points = 0;
            $randomkey = rand(13649875, 98732458);
            $headers = 'From: ' . $setting['sitename'] . ' <' . $setting['sitename'] . '>';
            $subject = 'Activate your account at ' . $setting['siteurl'];
            $body = '
					Thank you for becomming a member here at ' . $setting['sitename'] . '\r\n
					We hope you enjoy our site and what we have to offer.\r\n>
					To activate your account click the link below:\r\n
					' . $setting{'siteurl'} . 'activated.php?id=' . $id . '=code=' . $randomkey . '
					\r\n
					Thanks,
					Admin:';
            mail($email, $subject, $body, $headers);
            $stmt = yasDB_select("SELECT * FROM user WHERE username LIKE '$username'");
            if ($stmt->num_rows == 0) {
            $stmt = yasDB_insert("INSERT INTO `user` (username, password, repeatpassword, name, email, website, plays, points, date, randomkey, activated) VALUES ('$username','$password','$repeatpassword','$name','$email','$website','$plays','$points', '$date', $randomkey, '0')", false);
            if ($stmt) {
            ?>
                <script>alert("Registered: Please check email to activate your account!");</script>
            <META HTTP-EQUIV="Refresh" CONTENT="0; URL=<?php echo $setting['siteurl']; ?>">
            <?php
            exit;
            } else {
            $stmt->close();
            ?>
                <script>alert("Action Failed");</script>
            <META HTTP-EQUIV="Refresh"
                  CONTENT="0; URL=<?php echo $setting['siteurl'] . 'index.php?act=register'; ?>">
            <?php
            exit;
            }
            } else {
            $stmt->close();
            ?>
                <script>alert("Sorry username or email exists try again!!");</script>
            <META HTTP-EQUIV="Refresh"
                  CONTENT="0; URL=<?php echo $setting['siteurl'] . 'index.php?act=register'; ?>">
            <?php }
            }
            }
            ?></noscript>
        <div id="preview"></div>
        <div id="contactBox">
            <?php
            if ($setting['regclosed'] == 'yes') {
                echo '<center>Registration is now closed.</center>';
            } else {
                ?>

                <div class="form-style-3">
                    <form name="myform" id="form" action="index.php?act=register" method="post">
                        <fieldset>
                            <legend>Personal</legend>
                            <label for="field1"><span>Username <span class="required">*</span></span><input type="text"
                                                                                                            class="input-field"
                                                                                                            name="username2"
                                                                                                            value=""/></label>
                            <label for="field2"><span>Password <span class="required">*</span></span><input
                                    type="password" class="input-field" name="password" value=""/></label>
                            <label for="field3"><span>Repeat Password <span class="required">*</span></span><input
                                    type="password" class="input-field" name="repeatpassword" value=""/></label>
                            <label for="field4"><span>Email <span class="required">*</span></span><input type="email"
                                                                                                         class="input-field"
                                                                                                         name="email"
                                                                                                         value=""/></label>

                            <br>
                            <div class="info">Email is used to reset a forgotten password only.</div>

                        </fieldset>
                        <fieldset>
                            <legend>Security</legend>

                            <?php
                            if ($setting['userecaptcha'] == "yes") {
                                @session_start();
                                // securimage captcha
                                ?>
                                <img id="siimage" align="center"
                                     style="padding-right: 5px; border: 1px solid;background-color: #fff;"
                                     src="<?php echo $setting['siteurl']; ?>includes/securimage/securimage_show.php?sid=<?php echo md5(time()) ?>"/>
                                <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
                                        codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0"
                                        width="19" height="19" id="SecurImage_as3" align="middle">
                                    <param name="allowScriptAccess" value="sameDomain"/>
                                    <param name="allowFullScreen" value="false"/>
                                    <param name="movie"
                                           value="<?php echo $setting['siteurl']; ?>includes/securimage/securimage_play.swf?audio=securimage_play.php&bgColor1=#fff&bgColor2=#284062&iconColor=#000&roundedCorner=5"/>
                                    <param name="quality" value="high"/>
                                    <param name="bgcolor" value="#284062"/>
                                    <embed
                                        src="<?php echo $setting['siteurl']; ?>includes/securimage/securimage_play.swf?audio=securimage_play.php&bgColor1=#fff&bgColor2=#284062&iconColor=#000&roundedCorner=5"
                                        quality="high" bgcolor="#284062" width="19" height="19" name="SecurImage_as3"
                                        align="middle" allowScriptAccess="sameDomain" allowFullScreen="false"
                                        type="application/x-shockwave-flash"
                                        pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>
                                </object>
                                <!-- pass a session id to the query string of the script to prevent ie caching -->
                                <a tabindex="-1" style="border-style: none" href="#" title="Refresh Image"
                                   onclick="document.getElementById('siimage').src = '<?php echo $setting['siteurl']; ?>includes/securimage/securimage_show.php?sid=' + Math.random(); return false"><img
                                        src="<?php echo $setting['siteurl']; ?>includes/securimage/images/refresh.gif"
                                        alt="Reload Image" border="0" onclick="this.blur()" align="middle"/></a>

                                <br><br>
                                <label for="field5"><span>Security Code <span class="required">*</span></span><input
                                        type="text" class="input-field" name="code" id="code" value=""/></label>

                                <input name="recaptcha" type="hidden" value="yes"/><?php
                                // end securimage captcha
                            } else {
                                ?>Security Question: five + five = <br/>
                                <input class="registerInput" name="security" id="security" type="text"
                                       style="border: 1px solid #000;"/><br/>
                                <input name="recaptcha" type="hidden" value="no"/><?php
                            } ?>
                            <label><input type="submit" value="Sign Up"/></label>
                        </fieldset>
                    </form>
                </div>


            <?php } ?>
        </div>
    </div>

    <div class="clear"></div>