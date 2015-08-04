<?php
include("db_functions.inc.php");
include("config.inc.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['recaptcha'] == 'yes') {
        include("securimage/securimage.php");
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
    if ($passed === true) {
        $username = yasDB_clean($_POST["username2"]);
        $password = md5(yasDB_clean($_POST["password"]));
        $repeatpassword = md5(yasDB_clean($_POST["repeatpassword"]));
        $name = yasDB_clean($_POST["name"]);
        $email = yasDB_clean($_POST["email"]);;
        $website = yasDB_clean($_POST["website"]);
        $date = time() + (0 * 24 * 60 * 60);
        $plays = 0;
        $points = 0;
        if (preg_match("/[^\w-.]/", $_POST["username2"])) {
            echo "<h3>invalid characters in username</h3>";
        } else {
            if (strlen($_POST['username2']) < 2 || strlen($_POST['username2']) > 20) {
                echo "<h3>Username must be between 2 and 20 characters!</h3>";
            } else {
                $stmt = yasDB_select("SELECT `id` FROM `user` WHERE `username` LIKE '$username'");
                $stmt2 = yasDB_select("SELECT `id` FROM `user` WHERE `email` LIKE '$email'");
                if ($stmt->num_rows == 0 && $stmt2->num_rows == 0) {
                    $stmt3 = yasDB_insert("INSERT INTO `user` (`username`, `password`, `repeatpassword`, `name`, `email`, `website`, `plays`, `points`, `date`) VALUES ('$username','$password','$repeatpassword','$name','$email','$website','$plays','$points', '$date')", false);
                    if ($stmt3) {
                        echo "<h3>Registered! You may now log in.</h3>";
                    } else {
                        $stmt3->close();
                        echo "<h3>Registration failed!</h3>";
                    }
                } else {
                    $stmt->close();
                    echo "<h3>Sorry, username or email exists. Please try again.</h3>";
                }
            }
        }
    } else {
            echo '<h3><span style="color:red;">The security question was answered incorrectly. Please try again.</span></h3>';
        }

    } else {
        echo 'Invalid request.';
    }
    ?>