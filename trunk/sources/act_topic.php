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
|  View Topic
+----------------------------------------------------------------------------------------
*/

if(!defined('breeze')) {
	die('Error: You may not access this file directly!');
}

class topic {
	var $breeze;
	var $output = '';
	
	function topic(&$breeze) {
		$this->breeze =& $breeze;
		
		$this->breeze->load_skin('topic');
		
		$tid = isset($this->breeze->input['id']) ? intval($this->breeze->input['id']) : 0;
		
		if(!$tid) {
			$this->breeze->error('broken_link');
		}
		
		$get_new = (isset($this->breeze->input['get']) && $this->breeze->input['get'] == 'lastpost') ? true: false;
		
		if($get_new) {
			$this->breeze->db->query("SELECT p.p_id FROM ib_posts p WHERE p.p_topic=".$tid." ORDER BY p_id DESC LIMIT 0,1", __FILE__, __LINE__);
			$last = $this->breeze->db->fetch_row();
			
			if(!$last) {
				$this->breeze->error('broken_link');
			}
			
			$url = $this->breeze->baseurl.'act=topic&id='.$tid.'#p'.$last['p_id'];
			$this->breeze->redirect($url, '', 0);
		}
		
		$this->breeze->db->query("SELECT t.*,f.f_name,f.f_perms FROM ib_topics t
							LEFT JOIN ib_forums f ON(f.f_id=t.t_forum)
							WHERE t_id=".$tid, __FILE__, __LINE__);
		$topic = $this->breeze->db->fetch_row();
		
		if(!$topic) {
			$this->breeze->error('broken_link');
		}
		
		$perms = unserialize($topic['f_perms']);
		if(!in_array($this->breeze->member['g_perms'], explode('|', $perms['view']))) {
			$this->breeze->error('no_perms');
		}
		
		$this->output .= $this->breeze->skin['topic']->topic_head($topic);
		
		$this->breeze->db->query("SELECT * FROM ib_posts WHERE p_topic=".$tid." ORDER BY p_date ASC", __FILE__, __LINE__);
		
		while($row = $this->breeze->db->fetch_row()) {
			$row['p_date'] = $this->breeze->get_date($row['p_date']);
			
			$this->output .= $this->breeze->skin['topic']->post_row($row);
		}
		
		$this->output .= $this->breeze->skin['topic']->topic_foot();
		
		
		$this->breeze->nav[] = array($topic['f_name'], '?act=forum&amp;id='.$topic['t_forum']);
		$this->breeze->nav[] = array($topic['t_title'], '?act=topic&amp;id='.$topic['t_id']);
		
		$this->breeze->output->add_output($this->output);
		$this->breeze->output->do_output($topic['t_title']);
	}
}
?>