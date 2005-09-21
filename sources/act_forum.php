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
|  along with IntuiBoard; if not, write to the Free Software
|  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
+----------------------------------------------------------------------------------------
|  Forum Display
+----------------------------------------------------------------------------------------
*/

if(!defined('breeze')) {
	die('Error: You may not access this file directly!');
}

class forum {
	var $breeze;
	
	var $output = '';
	
	var $fid;
	
	function forum(&$breeze) {
		$this->breeze =& $breeze;
		
		$this->breeze->load_skin('forum');
		
		$fid = isset($this->breeze->input['id']) ? intval($this->breeze->input['id']) : 0;
		
		if(!$fid) {
			$this->breeze->error('broken_link');
		}
		
		$this->breeze->db->query("SELECT * FROM ib_forums WHERE f_id=".$fid, __FILE__, __LINE__);
		$forum = $this->breeze->db->fetch_row();
		
		if(!$forum) {
			$this->breeze->error('broken_link');
		}
		
		$perms = unserialize($forum['f_perms']);
		if(!in_array($this->breeze->member['g_perms'], explode('|', $perms['list']))) {
			$this->breeze->error('no_perms');
		}
		
		$this->output .= $this->breeze->skin['forum']->topic_head();
		
		$this->breeze->db->query("SELECT * FROM ib_topics WHERE t_forum=".$fid." ORDER BY t_last_post_date DESC", __FILE__, __LINE__);
		
		while($row = $this->breeze->db->fetch_row()) {
			$row['t_last_post_date'] = $this->breeze->get_date($row['t_last_post_date']);
			
			$this->output .= $this->breeze->skin['forum']->topic_row($row);
		}
		
		$this->output .= $this->breeze->skin['forum']->topic_foot();
		
		
		$this->breeze->nav[] = array($forum['f_name'], '?act=forum&amp;id='.$fid);
		
		$this->breeze->output->add_output($this->output);
		$this->breeze->output->do_output($forum['f_name']);
	}
}
?>