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
		
		$this->ib_core->db->query("SELECT * FROM ib_topics WHERE t_forum=".$fid, __FILE__, __LINE__);
		
		while($row = $this->ib_core->db->fetch_row()) {
			$this->output .= $this->ib_core->skin['forum']->topic_row($row);
		}
		
		$this->ib_core->output->add_output($this->output);
		$this->ib_core->output->do_output('Test Forums');
	}
}
?>