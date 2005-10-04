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
|  Session Library
+----------------------------------------------------------------------------------------
*/

if(!defined('breeze')) {
	die('Error: You may not access this file directly!');
}

class lib_session {
	var $breeze;
	
	var $sess;
	
	function lib_session(&$breeze) {
		$this->breeze =& $breeze;
		
		session_set_save_handler(array($this, 'cb_open'), array($this, 'cb_close'), array($this, 'cb_read'), array($this, 'cb_write'), array($this, 'cb_destroy'), array($this, 'cb_gc'));
		if(!ini_get('session.auto_start')) {
			session_start();
		}
		if(ini_get('url_rewriter.tags')) {
			ini_set('url_rewriter.tags', '');
		}
	}
	
	function load_member() {
		if(!isset($this->breeze->member)) {
			if(isset($_SESSION['m_id']) && isset($_SESSION['m_pass_hash'])) {
				$m_id = intval($_SESSION['m_id']);
				$m_pass_hash = $_SESSION['m_pass_hash'];
			}
			elseif(isset($_COOKIE['m_id']) && isset($_COOKIE['m_pass_hash'])) {
				$m_id = intval($_COOKIE['m_id']);
				$m_pass_hash = $_COOKIE['m_pass_hash'];
			}
			else {
				$m_id = 0;
				$m_pass_hash = '';
			}
			
			if($m_id) {
				$this->breeze->db->query("SELECT m.*,g.*,p.perm_id FROM ib_members m
									LEFT JOIN ib_groups g ON(g.g_id=m.m_group)
									LEFT JOIN ib_perms p ON(p.perm_id=g.g_perms)
									WHERE m_id=".$m_id, __FILE__, __LINE__);
									
				$member = $this->breeze->db->fetch_row();
				
				if($m_pass_hash != $member['m_pass_hash']) {
					$m_id = 0;
					unset($member);
				}
			}
			
			if(!$m_id) {
				//guest
				$this->breeze->db->query("SELECT g.*,p.perm_id FROM ib_groups g
									LEFT JOIN ib_perms p ON(p.perm_id=g.g_perms)
									WHERE g_id=2", __FILE__, __LINE__);
									
				$member = $this->breeze->db->fetch_row();
				
				$member['m_id'] = 0;
				$member['m_name'] = 'Guest';
			}
			
			$this->breeze->member =& $member;
		}
	}
		
	// callback functions to handle the session data
	function cb_open($save_path, $session_name) {		
		return true;
	}
	
	function cb_close() {
		return true;
	}
	
	function cb_read($id) {
		if(isset($this->sess) && ($this->sess['s_id'] == $id)) {
			return $this->sess['s_data'];
		}
		
		$this->breeze->db->query("SELECT * FROM ib_sessions WHERE s_id='".addslashes($id)."'", __FILE__, __LINE__);
		
		if(!$this->breeze->db->num_rows()) {
			$this->breeze->db->query("INSERT INTO ib_sessions VALUES('".addslashes($id)."','',".time().",".intval($this->breeze->member['m_id']).")", __FILE__, __LINE__);
			$this->sess = array('s_id' => $id, 's_data' => '', 's_age' => time(), 's_member_id' => intval($this->breeze->member['m_id']));
			
			return '';
		}
		
		$row = $this->breeze->db->fetch_row();
		
		$this->sess = $row;
		
		return $row['s_data'];
	}
	
	function cb_write($id, $sess_data) {
		$this->breeze->db->query("UPDATE ib_sessions SET s_data='".addslashes($sess_data)."',s_age=".time().",s_member_id=".intval($this->breeze->member['m_id'])." WHERE s_id='".addslashes($id)."'", __FILE__, __LINE__);
		$this->sess = array('s_id' => $id, 's_data' => $sess_data, 's_age' => time(), 's_member_id' => intval($this->breeze->member['m_id']));
		
		return true;
	}
	
	function cb_destroy($id)  {
		$this->breeze->db->query("DELETE FROM ib_sessions WHERE s_id='".addslashes($id)."'", __FILE__, __LINE__);
		unset($this->sess);
		
		return true;
	}
	
	function cb_gc($maxlifetime) {
		$maxage = time() - ($this->breeze->conf['sess_max_age']*60);
		
		$this->breeze->db->query("DELETE FROM ib_sessions WHERE s_age>".addslashes($maxage), __FILE__, __LINE__);
		if($this->sess['s_age'] > $maxlifetime) {
			unset($this->sess);
		}
		
		return true;
	}
}
?>