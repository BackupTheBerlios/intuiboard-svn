<?php
/*
========================================
| IntuiBoard 0.1
| http://www.intuiboard.com
----------------------------------------
| Copyright 2005 Michael Corcoran
========================================
| Authentication System Library
========================================
*/

class lib_auth {
	var $ib_core;
	
	function lib_auth(&$ib_core) {
		$this->ib_core =& $ib_core;
	}
	
	// Generates password salts
	// $length: length of the salt required
	function generate_salt($length = 5) {
		$salt = '';
	
		for($i = 0; $i < $length; $i++) {
			$salt .= chr(rand(40, 126));
		}
		
		return $salt;
	}
	
	// Generates password hashes
	// $md5_password: md5 hash of the password to be hashed
	// $salt: salt to be used, will be generated if not given
	function generate_pass_hash($md5_password, $salt = false) {
		if(!$salt) {
			$salt = $this->generate_salt(5);
		}
	
		$hash = md5(md5($salt).$md5_password);
		
		return $hash;
	}
}
?>