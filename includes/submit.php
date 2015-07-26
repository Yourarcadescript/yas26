<?php
include ("db_functions.inc.php");
include ("config.inc.php");
if($_SERVER["REQUEST_METHOD"] == "POST") {
	if ($_POST['recaptcha'] == 'yes') {	
		include("securimage/securimage.php");
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
	if ($passed) {
		$username = yasDB_clean($_POST['name']);
		$email = yasDB_clean($_POST['email']);
		$usermessage = yasDB_clean($_POST['message'], true);
		$time = time();
		$attachment = false;
		if (isset($_POST['career']) && $_POST['career'] == 'yes') {
			$ext = pathinfo($_FILES['browse']['name'], PATHINFO_EXTENSION);
			$os = array("txt", "rtf", "doc", "docx", "xls", "zip", "rar");
			if (!in_array(strtolower($ext), $os)) {
				echo '<h2>File type (' . $ext. ') not allowed.</h2>';
				exit(); 
			}
			//This assigns the subdirectory you want to save into... make sure it exists!
			if (!is_dir($setting['sitepath'].'/careeruploads/')) {
				mkdir($setting['sitepath'].'/careeruploads/', 0777);
			}
			$path = $setting['sitepath'].'/careeruploads/'; 
			
			$filename = date('d-m-y-h-i') . '_'. $username . '.' . $ext; // create file name
			$target = $path . $filename; 
			if(!move_uploaded_file($_FILES['browse']['tmp_name'], $target)){
				switch ($_FILES['browse'] ['error']) {
					case 1:
						print '<h2> The file is too big.</h2>';
						break;
					case 2:
						print '<dh2> The file is bigger than this form allows</h2>';
						break;
					case 3:
						print '<h2> Only part of the file was uploaded</h2>';
						break;
					case 4:
						print '<h2> No file was uploaded.</h2>';
						break;
				}
				exit;
			} else {
				require_once ('mailer/lib/swift_required.php');
				// Create the Transport
				$transport = Swift_MailTransport::newInstance();
				// Create the Mailer using your created Transport
				$mailer = Swift_Mailer::newInstance($transport);
				// Create the message
				$message = Swift_Message::newInstance();
				// Give the message a subject
				$message->setSubject('Contact message from '.$username.' through '.$setting['sitename']);
				// Set the From address with an associative array
				$message->setFrom(array($email => $username));
				// Set the To addresses with an associative array
				$message->setTo(array($setting['email'] => 'Admin'));
				$body = "<br/><b>Name:</b> ".$username."<br/><br/><b>Return email:</b> ".$email."<br/><br/><b>Reason:</b> Sent from Career Opportunities Form<br/><br/><b>Message:</b> ".$usermessage;
				$message->setBody($body, 'text/html', 'iso-8859-2');
				$message->attach(Swift_Attachment::fromPath($target)->setFilename($filename));
				if($mailer->send($message)) {
					echo "Message sent, Thank You.";
				} else {
					echo "Message failed to send.";
				}
			}
		} else {
			yasDB_insert("INSERT INTO contact (name,email,message,created_date) VALUES('$name','$email','$usermessage','$time')");
			echo "<h2>Thank You !</h2>";
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'To: Admin <'.$setting['email'].'>' . "\r\n";
			$headers .= 'From: '.$username.' <'.$username.'>' . "\r\n";
			$subject = 'Contact message from '.$username.' through '.$setting['sitename'];
			$reason = yasDB_clean($_POST['reason']);
			$message = "<br/><b>Name:</b> ".$username."<br/><br/><b>Return email:</b> ".$email."<br/><br/><b>Reason:</b> ".$reason."<br/><br/><b>Message:</b> ".$usermessage;
			if (@mail($setting['email'], $subject, stripslashes($message), $headers)) {
				echo '<span style="color:red;">Message sent</span><br/><br/>';
			} else {
				echo '<span style="color:red;">Error sending message</span><br/><br/>';
			}
		}
	} else {
		echo "<h2>Security Check Failed!</h2>";
	}
} else {
	echo "<h2>Invalid Request!<h2>";
}
?>