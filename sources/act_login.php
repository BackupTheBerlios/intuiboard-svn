<?php
/*
========================================
| IntuiBoard 0.1
| http://www.intuiboard.com
----------------------------------------
| Copyright 2005 Michael Corcoran
========================================
| Login/Register
========================================
*/

class login {
	var $ib_core;
	var $skin;
	
	function login(&$ibcore) {
		$this->ib_core =& $ibcore;

		if(!isset($this->ib_core->input['code'])) {
			$this->ib_core->input['code'] = '';
		}

		switch($this->ib_core->input['code']) {
			case 'reg':
				break;
			case 'login':
			default:
				break;
		}
	}
}
?>