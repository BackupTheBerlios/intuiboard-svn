<?php
/*
========================================
| IntuiBoard 0.1
| http://www.intuiboard.com
----------------------------------------
| Copyright 2005 Michael Corcoran
========================================
| Output Control
========================================
*/

class output {
	var $ib_core;
	
	var $output = '';
	
	function output(&$ib_core) {
		$this->ib_core =& $ib_core;
	}
	
	function add_output($txt) {
		$this->output .= $txt;
	}
	
	function do_output($title) {
		$vars = array();
		
		$stats = $this->ib_core->finish();
		
		$vars['title'] = $title. ' - Powered by IntuiBoard';
		$vars['css'] = $this->ib_core->skin->css_cached('cache/css/style.css');
		$vars['header'] = $this->ib_core->skin->board_header();
		$vars['footer'] = $this->ib_core->skin->board_footer($stats);
		$vars['body'] = $this->output;
		
		if($this->ib_core->member['m_id']) {
			$vars['mbar'] = $this->ib_core->skin->member_bar($this->ib_core->member);
		}
		else {
			$vars['mbar'] = $this->ib_core->skin->member_bar_guest();
		}
		
		
		$output = $this->ib_core->skin->wrapper($vars);
		
		echo $output;
	}
}
?>