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
        $email = yasDB_clean($_POST["email"]);;
        $date = time() + (0 * 24 * 60 * 60);

        if (preg_match("/[^\w-.]/", $_POST["username2"])) {
            $errorMessage = $errorMessage . "invalid characters in username" . "<br>";
        }
        if (strlen($_POST['username2']) < 2 || strlen($_POST['username2']) > 20) {
            $errorMessage = $errorMessage . "Username must be between 2 and 20 characters!" . "<br>";
        }
        if (strlen($_POST['password']) < 4 || strlen($_POST['password']) > 26) {
            $errorMessage = $errorMessage . "Password must be between 4 and 26 characters!" . "<br>";
        }
        if ($_POST['password'] != $_POST['repeatpassword']) {
            $errorMessage = $errorMessage . "Password and repeat password are not the same!" . "<br>";
        }

        if (!$errorMessage) {
            $stmt = yasDB_select("SELECT `id` FROM `user` WHERE `username` LIKE '$username'");
            $stmt2 = yasDB_select("SELECT `id` FROM `user` WHERE `email` LIKE '$email'");
            if($stmt->num_rows == 0 && $stmt2->num_rows == 0){
                $stmt3 = yasDB_insert("INSERT INTO `user` (`username`, `password`, `repeatpassword`, `name`, `email`, `website`, `plays`, `points`, `date`) VALUES ('$username','$password','$repeatpassword','','$email','','','', '$date')",false);
                if ($stmt3) {
                    echo '<div class="success">Registered! You may now log in.</div>';
                } else {
                    $stmt3->close();
                    echo '<div class="warning">Registration failed!</div>';
                }
            } else {
                $stmt->close();
                echo '<div class="validation">Sorry, username or email exists. Please try again.</div>';
            }

        } else {
            // echo $errorMessage;
            echo '<div class="validation"> ' . $errorMessage . ' </div>';
        }
    } else {
        echo '<div class="validation">The security question was answered incorrectly. Please reload image and try again.</div>';
    }

} else {
    echo 'Invalid request.';
}
?>