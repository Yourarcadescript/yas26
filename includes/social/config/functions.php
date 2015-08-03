<?php
class User {
    function checkUser($uid, $oauth_provider, $username,$email,$twitter_otoken='',$twitter_otoken_secret='') {
        $query = yasDB_select("SELECT * FROM `user` WHERE oauth_uid = '$uid' and oauth_provider = '$oauth_provider'");
        if ($query->num_rows != 0) {
            # User is already present
        } else {
            #user not present. Insert a new Record
            $query = yasDB_select("INSERT INTO `user` (oauth_provider, oauth_uid, username,email,twitter_oauth_token,twitter_oauth_token_secret,date) VALUES ('$oauth_provider', '$uid', '$username','$email','$twitter_otoken','$twitter_otoken_secret','".time()."')");
            $query = yasDB_select("SELECT * FROM `user` WHERE oauth_uid = '".$uid."' and oauth_provider = '".$oauth_provider."' limit 1" );
            $result = $query->fetch_array(MYSQLI_BOTH);
            return $result;
		}
        $result = $query->fetch_array(MYSQLI_BOTH);
		return $result;
	}
}
?>