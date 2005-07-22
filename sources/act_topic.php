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
|  View Topic
+----------------------------------------------------------------------------------------
*/

class topic {
	var $ib_core;
	var $output = '';
	
	function topic(&$ibcore) {
		$this->ib_core =& $ibcore;
		
		$this->ib_core->load_skin('topic');
		
		$tid = isset($this->ib_core->input['id']) ? intval($this->ib_core->input['id']) : 0;
		
		if(!$tid) {
			$this->ib_core->error('broken_link');
		}
		
		$this->ib_core->db->query("SELECT t.*,f.f_name,f.f_perms FROM ib_topics t
							LEFT JOIN ib_forums f ON(f.f_id=t.t_forum)
							WHERE t_id=".$tid, __FILE__, __LINE__);
		$topic = $this->ib_core->db->fetch_row();
		
		if(!$topic) {
			$this->ib_core->error('broken_link');
		}
		
		$perms = unserialize($topic['f_perms']);
		if(!in_array($this->ib_core->member['g_perms'], explode('|', $perms['view']))) {
			$this->ib_core->error('no_perms');
		}
		
		$this->output .= $this->ib_core->skin['topic']->topic_head($topic);
		
		$this->ib_core->db->query("SELECT * FROM ib_posts WHERE p_topic=".$tid." ORDER BY p_date ASC", __FILE__, __LINE__);
		
		while($row = $this->ib_core->db->fetch_row()) {
			$row['p_date'] = $this->ib_core->get_date($row['p_date']);
			
			$this->output .= $this->ib_core->skin['topic']->post_row($row);
		}
		
		$this->output .= $this->ib_core->skin['topic']->topic_foot();
		
		
		$this->ib_core->nav[] = array($topic['f_name'], '?act=forum&amp;id='.$topic['t_forum']);
		$this->ib_core->nav[] = array($topic['t_title'], '?act=topic&amp;id='.$topic['t_id']);
		
		$this->ib_core->output->add_output($this->output);
		$this->ib_core->output->do_output($topic['t_title']);
	}
}
?>