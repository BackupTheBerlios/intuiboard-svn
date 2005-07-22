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
|  Output Control
+----------------------------------------------------------------------------------------
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
	
	function clear_output() {
		$this->output = '';
	}
	
	function do_output($title, $options = array()) {
		$vars = array();
		
		$stats = $this->ib_core->finish();
		
		$vars['title'] 	= $title. ' - Powered by IntuiBoard';
		$vars['css'] 	= $this->ib_core->skin['global']->css_cached('cache/css/style.css');
		$vars['header'] 	= $this->ib_core->skin['global']->board_header();
		$vars['footer'] 	= $this->ib_core->skin['global']->board_footer($stats);
		$vars['body'] 	= $this->output;
		$vars['nav'] 	= $this->ib_core->build_breadcrumb();
		
		if($this->ib_core->member['m_id']) {
			$vars['mbar'] = $this->ib_core->skin['global']->member_bar($this->ib_core->member);
		}
		else {
			$vars['mbar'] = $this->ib_core->skin['global']->member_bar_guest();
		}
		
		
		$output = $this->ib_core->skin['global']->wrapper($vars);
		
		if(count($options)) {
			foreach($options as $var => $val) {
				$output = str_replace('{'.$var.'}', $val, $output);
			}
		}
		
		$output = preg_replace("#\{.+?\}#", "", $output);
		
		echo $output;
	}
}
?>