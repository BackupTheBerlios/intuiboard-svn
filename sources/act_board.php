<?php
/*
========================================
| IntuiBoard 0.1
| http://www.intuiboard.com
----------------------------------------
| Copyright 2005 Michael Corcoran
========================================
| Board Index
========================================
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
		$this->ib_core->output->do_output('Test Forums');
	}
	
	function _show_index() {
		$forums = array();
		
		$this->ib_core->db->query("SELECT f.* FROM ib_forums f ORDER BY f.f_order", __FILE__, __LINE__);
		
		while($row = $this->ib_core->db->fetch_row()) {
			if(!$row['f_last_topic_id']) {
				$row['f_last_topic'] = '<span class="blank">None</span>';
				$row['f_last_poster'] = '<span class="blank">None</span>';
			}
			else {
				$row['f_last_topic'] = '<a href="?act=topic&id='.$row['f_last_topic_id'].'&get=lastpost">'.$row['f_last_topic_title'].'</a>';
				$row['f_last_poster'] = '<em><a href="?act=member&code=profile&id='.$row['f_last_poster_id'].'">'.$row['f_last_poster_name'].'</a></em>';
			}
				
			$this->output .= $this->ib_core->skin['board']->forum_row($row);
		}
	}
}
?>