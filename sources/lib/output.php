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
|  Output Control
+----------------------------------------------------------------------------------------
*/

if(!defined('breeze')) {
	die('Error: You may not access this file directly!');
}

class output {
	var $breeze;
	
	var $output = '';
	
	function output(&$breeze) {
		$this->breeze =& $breeze;
	}
	
	function add_output($txt) {
		$this->output .= $txt;
	}
	
	function clear_output() {
		$this->output = '';
	}
	
	function do_output($title, $options = array()) {	
		if(isset($this->breeze->input['db_debug']) && $this->breeze->db->debug) {
			$this->clear_output();
			$this->add_output($this->breeze->db->get_debug_html());
			
			$this->breeze->nav[] = array('DB Debuger', '');
			$title .= ' :DB Debugger';
		}
		
		$vars = array();
		
		$stats = $this->breeze->finish();
		
		$vars['title'] 	= $title. ' - Powered by IntuiBoard';
		$vars['css'] 	= $this->breeze->skin['global']->css_cached('cache/css/style.css');
		$vars['header'] 	= $this->breeze->skin['global']->board_header();
		$vars['footer'] 	= $this->breeze->skin['global']->board_footer($stats);
		$vars['body'] 	= $this->output;
		$vars['nav'] 	= $this->breeze->build_breadcrumb();
		
		if($this->breeze->member['m_id']) {
			$vars['mbar'] = $this->breeze->skin['global']->member_bar($this->breeze->member);
		}
		else {
			$vars['mbar'] = $this->breeze->skin['global']->member_bar_guest();
		}
				
		
		$output = $this->breeze->skin['global']->wrapper($vars);
		
		if(count($options)) {
			foreach($options as $var => $val) {
				$output = str_replace('{'.$var.'}', $val, $output);
			}
		}
		
		$output = preg_replace("#\{.+?\}#", "", $output);
		
		if($this->breeze->conf['gzip_compress']) {
			ob_start('ob_gzhandler');
		}
		
		echo $output;
	}
}
?>