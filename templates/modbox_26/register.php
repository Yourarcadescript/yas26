<div id="center">
    <div class="container_box1">
	<div class="header">Register:</div>
        <div class="container_box2">
            <?php
            if (isset($_POST['submit'])) {
                if ($_POST['recaptcha'] == 'yes') {
					include($setting['sitepath']."/includes/securimage/securimage.php");
					$img = new Securimage();
					$valid = $img->check($_POST['code']);
					if (!$valid) {
						$passed = false;
					} else {
						$passed = true;
					}
				}
				elseif ($_POST['recaptcha'] == 'no') {
					$answer = array('10', 'ten');
					if(!in_array(strtolower($_POST['security']),$answer)) {
						$passed = false;
					} else {
						$passed = true;
					}
				}
                if ($_POST['username2']=='' || $_POST['password']=='') {
					?><script>alert("Sorry username or password is empty!!");</script>
					<META HTTP-EQUIV="Refresh" CONTENT="0; URL=<?php echo $setting['siteurl'].'index.php?act=register';?>">
					<?php
					exit;
				}
				if ($passed) {
					$username = yasDB_clean($_POST["username2"]);
					$password = md5(yasDB_clean($_POST["password"]));
					$name = yasDB_clean($_POST["name"]);
					$email = yasDB_clean($_POST["email"]);;
					$website = yasDB_clean($_POST["website"]);
					$date = time() + (0 * 24 * 60 * 60);
					$plays = 0;
					$points = 0;
					$stmt=yasDB_select("SELECT * FROM user WHERE username LIKE '$username'");
					if($stmt->num_rows == 0){
						$stmt = yasDB_insert("INSERT INTO `user` (username, password, name, email, website, plays, points, date) VALUES ('$username','$password','$name','$email','$website','$plays','$points', '$date')",false);
						if ($stmt) {
							?><script>alert("Registered: You can now log in");</script>
							<META HTTP-EQUIV="Refresh" CONTENT="0; URL=<?php echo $setting['siteurl'];?>">
							<?php
							exit;
						} else {
							$stmt->close();
							?><script>alert("Action Failed");</script>
							<META HTTP-EQUIV="Refresh" CONTENT="0; URL=<?php echo $setting['siteurl'];?>">
							<?php
							exit;
						}
					} else {
						$stmt->close();
						?><script>alert("Sorry username or email exists try again!!");</script>
						<META HTTP-EQUIV="Refresh" CONTENT="0; URL=<?php echo $setting['siteurl'].'index.php?act=register';?>">
					<?php }
				} else {
					echo '<span style="color:red;">The security question was answered incorrectly. Please try again.</span><br/><br/>';
				}
			}?>
           <div id="preview"></div><div id="contactBox">
		  <?php
          if ($setting['regclosed'] == 'yes') {
          echo '<center>Registration is now closed.</center>';
          } else {
          ?>
		   <div style="float:left;">
		   <form name="myform" id="form" action="index.php?act=register" method="post" >
             Username: <br />
             <input class="blue" type="text" name="username2" id="username2" size="35" /><br />
             Password: <br />
             <input class="formsheader" type="password" name="password" id="password" size="35" />
             <Br />
             Name: <br />
             <input  type="text" name="name" size="35" />
             <br />
             Email: <br />
             <input  type="text" name="email" id="email" size="35" />
             <br />
             Website :<br />
             <input  type="text" name="website" size="35" />
             <br /><br /></div><div style="float:right;">
              <?php
             if ($setting['userecaptcha'] == "yes") {
				@session_start();
				// securimage captcha
				?>
				<div style="width: 350px; float:left;height: 90px">
				<img id="siimage" align="center" style="padding-right: 5px; border: 0;" src="<?php echo $setting['siteurl']; ?>includes/securimage/securimage_show.php?sid=<?php echo md5(time()) ?>" />

				<!-- pass a session id to the query string of the script to prevent ie caching -->
				<a tabindex="-1" style="border-style: none" href="#" title="Refresh Image" onclick="document.getElementById('siimage').src = '<?php echo $setting['siteurl']; ?>includes/securimage/securimage_show.php?sid=' + Math.random(); return false"><img src="<?php echo $setting['siteurl']; ?>includes/securimage/images/refresh.gif" alt="Reload Image" border="0" onclick="this.blur()" align="middle" /></a>
				<div style="clear: both"></div>
				</div><br/>
				Security Code:<br />
				<input type="text" name="code" id="code" size="12" /><br /><br />
				<input name="recaptcha" type="hidden" value="yes" /><?php
				// end securimage captcha
		}
		else {
				?>Security Question: five + five = <br />
				<input name="security" id="security" type="text" style="border: 1px solid #000;" /><br/>
				<input name="recaptcha" type="hidden" value="no" /><?php
		}?>
             <br/></div> <div class="clear"></div>
             <input type="submit" class="button" name="submit" value="Sign Up" />
          </form>
          <br/><br/>*Your email is not shared and is used to reset a forgotten password only.
		  <?php } ?>
    </div></div>
    <div class="clear"></div>
    </div>