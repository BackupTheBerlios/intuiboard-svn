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
|  Board Index
+----------------------------------------------------------------------------------------
*/

if(!defined('breeze')) {
	die('Error: You may not access this file directly!');
}

class board {
	var $breeze;
	
	var $skin;
	var $output = '';
	
	function board(&$breeze) {
		$this->breeze =& $breeze;
		
		$this->skin = $this->breeze->load_skin('board');
		
		$this->breeze->input['code'] = isset($this->breeze->input['code']) ? $this->breeze->input['code'] : '';
		
		switch($this->breeze->input['code']) {
			default:
				$this->_show_index();
				break;
		}
				
		$this->breeze->output->add_output($this->output);
		$this->breeze->output->do_output($this->breeze->conf['board_name']);
	}
	
	function _show_index() {
		if($this->breeze->conf['single_forum']) {
			$this->_show_index_topics(intval($this->breeze->conf['single_forum']));
		}
		else {
			$this->_show_index_forums();
		}
		
		if($this->breeze->conf['show_stats']) {
			$this->_show_index_stats();
		}
	}
	
	function _show_index_forums() {
		$this->breeze->db->query("SELECT * FROM ib_forums ORDER BY f_order", __FILE__, __LINE__);
		
		while($row = $this->breeze->db->fetch_row()) {
			$perms = unserialize($row['f_perms']);
			if(!in_array($this->breeze->member['g_perms'], explode('|', $perms['list']))) {
				continue;
			}
			
			if(!$row['f_last_topic_id']) {
				$row['f_last_topic'] = '<span class="blank">None</span>';
				$row['f_last_poster'] = '<span class="blank">None</span>';
			}
			else {
				$row['f_last_topic'] = '<a href="'.$this->breeze->baseurl.'act=topic&amp;id='.$row['f_last_topic_id'].'&amp;get=lastpost">'.$row['f_last_topic_title'].'</a>';
				$row['f_last_poster'] = '<em><a href="'.$this->breeze->baseurl.'act=member&amp;code=profile&amp;id='.$row['f_last_poster_id'].'">'.$row['f_last_poster_name'].'</a></em>';
			}
				
			$this->output .= $this->breeze->skin['board']->forum_row($row);
		}
	}
	
	function _show_index_topics($fid) {
		$this->breeze->load_skin('forum');
		
		$this->breeze->db->query("SELECT * FROM ib_forums WHERE f_id=".$fid, __FILE__, __LINE__);
		$forum = $this->breeze->db->fetch_row();
		
		if(!$forum) {
			$this->_show_index_forums();
			return;
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
	}
	
	function _show_index_stats() {
		$stats = array();
		
		$maxage = (time() - (intval($this->breeze->conf['stats_online_max_age']) * 60));
		$this->breeze->db->query("SELECT s.s_age,m.m_id,m.m_name FROM ib_sessions s 
							LEFT JOIN ib_members m ON(m.m_id=s.s_member_id)
							WHERE s_age>".$maxage, __FILE__, __LINE__);
		
		$stats['users'] = '';
		
		$stats['guests'] = 0;
		$stats['members'] = 0;
		
		while($row = $this->breeze->db->fetch_row()) {
			if($row['m_id']) {
				$title = $this->breeze->get_date($row['s_age']);
				
				$stats['users'] .= '<a href="'.$this->breeze->baseurl.'act=member&amp;code=profile&amp;id='.$row['m_id'].'" title="'.$title.'">'.
							$row['m_name'].'</a>,';
				$stats['members']++;
			}
			else {
				$stats['guests']++;
			}
		}
		
		$stats['users'] = preg_replace("#,$#", "", $stats['users']);
		
		
		$stats['total_members']	= $this->breeze->caches['stats']['total_members'];
		$stats['total_topics']	= $this->breeze->caches['stats']['total_topics'];
		$stats['total_replies']	= $this->breeze->caches['stats']['total_replies'];
		
		$this->output .= $this->breeze->skin['board']->board_stats($stats);
	}
}
?>