<?php
/*
+----------------------------------------------------------------------------------------
|  Breeze {$version_str$} ({$version_num$})
|  http://www.breezeboard.com
+----------------------------------------------------------------------------------------
|  Revision: $WCREV$
|  Date: $WCDATE$
+----------------------------------------------------------------------------------------
|  Copyright (C) {$copyright_year$} Michael Corcoran
+----------------------------------------------------------------------------------------
|  Breeze is free software; you can redistribute it and/or modify
|  it under the terms of the GNU General Public License as published by
|  the Free Software Foundation; either version 2 of the License, or
|  (at your option) any later version.
|  
|  Breeze is distributed in the hope that it will be useful,
|  but WITHOUT ANY WARRANTY; without even the implied warranty of
|  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
|  GNU General Public License for more details.
|  
|  You should have received a copy of the GNU General Public License
|  along with Breeze; if not, write to the Free Software
|  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
+----------------------------------------------------------------------------------------
|  Login/Register
+----------------------------------------------------------------------------------------
*/

if(!defined('breeze')) {
	die('Error: You may not access this file directly!');
}

class login {
	var $breeze;
	var $output = '';
	
	function login(&$breeze) {
		$this->breeze =& $breeze;

		$this->breeze->load_skin('login');
		
		$this->breeze->input['code'] = isset($this->breeze->input['code']) ? $this->breeze->input['code'] : '';

		switch($this->breeze->input['code']) {
			//-----------------------------
			case 'reg':
				$this->_register_form();
				break;
			case 'doreg':
				$this->_do_register();
				break;
			//-----------------------------
			case 'logout':
				$this->_logout();
				break;
			case 'dologin':
				$this->_do_login();
				break;
			case 'login':
			default:
				$this->_login_form();
				break;
			//-----------------------------
		}
		
		$this->breeze->output->add_output($this->output);
		$this->breeze->output->do_output($this->breeze->conf['board_name']);
	}
	
	// Main registration functions
	function _register_form() {
		
	}
	
	function _do_register() {
		
	}
	
	// Main login functions
	function _login_form() {
		$this->output .= $this->breeze->skin['login']->login_form();
	}
	
	function _do_login() {
		$clean_input = $this->breeze->validate_form(
					array(	'username' 	=> 'text',
							'password' 	=> 'text',
							'remember' 	=> 'bool',
							'submit'	=> 'submit'
						));
		
		// login here
		$m_name = addslashes($clean_input['username']);
		$this->breeze->db->query("SELECT m_id, m_pass_hash, m_pass_salt FROM ib_members WHERE m_name='".$m_name."'", __FILE__, __LINE__);
		$row = $this->breeze->db->fetch_row();
		
		if($row['m_pass_hash'] == md5(md5($row['m_pass_salt']).md5($clean_input['password']))) {
			// login
			$_SESSION['m_id'] = $row['m_id'];
			$_SESSION['m_pass_hash'] = $row['m_pass_hash'];
			
			if($clean_input['remember']) {
				$this->breeze->my_set_cookie('m_id', $row['m_id'], (60*60*24*730));
				$this->breeze->my_set_cookie('m_pass_hash', $row['m_pass_hash'], (60*60*24*730));
			}
			
			$this->breeze->redirect('?act=index', 'logged_in');
		}
		else {
			$this->breeze->error('invalid_login');
		}
	}
	
	function _logout() {
		$_SESSION = array();
		session_destroy();
		
		$this->breeze->my_unset_cookie('m_id');
		$this->breeze->my_unset_cookie('m_pass_hash');
			
		$this->breeze->redirect('?act=index', 'logged_out');
	}
}
?>