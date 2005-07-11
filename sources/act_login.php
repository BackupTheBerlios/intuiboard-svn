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
	var $output = '';
	
	function login(&$ibcore) {
		$this->ib_core =& $ibcore;

		$this->ib_core->load_skin('login');
		
		$this->ib_core->input['code'] = isset($this->ib_core->input['code']) ? $this->ib_core->input['code'] : '';

		switch($this->ib_core->input['code']) {
			//-----------------------------
			case 'reg':
				$this->_register_form();
				break;
			case 'doreg':
				$this->_do_register();
				break;
			//-----------------------------
			case 'dologin':
				$this->_do_login();
			case 'login':
			default:
				$this->_login_form();
				break;
			//-----------------------------
		}
		
		$this->ib_core->output->add_output($this->output);
		$this->ib_core->output->do_output('Test Forums');
	}
	
	// Main registration functions
	function _register_form() {
		
	}
	
	function _do_register() {
		
	}
	
	// Main login functions
	function _login_form() {
		$this->output .= $this->ib_core->skin['login']->login_form();
	}
	
	function _do_login() {
		$clean_input = $this->ib_core->validate_form(
					array(	'username' 	=> 'text',
							'password' 	=> 'text',
							'remember' 	=> 'bool',
							'submit'	=> 'submit'
						));
					
		// login here
	}
}
?>