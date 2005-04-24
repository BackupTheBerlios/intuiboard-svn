<?php
/*
========================================
| IntuiBoard 0.1
| http://www.intuiboard.com
----------------------------------------
| Copyright 2005 Michael Corcoran
========================================
| Standard Function Library
========================================
*/

class functions {
	var $ib_core;
	
	function functions(&$ibcore) {
		$this->ib_core =& $ibcore;
	}
	
	function get_input() {
		$input = array();
		
		foreach($_GET as $k => $v) {
			$input[$k] = $v;
		}
		foreach($_POST as $k => $v) {
			$input[$k] = $v;
		}
		
		return $input;
	}
	
	function load_skin($skin) {
		require rootpath. 'cache/skin/skin_'. $skin. '.php';
		
		$class = 'skin_'. $skin;
		$return = new $class($this->ib_core);
		
		return $return;
	}
}
?>