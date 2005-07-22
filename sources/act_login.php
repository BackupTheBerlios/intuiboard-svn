<?php
/*
+----------------------------------------------------------------------------------------
|  IntuiBoard {$version_str$} ({$version_num$})
|  http://www.intuiboard.com
+----------------------------------------------------------------------------------------
|  Revision: $WCREV$
|  Date: $WCDATE$
+----------------------------------------------------------------------------------------
|  Copyright (C) {$copyright_year$} Michael Corcoran
+----------------------------------------------------------------------------------------
|  IntuiBoard is free software; you can redistribute it and/or modify
|  it under the terms of the GNU General Public License as published by
|  the Free Software Foundation; either version 2 of the License, or
|  (at your option) any later version.
|  
|  IntuiBoard is distributed in the hope that it will be useful,
|  but WITHOUT ANY WARRANTY; without even the implied warranty of
|  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
|  GNU General Public License for more details.
|  
|  You should have received a copy of the GNU General Public License
|  along with IntuiBoard; if not, write to the Free Software
|  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
+----------------------------------------------------------------------------------------
|  Login/Register
+----------------------------------------------------------------------------------------
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
		
		$this->ib_core->output->add_output($this->output);
		$this->ib_core->output->do_output($this->ib_core->conf['board_name']);
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
		$m_name = addslashes($clean_input['username']);
		$this->ib_core->db->query("SELECT m_id, m_pass_hash, m_pass_salt FROM ib_members WHERE m_name='".$m_name."'", __FILE__, __LINE__);
		$row = $this->ib_core->db->fetch_row();
		
		if($row['m_pass_hash'] == md5(md5($row['m_pass_salt']).md5($clean_input['password']))) {
			// login
			$_SESSION['m_id'] = $row['m_id'];
			$_SESSION['m_pass_hash'] = $row['m_pass_hash'];
			
			if($clean_input['remember']) {
				$this->ib_core->my_set_cookie('m_id', $row['m_id']);
				$this->ib_core->my_set_cookie('m_pass_hash', $row['m_pass_hash']);
			}
			
			$this->ib_core->redirect('?act=index', 'logged_in');
		}
		else {
			$this->ib_core->error('invalid_login');
		}
	}
	
	function _logout() {
		$_SESSION = array();
		session_destroy();
		
		$this->ib_core->my_unset_cookie('m_id');
		$this->ib_core->my_unset_cookie('m_pass_hash');
			
		$this->ib_core->redirect('?act=index', 'logged_out');
	}
}
?>