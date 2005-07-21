<?php
/*
========================================
| IntuiBoard 0.1
| http://www.intuiboard.com
----------------------------------------
| Copyright 2005 Michael Corcoran
========================================
| Forum Display
========================================
*/

class forum {
	var $ib_core;
	
	var $output = '';
	
	var $fid;
	
	function forum(&$ibcore) {
		$this->ib_core =& $ibcore;
		
		$this->ib_core->load_skin('forum');
		
		$fid = isset($this->ib_core->input['id']) ? intval($this->ib_core->input['id']) : 0;
		
		if(!$fid) {
			$this->ib_core->error('broken_link');
		}
		
		$this->ib_core->db->query("SELECT * FROM ib_forums WHERE f_id=".$fid, __FILE__, __LINE__);
		$forum = $this->ib_core->db->fetch_row();
		
		if(!$forum) {
			$this->ib_core->error('broken_link');
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
		
		$this->ib_core->output->add_output($this->output);
		$this->ib_core->output->do_output($forum['f_name']);
	}
}
?>