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
|  Board Index
+----------------------------------------------------------------------------------------
*/

class board {
	var $ib_core;
	
	var $skin;
	var $output = '';
	
	function board(&$ibcore) {
		$this->ib_core =& $ibcore;
		
		$this->skin = $this->ib_core->load_skin('board');
		
		$this->ib_core->input['code'] = isset($this->ib_core->input['code']) ? $this->ib_core->input['code'] : '';
		
		switch($this->ib_core->input['code']) {
			default:
				$this->_show_index();
				break;
		}
				
		$this->ib_core->output->add_output($this->output);
		$this->ib_core->output->do_output($this->ib_core->conf['board_name']);
	}
	
	function _show_index() {
		if($this->ib_core->conf['single_forum']) {
			$this->_show_index_topics(intval($this->ib_core->conf['single_forum']));
		}
		else {
			$this->_show_index_forums();
		}
		
		if($this->ib_core->conf['show_stats']) {
			$this->_show_index_stats();
		}
	}
	
	function _show_index_forums() {
		$this->ib_core->db->query("SELECT * FROM ib_forums ORDER BY f_order", __FILE__, __LINE__);
		
		while($row = $this->ib_core->db->fetch_row()) {
			$perms = unserialize($row['f_perms']);
			if(!in_array($this->ib_core->member['g_perms'], explode('|', $perms['list']))) {
				continue;
			}
			
			if(!$row['f_last_topic_id']) {
				$row['f_last_topic'] = '<span class="blank">None</span>';
				$row['f_last_poster'] = '<span class="blank">None</span>';
			}
			else {
				$row['f_last_topic'] = '<a href="'.$this->ib_core->baseurl.'act=topic&amp;id='.$row['f_last_topic_id'].'&amp;get=lastpost">'.$row['f_last_topic_title'].'</a>';
				$row['f_last_poster'] = '<em><a href="'.$this->ib_core->baseurl.'act=member&amp;code=profile&amp;id='.$row['f_last_poster_id'].'">'.$row['f_last_poster_name'].'</a></em>';
			}
				
			$this->output .= $this->ib_core->skin['board']->forum_row($row);
		}
	}
	
	function _show_index_topics($fid) {
		$this->ib_core->load_skin('forum');
		
		$this->ib_core->db->query("SELECT * FROM ib_forums WHERE f_id=".$fid, __FILE__, __LINE__);
		$forum = $this->ib_core->db->fetch_row();
		
		if(!$forum) {
			$this->_show_index_forums();
			return;
		}
		
		$perms = unserialize($forum['f_perms']);
		if(!in_array($this->ib_core->member['g_perms'], explode('|', $perms['list']))) {
			$this->ib_core->error('no_perms');
		}
		
		$this->output .= $this->ib_core->skin['forum']->topic_head();
		
		$this->ib_core->db->query("SELECT * FROM ib_topics WHERE t_forum=".$fid." ORDER BY t_last_post_date DESC", __FILE__, __LINE__);
		
		while($row = $this->ib_core->db->fetch_row()) {
			$row['t_last_post_date'] = $this->ib_core->get_date($row['t_last_post_date']);
			
			$this->output .= $this->ib_core->skin['forum']->topic_row($row);
		}
		
		$this->output .= $this->ib_core->skin['forum']->topic_foot();
		
		$this->ib_core->nav[] = array($forum['f_name'], '?act=forum&amp;id='.$fid);
	}
	
	function _show_index_stats() {
		$stats = array();
		
		$maxage = (time() - (intval($this->ib_core->conf['stats_online_max_age']) * 60));
		$this->ib_core->db->query("SELECT s.s_age,m.m_id,m.m_name FROM ib_sessions s 
							LEFT JOIN ib_members m ON(m.m_id=s.s_member_id)
							WHERE s_age>".$maxage, __FILE__, __LINE__);
		
		$stats['users'] = '';
		
		while($row = $this->ib_core->db->fetch_row()) {
			if($row['m_id']) {
				$stats['users'] .= '<a href="'.$this->ib_core->baseurl.'act=member&amp;code=profile&amp;id='.$row['m_id'].'">'.$row['m_name'].'</a>,';
			}
		}
		
		$stats['users'] = preg_replace("#,$#", "", $stats['users']);
		
		$this->output .= $this->ib_core->skin['global']->online_stats($stats);
	}
}
?>